<?php

namespace App\Http\Controllers\Users;

use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\UserResource;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\InvoicePaid;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use function Symfony\Component\Finder\name;


class SubscriptionController extends Controller
{
    /**
     * @OA\Get(
     * tags={"admin"},
     * path="/api/subscriptions/last",
     * summary="Subscriptions",
     * description="Get last subscriptions",
     * security = {{ "Bearer":{} }},
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example="1"),
     * @OA\Property(property="user", type="string", example="Иванов Иван"),
     * @OA\Property(property="action", type="string", example="подписался"),
     * @OA\Property(property="user_email", type="string", example="ivan@mail.ru"),
     * @OA\Property(property="status", type="string", example="действительна"),
     * @OA\Property(property="updated_at", type="string", example="2 seconds ago"),
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Forbidden")
     * ),
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthorized")
     * ),
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Not Found")
     * )
     * )
     * )
     */
    public function lastSubscriptions()
    {
        $subscriptions = Subscription::query()->limit(3)->orderByDesc('created_at')->get();
        if ($subscriptions){
            return SubscriptionResource::collection($subscriptions);
        }
       return response()->json([
           'message' => 'Не найдено'
       ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */

    /**
     * @OA\Get(
     * tags={"admin"},
     * path="/api/subscriptions",
     * summary="Subscriptions",
     * description="Get all subscriptions",
     * security = {{ "Bearer":{} }},
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example="1"),
     * @OA\Property(property="user", type="string", example="Иванов Иван"),
     * @OA\Property(property="action", type="string", example="подписался"),
     * @OA\Property(property="user_email", type="string", example="ivan@mail.ru"),
     * @OA\Property(property="status", type="string", example="действительна"),
     * @OA\Property(property="updated_at", type="string", example="2 seconds ago"),
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Forbidden")
     * ),
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthorized")
     * ),
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Not Found")
     * )
     * )
     * )
     */
    public function index()
    {
        $subscriptions = Subscription::all();
        if ($subscriptions){
            return SubscriptionResource::collection($subscriptions);
        }
        return response()->json([
            'message' => 'Не найдено'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|MailMessage
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Subscription $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Subscription $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription $subscription
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     *     @OA\Put(
     *     tags={"auth users"},
     *     path="/api/subscriptions",
     *     summary="Subscriptions",
     *     description="Stoping subscription",
     *     security = {{ "Bearer":{} }},
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="message_success", type="string", example="Действительна"),
     *     @OA\Property(property="message_stop", type="string", example="Приостановлена"),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Unauthorized")
     *     ),
     *     ),
     *     )
     */
    public function update()
    {
        $subscription = Subscription::query()->where('user_id', auth()->user()->id)->first();

        if ($subscription) {
            if ($subscription->status == "действительна") {
                $subscription->status = 'приостоновлена';
                $subscription->save();
                return response()->json([
                    'message' => 'рассылка приостановлена'
                ]);
            } else {
                $subscription->status = 'действительна';
                $subscription->save();
                return response()->json([
                    'message' => 'вы снова подписаны на рассылку'
                ]);
            }
        }
        return response()->json([
            'message' => 'не найдена'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Subscription $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

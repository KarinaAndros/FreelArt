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


class SubscriptionController extends Controller
{
    public function lastSubscriptions()
    {
        return SubscriptionResource::collection(Subscription::query()->limit(3)->orderByDesc('created_at')->get());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return SubscriptionResource::collection(Subscription::all());
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
        $validation = Validator::make($request->all(), [
            'user_email' => 'required|string|max:50|email|unique:subscriptions',
        ],
            [
                'user_email.max' => 'не более 50 символов',
                'user_email.email' => 'некоректный тип',
                'user_email.unique' => 'email уже используется',
                'user_email.required' => 'обязательно для заполнения',
            ]);

        if ($validation->fails()) {
            return response()->json([
                'user_email_error' => $validation->errors()->first('user_email')
            ], 400);
        }
        $subscription = new Subscription();
        $subscription->user_id = auth()->user()->id;
        $subscription->user_email = $request->input('user_email');
        $subscription->save();
        Mail::send(['text' => 'mails.subscription'], ['name', 'FreelArt'], function ($message) use ($subscription) {
            $message->to($subscription->user_email, 'FreelArt')->subject('Рассылка');
            $message->from('ffreelart@mail.ru', 'FreelArt');
        });
//        Notification::send($subscription, new InvoicePaid());
        return response()->json([
            'message' => 'Вы подписаны на рассылку'
        ]);

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

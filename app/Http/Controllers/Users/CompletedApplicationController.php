<?php

namespace App\Http\Controllers\Users;

use App\Http\Resources\ApplicationComletedResource;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\ApplicationUserResource;
use App\Http\Resources\CompletedApplicationResource;
use App\Models\Account;
use App\Models\AccountUser;
use App\Models\Application;
use App\Models\ApplicationUser;
use App\Models\CompletedApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class CompletedApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return
     */
    /**
     *     @OA\Get(
     *     tags={"executor"},
     *     path="/api/completed_applications",
     *     summary="Completed applications",
     *     description="Get user's completed applications",
     *     security = {{ "Bearer":{} }},
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="id", type="integer", example="1"),
     *     @OA\Property(property="description", type="text", example="Нарисовать натюрморт"),
     *     @OA\Property(property="genre", type="string", example="Натюрморт"),
     *     @OA\Property(property="category", type="string", example="Для всех"),
     *     @OA\Property(property="payment", type="float", example="5000"),
     *     @OA\Property(property="user", type="string", example="Иван Иванов"),
     *     @OA\Property(property="user_avatar", type="string", example="/storage/img/avatar.png"),
     *     @OA\Property(property="complete", type="date", example="10.04.2022"),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=403,
     *     description="Forbidden",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Forbidden")
     *     ),
     *     ),
     *     @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Unauthorized")
     *     ),
     *     ),
     *     @OA\Response(
     *     response=404,
     *     description="Not Found",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Not Found")
     *     ),
     *     ),
     *     )
     */
    public function index()
    {
        $applications = auth()->user()->completed_applications->all();
        if ($applications){
            return ApplicationComletedResource::collection($applications);
        }
        else{
            return response()->json([
                'message' => 'вы не выполнили ни одного заказа'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return
     */

    /**
     *     @OA\Post(
     *     tags={"executor"},
     *     path="/api/completed_applications/{id}",
     *     summary="Completed applications",
     *     description="Create comleted application",
     *     security = {{ "Bearer":{} }},
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Application Id",
     *     @OA\Schema(
     *     type="integer",
     *     format="int"
     *     ),
     *     required=true,
     *     example=1
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Заявка выполнена"),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=403,
     *     description="Forbidden",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Forbidden")
     *     ),
     *     ),
     *     @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Unauthorized")
     *     ),
     *     ),
     *     @OA\Response(
     *     response=404,
     *     description="Not Found",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Not Found")
     *     ),
     *     ),
     *     )
     */

    public function store($id)
    {

        $application_user = ApplicationUser::findOrFail($id);
        if ($application_user->status == 'вы исполнитель') {
            $completed_application = new CompletedApplication();
            $completed_application->user_id = auth()->user()->id;
            $completed_application->application_id = $application_user->application_id;
            $completed_application->save();
            $application_user->delete();
            return response()->json([
                'message' => 'Заявка выполнена'
            ]);

            $counts = CompletedApplicationResource::collection(auth()->user()->completed_applications)->keys();
            $account = Account::query()->where('title', 'PRO аккаунт')->first();
            if ($counts->count() == '20'){
                $account_user = new AccountUser();
                $account_user->user_id = auth()->user()->id;
                $account_user->account_id = $account->id;
                $account_user->start_action = Carbon::now();
                $account_user->save();
                return response()->json([
                    'message' => 'Выполнено 20 заказов. Вы получилаете аккаунт уровня PRO бесплатно'
                ]);
            }

        }
        else{
            return response()->json([
                'message' => 'Вы не назначены исполнителем'
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\CompletedApplication $completedApplication
     * @return \Illuminate\Http\Response
     */
    public function show(CompletedApplication $completedApplication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\CompletedApplication $completedApplication
     * @return \Illuminate\Http\Response
     */
    public function edit(CompletedApplication $completedApplication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CompletedApplication $completedApplication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompletedApplication $completedApplication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\CompletedApplication $completedApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompletedApplication $completedApplication)
    {
        //
    }
}

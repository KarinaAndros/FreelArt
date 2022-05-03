<?php

namespace App\Http\Controllers\Users;

use App\Http\Resources\ApplicationResource;
use App\Http\Resources\ApplicationUserResource;
use App\Models\Application;
use App\Models\ApplicationUser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class ApplicationUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */

    /**
     *     @OA\Get(
     *     tags={"users"},
     *     path="/api/application_users",
     *     summary="Users applications",
     *     description="Applications that the user has responded to",
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="id", type="integer", example="1"),
     *     @OA\Property(property="id application", type="integer", example="1"),
     *     @OA\Property(property="genre", type="string", example="Натюрморт"),
     *     @OA\Property(property="user", type="string", example="Иван Иванов"),
     *     @OA\Property(property="user_avatar", type="string", example="/storage/img/avatar.png"),
     *     @OA\Property(property="application_category", type="string", example="Для всех"),
     *     @OA\Property(property="description", type="text", example="Нарисовать натюрморт"),
     *     @OA\Property(property="payment", type="float", example="5000"),
     *     @OA\Property(property="writing_technique", type="string", example="Акварель"),
     *     @OA\Property(property="deadline", type="date", example="20.04.2022"),
     *     @OA\Property(property="updated_at", type="string", example="3 weeks ago"),
     *     @OA\Property(property="message", type="text", example="Готов выполнить работу и сдать ровно в срок"),
     *     @OA\Property(property="status", type="string", example="На рассмотрении"),
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
        if (auth()->user()->hasRole('executor')){
            $applications = auth()->user()->responses->all();
            if ($applications){
                return ApplicationUserResource::collection(auth()->user()->responses);
            }
            return response()->json(['message' => 'Вы пока что не откликнулись ни на одну заявку']);
        }
        if (auth()->user()->hasRole('customer')){
            $customer_applications = auth()->user()->customer_applications->all();
            if ($customer_applications){
                return ApplicationResource::collection($customer_applications);
            }
            return response()->json(['message' => 'Вы пока что не откликнулись ни на одну заявку']);
        }
        return response()->json(['message' => 'Нет доступа']);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    /**
     *     @OA\Post(
     *     tags={"executor"},
     *     path="/api/application_users/{id}",
     *     summary="Response",
     *     description="Create response",
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
     *     security = {{ "Bearer":{} }},
     *     @OA\RequestBody(
     *     @OA\MediaType(
     *     mediaType="application/json",
     *     @OA\Schema(
     *     @OA\Property(property="message", type="required, string, min:10, max:200"),
     *     example={
     *     "message":"Готов выполнить работу за пачку печенек",
     *     },
     *     ),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Вы откликнулись на заявку"),
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
    public function store(Request $request, $id)
    {
        $validation =  Validator::make($request->all(),[
            'message' => 'required|string|min:10|max:200'
        ],[
            'message.required' => 'обязательно для заполнения',
            'message.min' => 'минимум 10 символов',
            'message.max' => 'максимум 200 символов',
        ]);
        if ($validation->fails()){
            return response()->json([
                'message_error' => $validation->errors()->first('message')
            ]);
        }
        $application = Application::findOrFail($id);
        $application_user = new ApplicationUser();
        $application_user->user_id = auth()->user()->id;
        $application_user->application_id = $application->id;
        $application_user->message = $request->input('message');
        $application_user->save();
        return response()->json(['message' => 'Вы откликнулись на заявку']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ApplicationUser  $applicationUser
     * @return \Illuminate\Http\Response
     */
    public function show(ApplicationUser $applicationUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ApplicationUser  $applicationUser
     * @return \Illuminate\Http\Response
     */
    public function edit(ApplicationUser $applicationUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ApplicationUser  $applicationUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApplicationUser $applicationUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ApplicationUser  $applicationUser
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    /**
     *     @OA\Delete(
     *     tags={"executor"},
     *     path="/api/application_users/{id}",
     *     summary="Response",
     *     description="Delete response",
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Response Id",
     *     @OA\Schema(
     *     type="integer",
     *     format="int"
     *     ),
     *     required=true,
     *     example=1
     *     ),
     *     security = {{ "Bearer":{} }},
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Удалено"),
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
    public function destroy($id)
    {
        $application_user = ApplicationUser::findOrFail($id);
        if ($application_user->status == 'отказано'){
            $application_user->delete();
            return response()->json([
                'message' => 'Удалено'
            ]);
        }
        elseif($application_user->status == 'на рассмотрении'){
            $application_user->delete();
            return response()->json([
                'message' => 'Ваш отклик удален'
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Users;

use App\Http\Requests\ApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\GenreResource;
use App\Http\Resources\PictureResource;
use App\Models\Account;
use App\Models\Application;
use App\Models\Genre;
use App\Models\Picture;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;


class ApplicationController extends Controller
{
    /**
     *     @OA\Get(
     *     tags={"users"},
     *     path="/api/main/applications",
     *     summary="Last applications",
     *     description="Get two last applications",
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="id", type="integer", example="1"),
     *     @OA\Property(property="genre", type="string", example="Натюрморт"),
     *     @OA\Property(property="user", type="string", example="Иван Иванов"),
     *     @OA\Property(property="user_avatar", type="string", example="/storage/img/avatar.png"),
     *     @OA\Property(property="application_category", type="string", example="Для всех"),
     *     @OA\Property(property="description", type="text", example="Нарисовать натюрморт"),
     *     @OA\Property(property="payment", type="float", example="5000"),
     *     @OA\Property(property="writing_technique", type="string", example="Акварель"),
     *     @OA\Property(property="deadline", type="date", example="20.04.2022"),
     *     @OA\Property(property="updated_at", type="string", example="3 weeks ago"),
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
    public function ApplicationMain(){
        $applications = Application::query()->limit(2)->orderByDesc('created_at')->get();
        if (is_null($applications)){
            return response()->json([
                'message' => 'Не найдено'
            ]);
        }
        return ApplicationResource::collection($applications);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    /**
     *     @OA\Get(
     *     tags={"users"},
     *     path="/api/applications",
     *     summary="Applications",
     *     description="Get all applications",
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="id", type="integer", example="1"),
     *     @OA\Property(property="genre", type="string", example="Натюрморт"),
     *     @OA\Property(property="user", type="string", example="Иван Иванов"),
     *     @OA\Property(property="user_avatar", type="string", example="/storage/img/avatar.png"),
     *     @OA\Property(property="application_category", type="string", example="Для всех"),
     *     @OA\Property(property="description", type="text", example="Нарисовать натюрморт"),
     *     @OA\Property(property="payment", type="float", example="5000"),
     *     @OA\Property(property="writing_technique", type="string", example="Акварель"),
     *     @OA\Property(property="deadline", type="date", example="20.04.2022"),
     *     @OA\Property(property="updated_at", type="string", example="3 weeks ago"),
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
        $applications = Application::all();
        if ($applications){
            return ApplicationResource::collection($applications);
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
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     *     @OA\Post(
     *     tags={"customer"},
     *     path="/api/applications",
     *     summary="Applications",
     *     description="Create application",
     *     security = {{ "Bearer":{} }},
     *     @OA\RequestBody(
     *     @OA\MediaType(
     *     mediaType="application/json",
     *     @OA\Schema(
     *     @OA\Property(property="genre_id", type="required, exists:genres,id"),
     *     @OA\Property(property="description", type="required, string, min:10, max:300"),
     *     @OA\Property(property="payment", type="required, numeric, min:1, max:999999"),
     *     @OA\Property(property="writing_technique", type="required, string, max:50"),
     *     @OA\Property(property="deadline", type="required, date"),
     *     @OA\Property(property="application_category_id", type="required, exists:application_categories,id"),
     *     example={
     *     "reason":"1",
     *     "description":"Нарисовать натюрморт",
     *     "payment":"1000",
     *     "writing_technique":"Масло",
     *     "deadline":"10.10.2022",
     *     "application_category_id":"1",
     *     },
     *     ),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Заявка успешно создана"),
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
     *     )
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'genre_id' => 'required|exists:genres,id',
            'description' => 'required|string|min:10|max:300',
            'payment' => 'required|numeric|min:1|max:999999',
            'writing_technique' => 'required|string|max:50',
            'deadline' => 'required|date',
            'application_category_id' => 'required|exists:application_categories,id',
        ],
            [
                'genre_id.required' => 'выберите жанр',
                'genre_id.exists' => 'жанр не найден',
                'application_category_id.required' => 'выберите категорию',
                'application_category_id.exists' => 'категория не найдена',
                'description.required' => 'обязательно для заполнения',
                'description.min' => 'не менее 10 символов',
                'description.max' => 'не более 300 символов',
                'writing_technique.required' => 'обязательно для заполнения',
                'writing_technique.max' => 'не более 50 символов',
                'payment.required' => 'обязательно для заполнения',
                'payment.numeric' => 'поле должно содержать число',
                'payment.min' => 'минимум 1 руб',
                'payment.max' => 'максимум 999999 руб',
                'deadline.required' => 'выберите срок здачи работы',
            ]);
        if ($validation->fails()) {
            return response()->json([
                'genre_error' => $validation->errors()->first('genre_id'),
                'description_error' => $validation->errors()->first('description'),
                'payment_error' => $validation->errors()->first('payment'),
                'writing_technique_error' => $validation->errors()->first('writing_technique'),
                'deadline_error' => $validation->errors()->first('deadline'),
                'application_category_error' => $validation->errors()->first('application_category_id'),
            ], 400);
        }
       $application = new Application();
        $application->user_id = auth()->user()->id;
        $application->genre_id = $request->input('genre_id');
        $application->description = $request->input('description');
        $application->payment = $request->input('payment');
        $application->writing_technique = $request->input('writing_technique');
        $application->deadline = $request->input('deadline');
        $application->application_category_id = $request->input('application_category_id');
        $application->save();
        return response()->json([
            'message' => 'Заявка успешно создана'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Application $application
     * @return ApplicationResource|\Illuminate\Http\JsonResponse
     */

    /**
     *     @OA\Get(
     *     tags={"users"},
     *     path="/api/applications/{id}",
     *     summary="Application",
     *     description="Find one application",
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
     *     @OA\Property(property="id", type="integer", example="1"),
     *     @OA\Property(property="genre", type="string", example="Натюрморт"),
     *     @OA\Property(property="user", type="string", example="Иван Иванов"),
     *     @OA\Property(property="user_avatar", type="string", example="/storage/img/avatar.png"),
     *     @OA\Property(property="application_category", type="string", example="Для всех"),
     *     @OA\Property(property="description", type="text", example="Нарисовать натюрморт"),
     *     @OA\Property(property="payment", type="float", example="5000"),
     *     @OA\Property(property="writing_technique", type="string", example="Акварель"),
     *     @OA\Property(property="deadline", type="date", example="20.04.2022"),
     *     @OA\Property(property="updated_at", type="string", example="3 weeks ago"),
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
    public function show($id)
    {
        $application = Application::findOrFail($id);
        if ($application) {
            return new ApplicationResource($application);
        }
        return response()->json([
            'message' => 'Не существует'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Application $application
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Application $application
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    /**
     *     @OA\Put(
     *     tags={"customer"},
     *     path="/api/applications/{id}",
     *     summary="Applications",
     *     description="Update application",
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
     *     @OA\Property(property="genre_id", type="required, exists:genres,id"),
     *     @OA\Property(property="description", type="required, string, min:10, max:300"),
     *     @OA\Property(property="payment", type="required, numeric, min:1, max:999999"),
     *     @OA\Property(property="writing_technique", type="required, string, max:50"),
     *     @OA\Property(property="deadline", type="required, date"),
     *     @OA\Property(property="application_category_id", type="required, exists:application_categories,id"),
     *     example={
     *     "reason":"1",
     *     "description":"Нарисовать натюрморт",
     *     "payment":"1000",
     *     "writing_technique":"Масло",
     *     "deadline":"10.10.2022",
     *     "application_category_id":"1",
     *     },
     *     ),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Заявка успешно изменена"),
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
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'genre_id' => 'required|exists:genres,id',
            'description' => 'required|string|min:10|max:300',
            'payment' => 'required|numeric|min:1|max:999999',
            'writing_technique' => 'required|string|max:50',
            'deadline' => 'required|date',
            'application_category_id' => 'required|exists:application_categories,id',
        ],
            [
                'genre_id.required' => 'выберите жанр',
                'genre_id.exists' => 'жанр не найден',
                'application_category_id.required' => 'выберите категорию',
                'application_category_id.exists' => 'категория не найдена',
                'description.required' => 'обязательно для заполнения',
                'description.min' => 'не менее 10 символов',
                'description.max' => 'не более 300 символов',
                'writing_technique.required' => 'обязательно для заполнения',
                'writing_technique.max' => 'не более 50 символов',
                'payment.required' => 'обязательно для заполнения',
                'payment.numeric' => 'поле должно содержать число',
                'payment.min' => 'минимум 1 руб',
                'payment.max' => 'максимум 999999 руб',
                'deadline.required' => 'выберите срок здачи работы',
            ]);
        if ($validation->fails()) {
            return response()->json([
                'genre_error' => $validation->errors()->first('genre_id'),
                'description_error' => $validation->errors()->first('description'),
                'payment_error' => $validation->errors()->first('payment'),
                'writing_technique_error' => $validation->errors()->first('writing_technique'),
                'deadline_error' => $validation->errors()->first('deadline'),
                'application_category_error' => $validation->errors()->first('application_category_id'),
            ], 400);
        }
        $application = Application::find($id);
        if ($application) {
            $application->update($validation->validated());
            return response()->json([
                'message' => 'Заявка успешно изменена'
            ]);
        }
        return response()->json([
            'message' => 'Не существует'
        ], 404);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Application $application
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    /**
     *     @OA\Delete(
     *     tags={"customer"},
     *     path="/api/applications/{id}",
     *     summary="Applications",
     *     description="Delete application",
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
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Заявка успешно удалена"),
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
        $application = Application::find($id);
        if ($application) {
            $application->delete();
            return response()->json([
                'message' => 'Заявка успешно удалена'
            ]);
        }
        return response()->json([
            'message' => 'Не существует'
        ], 404);
    }
}

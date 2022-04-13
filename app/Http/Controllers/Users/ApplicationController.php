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
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;


class ApplicationController extends Controller
{

    public function ApplicationMain(){
        $applications = Application::query()->limit(2)->orderByDesc('created_at')->get();
        return ApplicationResource::collection($applications);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index()
    {
        return ApplicationResource::collection(Application::all());
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
    public function show($id)
    {
        $application = Application::find($id);
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

        return response()->json([
            'message' => 'К сожалению, доступ закрыт'
        ], 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Application $application
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
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

        return response()->json([
            'message' => 'К сожалению, доступ закрыт'
        ], 403);
    }
}

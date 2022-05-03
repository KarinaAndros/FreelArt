<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ApplicationCategoryResource;
use App\Models\ApplicationCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class ApplicationCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */

    /**
     *     @OA\Get(
     *     tags={"users"},
     *     path="/api/application_categories",
     *     summary="Application category",
     *     description="Information about all application categories",
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="id", type="integer", example="1"),
     *     @OA\Property(property="title", type="string", example="Для всех"),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=404,
     *     description="Not Found",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Not Found")
     *     )
     *     )
     *     )
     */
    public function index()
    {
        $application_categories = ApplicationCategory::all();
        if (is_null($application_categories)){
            return response()->json([
                'message' => 'Не найдено'
            ]);
        }
        return ApplicationCategoryResource::collection(ApplicationCategory::all());
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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     * tags={"admin"},
     * path="/api/application_categories",
     * summary="Application category",
     * description="Add application category",
     * security = {{ "Bearer":{} }},
     * @OA\RequestBody(
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * @OA\Property(property="title", type="required, string, max:100"),
     * example={
     * "title":"Для всех",
     * },
     * ),
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Категория успешно создана"),
     * )
     * ),
     * @OA\Response(
     * response=400,
     * description="Validation error",
     * @OA\JsonContent(
     * @OA\Property(property="title_error", type="string", example="ошибка валидации"),
     * ),
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
     * )
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required|string|max:100|unique:application_categories'
        ],
        [
            'title.required' => 'обязательно для заполнения',
            'title.max' => 'не более 100 символов',
            'title.unique' => 'такая категория уже есть',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'Ошибка добавления',
                'title_error' => $validation->errors()->first('title')
            ], 400);
        }
        ApplicationCategory::create($validation->validated());
        return response()->json([
            'message' => 'Категория успешно создана'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ApplicationCategory $applicationCategory
     * @return ApplicationCategoryResource|\Illuminate\Http\Response
     */
    public function show($id)
    {
        return new ApplicationCategoryResource(ApplicationCategory::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ApplicationCategory $applicationCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ApplicationCategory $applicationCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ApplicationCategory $applicationCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApplicationCategory $applicationCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ApplicationCategory $applicationCategory
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    /**
     * @OA\Delete(
     * tags={"admin"},
     * path="/api/application_categories/{id}",
     * summary="Application category",
     * description="Delete application category",
     * security = {{ "Bearer":{} }},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="Application category Id",
     * @OA\Schema(
     * type="integer",
     * format="int"
     * ),
     * required=true,
     * example=1
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Категория успешно удалена"),
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
     * description="Not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Not Found")
     * ),
     * ),
     * )
     */
    public function destroy($id)
    {
       $application_category = ApplicationCategory::findOrFail($id);
       if ($application_category){
           $application_category->delete();
           return response()->json([
               'message' => 'Категория успешно удалена'
           ]);
       }
        return response()->json([
            'message' => 'Ошибка удаления'
        ], 400);


    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GenreRequest;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\GenreResource;
use App\Models\Application;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use function Symfony\Component\HttpFoundation\all;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|Response
     */

    /**
     *     @OA\Get(
     *     tags={"users"},
     *     path="/api/genres",
     *     summary="Genres",
     *     description="Information about all genres",
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="id", type="integer", example="1"),
     *     @OA\Property(property="title", type="string", example="Портрет"),
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
        $genres = Genre::all();
        if ($genres){
            return GenreResource::collection($genres);
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
     * @return \Illuminate\Http\JsonResponse|Response
     */

    /**
     * @OA\Post(
     * tags={"admin"},
     * path="/api/genres",
     * summary="Genres",
     * description="Add genre",
     * security = {{ "Bearer":{} }},
     * @OA\RequestBody(
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * @OA\Property(property="title", type="required, string, max:50"),
     * example={
     * "title":"Натюрморт",
     * },
     * ),
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Жанр успешно создан"),
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
            'title' => 'required|string|max:50|unique:genres'
        ],
            [
                'title.required' => 'обязательно для заполнения',
                'title.max' => 'не более 50 символов',
                'title.unique' => 'жанр уже есть',
            ]);

        if ($validation->fails()) {
            return response()->json([
                'title_error' => $validation->errors()->first('title')
            ], 400);
        }
        Genre::create($validation->validated());
        return response()->json([
            'message' => 'Жанр успешно создан'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Genre $genre
     * @return GenreResource|\Illuminate\Http\JsonResponse|Response
     */
    public function show($id)
    {
        $genre = Genre::find($id);
        if ($genre) {
            return new GenreResource($genre);
        }
        return response()->json([
            'message' => 'Не существует'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Genre $genre
     * @return \Illuminate\Http\Response
     */
    public function edit(Genre $genre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Genre $genre
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required|string|max:50'
        ],
            [
                'title.required' => 'обязательно для заполнения',
                'title.max' => 'не более 50 символов',
            ]);

        if ($validation->fails()) {
            return response()->json([
                'title_error' => $validation->errors()->first('title')
            ], 400);
        }
        $genre = Genre::find($id);
        if ($genre) {
            $genre->update($validation->validated());
            return response()->json([
                'message' => 'Жанр успешно изменён'
            ]);
        }
        return response()->json([
            'message' => 'Не существует'
        ], 404);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Genre $genre
     * @return \Illuminate\Http\JsonResponse|Response
     */

    /**
     * @OA\Delete(
     * tags={"admin"},
     * path="/api/genres/{id}",
     * summary="Genres",
     * description="Delete genre",
     * security = {{ "Bearer":{} }},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="Genre Id",
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
     * @OA\Property(property="message", type="string", example="Жанр успешно удалён"),
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
        $genre = Genre::findOrFail($id);
        if ($genre) {
            $genre->delete();
            return response()->json([
                'message' => 'Жанр успешно удалён'
            ]);
        }
        return response()->json([
            'message' => 'Не существует'
        ], 404);

    }
}

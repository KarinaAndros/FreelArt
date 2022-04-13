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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|Response
     */
    public function index()
    {
        return GenreResource::collection(Genre::all());
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
    public function store(Request $request)
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
     * @return \Illuminate\Http\Response
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

        return response()->json([
            'message' => 'К сожалению, доступ закрыт'
        ], 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Genre $genre
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function destroy($id)
    {
        $genre = Genre::find($id);
        if ($genre) {
            $genre->delete();
            return response()->json([
                'message' => 'Жанр успешно удалён'
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

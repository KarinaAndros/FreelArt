<?php

namespace App\Http\Controllers\Users;

use App\Http\Resources\ApplicationResource;
use App\Http\Resources\PictureResource;
use App\Models\Application;
use App\Models\FavoriteApplication;
use App\Models\FavoritePicture;
use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FavoritePictureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */

    /**
     *     @OA\Get(
     *     tags={"users"},
     *     path="/api/favorite_pictures",
     *     summary="Favorite pictures",
     *     description="Get user's favorite pictures",
     *     security = {{ "Bearer":{} }},
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="id", type="integer", example="1"),
     *     @OA\Property(property="title", type="string", example="Картина"),
     *     @OA\Property(property="user", type="string", example="Соня"),
     *     @OA\Property(property="user_avatar", type="string", example="/storage/img/avatar.png"),
     *     @OA\Property(property="genre", type="string", example="Портрет"),
     *     @OA\Property(property="description", type="text", example="Описание картины"),
     *     @OA\Property(property="price", type="float", example="1000"),
     *     @OA\Property(property="discount", type="integer", example="10"),
     *     @OA\Property(property="size", type="string", example="100*100"),
     *     @OA\Property(property="writing_technique", type="string", example="Масло"),
     *     @OA\Property(property="img", type="string", example="/storage/img/portret.jpg"),
     *     @OA\Property(property="created_at", type="string", example="2022-04-16T14:32:03.000000Z"),
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
        $pictures = auth()->user()->pictures->all();
        if ($pictures){
            return PictureResource::collection($pictures);
        }
        else{
            return response()->json([
                'message' => 'В избранном нет ни одной картины'
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
     *     tags={"users"},
     *     path="/api/favorite_pictures/{id}",
     *     summary="Favorite pictures",
     *     description="Add favorite pictures",
     *     security = {{ "Bearer":{} }},
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Picture Id",
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
     *     @OA\Property(property="message", type="string", example="Добавлено в избранное"),
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
        $picture = Picture::findOrFail($id);
        if ($picture){
            $favorite = new FavoritePicture();
            $favorite->user_id = auth()->user()->id;
            $favorite->picture_id = $picture->id;
            $favorite->save();
            return response()->json([
                'message' => 'добавлено в избранное'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FavoritePicture  $favoritePicture
     * @return \Illuminate\Http\Response
     */
    public function show(FavoritePicture $favoritePicture)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FavoritePicture  $favoritePicture
     * @return \Illuminate\Http\Response
     */
    public function edit(FavoritePicture $favoritePicture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FavoritePicture  $favoritePicture
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FavoritePicture $favoritePicture)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FavoritePicture  $favoritePicture
     * @return \Illuminate\Http\Response
     */
    public function destroy(FavoritePicture $favoritePicture)
    {
        //
    }
}

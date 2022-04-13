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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index()
    {
        return PictureResource::collection(auth()->user()->pictures);
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
     * @return \Illuminate\Http\Response
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

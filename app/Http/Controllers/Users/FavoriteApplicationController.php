<?php

namespace App\Http\Controllers\Users;

use App\Http\Resources\ApplicationFavoriteResource;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\FavoriteApplication;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FavoriteApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index()
    {
        return ApplicationResource::collection(auth()->user()->applications);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id)
    {
        $application = Application::findOrFail($id);
        if ($application){
            $favorite = new FavoriteApplication();
            $favorite->user_id = auth()->user()->id;
            $favorite->application_id = $application->id;
            $favorite->save();
            return response()->json([
                'message' => 'добавлено в избранное'
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FavoriteApplication  $favoriteApplication
     * @return \Illuminate\Http\Response
     */
    public function show(FavoriteApplication $favoriteApplication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FavoriteApplication  $favoriteApplication
     * @return \Illuminate\Http\Response
     */
    public function edit(FavoriteApplication $favoriteApplication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FavoriteApplication  $favoriteApplication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FavoriteApplication $favoriteApplication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FavoriteApplication  $favoriteApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy(FavoriteApplication $favoriteApplication)
    {
        //
    }
}

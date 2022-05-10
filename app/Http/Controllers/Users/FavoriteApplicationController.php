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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */

    /**
     *     @OA\Get(
     *     tags={"executor"},
     *     path="/api/favorite_applications",
     *     summary="Favorite applications",
     *     description="Get user's favorite applications",
     *     security = {{ "Bearer":{} }},
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
        $applications = auth()->user()->applications->all();
        if ($applications){
            return ApplicationResource::collection($applications);
        }
        return response()->json([
            'message' => 'В избранном нет ни одной заявки'
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     *     @OA\Post(
     *     tags={"executor"},
     *     path="/api/favorite_applications/{id}",
     *     summary="Favorite applications",
     *     description="Add favorite applications",
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
     *     @OA\Property(property="message", type="string", example="Добавлено в избранное"),
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
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     *     @OA\Delete(
     *     tags={"executor"},
     *     path="/api/favorite_applications/{id}",
     *     summary="Favorite applications",
     *     description="Delete favorite application",
     *     security = {{ "Bearer":{} }},
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Favorite application Id",
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
     *     @OA\Property(property="message", type="string", example="Заявка удалена из избранного"),
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
        $favorite_application = FavoriteApplication::findOrFail($id);
        if ($favorite_application){
            $favorite_application->delete();
            return response()->json([
                'message' => 'Заявка удалена из избранного'
            ]);
        }
        return response()->json([
            'message' => 'Не найдено'
        ]);
    }
}

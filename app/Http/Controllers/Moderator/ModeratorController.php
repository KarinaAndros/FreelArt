<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Resources\PictureResource;
use App\Models\Failure;
use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class ModeratorController extends Controller
{

    /**
     *     @OA\Get(
     *     tags={"moderator"},
     *     path="/api/newPictures",
     *     summary="New pictures",
     *     description="Paintings for sale",
     *     security = {{ "Bearer":{} }},
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="id", type="integer", example="1"),
     *     @OA\Property(property="title", type="string", example="Портрет"),
     *     @OA\Property(property="user", type="string", example="Соня"),
     *     @OA\Property(property="user_avatar", type="string", example="/storage/img/avatar.png"),
     *     @OA\Property(property="genre", type="string", example="Портрет"),
     *     @OA\Property(property="description", type="text", example="Нарисовать портрет"),
     *     @OA\Property(property="price", type="float", example="1000"),
     *     @OA\Property(property="discount", type="integer", example="10"),
     *     @OA\Property(property="size", type="string", example="200*200"),
     *     @OA\Property(property="writing_technique", type="string", example="Масло"),
     *     @OA\Property(property="img", type="string", example="/storage/img/cats.jpg"),
     *     @OA\Property(property="created_at", type="string", example="2022-04-08T04:48:40.000000Z"),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=404,
     *     description="Not Found",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Not Found")
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
    public function newPictures()
    {
        $pictures = Picture::query()->where('status', '=', 'в обработке')->get();
        if ($pictures) {
            return PictureResource::collection($pictures);
        }
        return response()->json([
            'message' => 'Не найдено'
        ]);
    }

    /**
     *     @OA\Put(
     *     tags={"moderator"},
     *     path="/api/newPictures/{id}",
     *     summary="Allow",
     *     description="Allow sale",
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
     *     @OA\Property(property="message", type="string", example="Картина выставлена на продажу"),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=404,
     *     description="Not Found",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Not Found")
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
    public function acceptPicture($id){
        $picture = Picture::findOrFail($id);
        if ($picture->status == 'в обработке'){
            $picture->status = 'одобрено';
            $picture->save();
            return response()->json(['message' => 'Картина выставлена на продажу']);
        }
        return response()->json(['message' => 'Не найдено']);
    }

    /**
     *     @OA\Post(
     *     tags={"moderator"},
     *     path="/api/newPictures/{id}",
     *     summary="Reject",
     *     description="Refusal to sell",
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
     *     @OA\RequestBody(
     *     @OA\MediaType(
     *     mediaType="application/json",
     *     @OA\Schema(
     *     @OA\Property(property="reason", type="required, string, min:10, max:100"),
     *     example={
     *     "reason":"Недопустимое содержимое",
     *     },
     *     ),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Отказ в продаже"),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=404,
     *     description="Not Found",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Not Found")
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
    public function rejectedPictures(Request $request, $id){
        $validation = Validator::make($request->all(),[
            'reason' => 'required|string|min:10|max:100'
        ],[
            'reason.required' => 'обязательно для заполнения',
            'reason.min' => 'минимум 10 символов',
            'reason.max' => 'максимум 100 символов',
        ]);
        if ($validation->fails()){
            return response()->json([
            'reason_error' => $validation->errors()->first('reason')
       ]);
        }
        $picture = Picture::findOrFail($id);
        if ($picture->status == 'в обработке'){
            $failure = new Failure();
            $failure->picture_id = $picture->id;
            $failure->reason = $request->input('reason');
            $failure->save();
            $picture->status = 'отказано';
            $picture->save();
            return response()->json(['message' => 'Отказ в продаже']);
        }
        return response()->json(['message' => 'Не найдено']);
    }
}

<?php

namespace App\Http\Controllers\Users;

use App\Http\Resources\PictureResource;
use App\Models\Failure;
use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class ModeratorController extends Controller
{
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

    public function acceptPicture($id){
        $picture = Picture::findOrFail($id);
        if ($picture ==  'в обработке'){
            $picture->status = 'одобрено';
            $picture->save();
            return response()->json(['message' => 'Картина выставлена на продажу']);
        }
        return response()->json(['message' => 'Не найдено']);
    }


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
            return response()->json(['message' => 'Картина отказана в продаже']);
        }
        return response()->json(['message' => 'Не найдено']);
    }
}

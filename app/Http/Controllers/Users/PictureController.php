<?php

namespace App\Http\Controllers\Users;

use App\Http\Requests\PictureRequest;
use App\Http\Resources\AccountResource;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\PictureResource;
use App\Models\Account;
use App\Models\Application;
use App\Models\Genre;
use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class PictureController extends Controller
{

    public function getPictures(Request $request, $id, $sort, $k, $price){
        $pictures = PictureResource::collection(Picture::all());
        if ($sort !== '0'){
            if (($sort == 'price' && $k == 'max') || ($sort == 'created_at' && $k == 'max')){
                $pictures = $pictures->sortByDesc($sort);
            }
            else{
                $pictures = $pictures->sortBy($sort);
            }
        }
        if ($id !== '0'){
            $pictures = $pictures->where('genre_id', $id);
        }
        if ($price == '50') {
            $pictures = $pictures->filter(function ($value, $key){
               if($value['price'] < '50' && $value['price'] > '0'){
                   return true;
               }
            });
        }
        return $pictures;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PictureResource::collection(Picture::all());
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'genre_id' => 'required|exists:genres,id',
            'description' => 'nullable|string|min:10|max:300',
            'price' => 'required|numeric|min:1|max:999999',
            'discount' => 'nullable|integer|min:1|max:99',
            'size' => 'required|string|max:10',
            'writing_technique' => 'required|string|max:50',
            'img' => 'nullable|file|image|max:1024',
        ], [
            'title.required' => 'обязательно для заполнения',
            'title.max' => 'не более 50 символов',
            'genre_id.required' => 'выберите жанр',
            'genre_id.exists' => 'жанр не найден',
            'description.min' => 'не менее 10 символов',
            'description.max' => 'не более 300 символов',
            'writing_technique.required' => 'обязательно для заполнения',
            'writing_technique.max' => 'не более 50 символов',
            'price.required' => 'обязательно для заполнения',
            'price.numeric' => 'поле должно содержать число',
            'price.min' => 'минимум 1 руб',
            'price.max' => 'максимум 999999 руб',
            'discount.integer' => 'поле должно содержать целочисленное значение',
            'discount.min' => 'минимум 1%',
            'discount.max' => 'максимум 99%',
            'size.required' => 'обязательно для заполнения',
            'size.max' => 'не более 10 символов',
            'img.file' => 'должен быть выбран файл',
            'img.image' => 'должно быть выбрано изображение',
            'img.max' => 'не более 1КБ',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'title_error' => $validation->errors()->first('title'),
                'genre_error' => $validation->errors()->first('genre_id'),
                'description_error' => $validation->errors()->first('description'),
                'price_error' => $validation->errors()->first('price'),
                'discount_error' => $validation->errors()->first('discount'),
                'size_error' => $validation->errors()->first('size'),
                'writing_technique_error' => $validation->errors()->first('writing_technique'),
                'img_error' => $validation->errors()->first('img'),
            ], 400);
        }
        $path = '';
        if ($request->file('img')) {
            $path = $request->file('img')->store('/img');
        }
        $picture = new Picture();
        $picture->title = $request->input('title');
        $picture->genre_id = $request->input('genre_id');
        $picture->user_id = auth()->user()->id;
        $picture->description = $request->input('description');
        $picture->price = $request->input('price');
        $picture->discount = $request->input('discount');
        $picture->size = $request->input('size');
        $picture->writing_technique = $request->input('writing_technique');
        $picture->img = '/storage/' . $path;
        $picture->save();

        return response()->json([
            'message' => 'Картина отправлена на рассмотрение модератору'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Picture $picture
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $picture = Picture::find($id);
        if ($picture) {
            return new PictureResource($picture);
        }
        return response()->json([
            'message' => 'Не существует'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Picture $picture
     * @return \Illuminate\Http\Response
     */
    public function edit(Picture $picture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Picture $picture
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'genre_id' => 'required|exists:genres,id',
            'description' => 'nullable|string|min:10|max:300',
            'price' => 'required|numeric|min:1|max:999999',
            'discount' => 'nullable|integer|min:1|max:99',
            'size' => 'required|string|max:10',
            'writing_technique' => 'required|string|max:50',
            'img' => 'nullable|file|image|max:1024',
        ], [
            'title.required' => 'обязательно для заполнения',
            'title.max' => 'не более 50 символов',
            'genre_id.required' => 'выберите жанр',
            'genre_id.exists' => 'жанр не найден',
            'description.min' => 'не менее 10 символов',
            'description.max' => 'не более 300 символов',
            'writing_technique.required' => 'обязательно для заполнения',
            'writing_technique.max' => 'не более 50 символов',
            'price.required' => 'обязательно для заполнения',
            'price.numeric' => 'поле должно содержать число',
            'price.min' => 'минимум 1 руб',
            'price.max' => 'максимум 999999 руб',
            'discount.integer' => 'поле должно содержать целочисленное значение',
            'discount.min' => 'минимум 1%',
            'discount.max' => 'максимум 99%',
            'size.required' => 'обязательно для заполнения',
            'size.max' => 'не более 10 символов',
            'img.file' => 'должен быть выбран файл',
            'img.image' => 'должно быть выбрано изображение',
            'img.max' => 'не более 1КБ',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'title_error' => $validation->errors()->first('title'),
                'genre_error' => $validation->errors()->first('genre_id'),
                'description_error' => $validation->errors()->first('description'),
                'price_error' => $validation->errors()->first('price'),
                'discount_error' => $validation->errors()->first('discount'),
                'size_error' => $validation->errors()->first('size'),
                'writing_technique_error' => $validation->errors()->first('writing_technique'),
                'img_error' => $validation->errors()->first('img'),
            ], 400);
        }
        $picture = Picture::find($id);
        if ($picture) {
            if ($request->file('img')) {
                $path = $request->file('img')->store('/img');
                $picture->img = '/storage/' . $path;
            }
            $picture->title = $request->input('title');
            $picture->genre_id = $request->input('genre_id');
            $picture->user_id = auth()->user()->id;
            $picture->description = $request->input('description');
            $picture->price = $request->input('price');
            $picture->discount = $request->input('discount');
            $picture->size = $request->input('size');
            $picture->writing_technique = $request->input('writing_technique');
            $picture->save();
            return response()->json([
                'message' => 'Картина успешно изменена'
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
     * @param \App\Models\Picture $picture
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $picture = Picture::find($id);
        if ($picture) {
            $picture->delete();
            return response()->json([
                'message' => 'Картина успешно удалена'
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

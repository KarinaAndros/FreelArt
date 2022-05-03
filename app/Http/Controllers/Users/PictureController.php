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
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;


class PictureController extends Controller
{
    /**
     * @OA\Get(
     * tags={"users"},
     * path="/api/main/pictures",
     * summary="Pictures",
     * description="Get last pictures",
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example="1"),
     * @OA\Property(property="title", type="string", example="Картина"),
     * @OA\Property(property="user", type="string", example="Соня"),
     * @OA\Property(property="user_avatar", type="string", example="/storage/img/avatar.png"),
     * @OA\Property(property="genre", type="string", example="Портрет"),
     * @OA\Property(property="description", type="text", example="Описание картины"),
     * @OA\Property(property="price", type="float", example="1000"),
     * @OA\Property(property="discount", type="integer", example="10"),
     * @OA\Property(property="size", type="string", example="100*100"),
     * @OA\Property(property="writing_technique", type="string", example="Масло"),
     * @OA\Property(property="img", type="string", example="/storage/img/portret.jpg"),
     * @OA\Property(property="created_at", type="string", example="2022-04-16T14:32:03.000000Z"),
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Not Found")
     * )
     * )
     * )
     */

    public function PicturesMain(){
        $pictures = Picture::query()->limit(5)->orderByDesc('created_at')->get();
        if ($pictures){
            return PictureResource::collection($pictures);
        }
       return response()->json('Не найдено');
    }

//    public function getPictures(Request $request, $id, $sort, $k, $price){
//        $pictures = PictureResource::collection(Picture::all());
//        if ($sort !== '0'){
//            if (($sort == 'price' && $k == 'max') || ($sort == 'created_at' && $k == 'max')){
//                $pictures = $pictures->sortByDesc($sort);
//            }
//            else{
//                $pictures = $pictures->sortBy($sort);
//            }
//        }
//        if ($id !== '0'){
//            $pictures = $pictures->where('genre_id', $id);
//        }
//        if ($price == '50') {
//            $pictures = $pictures->filter(function ($value, $key){
//               if($value['price'] < '50' && $value['price'] > '0'){
//                   return true;
//               }
//            });
//        }
//        return $pictures;
//    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */


    /**
     * @OA\Get(
     * tags={"users"},
     * path="/api/pictures",
     * summary="Pictures",
     * description="Get all pictures",
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example="1"),
     * @OA\Property(property="title", type="string", example="Картина"),
     * @OA\Property(property="user", type="string", example="Соня"),
     * @OA\Property(property="user_avatar", type="string", example="/storage/img/avatar.png"),
     * @OA\Property(property="genre", type="string", example="Портрет"),
     * @OA\Property(property="description", type="text", example="Описание картины"),
     * @OA\Property(property="price", type="float", example="1000"),
     * @OA\Property(property="discount", type="integer", example="10"),
     * @OA\Property(property="size", type="string", example="100*100"),
     * @OA\Property(property="writing_technique", type="string", example="Масло"),
     * @OA\Property(property="img", type="string", example="/storage/img/portret.jpg"),
     * @OA\Property(property="created_at", type="string", example="2022-04-16T14:32:03.000000Z"),
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Not Found")
     * )
     * )
     * )
     */
    public function index()
    {
        $pictures = Picture::query()->where('status', '=', 'одобрено')->get();
        if ($pictures){
            return PictureResource::collection($pictures);
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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    /**
     *     @OA\Post(
     *     tags={"executor"},
     *     path="/api/pictures",
     *     summary="Pictures",
     *     description="Create picture",
     *     security = {{ "Bearer":{} }},
     *     @OA\RequestBody(
     *     @OA\MediaType(
     *     mediaType="application/json",
     *     @OA\Schema(
     *     @OA\Property(property="title", type="required,string,max:50"),
     *     @OA\Property(property="genre_id", type="required,exists:genres,id"),
     *     @OA\Property(property="description", type="nullable,string,min:10,max:300"),
     *     @OA\Property(property="price", type="required,numeric,min:1,max:999999"),
     *     @OA\Property(property="discount", type="nullable,integer,min:1,max:99"),
     *     @OA\Property(property="size", type="required,string,max:10"),
     *     @OA\Property(property="writing_technique", type="required,string,max:50"),
     *     @OA\Property(property="img", type="nullable,file,image,max:1024"),
     *     example={
     *     "title":"Портрет",
     *     "genre_id":"1",
     *     "description":"Нарисовать портрет",
     *     "price":"500",
     *     "discount":"",
     *     "size":"100*100",
     *     "writing_technique":"Масло",
     *     "img":"/storage/img/portret.jpg",
     *     },
     *     ),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Картина отправлена на рассмотрение модератору"),
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
     * @return PictureResource|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    /**
     *     @OA\Get(
     *     tags={"users"},
     *     path="/api/pictures/{id}",
     *     summary="Pictures",
     *     description="Find one picture",
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
     *     response=404,
     *     description="Not Found",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Not Found")
     *     ),
     *     ),
     *     )
     */
    public function show($id)
    {
        $picture = Picture::findOrFail($id);
        if ($picture) {
            return new PictureResource($picture);
        }
        return response()->json([
            'message' => 'Не найдено'
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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    /**
     *     @OA\Put(
     *     tags={"executor"},
     *     path="/api/pictures/{id}",
     *     summary="Pictures",
     *     description="Update picture",
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
     *     security = {{ "Bearer":{} }},
     *     @OA\RequestBody(
     *     @OA\MediaType(
     *     mediaType="application/json",
     *     @OA\Schema(
     *     @OA\Property(property="title", type="required,string,max:50"),
     *     @OA\Property(property="genre_id", type="required,exists:genres,id"),
     *     @OA\Property(property="description", type="nullable,string,min:10,max:300"),
     *     @OA\Property(property="price", type="required,numeric,min:1,max:999999"),
     *     @OA\Property(property="discount", type="nullable,integer,min:1,max:99"),
     *     @OA\Property(property="size", type="required,string,max:10"),
     *     @OA\Property(property="writing_technique", type="required,string,max:50"),
     *     @OA\Property(property="img", type="nullable,file,image,max:1024"),
     *     example={
     *     "title":"Портрет",
     *     "genre_id":"1",
     *     "description":"Нарисовать портрет",
     *     "price":"500",
     *     "discount":"",
     *     "size":"100*100",
     *     "writing_technique":"Масло",
     *     "img":"/storage/img/portret.jpg",
     *     },
     *     ),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Картина успешно изменена"),
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Picture $picture
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    /**
     *     @OA\Delete(
     *     tags={"executor"},
     *     path="/api/pictures/{id}",
     *     summary="Pictures",
     *     description="Delete picture",
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
     *     security = {{ "Bearer":{} }},
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Картина успешно удалена"),
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
        $picture = Picture::findOrFail($id);
        if ($picture) {
            $picture->delete();
            return response()->json([
                'message' => 'Картина успешно удалена'
            ]);
        }
        return response()->json([
            'message' => 'Не существует'
        ], 404);

    }
}

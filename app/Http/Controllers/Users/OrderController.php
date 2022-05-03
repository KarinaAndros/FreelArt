<?php

namespace App\Http\Controllers\Users;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use function Symfony\Component\Routing\Loader\Configurator\collection;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return OrderResource|\Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */

    /**
     *     @OA\Get(
     *     tags={"auth users"},
     *     path="/api/orders",
     *     summary="Orders",
     *     description="Get user's orders",
     *     security = {{ "Bearer":{} }},
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="id", type="integer", example="1"),
     *     @OA\Property(property="picture_id", type="integer", example="1"),
     *     @OA\Property(property="picture_name", type="string", example="Картина"),
     *     @OA\Property(property="amount", type="float", example="20"),
     *     @OA\Property(property="discount", type="integer", example="10"),
     *     @OA\Property(property="total", type="float", example="18"),
     *     @OA\Property(property="address", type="text", example="Адрес пользователя"),
     *     @OA\Property(property="status", type="string", example="в обработке"),
     *     @OA\Property(property="created_at", type="string", example="7 seconds ago"),
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

      if (auth()->user()->hasRole('admin')){
          $orders = Order::all();
          if ($orders){
              return OrderResource::collection($orders);
          }
      }
      else{
          $orders = auth()->user()->orders->all();
          if ($orders){
              return OrderResource::collection($orders);
          }
          return response()->json([
              'message' => 'Вы пока что ничего не приобрели'
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

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     *     @OA\Post(
     *     tags={"auth users"},
     *     path="/api/orders/{id}",
     *     summary="Orders",
     *     description="Create order",
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
     *     @OA\Property(property="address", type="required, string, min:10, max:150"),
     *     example={
     *     "address":"Адрес пользователя",
     *     },
     *     ),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Заказ находится в обработке"),
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
    public function store(Request $request, $id)
    {
        $validation = Validator::make($request->all(),[
           'address' => 'required|string|min:10|max:150'
        ],
        [
            'address.required' => 'обязательно для заполнения',
            'address.min' => 'не менее 10 символов',
            'address.max' => 'не более 150 символов',
        ]);
        if ($validation->fails()){
            return response()->json([
                'address_error' => $validation->errors()->first('address')
            ], 400);
        }
        $picture = Picture::findOrFail($id);
        if ($picture){
            $order = new Order();
            $order->picture_id = $picture->id;
            $order->user_id = auth()->user()->id;
            $order->amount = $picture->price;
            $order->discount = $picture->discount;
            $order->total = $picture->price - (($picture->discount/100)*$picture->price);
            $order->address = $request->input('address');
            $order->status = 'в обработке';
            $order->save();
            return response()->json([
                'message' => 'в обработке'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Users;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
      if (auth()->user()->hasRole('admin')){
          return OrderResource::collection(Order::all());
      }
      else{
          $orders = Order::query()->where('user_id', auth()->user()->id)->get();
          return  OrderResource::collection($orders);
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

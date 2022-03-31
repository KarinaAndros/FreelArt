<?php

namespace App\Http\Controllers\Users;

use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SubscriptionResource::collection(Subscription::all());
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
            'user_email' => 'required|string|max:50|email|unique:subscriptions',
        ],
            [
                'user_email.max' => 'не более 50 символов',
                'user_email.email' => 'некоректный тип',
                'user_email.unique' => 'email уже используется',
                'user_email.required' => 'обязательно для заполнения',
            ]);

        if ($validation->fails()) {
            return response()->json([
                'user_email_error' => $validation->errors()->first('user_email')
            ], 400);
        }
        $subscription = new Subscription();
        $subscription->user_email = $request->input('user_email');
        $subscription->save();

        return response()->json([
            'message' => 'Вы подписаны на рассылку'
        ]);


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Subscription $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Subscription $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription $subscription
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $subscription = Subscription::find($id);
        if ($subscription) {
            $subscription->status = 'приостоновлена';
            $subscription->save();
            return response()->json([
                'message' => 'Подписка на рассылку приостоновлена. Её можно возобновить в своём личном кабинете'
            ]);
        }
        return response()->json([
            'message' => 'Не существует'
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Subscription $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return AccountResource::collection(Account::all());
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
        $validation = Validator::make($request->all(),[
            'title' => 'required|string|max:70',
            'description' => 'required|string|min:10|max:300',
            'price' => 'required|numeric|min:1|max:999999',
            'discount' => 'nullable|integer|min:1|max:99',
            'application_category_id' => 'required|exists:application_categories,id',
        ],
        [
            'title.required' => 'обязательно для заполнения',
            'description.required' => 'обязательно для заполнения',
            'title.max' => 'не более 70 символов',
            'description.min' => 'не менее 10 символов',
            'description.max' => 'не более 300 символов',
            'price.required' => 'обязательно для заполнения',
            'price.numeric' => 'поле должно содержать число',
            'price.min' => 'минимум 1 руб',
            'price.max' => 'максимум 999999 руб',
            'discount.integer' => 'поле должно содержать целочисленное значение',
            'discount.min' => 'минимум 1%',
            'discount.max' => 'максимум 99%',
            'application_category_id.required' => 'выберите категорию',
            'application_category_id.exists' => 'категория не найдена',
        ]);
        if ($validation->fails()){
            return response()->json([
                'title_error' => $validation->errors()->first('title'),
                'description_error' => $validation->errors()->first('description'),
                'price_error' => $validation->errors()->first('price'),
                'discount_error' => $validation->errors()->first('discount'),
                'application_category_id_error' => $validation->errors()->first('application_category_id'),
            ], 400);
        }
        Account::create($validation->validated());
            return response()->json([
                'message' => 'Тип аккаунта успешно создан'
            ]);

        return response()->json([
            'message' => 'К сожалению, доступ закрыт'
        ], 403);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Account $account
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account = Account::find($id);
        if ($account) {
            return new AccountResource($account);
        }
        return response()->json([
            'message' => 'Не существует'
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Account $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Account $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(),[
            'title' => 'required|string|max:70',
            'description' => 'required|string|min:10|max:300',
            'price' => 'required|numeric|min:1|max:999999',
            'discount' => 'nullable|integer|min:1|max:99',
            'application_category_id' => 'required|exists:application_categories,id',
        ],
            [
                'title.required' => 'обязательно для заполнения',
                'description.required' => 'обязательно для заполнения',
                'title.max' => 'не более 70 символов',
                'description.min' => 'не менее 10 символов',
                'description.max' => 'не более 300 символов',
                'price.required' => 'обязательно для заполнения',
                'price.numeric' => 'поле должно содержать число',
                'price.min' => 'минимум 1 руб',
                'price.max' => 'максимум 999999 руб',
                'discount.integer' => 'поле должно содержать целочисленное значение',
                'discount.min' => 'минимум 1%',
                'discount.max' => 'максимум 99%',
                'application_category_id.required' => 'выберите категорию',
                'application_category_id.exists' => 'категория не найдена',
            ]);
        if ($validation->fails()){
            return response()->json([
                'title_error' => $validation->errors()->first('title'),
                'description_error' => $validation->errors()->first('description'),
                'price_error' => $validation->errors()->first('price'),
                'discount_error' => $validation->errors()->first('discount'),
                'application_category_id_error' => $validation->errors()->first('application_category_id'),
            ], 400);
        }
        $account = Account::find($id);
        if ($account) {
            $account->title = $request->input('title');
            $account->description = $request->input('description');
            $account->price = $request->input('price');
            $account->discount = $request->input('discount');
            $account->application_category_id = $request->input('application_category_id');
            $account->save();
            return response()->json([
                'message' => 'Тип аккаунта успешно изменён'
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
     * @param \App\Models\Account $account
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $account = Account::find($id);
        if ($account) {
            $account->delete();
            return response()->json([
                'message' => 'Тип аккаунта успешно удалён'
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

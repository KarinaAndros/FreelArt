<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AccountRequest;
use App\Http\Resources\AccountResource;
use App\Http\Resources\AccountUserResource;
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\UserResource;
use App\Models\Account;
use App\Models\AccountUser;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     *     @OA\Get(
     *     tags={"users"},
     *     path="/api/proAccount",
     *     summary="Pro account",
     *     description="Information about PRO account",
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="id", type="integer", example="1"),
     *     @OA\Property(property="title", type="string", example="PRO аккаунт"),
     *     @OA\Property(property="description", type="text", example="Возможность выполнять заказы категории PRO"),
     *     @OA\Property(property="price", type="float", example="1000"),
     *     @OA\Property(property="discount", type="integer", example="10"),
     *     @OA\Property(property="application_category", type="string", example="для PRO"),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=404,
     *     description="Not Found",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Not Found")
     *     )
     *     )
     *     )
     */
    public function proAccount(){
        $account = Account::query()->where('title', '=', 'PRO аккаунт')->get();
        if (is_null($account)){
            return abort('404');
        }
        return AccountResource::collection($account);
    }

    /**
     *     @OA\Get(
     *     tags={"admin"},
     *     path="/api/last/accounts",
     *     summary="Last PRO accounts",
     *     description="Information about last PRO account",
     *     security={{ "Bearer":{} }},
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="user", type="string", example="Ерёмина Екатерина"),
     *     @OA\Property(property="user_avatar", type="string", example="/storage/img/avatar.png"),
     *     @OA\Property(property="account", type="string", example="PRO аккаунт"),
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
     *     )
     *     )
     *     )
     */

    public function lastAccounts()
    {
        $account = Account::query()->where('title', '=', 'PRO аккаунт')->first();
        $last_accounts = AccountUser::query()->where('account_id', $account->id)->where('end_action', '!=', null)->orderByDesc('created_at')->limit(3)->get();
        if (is_null($last_accounts)){
            return response()->json([
                'message' => "Не найдено"
            ]);
        }
        return AccountUserResource::collection($last_accounts);
    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */


    /**
     *     @OA\Get(
     *     tags={"admin"},
     *     path="/api/accounts",
     *     summary="Account levels",
     *     description="Information about all accounts",
     *     security={{ "Bearer":{} }},
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="id", type="integer", example="1"),
     *     @OA\Property(property="title", type="string", example="PRO аккаунт"),
     *     @OA\Property(property="description", type="text", example="Возможность выполнять заказы категории PRO"),
     *     @OA\Property(property="price", type="float", example="1000"),
     *     @OA\Property(property="discount", type="integer", example="10"),
     *     @OA\Property(property="application_category", type="string", example="для PRO"),
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
     *     )
     *     )
     *     )
     */
    public function index()
    {
        $accounts = Account::all();
        if (is_null($accounts)){
            return response()->json([
                'message' => 'Не найдено'
            ]);
        }
        return AccountResource::collection($accounts);
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
     * @OA\Post(
     * tags={"admin"},
     * path="/api/accounts",
     * summary="Account levels",
     * description="Add account levels",
     * security = {{ "Bearer":{} }},
     * @OA\RequestBody(
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * @OA\Property(property="title", type="required, string, max:70"),
     * @OA\Property(property="description", type="required, string, min:10, max:300"),
     * @OA\Property(property="price", type="required, numeric, min:1, max:999999"),
     * @OA\Property(property="discount", type="nullable, integer, min:1, max:99"),
     * @OA\Property(property="application_category_id", type="required, exists:application_categories,id"),
     * example={
     * "title":"Базовый аккаунт",
     * "description":"Выдаётся каждому пользователю при регистрации",
     * "price":"0",
     * "discount":"",
     * "application_category_id":"1",
     * },
     * ),
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Уровень аккаунта успешно создан"),
     * )
     * ),
     * @OA\Response(
     * response=400,
     * description="Validation error",
     * @OA\JsonContent(
     * @OA\Property(property="title_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="description_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="price_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="discount_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="application_category_id_error", type="string", example="ошибка валидации"),
     * ),
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Forbidden")
     * ),
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthorized")
     * ),
     * ),
     * )
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
                'message' => 'Уровень аккаунта успешно создан'
            ]);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Account $account
     * @return AccountResource|\Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     * tags={"admin"},
     * path="/api/accounts/{id}",
     * summary="One account level",
     * description="Finding one account level",
     * security={{ "Bearer":{} }},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="Account level Id",
     * @OA\Schema(
     * type="integer",
     * format="int"
     * ),
     * required=true,
     * example=1
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example="1"),
     * @OA\Property(property="title", type="string", example="PRO аккаунт"),
     * @OA\Property(property="description", type="text", example="Возможность выполнять заказы категории PRO"),
     * @OA\Property(property="price", type="float", example="1000"),
     * @OA\Property(property="discount", type="integer", example="10"),
     * @OA\Property(property="application_category", type="string", example="для PRO"),
     * ),
     * ),
     * @OA\Response(
     * response=404,
     * description="Not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Not Found")
     * ),
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Forbidden")
     * ),
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthorized")
     * ),
     * ),
     * )
     */

    public function show($id)
    {
        $account = Account::findOrFail($id);
        if ($account) {
            return new AccountResource($account);
        }
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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    /**
     * @OA\Put(
     * tags={"admin"},
     * path="/api/accounts/{id}",
     * summary="Account levels",
     * description="Update account levels",
     * security = {{ "Bearer":{} }},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="Account level Id",
     * @OA\Schema(
     * type="integer",
     * format="int"
     * ),
     * required=true,
     * example=1
     * ),
     * @OA\RequestBody(
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * @OA\Property(property="title", type="required, string, max:70"),
     * @OA\Property(property="description", type="required, string, min:10, max:300"),
     * @OA\Property(property="price", type="required, numeric, min:1, max:999999"),
     * @OA\Property(property="discount", type="nullable, integer, min:1, max:99"),
     * @OA\Property(property="application_category_id", type="required, exists:application_categories,id"),
     * example={
     * "title":"Базовый аккаунт",
     * "description":"Выдаётся каждому пользователю при регистрации",
     * "price":"0",
     * "discount":"",
     * "application_category_id":"1",
     * },
     * ),
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Уровень аккаунта успешно изменён"),
     * )
     * ),
     * @OA\Response(
     * response=400,
     * description="Validation error",
     * @OA\JsonContent(
     * @OA\Property(property="title_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="description_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="price_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="discount_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="application_category_id_error", type="string", example="ошибка валидации"),
     * ),
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Forbidden")
     * ),
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthorized")
     * ),
     * ),
     * @OA\Response(
     * response=404,
     * description="Not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Not Found")
     * ),
     * ),
     * )
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
        $account = Account::findOrFail($id);
        if ($account) {
            $account->title = $request->input('title');
            $account->description = $request->input('description');
            $account->price = $request->input('price');
            $account->discount = $request->input('discount');
            $account->application_category_id = $request->input('application_category_id');
            $account->save();
            return response()->json([
                'message' => 'Уровень аккаунта успешно изменён'
            ]);
        }
        return response()->json([
            'message' => 'Не существует'
        ], 404);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Account $account
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    /**
     * @OA\Delete(
     * tags={"admin"},
     * path="/api/accounts/{id}",
     * summary="Account levels",
     * description="Delete account levels",
     * security = {{ "Bearer":{} }},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="Account level Id",
     * @OA\Schema(
     * type="integer",
     * format="int"
     * ),
     * required=true,
     * example=1
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Уровень аккаунта успешно удалён"),
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Forbidden")
     * ),
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthorized")
     * ),
     * ),
     * @OA\Response(
     * response=404,
     * description="Not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Not Found")
     * ),
     * ),
     * )
     */
    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        if ($account) {
            $account->delete();
            return response()->json([
                'message' => 'Уровень аккаунта успешно удалён'
            ]);
        }
        return response()->json([
            'message' => 'Не найдено'
        ], 404);

    }
}

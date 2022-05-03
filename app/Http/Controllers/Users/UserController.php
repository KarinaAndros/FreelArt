<?php

namespace App\Http\Controllers\Users;

use App\Http\Middleware\Authenticate;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Account;
use App\Models\AccountUser;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use http\Env\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;


class UserController extends Controller
{


    /**
     * @OA\Get(
     * tags={"admin"},
     * path="/api/users/last",
     * summary="New users",
     * description="Finding of three new users",
     * security={{ "Bearer":{} }},
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example="1"),
     * @OA\Property(property="name", type="string", example="Карина"),
     * @OA\Property(property="surname", type="string", example="Андрос"),
     * @OA\Property(property="patronymic", type="string", example="Владимировна"),
     * @OA\Property(property="email", type="email", example="karina.andros@mail.ru"),
     * @OA\Property(property="avatar", type="string", example="/storage/img/avatar.png"),
     * @OA\Property(property="phone", type="string", example="+7(904)123-45-67"),
     * @OA\Property(property="accounts", type="string", example="PRO аккаунт"),
     * @OA\Property(property="role", type="string", example="admin"),
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
     * response=403,
     * description="Forbidden",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Forbidden")
     * ),
     * ),
     * )
     */
    public function lastUsers()
    {
        $users = User::query()->limit(3)->orderByDesc('created_at')->get();
        if (!is_null($users)) {
            return UserResource::collection($users);
        }
        return response()->json([
            'message' => 'Не найдено'
        ]);
    }


    /**
     * @OA\Get(
     * tags={"users"},
     * path="/api/users",
     * summary="All users",
     * description="Finding all users",
     * security={{ "Bearer":{} }},
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example="1"),
     * @OA\Property(property="name", type="string", example="Карина"),
     * @OA\Property(property="surname", type="string", example="Андрос"),
     * @OA\Property(property="patronymic", type="string", example="Владимировна"),
     * @OA\Property(property="email", type="email", example="karina.andros@mail.ru"),
     * @OA\Property(property="avatar", type="string", example="/storage/img/avatar.png"),
     * @OA\Property(property="phone", type="string", example="+7(904)123-45-67"),
     * @OA\Property(property="accounts", type="string", example="PRO аккаунт"),
     * @OA\Property(property="role", type="string", example="admin"),
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

    public function index()
    {
        $users = User::all();
        if ($users) {
            return UserResource::collection(User::all());
        }
        return response()->json([
            'message' => 'Не найдено'
        ]);
    }


    /**
     * @OA\Post(
     * tags={"users"},
     * path="/api/users",
     * summary="Registration",
     * description="User registration",
     * @OA\RequestBody(
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * @OA\Property(property="name", type="string, required, max:50, regex:/[А-Яа-яЁё]/u"),
     * @OA\Property(property="surname", type="string, required, max:50, regex:/[А-Яа-яЁё]/u"),
     * @OA\Property(property="patronymic", type="string, nullable, max:50, regex:/[А-Яа-яЁё]/u"),
     * @OA\Property(property="login", type="string, required, max:50, regex:/[A-Za-z]/u, unique:users"),
     * @OA\Property(property="email", type="string, email, required, max:50, unique:users"),
     * @OA\Property(property="password", type="string, required, max:50, min:6, confirmed"),
     * @OA\Property(property="password_confirmation", type="string, required, max:50, min:6"),
     * @OA\Property(property="phone", type="string, nullable, max:50"),
     * @OA\Property(property="rule", type="tinyInteger, accepted"),
     * @OA\Property(property="role", type="string, required"),
     * @OA\Property(property="link", type="string, url, nullable"),
     * @OA\Property(property="gender", type="string, required"),
     * example={
     * "name":"Карина",
     * "surname":"Андрос",
     * "patronymic":"",
     * "login":"Karina",
     * "email":"karina.andros@mail.ru",
     * "password":"123456",
     * "password_confirmation":"123456",
     * "phone":"123456",
     * "rule":"1",
     * "role":"executor",
     * "link":"",
     * "gender":"ж",
     * },
     * ),
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Регистрация прошла успешно"),
     * @OA\Property(property="token", type="string", example="1|uPuAXRls1e1D7LUqc10Y8pVK6TOL8NM3BlPX4d9p"),
     * @OA\Property(property="id", type="integer", example="1"),
     * @OA\Property(property="name", type="string", example="Карина"),
     * @OA\Property(property="surname", type="string", example="Андрос"),
     * @OA\Property(property="email", type="email", example="karina.andros@mail.ru"),
     * @OA\Property(property="avatar", type="string", example="/storage/img/avatar.png"),
     * @OA\Property(property="role", type="string", example="executor"),
     * )
     * ),
     * @OA\Response(
     * response=400,
     * description="Validation error",
     * @OA\JsonContent(
     *  @OA\Property(property="name_error", type="string", example="ошибка валидации"),
     *  @OA\Property(property="surname_error", type="string", example="ошибка валидации"),
     *  @OA\Property(property="patronymic_error", type="string", example="ошибка валидации"),
     *  @OA\Property(property="email_error", type="string", example="ошибка валидации"),
     *  @OA\Property(property="login_error", type="string", example="ошибка валидации"),
     *  @OA\Property(property="password_error", type="string", example="ошибка валидации"),
     *  @OA\Property(property="password_confirmation_error", type="string", example="ошибка валидации"),
     *  @OA\Property(property="phone_error", type="string", example="ошибка валидации"),
     *  @OA\Property(property="link_error", type="string", example="ошибка валидации"),
     *  @OA\Property(property="role_error", type="string", example="ошибка валидации"),
     *  @OA\Property(property="rule_error", type="string", example="ошибка валидации"),
     *  @OA\Property(property="gender_error", type="string", example="ошибка валидации"),
     *  @OA\Property(property="avatar_error", type="string", example="ошибка валидации"),
     * ),
     * ),
     * )
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:50|regex:/[А-Яа-яЁё]/u',
            'surname' => 'required|string|max:50|regex:/[А-Яа-яЁё]/u',
            'patronymic' => 'nullable|string|max:50|regex:/[А-Яа-яЁё]/u',
            'login' => 'required|string|max:50|regex:/[A-Za-z]/u|unique:users',
            'email' => 'required|string|max:50|email|unique:users',
            'password' => 'required|string|max:50|min:6|confirmed',
            'password_confirmation' => 'required|string|max:50|min:6',
            'phone' => 'nullable|string|max:50',
            'gender' => 'required|string',
            'link' => 'nullable|string|url',
            'rule' => 'accepted',
        ],
            [
                'name.required' => 'обязательно для заполнения',
                'name.max' => 'не более 50 символов',
                'name.regex' => 'только символы кириллицы',
                'surname.max' => 'не более 50 символов',
                'surname.regex' => 'только символы кириллицы',
                'patronymic.max' => 'не более 50 символов',
                'patronymic.regex' => 'только символы кириллицы',
                'surname.required' => 'обязательно для заполнения',
                'login.required' => 'обязательно для заполнения',
                'login.max' => 'не более 50 символов',
                'login.regex' => 'только символы латиницы',
                'login.unique' => 'логин уже используется',
                'email.max' => 'не более 50 символов',
                'email.email' => 'некоректный тип',
                'email.unique' => 'email уже используется',
                'email.required' => 'обязательно для заполнения',
                'password.required' => 'обязательно для заполнения',
                'password.max' => 'не более 50 символов',
                'password.min' => 'не менее 6 символов',
                'password.confirmed' => 'пароль не подтверждён',
                'password_confirmation.required' => 'обязательно для заполнения',
                'phone.max' => 'не более 50 символов',
                'role.required' => 'обязательно для заполнения',
                'rule.accepted' => 'обязательно для потверждения',
                'link.url' => 'некоректная ссылка',
                'gender.required' => 'обязательно'
            ]);
        if ($validation->fails()) {
            return response()->json([
                'name_error' => $validation->errors()->first('name'),
                'surname_error' => $validation->errors()->first('surname'),
                'patronymic_error' => $validation->errors()->first('patronymic'),
                'login_error' => $validation->errors()->first('login'),
                'email_error' => $validation->errors()->first('email'),
                'password_error' => $validation->errors()->first('password'),
                'password_confirmation_error' => $validation->errors()->first('password_confirmation'),
                'phone_error' => $validation->errors()->first('phone'),
                'role_error' => $validation->errors()->first('role'),
                'link_error' => $validation->errors()->first('link'),
                'rule_error' => $validation->errors()->first('rule'),
                'gender_error' => $validation->errors()->first('gender')
            ], 400);
        }
        $user = new User($validation->validated());
        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->patronymic = $request->input('patronymic');
        $user->email = $request->input('email');
        $user->login = $request->input('login');
        $user->phone = $request->input('phone');
        if ($request->input('role') == 'executor') {
            $user->assignRole('user', 'executor');
        } else {
            $user->assignRole('user', 'customer');
        }
        $user->password = md5($request->input('password'));
        $user->link = $request->input('link');
        if ($request->input('gender') == 'female') {
            $user->gender = 'ж';
        } else {
            $user->gender = 'м';
        }
        $user->save();
        event(new Registered($user));
        $token = $user->createToken($request->input('login'))->plainTextToken;
        auth()->login($user);
        $account = Account::query()->where('title', 'Базовый аккаунт')->first();
        $account_user = new AccountUser();
        $account_user->user_id = $user->id;
        $account_user->account_id = $account->id;
        $account_user->start_action = Carbon::now();
        $account_user->save();
        return response()->json([
            'message' => 'Подтвердите свой почтовый ящик',
            'token' => $token,
            'user' => new UserResource(auth()->user()),
        ], 200);

    }

    /**
     * @OA\Post(
     * tags={"users"},
     * path="/api/login",
     * summary="Login",
     * description="Login",
     * @OA\RequestBody(
     * required=true,
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * @OA\Property(property="login", type="string, required"),
     * @OA\Property(property="password", type="string, required"),
     * example={
     * "login":"Karina",
     * "password":"123456"
     * },
     * ),
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Успешно"),
     * @OA\Property(property="token", type="string", example="120|lFXMHfJHYSxZpnhfEU2foyN9bcU1BnwoYzjSI73h"),
     * @OA\Property(property="id", type="integer", example="1"),
     * @OA\Property(property="name", type="string", example="Карина"),
     * @OA\Property(property="surname", type="string", example="Андрос"),
     * @OA\Property(property="patronymic", type="string", example="Владимировна"),
     * @OA\Property(property="email", type="email", example="karina.andros@mail.ru"),
     * @OA\Property(property="avatar", type="string", example="/storage/img/avatar.png"),
     * @OA\Property(property="phone", type="string", example="+7(904)123-45-67"),
     * @OA\Property(property="role", type="string", example="admin"),
     * ),
     * ),
     * @OA\Response(
     * response=400,
     * description="One of the required fields is not filled",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Все поля являются обязательными для заполнения")
     * ),
     * ),
     * @OA\Response(
     * response=401,
     * description="Incorrect login or password",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Введён неверный логин или пароль")
     * ),
     * ),
     * )
     */
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required'
        ],
            [
                'login.required' => 'обязательно для заполнения',
                'password.required' => 'обязательно для заполнения'
            ]);
        if ($validation->fails()) {
            return response()->json([
                'login_error' => $validation->errors()->first('login'),
                'password_error' => $validation->errors()->first('password')
            ], 400);
        }

        $user = User::query()->where('login', $request->input('login'))->where('password', md5($request->input('password')))->first();
        if ($user) {
            $token = $user->createToken($request->input('login'));
            auth()->login($user);
            return response()->json([
                'message' => 'Успешно',
                'token' => $token->plainTextToken,
                'user' => new UserResource($request->user())
            ], 200);

        }
        return response()->json([
            'message' => 'Неверный логин или пароль'
        ], 401);
    }


    /**
     * @OA\Post(
     * tags={"users"},
     * path="/api/logout",
     * summary="Logout",
     * description="Logout",
     * security={{ "Bearer":{} }},
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Вы вышли из системы"),
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

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Вы вышли из системы'], 200);
        }
    }

    /**
     * @OA\Get(
     * tags={"users"},
     * path="/api/users/{id}",
     * summary="Find one user",
     * description="Finding one user",
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="user Id",
     *        @OA\Schema(
     *           type="integer",
     *           format="int"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Успешно"),
     * @OA\Property(property="token", type="string", example="120|lFXMHfJHYSxZpnhfEU2foyN9bcU1BnwoYzjSI73h"),
     * @OA\Property(property="id", type="integer", example="1"),
     * @OA\Property(property="name", type="string", example="Карина"),
     * @OA\Property(property="surname", type="string", example="Андрос"),
     * @OA\Property(property="patronymic", type="string", example="Владимировна"),
     * @OA\Property(property="email", type="email", example="karina.andros@mail.ru"),
     * @OA\Property(property="avatar", type="string", example="/storage/img/avatar.png"),
     * @OA\Property(property="phone", type="string", example="+7(904)123-45-67"),
     * @OA\Property(property="role", type="string", example="admin"),
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

    public function show($id)
    {
        $user = User::findOrFail($id);
        if ($user) {
            return new UserResource($user);
        }
        return response()->json([
            'message' => 'Пользователь не найден'
        ], 404);
    }

    public function profile()
    {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return view('admin.index', ['user' => $user]);
        } elseif ($user->hasRole('customer')) {
            return view('users.customers.index', ['user' => $user]);
        }
        return view('users.executors.index', ['user' => $user]);
    }


    /**
     * @OA\Put(
     * tags={"users"},
     * path="/api/users",
     * summary="Edit data",
     * description="Edit data in profile",
     * security={{ "Bearer":{} }},
     * @OA\RequestBody(
     * @OA\MediaType(
     * mediaType="application/json",
     *     @OA\Schema(
     *              @OA\Property(
     *                     description="name",
     *                     property="name",
     *                     type="string",
     *                ),
     *            @OA\Property(
     *                     description="surname",
     *                     property="surname",
     *                     type="string",
     *                ),
     *            @OA\Property(
     *                     description="patronymic",
     *                     property="patronymic",
     *                     type="string",
     *                ),
     *            @OA\Property(
     *                     description="email",
     *                     property="email",
     *                     type="string",
     *                ),
     *            @OA\Property(
     *                     description="new_password",
     *                     property="new_password",
     *                     type="string",
     *                ),
     *            @OA\Property(
     *                     description="new_password_confirmation",
     *                     property="new_password_confirmation",
     *                     type="string",
     *                ),
     *               @OA\Property(
     *                     description="phone",
     *                     property="phone",
     *                     type="string",
     *                ),
     *               @OA\Property(
     *                     description="link",
     *                     property="link",
     *                     type="string",
     *                ),
     *                @OA\Property(
     *                     description="password",
     *                     property="password",
     *                     type="string",
     *                ),
     *          required={"name", "surname", "email", "password"},
     * ),
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Ваши данные успешно изменены"),
     * ),
     * ),
     * @OA\Response(
     * response=400,
     * description="Validation error",
     * @OA\JsonContent(
     * @OA\Property(property="name_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="surname_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="patronymic_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="email_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="new_password_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="new_password_confirmation_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="phone_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="link_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="avatar_error", type="string", example="ошибка валидации"),
     * @OA\Property(property="password_error", type="string", example="ошибка валидации"),
     * ),
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthorized"),
     * ),
     * ),
     * )
     */


    public function update(Request $request)
    {

        $user = auth()->user();
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:50|regex:/[А-Яа-яЁё]/u',
            'surname' => 'required|string|max:50|regex:/[А-Яа-яЁё]/u',
            'patronymic' => 'nullable|string|max:50|regex:/[А-Яа-яЁё]/u',
            'email' => ['required', 'string', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'required|string|max:50|min:6',
            'new_password' => 'nullable|string|max:50|min:6|confirmed',
            'phone' => 'nullable|string|max:50',
            'link' => 'nullable|string|url',
        ],
            [
                'name.required' => 'обязательно для заполнения',
                'name.max' => 'не более 50 символов',
                'name.regex' => 'только символы кириллицы',
                'surname.max' => 'не более 50 символов',
                'surname.regex' => 'только символы кириллицы',
                'patronymic.max' => 'не более 50 символов',
                'patronymic.regex' => 'только символы кириллицы',
                'surname.required' => 'обязательно для заполнения',
                'email.max' => 'не более 50 символов',
                'email.email' => 'некоректный тип',
                'email.unique' => 'email уже используется',
                'email.required' => 'обязательно для заполнения',
                'password.required' => 'заполните поле для сохранения данных',
                'password.max' => 'не более 50 символов',
                'password.min' => 'не менее 6 символов',
                'new_password.max' => 'не более 50 символов',
                'new_password.min' => 'не менее 6 символов',
                'new_password.confirmed' => 'новый пароль не подтверждён',
                'phone.max' => 'не более 50 символов',
                'link.url' => 'некоректная ссылка',
            ]);
        if ($validation->fails()) {
            return response()->json([
                'name_error' => $validation->errors()->first('name'),
                'surname_error' => $validation->errors()->first('surname'),
                'patronymic_error' => $validation->errors()->first('patronymic'),
                'email_error' => $validation->errors()->first('email'),
                'password_error' => $validation->errors()->first('password'),
                'new_password_error' => $validation->errors()->first('new_password'),
                'phone_error' => $validation->errors()->first('phone'),
                'avatar_error' => $validation->errors()->first('avatar'),
                'link_error' => $validation->errors()->first('link'),
            ], 400);
        }

        if ($user) {
            if (md5($request->input('password')) == $user->password) {
                $user->name = $request->input('name');
                $user->surname = $request->input('surname');
                $user->patronymic = $request->input('patronymic');
                $user->email = $request->input('email');
                $user->phone = $request->input('phone');
                $user->link = $request->input('link');
                if ($request->input('new_password') !== null) {
                    $user->password = md5($request->input('new_password'));
                }

                $user->save();
                return response()->json([
                    'message' => 'ваши данные успешно изменены'
                ]);
            }
            return response()->json([
                'message' => 'введён неверный пароль, данные не изменены'
            ]);
        }
        return response()->json([
            'message' => 'не найдено'
        ]);

    }

    /**
     * @OA\Post(
     * tags={"users"},
     * path="/api/avatar",
     * summary="Edit avatar",
     * description="Edit avatar in profile",
     * security={{ "Bearer":{} }},
     * @OA\RequestBody(
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     *     @OA\Schema(
     *              @OA\Property(
     *                     description="avatar",
     *                     property="avatar",
     *                     type="file",
     *     format="file"
     *                ),
     * ),
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Аватарка успешно изменена"),
     * ),
     * ),
     * @OA\Response(
     * response=400,
     * description="Validation error",
     * @OA\JsonContent(
     * @OA\Property(property="avatar_error", type="string", example="ошибка валидации"),
     * ),
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthorized"),
     * ),
     * ),
     * )
     */
    public function avatar(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'avatar' => 'nullable|image|max:1024',
        ],
            [
                'avatar.file' => 'должен быть выбран файл',
                'avatar.image' => 'должно быть выбрано изображение',
                'avatar.max' => 'не более 1КБ',
            ]);
        if ($validation->fails()) {
            return response()->json([
                'avatar_error' => $validation->errors()->first('avatar'),
            ], 400);
        }
        $user = auth()->user();
        if ($request->file('avatar')) {
            $path = $request->file('avatar')->store('/img');
            $user->avatar = '/storage/' . $path;
            $user->save();
            return response()->json([
                'message' => 'Аватарка успешно изменена'
            ]);
        } else {
            return response()->json([
                'message' => 'Выберите файл'
            ]);
        }

    }


}

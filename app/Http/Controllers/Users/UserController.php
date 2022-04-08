<?php

namespace App\Http\Controllers\Users;

use App\Http\Middleware\Authenticate;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Account;
use App\Models\AccountUser;
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
    public function index()
    {
        return UserResource::collection(User::all());
    }

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
            'role' => 'required',
            'avatar' => 'nullable|file|image|max:1024',
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
                'avatar.file' => 'должен быть выбран файл',
                'avatar.image' => 'должно быть выбрано изображение',
                'avatar.max' => 'не более 1КБ',
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
                'avatar_error' => $validation->errors()->first('avatar'),
                'rule_error' => $validation->errors()->first('rule'),
            ], 400);
        }
        $user = new User($validation->validated());
        if ($request->file('img')) {
            $path = $request->file('img')->store('/img');
            $user->avatar = '/storage/' . $path;
        }
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
        $user->save();

        event(new Registered($user));
        $token = $user->createToken($request->input('login'))->plainTextToken;

//        auth()->login($user);

//        return redirect()->route('verification.notice');
        $account = Account::query()->where('title', 'Базовый аккаунт')->first();
        $account_user = new AccountUser();
        $account_user->user_id = $user->id;
        $account_user->account_id = $account->id;
        $account_user->start_action = Carbon::now();
        $account_user->save();
        return response()->json(['token' => $token], 200);
    }

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
            if (auth()->user()) {
                return redirect()->route('profile');
            }

            return response()->json([
                'message' => 'Успешно',
                'token' => $token->plainTextToken,
                'user' => $request->user()
            ], 200);

        }
        return response()->json([
            'message' => 'Неверный логин или пароль'
        ], 401);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
            return redirect()->route('home');
            return response()->json(['message' => 'Вы вышли из системы'], 200);
        }
    }

    public function show($id)
    {
        $user = User::findOrFals($id);
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
            'avatar' => 'nullable|file|image|max:1024',
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
                'avatar.file' => 'должен быть выбран файл',
                'avatar.image' => 'должно быть выбрано изображение',
                'avatar.max' => 'не более 1КБ',
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
                if ($request->file('img')) {
                    $path = $request->file('img')->store('/img');
                    $user->avatar = '/storage/' . $path;
                }
                $user->name = $request->input('name');
                $user->surname = $request->input('surname');
                $user->patronymic = $request->input('patronymic');
                $user->email = $request->input('email');
                $user->phone = $request->input('phone');
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
            'message' => 'пользователь не найден'
        ]);
    }

}

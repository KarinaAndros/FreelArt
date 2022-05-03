<?php

namespace App\Http\Controllers\Users;

use App\Models\Account;
use App\Models\AccountUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class AccountUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     *     @OA\Post(
     *     tags={"users"},
     *     path="/api/account_users",
     *     summary="Purchase of a PRO account",
     *     description="Refusal to sell",
     *     security = {{ "Bearer":{} }},
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="message success", type="string", example="Вы приобрели PRO аккаунт"),
     *     @OA\Property(property="message error", type="string", example="Ваш аккаунт уже имеет данный уровень"),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=404,
     *     description="Not Found",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Not Found")
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

        $pro_account = Account::query()->where('title', '=', 'PRO аккаунт')->first();
        $accounts = auth()->user()->accounts;
        foreach ($accounts as $account) {
            if ($account->pivot->account_id == $pro_account->id) {
                return response()->json([
                    'message' => 'Ваш аккаунт уже имеет данный уровень'
                ]);
            } else {
                $account_user = new AccountUser();
                $account_user->user_id = auth()->user()->id;
                $account_user->account_id = $pro_account->id;
                $account_user->start_action = Carbon::now();
                $account_user->end_action = Carbon::now()->addMonth(2);
                $account_user->save();
                return response()->json([
                    'message' => 'Вы приобрели PRO аккаунт'
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AccountUser $accountUser
     * @return \Illuminate\Http\Response
     */
    public function show(AccountUser $accountUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AccountUser $accountUser
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountUser $accountUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AccountUser $accountUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccountUser $accountUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AccountUser $accountUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountUser $accountUser)
    {
        //
    }
}

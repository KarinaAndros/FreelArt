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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
            $account = Account::query()->where('title', 'PRO аккаунт')->first();
            $account_user = new AccountUser();
            $account_user->user_id = auth()->user()->id;
            $account_user->account_id = $account->id;
            $account_user->start_action = Carbon::now();
            $account_user->end_action = Carbon::now()->addMonth(2);
            $account_user->save();
            return response()->json([
                'message' => 'Вы приобрели PRO аккаунт'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccountUser  $accountUser
     * @return \Illuminate\Http\Response
     */
    public function show(AccountUser $accountUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccountUser  $accountUser
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountUser $accountUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountUser  $accountUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccountUser $accountUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountUser  $accountUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountUser $accountUser)
    {
        //
    }
}

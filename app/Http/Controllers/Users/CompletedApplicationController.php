<?php

namespace App\Http\Controllers\Users;

use App\Http\Resources\ApplicationResource;
use App\Http\Resources\CompletedApplicationResource;
use App\Models\Account;
use App\Models\AccountUser;
use App\Models\Application;
use App\Models\CompletedApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class CompletedApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index()
    {
        $applications = auth()->user()->completed_applications;
        if ($applications){
            return ApplicationResource::collection($applications);
        }
        else{
            return response()->json([
                'message' => 'вы не выполнили ни одного заказа'
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
     * @param \Illuminate\Http\Request $request
     * @return
     */
    public function store($id)
    {
        $account = Account::query()->where('title', 'PRO аккаунт')->first();
        $application = Application::findOrFail($id);
        if ($application) {
            $completed_application = new CompletedApplication();
            $completed_application->user_id = auth()->user()->id;
            $completed_application->application_id = $application->id;
            $completed_application->save();

            $counts = CompletedApplicationResource::collection(auth()->user()->completed_applications)->keys();
            if ($counts->count() == '20'){
                $account_user = new AccountUser();
                $account_user->user_id = auth()->user()->id;
                $account_user->account_id = $account->id;
                $account_user->start_action = Carbon::now();
                $account_user->save();
                return response()->json([
                    'message' => 'Выполнено 20 заказов. Вы получилаете аккаунт уровня PRO бесплатно'
                ]);
            }

        }

//        else{
//            return response()->json([
//                'message' => 'Не найдено'
//            ]);
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\CompletedApplication $completedApplication
     * @return \Illuminate\Http\Response
     */
    public function show(CompletedApplication $completedApplication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\CompletedApplication $completedApplication
     * @return \Illuminate\Http\Response
     */
    public function edit(CompletedApplication $completedApplication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CompletedApplication $completedApplication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompletedApplication $completedApplication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\CompletedApplication $completedApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompletedApplication $completedApplication)
    {
        //
    }
}

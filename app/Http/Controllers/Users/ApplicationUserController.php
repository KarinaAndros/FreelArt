<?php

namespace App\Http\Controllers\Users;

use App\Http\Resources\ApplicationResource;
use App\Http\Resources\ApplicationUserResource;
use App\Models\Application;
use App\Models\ApplicationUser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class ApplicationUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index()
    {
       return ApplicationUserResource::collection(auth()->user()->responses);
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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $validation =  Validator::make($request->all(),[
            'message' => 'required|string|min:10|max:200'
        ],[
            'message.required' => 'обязательно для заполнения',
            'message.min' => 'минимум 10 символов',
            'message.max' => 'максимум 200 символов',
        ]);
        if ($validation->fails()){
            return response()->json([
                'message_error' => $validation->errors()->first('message')
            ]);
        }
        $application = Application::findOrFail($id);
        $application_user = new ApplicationUser();
        $application_user->user_id = auth()->user()->id;
        $application_user->application_id = $application->id;
        $application_user->message = $request->input('message');
        $application_user->save();
        return response()->json(['message' => 'Вы откликнулись на заявку']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ApplicationUser  $applicationUser
     * @return \Illuminate\Http\Response
     */
    public function show(ApplicationUser $applicationUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ApplicationUser  $applicationUser
     * @return \Illuminate\Http\Response
     */
    public function edit(ApplicationUser $applicationUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ApplicationUser  $applicationUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApplicationUser $applicationUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ApplicationUser  $applicationUser
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $application_user = ApplicationUser::findOrFail($id);
        if ($application_user->status == 'отказано'){
            $application_user->delete();
            return response()->json([
                'message' => 'Удалено'
            ]);
        }
        elseif($application_user->status == 'на рассмотрении'){
            $application_user->delete();
            return response()->json([
                'message' => 'Ваша заявка на выполнение удалена'
            ]);
        }
    }
}

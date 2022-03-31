<?php

namespace App\Http\Controllers\Users;

use App\Models\ApplicationUser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ApplicationUserController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApplicationUser $applicationUser)
    {
        //
    }
}

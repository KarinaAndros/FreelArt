<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationCategoryResource;
use App\Models\ApplicationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ApplicationCategoryResource::collection(ApplicationCategory::all());
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
            'title' => 'required|string|max:100'
        ],
        [
            'title.required' => 'обязательно для заполнения',
            'title.max' => 'не более 100 символов',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'Ошибка добавления',
                'title_error' => $validation->errors()->first('title')
            ], 400);
        }
        ApplicationCategory::create($validation->validated());
        return response()->json([
            'message' => 'Категория успешно создана'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ApplicationCategory $applicationCategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new ApplicationCategoryResource(ApplicationCategory::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ApplicationCategory $applicationCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ApplicationCategory $applicationCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ApplicationCategory $applicationCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApplicationCategory $applicationCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ApplicationCategory $applicationCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $application_category = ApplicationCategory::find($id);
       if ($application_category){
           $application_category->delete();
           return response()->json([
               'message' => 'Категория успешно удалена'
           ]);
       }
        return response()->json([
            'message' => 'Ошибка удаления'
        ], 400);


    }
}

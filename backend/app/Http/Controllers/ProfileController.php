<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return Profile::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Profile::create($data);
        return response()->json($model, 201);
    }

    public function show(Profile $model)
    {
        return $model;
    }

    public function update(Request $request, Profile $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Profile $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

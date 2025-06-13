<?php

namespace App\Http\Controllers;

use App\Models\MaterialFilial;
use Illuminate\Http\Request;

class MaterialFilialController extends Controller
{
    public function index(Request $request)
    {
        return MaterialFilial::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = MaterialFilial::create($data);
        return response()->json($model, 201);
    }

    public function show(MaterialFilial $model)
    {
        return $model;
    }

    public function update(Request $request, MaterialFilial $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(MaterialFilial $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

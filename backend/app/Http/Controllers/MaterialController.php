<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        return Material::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Material::create($data);
        return response()->json($model, 201);
    }

    public function show(Material $model)
    {
        return $model;
    }

    public function update(Request $request, Material $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Material $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MaterialItem;
use Illuminate\Http\Request;

class MaterialItemController extends Controller
{
    public function index(Request $request)
    {
        return MaterialItem::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = MaterialItem::create($data);
        return response()->json($model, 201);
    }

    public function show(MaterialItem $model)
    {
        return $model;
    }

    public function update(Request $request, MaterialItem $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(MaterialItem $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

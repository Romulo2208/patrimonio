<?php

namespace App\Http\Controllers;

use App\Models\ItemRemessa;
use Illuminate\Http\Request;

class ItemRemessaController extends Controller
{
    public function index(Request $request)
    {
        return ItemRemessa::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = ItemRemessa::create($data);
        return response()->json($model, 201);
    }

    public function show(ItemRemessa $model)
    {
        return $model;
    }

    public function update(Request $request, ItemRemessa $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(ItemRemessa $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

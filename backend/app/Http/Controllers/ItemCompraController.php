<?php

namespace App\Http\Controllers;

use App\Models\ItemCompra;
use Illuminate\Http\Request;

class ItemCompraController extends Controller
{
    public function index(Request $request)
    {
        return ItemCompra::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = ItemCompra::create($data);
        return response()->json($model, 201);
    }

    public function show(ItemCompra $model)
    {
        return $model;
    }

    public function update(Request $request, ItemCompra $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(ItemCompra $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ItemPedido;
use Illuminate\Http\Request;

class ItemPedidoController extends Controller
{
    public function index(Request $request)
    {
        return ItemPedido::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = ItemPedido::create($data);
        return response()->json($model, 201);
    }

    public function show(ItemPedido $model)
    {
        return $model;
    }

    public function update(Request $request, ItemPedido $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(ItemPedido $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

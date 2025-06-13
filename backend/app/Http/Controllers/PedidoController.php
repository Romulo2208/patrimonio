<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        return Pedido::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Pedido::create($data);
        return response()->json($model, 201);
    }

    public function show(Pedido $model)
    {
        return $model;
    }

    public function update(Request $request, Pedido $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Pedido $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

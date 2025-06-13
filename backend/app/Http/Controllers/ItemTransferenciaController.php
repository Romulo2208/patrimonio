<?php

namespace App\Http\Controllers;

use App\Models\ItemTransferencia;
use Illuminate\Http\Request;

class ItemTransferenciaController extends Controller
{
    public function index(Request $request)
    {
        return ItemTransferencia::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = ItemTransferencia::create($data);
        return response()->json($model, 201);
    }

    public function show(ItemTransferencia $model)
    {
        return $model;
    }

    public function update(Request $request, ItemTransferencia $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(ItemTransferencia $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

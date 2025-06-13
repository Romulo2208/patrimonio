<?php

namespace App\Http\Controllers;

use App\Models\ItemOrcamento;
use Illuminate\Http\Request;

class ItemOrcamentoController extends Controller
{
    public function index(Request $request)
    {
        return ItemOrcamento::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = ItemOrcamento::create($data);
        return response()->json($model, 201);
    }

    public function show(ItemOrcamento $model)
    {
        return $model;
    }

    public function update(Request $request, ItemOrcamento $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(ItemOrcamento $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

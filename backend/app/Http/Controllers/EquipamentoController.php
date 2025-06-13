<?php

namespace App\Http\Controllers;

use App\Models\Equipamento;
use Illuminate\Http\Request;

class EquipamentoController extends Controller
{
    public function index(Request $request)
    {
        return Equipamento::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Equipamento::create($data);
        return response()->json($model, 201);
    }

    public function show(Equipamento $model)
    {
        return $model;
    }

    public function update(Request $request, Equipamento $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Equipamento $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

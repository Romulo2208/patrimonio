<?php

namespace App\Http\Controllers;

use App\Models\Carregamento;
use Illuminate\Http\Request;

class CarregamentoController extends Controller
{
    public function index(Request $request)
    {
        return Carregamento::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Carregamento::create($data);
        return response()->json($model, 201);
    }

    public function show(Carregamento $model)
    {
        return $model;
    }

    public function update(Request $request, Carregamento $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Carregamento $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use Illuminate\Http\Request;

class OrcamentoController extends Controller
{
    public function index(Request $request)
    {
        return Orcamento::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Orcamento::create($data);
        return response()->json($model, 201);
    }

    public function show(Orcamento $model)
    {
        return $model;
    }

    public function update(Request $request, Orcamento $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Orcamento $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

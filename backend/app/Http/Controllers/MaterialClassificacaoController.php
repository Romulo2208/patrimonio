<?php

namespace App\Http\Controllers;

use App\Models\MaterialClassificacao;
use Illuminate\Http\Request;

class MaterialClassificacaoController extends Controller
{
    public function index(Request $request)
    {
        return MaterialClassificacao::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = MaterialClassificacao::create($data);
        return response()->json($model, 201);
    }

    public function show(MaterialClassificacao $model)
    {
        return $model;
    }

    public function update(Request $request, MaterialClassificacao $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(MaterialClassificacao $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

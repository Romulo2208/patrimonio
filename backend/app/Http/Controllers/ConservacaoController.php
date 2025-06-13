<?php

namespace App\Http\Controllers;

use App\Models\Conservacao;
use Illuminate\Http\Request;

class ConservacaoController extends Controller
{
    public function index(Request $request)
    {
        return Conservacao::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Conservacao::create($data);
        return response()->json($model, 201);
    }

    public function show(Conservacao $model)
    {
        return $model;
    }

    public function update(Request $request, Conservacao $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Conservacao $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

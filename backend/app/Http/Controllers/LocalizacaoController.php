<?php

namespace App\Http\Controllers;

use App\Models\Localizacao;
use Illuminate\Http\Request;

class LocalizacaoController extends Controller
{
    public function index(Request $request)
    {
        return Localizacao::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Localizacao::create($data);
        return response()->json($model, 201);
    }

    public function show(Localizacao $model)
    {
        return $model;
    }

    public function update(Request $request, Localizacao $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Localizacao $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

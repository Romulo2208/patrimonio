<?php

namespace App\Http\Controllers;

use App\Models\Saida;
use Illuminate\Http\Request;

class SaidaController extends Controller
{
    public function index(Request $request)
    {
        return Saida::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Saida::create($data);
        return response()->json($model, 201);
    }

    public function show(Saida $model)
    {
        return $model;
    }

    public function update(Request $request, Saida $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Saida $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

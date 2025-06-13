<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use Illuminate\Http\Request;

class EntradaController extends Controller
{
    public function index(Request $request)
    {
        return Entrada::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Entrada::create($data);
        return response()->json($model, 201);
    }

    public function show(Entrada $model)
    {
        return $model;
    }

    public function update(Request $request, Entrada $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Entrada $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

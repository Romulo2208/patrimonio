<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use Illuminate\Http\Request;

class EstadoController extends Controller
{
    public function index(Request $request)
    {
        return Estado::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Estado::create($data);
        return response()->json($model, 201);
    }

    public function show(Estado $model)
    {
        return $model;
    }

    public function update(Request $request, Estado $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Estado $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

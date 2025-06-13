<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\Http\Request;

class TipoController extends Controller
{
    public function index(Request $request)
    {
        return Tipo::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Tipo::create($data);
        return response()->json($model, 201);
    }

    public function show(Tipo $model)
    {
        return $model;
    }

    public function update(Request $request, Tipo $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Tipo $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Setor;
use Illuminate\Http\Request;

class SetorController extends Controller
{
    public function index(Request $request)
    {
        return Setor::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Setor::create($data);
        return response()->json($model, 201);
    }

    public function show(Setor $model)
    {
        return $model;
    }

    public function update(Request $request, Setor $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Setor $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

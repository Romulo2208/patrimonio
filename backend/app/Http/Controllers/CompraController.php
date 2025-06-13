<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        return Compra::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Compra::create($data);
        return response()->json($model, 201);
    }

    public function show(Compra $model)
    {
        return $model;
    }

    public function update(Request $request, Compra $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Compra $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

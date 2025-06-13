<?php

namespace App\Http\Controllers;

use App\Models\Transferencia;
use Illuminate\Http\Request;

class TransferenciaController extends Controller
{
    public function index(Request $request)
    {
        return Transferencia::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Transferencia::create($data);
        return response()->json($model, 201);
    }

    public function show(Transferencia $model)
    {
        return $model;
    }

    public function update(Request $request, Transferencia $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Transferencia $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

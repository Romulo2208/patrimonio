<?php

namespace App\Http\Controllers;

use App\Models\SaldoAnterior;
use Illuminate\Http\Request;

class SaldoAnteriorController extends Controller
{
    public function index(Request $request)
    {
        return SaldoAnterior::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = SaldoAnterior::create($data);
        return response()->json($model, 201);
    }

    public function show(SaldoAnterior $model)
    {
        return $model;
    }

    public function update(Request $request, SaldoAnterior $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(SaldoAnterior $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

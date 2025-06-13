<?php

namespace App\Http\Controllers;

use App\Models\Patrimonio;
use Illuminate\Http\Request;

class PatrimonioController extends Controller
{
    public function index(Request $request)
    {
        return Patrimonio::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Patrimonio::create($data);
        return response()->json($model, 201);
    }

    public function show(Patrimonio $model)
    {
        return $model;
    }

    public function update(Request $request, Patrimonio $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Patrimonio $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

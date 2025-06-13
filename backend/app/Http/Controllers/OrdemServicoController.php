<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use Illuminate\Http\Request;

class OrdemServicoController extends Controller
{
    public function index(Request $request)
    {
        return OrdemServico::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = OrdemServico::create($data);
        return response()->json($model, 201);
    }

    public function show(OrdemServico $model)
    {
        return $model;
    }

    public function update(Request $request, OrdemServico $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(OrdemServico $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

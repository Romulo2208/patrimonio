<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        return Produto::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Produto::create($data);
        return response()->json($model, 201);
    }

    public function show(Produto $model)
    {
        return $model;
    }

    public function update(Request $request, Produto $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Produto $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

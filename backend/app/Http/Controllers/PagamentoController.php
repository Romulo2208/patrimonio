<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    public function index(Request $request)
    {
        return Pagamento::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Pagamento::create($data);
        return response()->json($model, 201);
    }

    public function show(Pagamento $model)
    {
        return $model;
    }

    public function update(Request $request, Pagamento $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Pagamento $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

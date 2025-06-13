<?php

namespace App\Http\Controllers;

use App\Models\NotaFiscal;
use Illuminate\Http\Request;

class NotaFiscalController extends Controller
{
    public function index(Request $request)
    {
        return NotaFiscal::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = NotaFiscal::create($data);
        return response()->json($model, 201);
    }

    public function show(NotaFiscal $model)
    {
        return $model;
    }

    public function update(Request $request, NotaFiscal $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(NotaFiscal $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Porta;
use Illuminate\Http\Request;

class PortaController extends Controller
{
    public function index(Request $request)
    {
        return Porta::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Porta::create($data);
        return response()->json($model, 201);
    }

    public function show(Porta $model)
    {
        return $model;
    }

    public function update(Request $request, Porta $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Porta $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

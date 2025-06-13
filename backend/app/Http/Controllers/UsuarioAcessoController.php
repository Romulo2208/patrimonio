<?php

namespace App\Http\Controllers;

use App\Models\UsuarioAcesso;
use Illuminate\Http\Request;

class UsuarioAcessoController extends Controller
{
    public function index(Request $request)
    {
        return UsuarioAcesso::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = UsuarioAcesso::create($data);
        return response()->json($model, 201);
    }

    public function show(UsuarioAcesso $model)
    {
        return $model;
    }

    public function update(Request $request, UsuarioAcesso $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(UsuarioAcesso $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

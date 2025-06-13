<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        return Usuario::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Usuario::create($data);
        return response()->json($model, 201);
    }

    public function show(Usuario $model)
    {
        return $model;
    }

    public function update(Request $request, Usuario $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Usuario $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

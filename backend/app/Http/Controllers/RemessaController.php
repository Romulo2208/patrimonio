<?php

namespace App\Http\Controllers;

use App\Models\Remessa;
use Illuminate\Http\Request;

class RemessaController extends Controller
{
    public function index(Request $request)
    {
        return Remessa::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = Remessa::create($data);
        return response()->json($model, 201);
    }

    public function show(Remessa $model)
    {
        return $model;
    }

    public function update(Request $request, Remessa $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(Remessa $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ServicoPeca;
use Illuminate\Http\Request;

class ServicoPecaController extends Controller
{
    public function index(Request $request)
    {
        return ServicoPeca::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = ServicoPeca::create($data);
        return response()->json($model, 201);
    }

    public function show(ServicoPeca $model)
    {
        return $model;
    }

    public function update(Request $request, ServicoPeca $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(ServicoPeca $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

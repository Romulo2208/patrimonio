<?php

namespace App\Http\Controllers;

use App\Models\ServicoItem;
use Illuminate\Http\Request;

class ServicoItemController extends Controller
{
    public function index(Request $request)
    {
        return ServicoItem::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = ServicoItem::create($data);
        return response()->json($model, 201);
    }

    public function show(ServicoItem $model)
    {
        return $model;
    }

    public function update(Request $request, ServicoItem $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(ServicoItem $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

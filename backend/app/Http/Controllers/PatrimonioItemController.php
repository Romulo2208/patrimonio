<?php

namespace App\Http\Controllers;

use App\Models\PatrimonioItem;
use Illuminate\Http\Request;

class PatrimonioItemController extends Controller
{
    public function index(Request $request)
    {
        return PatrimonioItem::paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $model = PatrimonioItem::create($data);
        return response()->json($model, 201);
    }

    public function show(PatrimonioItem $model)
    {
        return $model;
    }

    public function update(Request $request, PatrimonioItem $model)
    {
        $model->update($request->all());
        return $model;
    }

    public function destroy(PatrimonioItem $model)
    {
        $model->delete();
        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Material::query();
        if ($search) {
            $query->where('nome', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%");
        }
        return $query->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required',
            'barcode' => 'nullable|unique:materiais,barcode',
            'descricao' => 'nullable'
        ]);
        $material = Material::create($data);
        return response()->json($material, 201);
    }

    public function show(Material $material)
    {
        return $material;
    }

    public function update(Request $request, Material $material)
    {
        $material->update($request->all());
        return $material;
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return response()->noContent();
    }
}

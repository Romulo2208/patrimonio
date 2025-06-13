<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Fornecedor::query();
        if ($search) {
            $query->where('nome_fantasia', 'like', "%{$search}%");
        }
        return $query->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cnpj' => 'required|unique:fornecedores,cnpj',
            'nome_fantasia' => 'required',
        ]);
        $fornecedor = Fornecedor::create($data);
        return response()->json($fornecedor, 201);
    }

    public function show(Fornecedor $fornecedor)
    {
        return $fornecedor;
    }

    public function update(Request $request, Fornecedor $fornecedor)
    {
        $fornecedor->update($request->all());
        return $fornecedor;
    }

    public function destroy(Fornecedor $fornecedor)
    {
        $fornecedor->delete();
        return response()->noContent();
    }
}

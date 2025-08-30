<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    // Listar inventarios
    public function index()
    {
        $inventarios = Inventario::with('producto', 'movimientos')->get();
        return response()->json($inventarios);
    }

    // Mostrar inventario por ID
    public function show($id)
    {
        $inventario = Inventario::with('producto', 'movimientos')->find($id);

        if (!$inventario) {
            return response()->json(['message' => 'Inventario no encontrado'], 404);
        }

        return response()->json($inventario);
    }

    // Crear inventario (para un producto)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad_disponible' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:255',
        ]);

        // Asegurarse de que no tenga ya inventario
        if (Inventario::where('producto_id', $validated['producto_id'])->exists()) {
            return response()->json(['message' => 'El producto ya tiene un inventario registrado'], 400);
        }

        $inventario = Inventario::create($validated);

        return response()->json($inventario, 201);
    }

    // Actualizar inventario
    public function update(Request $request, $id)
    {
        $inventario = Inventario::find($id);

        if (!$inventario) {
            return response()->json(['message' => 'Inventario no encontrado'], 404);
        }

        $validated = $request->validate([
            'cantidad_disponible' => 'nullable|integer|min:0',
            'ubicacion' => 'nullable|string|max:255',
        ]);

        $inventario->update($validated);

        return response()->json($inventario);
    }

    // Eliminar inventario
    public function destroy($id)
    {
        $inventario = Inventario::find($id);

        if (!$inventario) {
            return response()->json(['message' => 'Inventario no encontrado'], 404);
        }

        $inventario->delete();

        return response()->json(['message' => 'Inventario eliminado correctamente']);
    }
}

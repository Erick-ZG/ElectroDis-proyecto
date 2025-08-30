<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;

class MovimientoInventarioController extends Controller
{
    // Listar movimientos
    public function index()
    {
        $movimientos = MovimientoInventario::with('inventario.producto')->get();
        return response()->json($movimientos);
    }

    // Mostrar movimiento por ID
    public function show($id)
    {
        $movimiento = MovimientoInventario::with('inventario.producto')->find($id);

        if (!$movimiento) {
            return response()->json(['message' => 'Movimiento no encontrado'], 404);
        }

        return response()->json($movimiento);
    }

    // Crear movimiento
    public function store(Request $request)
    {
        $validated = $request->validate([
            'inventario_id' => 'required|exists:inventarios,id',
            'tipo_movimiento' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:255',
            'fecha' => 'required|date',
        ]);

        $inventario = Inventario::find($validated['inventario_id']);

        // Actualizar stock según el tipo de movimiento
        if ($validated['tipo_movimiento'] === 'entrada') {
            $inventario->cantidad_disponible += $validated['cantidad'];
        } else { // salida
            if ($inventario->cantidad_disponible < $validated['cantidad']) {
                return response()->json(['message' => 'Stock insuficiente para salida'], 400);
            }
            $inventario->cantidad_disponible -= $validated['cantidad'];
        }

        $inventario->save();

        $movimiento = MovimientoInventario::create($validated);

        return response()->json($movimiento, 201);
    }

    // Actualizar movimiento (⚠️ usualmente no se permite, pero lo dejo por si acaso)
    public function update(Request $request, $id)
    {
        $movimiento = MovimientoInventario::find($id);

        if (!$movimiento) {
            return response()->json(['message' => 'Movimiento no encontrado'], 404);
        }

        $validated = $request->validate([
            'descripcion' => 'nullable|string|max:255',
            'fecha' => 'nullable|date',
        ]);

        $movimiento->update($validated);

        return response()->json($movimiento);
    }

    // Eliminar movimiento (⚠️ puede afectar stock, se debe validar)
    public function destroy($id)
    {
        $movimiento = MovimientoInventario::find($id);

        if (!$movimiento) {
            return response()->json(['message' => 'Movimiento no encontrado'], 404);
        }

        // Ajustar stock en reversa al eliminar
        $inventario = $movimiento->inventario;

        if ($movimiento->tipo_movimiento === 'entrada') {
            $inventario->cantidad_disponible -= $movimiento->cantidad;
        } else {
            $inventario->cantidad_disponible += $movimiento->cantidad;
        }

        $inventario->save();

        $movimiento->delete();

        return response()->json(['message' => 'Movimiento eliminado y stock actualizado']);
    }
}

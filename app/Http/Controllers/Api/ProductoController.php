<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoController extends Controller
{
    /**
     * Listar todos los productos con su categoría e inventario.
     */
    public function index()
    {
        $productos = Producto::with(['categoria', 'inventario'])->get();
        return response()->json($productos, 200);
    }

    /**
     * Crear un nuevo producto.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku'          => 'required|string|max:50|unique:productos,sku',
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'precio_venta' => 'required|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'ubicacion'    => 'nullable|string|max:255',
        ]);

        $producto = Producto::create($validated);

        return response()->json([
            'message'  => 'Producto creado exitosamente',
            'producto' => $producto->load(['categoria', 'inventario'])
        ], 201);
    }

    /**
     * Mostrar un producto específico.
     */
    public function show($id)
    {
        $producto = Producto::with(['categoria', 'inventario'])->findOrFail($id);
        return response()->json($producto, 200);
    }

    /**
     * Actualizar un producto existente.
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $validated = $request->validate([
            'sku'          => 'sometimes|string|max:50|unique:productos,sku,' . $producto->id,
            'nombre'       => 'sometimes|string|max:255',
            'descripcion'  => 'nullable|string',
            'categoria_id' => 'sometimes|exists:categorias,id',
            'precio_venta' => 'sometimes|numeric|min:0',
            'stock_minimo' => 'sometimes|integer|min:0',
            'ubicacion'    => 'nullable|string|max:255',
        ]);

        $producto->update($validated);

        return response()->json([
            'message'  => 'Producto actualizado exitosamente',
            'producto' => $producto->load(['categoria', 'inventario'])
        ], 200);
    }

    /**
     * Eliminar un producto.
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return response()->json(['message' => 'Producto eliminado exitosamente'], 200);
    }
}

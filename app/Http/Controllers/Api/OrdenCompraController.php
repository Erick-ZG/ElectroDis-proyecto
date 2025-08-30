<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrdenCompra;
use App\Models\DetalleOrdenCompra;
use Illuminate\Http\Request;

class OrdenCompraController extends Controller
{
    public function index()
    {
        $ordenes = OrdenCompra::with(['proveedor', 'detalles.producto'])->get();
        return response()->json($ordenes);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha' => 'required|date',
            'estado' => 'required|string',
            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|numeric|min:1',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        $orden = OrdenCompra::create([
            'proveedor_id' => $data['proveedor_id'],
            'fecha' => $data['fecha'],
            'estado' => $data['estado'],
        ]);

        foreach ($data['detalles'] as $detalle) {
            $detalle['orden_compra_id'] = $orden->id;
            DetalleOrdenCompra::create($detalle);
        }

        return response()->json($orden->load('detalles.producto'), 201);
    }

    public function show(OrdenCompra $ordenCompra)
    {
        return response()->json($ordenCompra->load(['proveedor', 'detalles.producto']));
    }

    public function update(Request $request, OrdenCompra $ordenCompra)
    {
        $data = $request->validate([
            'proveedor_id' => 'sometimes|exists:proveedores,id',
            'fecha' => 'sometimes|date',
            'estado' => 'sometimes|string',
            'detalles' => 'sometimes|array|min:1',
            'detalles.*.producto_id' => 'required_with:detalles|exists:productos,id',
            'detalles.*.cantidad' => 'required_with:detalles|numeric|min:1',
            'detalles.*.precio_unitario' => 'required_with:detalles|numeric|min:0',
        ]);

        $ordenCompra->update($request->only(['proveedor_id', 'fecha', 'estado']));

        if ($request->has('detalles')) {
            $ordenCompra->detalles()->delete(); // reseteamos los detalles
            foreach ($data['detalles'] as $detalle) {
                $detalle['orden_compra_id'] = $ordenCompra->id;
                DetalleOrdenCompra::create($detalle);
            }
        }

        return response()->json($ordenCompra->load('detalles.producto'));
    }

    public function destroy(OrdenCompra $ordenCompra)
    {
        $ordenCompra->detalles()->delete();
        $ordenCompra->delete();
        return response()->json(['message' => 'Orden de compra eliminada correctamente']);
    }
}

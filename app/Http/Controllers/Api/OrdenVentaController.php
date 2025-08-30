<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrdenVenta;
use App\Models\DetalleOrdenVenta;
use Illuminate\Http\Request;

class OrdenVentaController extends Controller
{
    public function index()
    {
        $ordenes = OrdenVenta::with(['cliente', 'detalles.producto'])->get();
        return response()->json($ordenes);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha' => 'required|date',
            'estado' => 'required|string',
            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|numeric|min:1',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        $orden = OrdenVenta::create([
            'cliente_id' => $data['cliente_id'],
            'fecha' => $data['fecha'],
            'estado' => $data['estado'],
        ]);

        foreach ($data['detalles'] as $detalle) {
            $detalle['orden_venta_id'] = $orden->id;
            DetalleOrdenVenta::create($detalle);
        }

        return response()->json($orden->load('detalles.producto'), 201);
    }

    public function show(OrdenVenta $ordenVenta)
    {
        return response()->json($ordenVenta->load(['cliente', 'detalles.producto']));
    }

    public function update(Request $request, OrdenVenta $ordenVenta)
    {
        $data = $request->validate([
            'cliente_id' => 'sometimes|exists:clientes,id',
            'fecha' => 'sometimes|date',
            'estado' => 'sometimes|string',
            'detalles' => 'sometimes|array|min:1',
            'detalles.*.producto_id' => 'required_with:detalles|exists:productos,id',
            'detalles.*.cantidad' => 'required_with:detalles|numeric|min:1',
            'detalles.*.precio_unitario' => 'required_with:detalles|numeric|min:0',
        ]);

        $ordenVenta->update($request->only(['cliente_id', 'fecha', 'estado']));

        if ($request->has('detalles')) {
            $ordenVenta->detalles()->delete();
            foreach ($data['detalles'] as $detalle) {
                $detalle['orden_venta_id'] = $ordenVenta->id;
                DetalleOrdenVenta::create($detalle);
            }
        }

        return response()->json($ordenVenta->load('detalles.producto'));
    }

    public function destroy(OrdenVenta $ordenVenta)
    {
        $ordenVenta->detalles()->delete();
        $ordenVenta->delete();
        return response()->json(['message' => 'Orden de venta eliminada correctamente']);
    }
}

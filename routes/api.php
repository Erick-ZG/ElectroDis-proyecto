<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controladores
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\ProveedorController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\InventarioController;
use App\Http\Controllers\Api\MovimientoInventarioController;
use App\Http\Controllers\Api\OrdenCompraController;
use App\Http\Controllers\Api\OrdenVentaController;

// === Rutas de Roles y Usuarios ===
Route::apiResource('roles', RoleController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('permissions', PermissionController::class);

// === Rutas Fase 2 ===

Route::apiResource('clientes', ClienteController::class);
Route::apiResource('proveedores', ProveedorController::class);
Route::apiResource('productos', ProductoController::class);
Route::apiResource('inventarios', InventarioController::class);
Route::apiResource('movimientos', MovimientoInventarioController::class);
Route::apiResource('ordenes-compra', OrdenCompraController::class);
Route::apiResource('ordenes-venta', OrdenVentaController::class);

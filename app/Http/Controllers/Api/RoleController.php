<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json(Role::all(), 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|unique:roles',
            'descripcion' => 'nullable|string',
        ]);

        $role = Role::create($data);
        return response()->json($role, 201);
    }

    public function show(Role $role)
    {
        return response()->json($role, 200);
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'nombre' => 'required|string|unique:roles,nombre,' . $role->id,
            'descripcion' => 'nullable|string',
        ]);

        $role->update($data);
        return response()->json($role, 200);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(null, 204);
    }
}

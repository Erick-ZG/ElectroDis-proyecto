<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(
            User::with('roles')->get(), // 👈 ahora roles (de Spatie)
            200
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'roles' => 'nullable|array',             // 👈 puede venir vacío
            'roles.*' => 'exists:roles,name',        // 👈 validar nombres de roles de Spatie
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        // Asignar rol por defecto si no se envió ninguno
        if (empty($data['roles'])) {
            $user->assignRole('usuario'); // 👈 rol por defecto
        } else {
            $user->assignRole($data['roles']);
        }

        return response()->json([
            'user' => $user,
            'roles' => $user->getRoleNames(),
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json([
            'user' => $user,
            'roles' => $user->getRoleNames(),
        ], 200);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'roles' => 'nullable|array',      // 👈 ahora se pasa roles
            'roles.*' => 'exists:roles,name',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        // Actualizar roles
        if (isset($data['roles'])) {
            if (empty($data['roles'])) {
                $user->syncRoles(['usuario']); // 👈 si se manda vacío, le dejamos solo "usuario"
            } else {
                $user->syncRoles($data['roles']); // 👈 reemplaza todos los roles actuales
            }
        }

        return response()->json([
            'user' => $user,
            'roles' => $user->getRoleNames(),
        ], 200);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}

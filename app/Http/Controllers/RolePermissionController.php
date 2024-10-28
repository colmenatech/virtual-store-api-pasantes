<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
        // Método para crear un nuevo rol
        public function createRole(Request $request)
        {
            // Validar los datos recibidos en la solicitud
            $request->validate([
                'name' => 'required|string|unique:roles,name', // El nombre del rol es requerido, debe ser una cadena y único
            ]);

            // Crear un nuevo rol con el nombre proporcionado
            $role = Role::create(['name' => $request->name]);

            // Retornar una respuesta JSON indicando que el rol fue creado exitosamente
            return response()->json(['message' => 'Role created successfully', 'role' => $role]);
        }

        // Método para crear un nuevo permiso
        public function createPermission(Request $request)
        {
            // Validar los datos recibidos en la solicitud
            $request->validate([
                'name' => 'required|string|unique:permissions,name', // El nombre del permiso es requerido, debe ser una cadena y único
            ]);

            // Crear un nuevo permiso con el nombre proporcionado
            $permission = Permission::create(['name' => $request->name]);

            // Retornar una respuesta JSON indicando que el permiso fue creado exitosamente
            return response()->json(['message' => 'Permission created successfully', 'permission' => $permission]);
        }

        // Método para asignar permisos a un rol existente
        public function assignPermissionToRole(Request $request)
        {
            // Validar los datos recibidos en la solicitud
            $request->validate([
                'role_name' => 'required|string|exists:roles,name', // El nombre del rol es requerido, debe ser una cadena y debe existir en la tabla roles
                'permissions' => 'required|array', // Los permisos son requeridos y deben ser un array
                'permissions.*' => 'required|string|exists:permissions,name', // Cada permiso en el array debe ser una cadena y debe existir en la tabla permissions
            ]);

            // Buscar el rol por nombre
            $role = Role::where('name', $request->role_name)->firstOrFail();

            // Asignar los permisos al rol
            $role->syncPermissions($request->permissions);

            // Retornar una respuesta JSON indicando que los permisos fueron asignados exitosamente
            return response()->json(['message' => 'Permissions assigned to role successfully']);
        }
}

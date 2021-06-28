<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'Administrador']);
        $role2 = Role::create(['name' => 'Usuario']);
        //PERMISOS PARA TODAS LAS VISTAS
        Permission::create(['name' => 'dashboard', 'description' => 'Inicio Servidor UIS'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'dashboard.show', 'description' => 'Directorios Servidor UIS'])->syncRoles([$role1, $role2]);

        Permission::create(['name' => 'home.index', 'description' => 'Dashboard administradores'])->assignRole($role1);

        Permission::create(['name' => 'recycleds.index', 'description' => 'Papelera de Reciclaje'])->assignRole($role1);

        Permission::create(['name' => 'users.index', 'description' => 'Lista de usuarios'])->assignRole($role1);
        Permission::create(['name' => 'users.create', 'description' => 'Agregar usuarios'])->assignRole($role1);
        Permission::create(['name' => 'users.edit', 'description' => 'Editar usuarios'])->assignRole($role1);
        Permission::create(['name' => 'users.destroy', 'description' => 'Eliminar usuarios'])->assignRole($role1);

        Permission::create(['name' => 'roles.index', 'description' => 'Lista de roles'])->assignRole($role1);
        Permission::create(['name' => 'roles.create', 'description' => 'Agregar roles'])->assignRole($role1);
        Permission::create(['name' => 'roles.edit', 'description' => 'Editar roles'])->assignRole($role1);
        Permission::create(['name' => 'roles.destroy', 'description' => 'Eliminar roles'])->assignRole($role1);

        Permission::create(['name' => 'categories.index', 'description' => 'Lista de directorios'])->assignRole($role1);
        Permission::create(['name' => 'categories.create', 'description' => 'Agregar directorios'])->assignRole($role1);
        Permission::create(['name' => 'categories.edit', 'description' => 'Editar directorios'])->assignRole($role1);
        //Permission::create(['name' => 'categories.destroy', 'description' => 'Eliminar directorios'])->assignRole($role1);

        Permission::create(['name' => 'files.index', 'description' => 'Lista de archivos'])->assignRole($role1);
        //Permission::create(['name' => 'files.create', 'description' => 'Agregar archivos'])->assignRole($role1);
        Permission::create(['name' => 'files.edit', 'description' => 'Editar archivos'])->assignRole($role1);
        Permission::create(['name' => 'files.destroy', 'description' => 'Eliminar archivos'])->assignRole($role1);

        Permission::create(['name' => 'logs.index', 'description' => 'Logs'])->assignRole($role1);

        Permission::create(['name' => 'dashboard.list_files', 'description' => 'Lista de archivos Servidor UIS'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'dashboard.delete_files', 'description' => 'Borrar archivos Servidor UIS'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'dashboard.edit_files', 'description' => 'Cargar edición de archivos Servidor UIS'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'dashboard.details', 'description' => 'Detalles Servidor UIS'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'dashboard.created_files', 'description' => 'Crear archivos Servidor UIS'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'dashboard.download_files', 'description' => 'Descargar archivos Servidor UIS'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'dashboard.update_files', 'description' => 'Editar archivos Servidor UIS'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'dashboard.comprimir_files', 'description' => 'Comprimir archivos Servidor UIS'])->syncRoles([$role1, $role2]);

        Permission::create(['name' => 'dashboard.folderdetails', 'description' => 'Detalles carpetas Servidor UIS'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'dashboard.create_folder', 'description' => 'Crear carpeta Servidor UIS'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'dashboard.edit_folder', 'description' => 'Cargar edición de carpeta Servidor UIS'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'dashboard.update_folder', 'description' => 'Editar carpeta Servidor UIS'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'dashboard.delete_folder', 'description' => 'Eliminar carpeta Servidor UIS'])->syncRoles([$role1, $role2]);

    }
}

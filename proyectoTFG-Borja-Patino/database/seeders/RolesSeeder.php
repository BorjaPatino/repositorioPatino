<?php

use Spatie\Permission\Models\Role;
use App\Models\Usuario;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // 1. Crear roles (esto se ejecutará automáticamente)
        $adminRole = Role::create(['nombre' => 'admin']);
        $userRole = Role::create(['nombre' => 'user']);
        
        // 2. Opcional: Asignar rol admin al primer usuario
        if ($user = Usuario::first()) {
            $user->assignRole('admin');
        }
    }
}
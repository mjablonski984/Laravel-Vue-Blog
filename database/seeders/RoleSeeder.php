<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    
        $roles = [
            [
            'id'=>1,
            'roleName'=>'Admin',
            'permission'=>'[{"resourceName":"Home","read":true,"write":true,"update":true,"delete":true,"name":"/app"},{"resourceName":"Users","read":true,"write":true,"update":true,"delete":true,"name":"/app/users"},{"resourceName":"Roles","read":true,"write":true,"update":true,"delete":true,"name":"/app/roles"},{"resourceName":"Permissions","read":true,"write":true,"update":true,"delete":true,"name":"/app/permissions"},{"resourceName":"Posts","read":true,"write":true,"update":true,"delete":true,"name":"/app/posts"},{"resourceName":"Add Post","read":true,"write":true,"update":true,"delete":true,"name":"/app/createpost"},{"resourceName":"Categories","read":true,"write":true,"update":true,"delete":true,"name":"/app/categories"},{"resourceName":"Tags","read":true,"write":true,"update":true,"delete":true,"name":"/app/tags"}]',
            'isAdmin'=> 1,
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'id'=>2,
            'roleName'=>'Editor',
            'permission'=>'[{"resourceName":"Home","read":true,"write":true,"update":true,"delete":false,"name":"/app"},{"resourceName":"Users","read":true,"write":false,"update":false,"delete":false,"name":"/app/users"},{"resourceName":"Roles","read":false,"write":false,"update":false,"delete":false,"name":"/app/roles"},{"resourceName":"Permissions","read":false,"write":false,"update":false,"delete":false,"name":"/app/permissions"},{"resourceName":"Posts","read":true,"write":true,"update":true,"delete":true,"name":"/app/posts"},{"resourceName":"Add Post","read":true,"write":true,"update":true,"delete":true,"name":"/app/createpost"},{"resourceName":"Categories","read":true,"write":true,"update":true,"delete":true,"name":"/app/categories"},{"resourceName":"Tags","read":true,"write":true,"update":true,"delete":true,"name":"/app/tags"}]',
            'isAdmin'=> 1,
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'id'=>3,
            'roleName'=>'User',
            'permission'=>'[{"resourceName":"Home","read":false,"write":false,"update":false,"delete":false,"name":"/app"},{"resourceName":"Tags","read":false,"write":false,"update":false,"delete":false,"name":"app/tags"},{"resourceName":"Categories","read":false,"write":false,"update":false,"delete":false,"name":"/app/categories"},{"resourceName":"Create post","read":false,"write":false,"update":false,"delete":false,"name":"/app/createpost"},{"resourceName":"Posts","read":false,"write":false,"update":false,"delete":false,"name":"/app/posts"},{"resourceName":"Admin users","read":false,"write":false,"update":false,"delete":false,"name":"/app/users"},{"resourceName":"Roles","read":false,"write":false,"update":false,"delete":false,"name":"/app/roles"},{"resourceName":"Assign Roles","read":false,"write":false,"update":false,"delete":false,"name":"/app/permissions"}]',
            'isAdmin'=> 0,
            'created_at' => now(),
            'updated_at' => now(),
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    
    }
}

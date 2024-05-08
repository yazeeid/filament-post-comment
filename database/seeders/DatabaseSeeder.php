<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $user1=User::factory()->create([
            'name' => 'Admin',
            'email' => 'Admin@gmail.com',
        ]);
        $user2=User::factory()->create([
            'name'=> 'Test',
            'email'=> 'Test@gmail.com',
        ]);

        $role = Role::create(['name' => 'Admin']);
        $user1->assignRole($role);
        $role = Role::create(['name' => 'Test']);
        $user2->assignRole($role);

    }
}

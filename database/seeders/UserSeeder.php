<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::all();

        $users = User::factory(10)->create();

        /** @var User $user */
        foreach ($users as $user) {
            $user->roles()->attach($roles[rand(0, $roles->count() - 1)]);
        }
    }
}

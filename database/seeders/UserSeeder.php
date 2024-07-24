<?php

namespace Database\Seeders;

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
        User::updateOrCreate([
            'email' => 'admin@posgarden.com'
        ], [
            'first_name' => 'Admin',
            'last_name' => 'POS',
            'email'=>'admin@posgarden.com',
            'password' => bcrypt('posgarden.pk')
        ]);
    }
}

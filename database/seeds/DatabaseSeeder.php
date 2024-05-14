<?php

use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        (new User()) -> updateOrCreate(
            [
                'email' => 'admin@admin.com',
            ],
            [
                'email' => 'admin@admin.com',
                'is_admin' => true,
                'password' => bcrypt('12341234'),
            ]);
    }
}

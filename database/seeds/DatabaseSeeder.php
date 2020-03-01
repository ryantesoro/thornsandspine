<?php

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

        DB::table('users')->insert([
            'email' => 'test',
            'password' => bcrypt('test'),
            'access_level' => 2
        ]);

        DB::table('configurations')->insert([
            [
                'name' => 'address',
                'value' => '#123 Example St., Taytay, Rizal'
            ],
            [
                'name' => 'contact_number',
                'value' => '123456789'
            ],
            [
                'name' => 'email',
                'value' => 'thornsandspines@blacklistgraphics.com'
            ],
            [
                'name' => 'bank_name',
                'value' => 'BPI'
            ],
            [
                'name' => 'card_number',
                'value' => '1234567890123456'
            ],
            [
                'name' => 'gcash_number',
                'value' => '9161234567'
            ]
        ]);
    }
}

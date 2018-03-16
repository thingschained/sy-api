<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('flights')->insert(
        	[
            'name' => str_random(10),
            'airline' => 'A'.rand(100,900),
        ]
        );
    }
}

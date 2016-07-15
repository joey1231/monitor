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
         $this->call(CreateAddtioanlInfoContactSeeder::class);
         $this->call(CreateUsersTableSeeders::class);
    }
}

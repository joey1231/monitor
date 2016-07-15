<?php

use Illuminate\Database\Seeder;

class CreateUsersTableSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            ['email' => 'njsortigosa@gmail.com', 'password' => bcrypt('dwe2002'), 'first_name' => 'Nonz', 'last_name' => 'Sortigosa'],
            ['email' => 'markus@dreamwareenterprise.com', 'password' => bcrypt('dwe2002'), 'first_name' => 'Markus', 'last_name' => 'Skupeika'],
            ['email' => 'ljgalanza@gmail.com', 'password' => bcrypt('dwe2002'), 'first_name' => 'Liza', 'last_name' => 'Glanza'],
            ['email' => 'janice@dreamwareenterprise.com', 'password' => bcrypt('dwe2002'), 'first_name' => 'Janice', 'last_name' => 'Iquina'],
            ['email' => 'erik@optimalchemical.com', 'password' => bcrypt('dwe2002'), 'first_name' => 'Erik', 'last_name' => 'Skupeika'],
            ['email' => 'luz@dreamwareenterprise.com', 'password' => bcrypt('dwe2002'), 'first_name' => 'Luz', 'last_name' => 'Chauca'],
            ['email' => 'admin@dwecentral.com', 'password' => bcrypt('dwe2002'), 'first_name' => 'Admin', 'last_name' => 'Admin'],
            ['email' => 'joeydngcng1231@gmail.com', 'password' => bcrypt('dwe2002'), 'first_name' => 'Joey', 'last_name' => 'Dingcong'],

        ]);
    }
}

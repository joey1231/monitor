<?php

use Illuminate\Database\Seeder;

class CreateAddtioanlInfoContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('additional_contact_info')->insert([
                ['id_supplier'=>1,'info_name'=>'joey dingcong','info_details'=>'sample']
            ]);
    }
}

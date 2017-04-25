<?php

use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clients')->delete();
        factory(turnip\Clients::class, 400)->create()->each(function($c) {
            $c->orders()->saveMany(factory(turnip\Orders::class, 2)->make());
        });
    }
}
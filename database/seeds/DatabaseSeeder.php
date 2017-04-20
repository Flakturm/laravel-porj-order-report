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
        // Un Guard model
        Eloquent::unguard();

        // Ask for db migration refresh, default is no
        // if ($this->command->confirm('Do you wish to refresh migration before seeding, it will clear all old data ?')) {

        //     // Call the php artisan migrate:refresh using Artisan
        //     $this->command->call('migrate:refresh');

        //     $this->command->line("Data cleared, starting from blank database.");
        // }

        // $this->call('ProductsTableSeeder');
        // $this->command->info("Products seeded :)");

        // // How many users you need, defaulting to 20
        // $numberOfClient = $this->command->ask('How many clients do you need ?', 1000);

        // $this->command->info("Creating {$numberOfClient} users, each will have a channel associated.");

        // // Create the order, it will create a client and assign the order
        // $orders = factory(turnip\Orders::class, $numberOfClient)->create();

        // $this->command->info('Clients Created!');

        // // How many videos per channel
        // $orderProductRange = $this->command->ask('How many products per order should have, give a range ?', '10-20');

        // // Loop and create the product in range with order id
        // $orders->each(function($order) use ($orderProductRange){
        //     factory(turnip\OrderProducts::class, $this->getRandomRange($orderProductRange))
        //             ->create(['order_id' => $order->id]);
        // });

        // $this->command->info("Now all orders have {$orderProductRange} products !");

        if ($this->command->confirm('Do you wish to refresh migration before seeding, it will clear all old data ?')) {

            // Call the php artisan migrate:refresh using Artisan
            $this->command->call('migrate:refresh');

            $this->command->line("Data cleared, starting from blank database.");
        }
        $this->call('ProductsTableSeeder');
        $this->command->info("Products table seeded :)");
        $this->call('ClientsTableSeeder');
        $this->command->info("Clients table seeded :)");
        $this->call('OrderProductsTableSeeder');
        $this->command->info("Order products table seeded :)");

        // Re Guard model
        Eloquent::reguard();
    }

    /**
     * Return random value in given range
     *
     * @param $videoRange
     * @return int
     */
    // function getRandomRange($range)
    // {
    //     return rand(...explode('-', $range));
    // }
}
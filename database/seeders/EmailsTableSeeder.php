<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Email;

class EmailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Email::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 10; $i++) {
            Email::create([
            	'email' => $faker->unique()->email,
                'subject' => $faker->unique()->sentence,
                'body' => $faker->paragraph,
            ]);
        }
    }
}

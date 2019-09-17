<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\Contacts\Group;

class GroupSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for ($i=0; $i < 1; $i++) {
            Group::create([
                'user_id' => '76791420-964b-11e8-926d-1b09462347d8',
                'name' => $faker->company,
                'description' => 'Fuga totam reiciendis qui architecto fugiat nemo. Consequatur recusandae qui cupiditate eos quod.'
            ]);
        }
    }
}

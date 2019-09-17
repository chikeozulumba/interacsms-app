<?php

use Illuminate\Database\Seeder;
use App\Models\Contacts\Contact;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 20; $i++) {
            Contact::create([
                'user_id' => '76791420-964b-11e8-926d-1b09462347d8',
                'first_name' => 'Chike',
                'last_name' => 'Ozulumba',
                'phone_1' => '08131976306',
                'phone_2' => '08131976306',
                'location' => 'Nigeria',
            ]);
        }
    }
}

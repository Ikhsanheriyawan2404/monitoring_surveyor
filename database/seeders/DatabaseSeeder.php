<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Contact;
use App\Models\Organization;
use App\Models\Surveyor;
use App\Models\User;
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
        $account = Account::create(['name' => 'Acme Corporation']);

        User::factory()->create([
            'account_id' => $account->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'owner' => true,
        ]);

        User::factory(5)->create(['account_id' => $account->id]);

        $branches = Branch::factory(12)->create();

        for ($i = 0; $i < 100; $i++) {
            Surveyor::create([
                'branch_id' => $branches->random()->id,
                'name' => 'Ikhsan Heriyawan',
            ]);
        }
    }
}

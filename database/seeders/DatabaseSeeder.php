<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Surveyor;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = FakerFactory::create();

        $account = Account::create(['name' => 'Ikhsan Kurnia Corporation Official']);

        User::factory()->create([
            'account_id' => $account->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'owner' => true,
        ]);

        User::factory(5)->create(['account_id' => $account->id]);

        // $branches = Branch::factory(12)->create();

        // $surveyors = [];
        // for ($i = 0; $i < 100; $i++) {
        //     $surveyors[] = Surveyor::create([
        //         'branch_id' => $branches->random()->id,
        //         'name' => $faker->name(),
        //     ]);
        // }

        // for ($i = 0; $i < 1000; $i++) {
        //     Task::create([
        //         'surveyor_id' => $surveyors[array_rand($surveyors)]->id,
        //         'name' => 'Task ' . $i,
        //     ]);
        // }

        // for ($i = 0; $i < 100; $i++) {
        //     $surveyor = $surveyors[$i];
        //     for ($j = 0; $j < 12; $j++) {
        //         $efficiency = rand(60, 120);
        //         $productivity = rand(60, 120);
        //         $quality = rand(60, 120);

        //         $score = $this->rumus($efficiency, $productivity, $quality);

        //         $surveyor->performances()->create([
        //             'efficiency' => $efficiency,
        //             'productivity' => $productivity,
        //             'quality' => $quality,
        //             'month' => $j + 1,
        //             'year' => 2023,
        //             'score' => $score,
        //         ]);
        //     }
        // }
    }

    public function rumus($efficiency, $productivity, $quality)
    {
        // (prod x 120%) + (quality x 120%) + (effisiesni x 120%) / ((120% + 120% + 120%) x 120%)
        $score = ($efficiency * 1.2) + ($productivity * 1.2) + ($quality * 1.2);
        $dibagi = (1.2 + 1.2 + 1.2) * 1.2;
        return $score / $dibagi;
    }
}

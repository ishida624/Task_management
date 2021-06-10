<?php

use App\Card;
use App\Task;
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
        factory(App\Users::class, 5)->create()
            ->each(function ($user) {
                $user->showCards()->save(factory(App\Task::class)->make());
            });
    }
}

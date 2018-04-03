<?php

use Illuminate\Database\Seeder;

class SubscribersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $fields = factory(App\Field::class, 3)->create();

        factory(App\Subscriber::class, 50)->create()->each(function ($subscriber) use ($faker, $fields) {
            $fields->each(function ($field) use ($subscriber, $faker) {
                $subscriber->fields()->save($field, ['value' => $faker->word()])->make();
            });
        });
    }
}

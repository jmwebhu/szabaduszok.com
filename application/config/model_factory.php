<?php

Kohana::$factory->define(Model_Industry::class, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'slug' => $faker->slug
    ];
});

Kohana::$factory->define(Model_Skill::class, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'slug' => $faker->slug
    ];
});

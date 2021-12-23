<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        /*
        return [
            'title' => $this->faker->sentence(mt_rand(3, 10)),
            'content' => join("\n\n", $this->faker->paragraphs(mt_rand(3, 6))),
            'published_at' => $this->faker->dateTimeBetween('-1 month', '+3 days'),
        ];
        */

        $images = ['about-bg.jpg', 'contact-bg.jpg', 'home-bg.jpg', 'post-bg.jpg'];
        $title = $this->faker->sentence(mt_rand(3, 10));
        return [
            'title' => $title,
            'subtitle' => str_limit($this->faker->sentence(mt_rand(10, 20)), 252),
            'page_image' => $images[mt_rand(0, 3)],
            'content_raw' => join("\n\n", $this->faker->paragraphs(mt_rand(3, 6))),
            'published_at' => $this->faker->dateTimeBetween('-1 month', '+3 days'),
            'meta_description' => "Meta for $title",
            'is_draft' => false,
        ];

    }
}


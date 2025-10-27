<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobListing>
 */
class JobListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle,
            'type' => 'Full-Time',
            'description' => $this->faker->realText(),
            'responsibilities' => $this->faker->realText(),
            'location' => $this->faker->city . ', ' . $this->faker->country,
            'salary' => 'PHP ' . $this->faker->numberBetween(40, 150) . '000 - PHP ' . $this->faker->numberBetween(150, 250) . '000',
            'company_id' => Company::factory(),
        ];
    }
}

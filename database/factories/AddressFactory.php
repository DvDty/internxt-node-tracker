<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ip' => $this->faker->unique()->ipv4,
            'country_id' => Country::all()->random()->id,
            'email' => $this->faker->optional(weight: .9)->email,
        ];
    }
}

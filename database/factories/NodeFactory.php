<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Node;
use App\Models\Protocol;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Node>
 */
class NodeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reputation' => $this->faker->numberBetween(0, 5000),
            'status' => $this->faker->numberBetween(0, 1),
            'node_id' => $this->faker->bothify(str_repeat('*', 40)),
            'address_id' => Address::all()->random()->id,
            'protocol_id' => Protocol::all()->random()->id,
            'last_seen' => $this->faker->dateTime(),
            'port' => $this->faker->numberBetween(0, 65536),
            'user_agent' => '8.7.2',
            'ip' => $this->faker->ipv4,
            'space_available' => $this->faker->numberBetween(0, 1),
            'response_time' => mt_rand(133, 4577),
        ];
    }
}

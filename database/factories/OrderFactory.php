<?php

namespace Database\Factories;

use App\Models\Rate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rateIds = Rate::all()->pluck("id")->toArray();
        return [
            'rate_id' => $this->faker->randomElement($rateIds),
            'amount_paid' => $this->faker->randomDigit(6),
            'amount_received' => $this->faker->randomDigit(6),
            'email_address' => $this->faker->unique()->safeEmail(),
            'rate_state_value' => $this->faker->randomDigitNotZero(),
            'tracking_code' => $this->faker->unique()->ean8(),
        ];
    }
}

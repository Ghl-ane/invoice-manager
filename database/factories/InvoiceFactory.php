<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        $issueDate = fake()->dateTimeBetween('-6 months', 'now');

        return [
            'user_id'        => User::factory(),
            'client_id'      => Client::factory(),
            'invoice_number' => 'INV-' . str_pad(fake()->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'issue_date'     => $issueDate,
            'due_date'       => fake()->dateTimeBetween($issueDate, '+30 days'),
            'status'         => fake()->randomElement(['draft', 'sent', 'paid', 'overdue']),
            'currency'       => fake()->randomElement(['USD', 'EUR', 'GBP']),
            'total'          => fake()->randomFloat(2, 100, 10000),
            'notes'          => fake()->optional()->sentence(),
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => 'draft']);
    }

    public function paid(): static
    {
        return $this->state(['status' => 'paid']);
    }
}

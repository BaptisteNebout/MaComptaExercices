<?php

namespace Database\Factories;

use App\Models\Ecriture;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EcritureFactory extends Factory
{
    protected $model = Ecriture::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $compte = \App\Models\Compte::factory()->create();

        return [
            'compte_uuid' => $compte->uuid,
            'uuid' => Uuid::uuid4()->toString(),
            'label' => $this->faker->sentence,
            'date' => $this->faker->date(),
            'type' => $this->faker->randomElement(['C', 'D']),
            'amount' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}

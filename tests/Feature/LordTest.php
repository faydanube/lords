<?php

namespace Tests\Feature;

use Tests\TestCase;

class LordTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->travelTo(
            now()->subYears(2)
        );

        for ($i = 0; $i < 730; $i++) {
            $this->travelTo(
                now()->addDays(1)
            );

            for ($j = 0; $j < fake()->randomNumber(2); $j++) {
                $response = $this->post(route('lord.store'), ['lord' => fake()->md5()]);
                dump($i.'_'.$j);
            }
        }

        $response->assertStatus(200);
    }
}

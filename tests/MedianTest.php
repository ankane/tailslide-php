<?php

use Tests\TestCase;

final class MedianTest extends TestCase
{
    public function testEven()
    {
        foreach ([1, 1, 2, 3, 4, 100] as $n) {
            User::create(['visits_count' => $n]);
        }
        $this->assertEqualsWithDelta(2.5, User::median('visits_count'), 0.001);
    }

    public function testOdd()
    {
        foreach ([1, 1, 2, 4, 100] as $n) {
            User::create(['visits_count' => $n]);
        }
        $this->assertEqualsWithDelta(2, User::median('visits_count'), 0.001);
    }

    public function testEmpty()
    {
        $this->assertNull(User::median('visits_count'));
    }

    public function testNull()
    {
        foreach ([1, 1, 2, 3, 4, 100, null] as $n) {
            User::create(['visits_count' => $n]);
        }
        $this->assertEqualsWithDelta(2.5, User::median('visits_count'), 0.001);
    }

    public function testAllNull()
    {
        foreach ([null, null, null] as $n) {
            User::create(['visits_count' => $n]);
        }
        $this->assertNull(User::median('visits_count'));
    }

    public function testDecimal()
    {
        for ($n = 0; $n < 6; $n++) {
            User::create(['latitude' => $n * 0.1]);
        }
        $this->assertEqualsWithDelta(0.25, User::median('latitude'), 0.001);
    }

    public function testFloat()
    {
        for ($n = 0; $n < 6; $n++) {
            User::create(['rating' => $n * 0.1]);
        }
        $this->assertEqualsWithDelta(0.25, User::median('rating'), 0.001);
    }

    public function testMissingColumn()
    {
        $this->expectErrorMessage('column');

        User::median('missing');
    }
}

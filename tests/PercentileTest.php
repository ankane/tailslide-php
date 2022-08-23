<?php

use Tests\TestCase;

final class PercentileTest extends TestCase
{
    public function testEven()
    {
        foreach ([1, 2, 3, 4] as $n) {
            User::create(['visits_count' => $n]);
        }
        $this->assertEqualsWithDelta(3.25, User::percentile('visits_count', 0.75), 0.001);
    }

    public function testOdd()
    {
        foreach ([15, 20, 35, 40, 50] as $n) {
            User::create(['visits_count' => $n]);
        }
        $this->assertEqualsWithDelta(29, User::percentile('visits_count', 0.4), 0.001);
    }

    public function testEmpty()
    {
        $this->assertNull(User::percentile('visits_count', 0.75));
    }

    public function testNull()
    {
        foreach ([1, 2, 3, 4, null] as $n) {
            User::create(['visits_count' => $n]);
        }
        $this->assertEqualsWithDelta(3.25, User::percentile('visits_count', 0.75), 0.001);
    }

    public function testAllNull()
    {
        foreach ([null, null, null] as $n) {
            User::create(['visits_count' => $n]);
        }
        $this->assertNull(User::percentile('visits_count', 0.75));
    }

    public function testZero()
    {
        foreach ([1, 2, 3, 4] as $n) {
            User::create(['visits_count' => $n]);
        }
        $this->assertEqualsWithDelta(1, User::percentile('visits_count', 0), 0.001);
    }

    public function testOne()
    {
        foreach ([1, 2, 3, 4] as $n) {
            User::create(['visits_count' => $n]);
        }
        $this->assertEqualsWithDelta(4, User::percentile('visits_count', 1), 0.001);
    }

    public function testHigh()
    {
        foreach ([1, 1, 2, 3, 4, 100] as $n) {
            User::create(['visits_count' => $n]);
        }
        $this->assertEqualsWithDelta(95.2, User::percentile('visits_count', 0.99), 0.001);
    }

    public function testMissingColumn()
    {
        $this->expectErrorMessage('column');

        User::percentile('missing', 0.75);
    }

    public function testPercentileOutOfRange()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('percentile is not between 0 and 1');

        User::percentile('visits_count', 1.1);
    }

    public function testPercentileNonNumeric()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('percentile is not numeric');

        User::percentile('visits_count', 'bad');
    }
}

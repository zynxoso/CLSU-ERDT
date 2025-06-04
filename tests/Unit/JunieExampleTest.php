<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class JunieExampleTest extends TestCase
{
    /**
     * A basic test example for the guidelines.
     */
    public function test_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    /**
     * A test that demonstrates string operations.
     */
    public function test_string_operations(): void
    {
        $string = 'CLSU-ERDT Scholar Management';

        $this->assertEquals('CLSU-ERDT Scholar Management', $string);
        $this->assertStringContainsString('Scholar', $string);
        $this->assertStringStartsWith('CLSU', $string);
        $this->assertStringEndsWith('Management', $string);
    }
}

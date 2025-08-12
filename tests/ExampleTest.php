<?php
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testAddition()
    {
        $sum = 2 + 3;
        $this->assertEquals(5, $sum);
    }

    public function testStringContains()
    {
        $str = 'Hello, PHPUnit!';
        $this->assertStringContainsString('PHPUnit', $str);
    }
}

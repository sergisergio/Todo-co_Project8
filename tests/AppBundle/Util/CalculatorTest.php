<?php
/**
 * Created by PhpStorm.
 * User: leazygomalas
 * Date: 23/09/2018
 * Time: 22:00
 */

namespace Tests\AppBundle\Util;

use AppBundle\Util\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function testAdd()
    {
        $calculator = new Calculator();
        $result = $calculator->add(30, 12);

        // assert that your calculator added the numbers correctly!
        $this->assertEquals(42, $result);
    }
}
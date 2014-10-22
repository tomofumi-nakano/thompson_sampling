<?php

require_once 'thompson.php';

class ThompsonTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function test_argment()
    {
        rbeta(0,0);
    }

    public function test_a()
    {
        $arms_list = [
            [[10000,100,20.0], [10000,100,20.0], [10000,50,20.0], [100,1,0.1], [100,1,0.1], [100,2,0.1]],
            [[10000,100,20.0], [10000,100,20.0], [10000,50,20.0], [500,5,0.5], [500,5,0.5], [500,10,0.5]],
            [[10000,100,20.0], [10000,100,20.0], [10000,50,20.0], [1000,10,1], [1000,10,1], [1000,20,1]]
        ];
        foreach ($arms_list as $arms) {
            $ratios = thompson($arms);
            var_dump($ratios);
        }
    }
}
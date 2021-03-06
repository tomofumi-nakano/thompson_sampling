<?php

require_once 'rbeta.php';

class RBetaTestSpeed extends PHPUnit_Framework_TestCase
{
    private $member = null;

    public function test_speed()
    {
        $rep = 100000;
        $list = array(
            array(2.7, 6.3),
            array(1, 2),
            array(1, 100),
            array(5, 100),
            array(10, 10000),
            array(20, 10000),
            array(1000, 10000),
        );
        print "\n";
        foreach ($list as $pair) {
            $alpha = $pair[0];
            $beta = $pair[1];
            $start = microtime(true);
            for ($i=0; $i<$rep; $i++) {
                $x = rbeta($alpha,$beta);
            }
            print 'time:'.(microtime(true)-$start)/$rep*1000 . "msec". '(alpha='.$alpha.', beta='.$beta.")\n";
        }
    }

    public function test_speed_class()
    {
        $rep = 100000;
        $list = array(
            array(2.7, 6.3),
            array(1, 1),
            array(1, 2),
            array(1, 100),
            array(5, 100),
            array(10, 10000),
            array(20, 10000),
            array(1000, 10000),
        );
        print "\n";
        foreach ($list as $pair) {
            $alpha = $pair[0];
            $beta = $pair[1];
            $start = microtime(true);
            $rbeta = new RBeta($alpha, $beta);
            for ($i=0; $i<$rep; $i++) {
                $x = $rbeta->rand();
            }
            print 'class time:'.(microtime(true)-$start)/$rep*1000 . "msec". '(alpha='.$alpha.', beta='.$beta.")\n";
        }
    }

    public function test_speed_classq()
    {
        $rep = 100000;
        $list = array(
            array(2.7, 6.3),
            array(1, 1),
            array(1, 2),
            array(1, 100),
            array(5, 100),
            array(10, 10000),
            array(20, 10000),
            array(1000, 10000),
        );
        print "\n";
        foreach ($list as $pair) {
            $alpha = $pair[0];
            $beta = $pair[1];
            $start = microtime(true);
            $rbeta = new RBetaQ($alpha, $beta);
            for ($i=0; $i<$rep; $i++) {
                $x = $rbeta->rand();
            }
            print 'classq time:'.(microtime(true)-$start)/$rep*1000 . "msec". '(alpha='.$alpha.', beta='.$beta.")\n";
        }
    }

    public function test_speed_class_func()
    {
        $rep = 100000;
        $list = array(
            array(2.7, 6.3),
            array(1, 2),
            array(1, 100),
            array(5, 100),
            array(10, 10000),
            array(20, 10000),
            array(1000, 10000),
        );
        print "\n";
        foreach ($list as $pair) {
            $alpha = $pair[0];
            $beta = $pair[1];
            $start = microtime(true);
            for ($i=0; $i<$rep; $i++) {
                $x = rbeta_class($alpha,$beta);
            }
            print 'class func time:'.(microtime(true)-$start)/$rep*1000 . "msec". '(alpha='.$alpha.', beta='.$beta.")\n";
        }
    }
}
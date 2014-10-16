<?php

require_once 'rbeta.php';

class RBetaTest extends PHPUnit_Framework_TestCase
{
    private $member = null;

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_argment_00()
    {
        rbeta(0,0);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_argment_01()
    {
        rbeta(0,1);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_argment_10()
    {
        rbeta(1,0);
    }

    public function test_argment_I2()
    {
        print rbeta(INF,2);
    }

    public function test_argment_2I()
    {
        print rbeta(2,INF);
    }


    public function test_plot_and_accuracy()
    {
        $list = array(
            array(2.7, 6.3),
            array(1, 1),
            array(1, 2),
            array(2, 1));
        $rep = 100000;
        $div = 20;
        foreach ($list as $ab) {
            $alpha = $ab[0];
            $beta = $ab[1];
            $hist = array_fill(0,$div,0);
            for ($i=0; $i<$rep; $i++) {
                $x = rbeta($alpha,$beta);
                $idx = (int) ($x*$div) ;
                $hist[$idx]++;
            }
            $lower = 0;
            for ($i=0; $i<$div; $i++) {
                $upper = stats_dens_beta(($i+1)/$div,$alpha,$beta);
                $mid = stats_dens_beta(($i+0.5)/$div,$alpha,$beta);
                $expect = ($lower+2*$mid+$upper)/4;
                $expect_max = max($lower, $mid, $upper);
                $expect_min = min($lower, $mid, $upper);
                print $i." = " . $hist[$i] . "(".($expect*$rep/$div).")\n";
                $this->assertLessThan($hist[$i], $expect_min*0.95*$rep/$div-1);
                $this->assertGreaterThan($hist[$i], $expect_max*1.05*$rep/$div+1);
                $lower = $upper;
            }
        }
    }

}
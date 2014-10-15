<?php

require_once 'rbeta.php';
 
class RBetaTest extends PHPUnit_Framework_TestCase
{
    private $member = null;
 
//    public function setup()
//    {
//#        $this->member = new Member();
//    }
 
    public function test_plot_x10000()
    {
        $rep = 1000000;
        $div = 40;
        $alpha = 2.7;
        $beta = 6.3;
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
            $this->assertLessThan($hist[$i], $expect_min*0.99*$rep/$div-1);
            $this->assertLessThan($expect_max*1.01*$rep/$div+1, $hist[$i]);
            $lower = $upper;
        }
    }
}
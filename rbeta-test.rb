# -*- coding: utf-8 -*-
require 'test/unit'
require 'rbeta'
require 'logger'

class TestRBeta < Test::Unit::TestCase

  def test_argment_error()
    [[0,0], [0,1], [1,0]].each do |arg|
      assert_raise(RuntimeError) do 
        RBetaQ.new(arg[0],arg[1])
      end
    end
  end

  def test_argment_normal()
    [[1,1], [1,2], [2,1]].each do |arg|
      assert_nothing_raised() do
        rb = RBetaQ.new(arg[0],arg[1])
        rb.rand
      end
    end
  end

=begin

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_argment_10_classq()
    {
        $rb = new RBetaQ(1,0);
        $rb->rand();
    }

    public function test_argment_I2_classq()
    {
        $rb = new RBetaQ(INF,2);
        print $rb->rand();

    }

    public function test_argment_2I_classq()
    {
        $rb = new RBetaQ(2,INF);
        print $rb->rand();
    }

    public function test_plot_and_accuracy_classq()
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
            $rb = new RBetaQ($alpha,$beta);
            for ($i=0; $i<$rep; $i++) {
                $x = $rb->rand();
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

    public function test_plot_and_accuracy_class_func()
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
                $x = rbeta_class($alpha,$beta);
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
=end
end

# -*- coding: utf-8 -*-
require 'test/unit'
require 'rbeta'
require 'logger'


class TestRBeta < Test::Unit::TestCase
  UseGSL = false

  def test_argment_exception()
    [[0,0], [0,1], [1,0]].each do |arg|
      assert_raise(RuntimeError) do 
        RBetaQ.new(arg[0],arg[1])
      end
    end
  end

  def test_argment_one()
    [[1,1], [1,2], [2,1]].each do |arg|
      assert_nothing_raised() do
        rb = RBetaQ.new(arg[0],arg[1])
        puts [arg, rb.rand].join(",")
      end
    end
  end

  def test_argment_normal()
    [[2,2], [2,3], [3,2]].each do |arg|
      assert_nothing_raised() do
        rb = RBetaQ.new(arg[0],arg[1])
        puts [arg, rb.rand].join(",")
      end
    end
  end

  def test_argment_inf()
    [[RBetaQ::INF,RBetaQ::INF], [2,RBetaQ::INF], [RBetaQ::INF,2]].each do |arg|
      assert_nothing_raised() do
        rb = RBetaQ.new(arg[0],arg[1])
        puts [arg, rb.rand].join(",") # 要確認
      end
    end
  end

  def test_plot_and_accuracy()
    list = [
            [2.7, 6.3],
            [1, 1],
            [1, 2],
            [2, 1]
           ]
    rep = 100000
    div = 20
    list.each do |ab| 
      alpha = ab[0]
      beta = ab[1]
      hist = Array.new(div,0)
      rb = RBetaQ.new(alpha,beta)
      for i in 0...rep do
          x = rb.rand()
          idx = (x*div).to_i 
          hist[idx]+=1
      end
      lower = 0
      for i in 0...div do
        if UseGSL
          # https://gnu.org/software/gsl/manual/gsl-ref.html#The-Beta-Distribution
          upper = GSL::Ran::beta_pdf((i+1.0)/div,alpha,beta)
          mid = GSL::Ran::beta_pdf((i+0.5)/div,alpha,beta)
        else
          # https://stat.ethz.ch/R-manual/R-devel/library/stats/html/Beta.html
          upper = @@r.dbeta((i+1.0)/div,alpha,beta)
          mid = @@r.dbeta((i+0.5)/div,alpha,beta)
        end
        expect = (lower+2*mid+upper)/4
        expect_max = [lower, mid, upper].max
        expect_min = [lower, mid, upper].min
        STDERR.puts i.to_s + " = " + hist[i].to_s + "(" + (expect*rep/div).to_s + ")\n"
        assert(hist[i] > expect_min*0.95*rep/div-1)
        assert(hist[i] < expect_max*1.05*rep/div+1)
        lower = upper
      end
      assert(hist.reduce(:+) == rep)
    end
  end
end

if TestRBeta::UseGSL
  require 'gsl'
else
  require 'rsruby'
  @@r = RSRuby.instance
end

# -*- coding: utf-8 -*-

require 'test/unit'
require 'rbeta'

class TestRBetaSpeed < Test::Unit::TestCase

  def test_speed()
    rep = 100000;
    list = [
            [2.7, 6.3],
            [1, 2],
            [1, 100],
            [5, 100],
            [10, 10000],
            [20, 10000],
            [1000, 10000],
           ]
    print "\n";
    list.each do |alpha,beta|
      rb = RBetaQ.new(alpha,beta)
      start = Time.now
      rep.times do
        x = rb.rand
      end
      print "time:#{(Time.now-start)/rep*1000} msec (alpha=#{alpha}, beta=#{beta}\n"
    end
  end
end

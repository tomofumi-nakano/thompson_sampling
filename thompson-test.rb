#! /usr/bin/ruby
# -*- coding: utf-8 -*-
require 'test/unit'
require 'thompson.rb'

class ThompsonTest < Test::Unit::TestCase
  def test_a()
    scenario_list = [
                     [[[10000,100,20.0], [10000,100,20.0]], [0,0], '同じ比率'],
                     [[[10000,100,20.0], [10000,50,20.0]], [-1,1], '同じeCPM'],
                     [[[10000,100,20.0], [10000,100,10.0]], [1,-1], '異なるeCPM'],
                     [[[100,1,0.1], [100,1,0.1]], [0,0], '同じ比率'],
                     [[[100,1,0.1], [100,2,0.1]], [1,-1], '同じeCPM'],
                     [[[100,1,0.1], [100,1,0.05]], [1,-1], '異なるeCPM'],
                     [[[100,1,0.1], [100,0,0.0]], [1,-1], '0-click'],
                     # 3つ以上の複数
                     [[[10000,100,20.0], [10000,100,20.0], [10000,100,20.0]], [0,0,0], '複数同じ'],
                     [[[10000,100,20.0], [10000,100,20.0], [10000,50,20.0]], [-1,-1,1], '複数eCPMが同じ'],
                     [[[10000,100,20.0], [10000,100,20.0], [10000,100,10.0]], [1,1,-1], '異なるeCPM'],
                     # よくありそうな
                     [[[1000,100,10], [10,1,0.1]], [-1,1], '探索か利益追求か(eCPMが同じ)'],
                     [[[1000,10,1], [100,1,0.1]], [-1,1], '探索か利益追求か(eCPMが同じ)'],
                     [[[10000,100,20.0], [100,1,0.1]], [1,-1], '探索か利益追求か(eCPMが半分)'],
                     [[[10000,100,20.0], [500,5,0.5]], [1,-1], '探索か利益追求か(eCPMが半分)'],
                     [[[10000,100,20.0], [1000,10,1.0]], [1,-1], '探索か利益追求か(eCPMが半分)'],
                     [[[10000,100,20.0], [2000,20,2.0]], [1,-1], '探索か利益追求か(eCPMが半分)'],
                    ];
    scenario_list.each do |scenario|
      arms, assertions, comment = scenario
      ratios = thompson(arms,10000);
      print arms.map{|e|e.join('/')}.join(':')
      print " ";
      print ratios.join(':')
      print " ";
      print assertions.join(':')
      average = arms.size;
      ratios.each.with_index do |ratio,id|
        case assertions[id]
        when 0
          assert(0.95/average < ratio, '大きすぎ');
          assert(1.05/average > ratio, '小さすぎ');
        when 1
          assert(1.0/average < ratio, '大きすぎ');
        when -1
          assert(1.0/average > ratio, '小さすぎ');
        else
          raise 'ないよ'
        end # case
      end # each
      print " # #{comment}.\n";
    end # each
  end # def
end # class

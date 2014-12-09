#!/usr/bin/ruby2.0
# -*- coding: utf-8 -*-

require 'rbeta.rb'

# thompson sampling
# @param [Array] arms [imps, click, cpc]
# @param [Fixnum] loop 繰り返し回数
# @return [Array] ratio_list 配信比率 [ratio]
def thompson(arms, loop = 1000)
  # rbeta 初期化
  rbs = []
  arms.each.with_index  do |(imps, click, _), id|
    rbs[id] = RBetaQ.new(click+1,imps-click+1) # +1 でいく
  end # each

  # thompson sampling
  ratio = Array.new(arms.size,0)
  for i in 0...loop
    max = -1
    max_id = -1
    arms.each.with_index do |(_, _, cpc),id|
      v = cpc * rbs[id].rand
      if (max < v)
        max = v
        max_id = id
      end
    end # each
    ratio[max_id]+=1
  end # for

  # ratio -> rate
  ratio.each.with_index do |count, id|
    ratio[id] = count.to_f/loop
  end
  return ratio
end

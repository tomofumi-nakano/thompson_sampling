#!/usr/bin/ruby2.0
# -*- coding: utf-8 -*-

require 'rbeta.rb'

# thompson sampling
# @param [Array] arms [imps, click, price]
# @param [Fixnum] loop 繰り返し回数
# @return [Array] ratio 配信比率
def thompson(arms, loop = 1000)
  # cpc の決定と rbeta 初期化
  cpcs = []
  rbs = []
  max_cpc = 0 # クリックが0のときのため
  arms.each.with_index  do |(imps, click, price), id|
    if (click > 0)
      cpcs[id] = price.to_f/click
      if (max_cpc < cpcs[id])
        max_cpc = cpcs[id]
      end
    else
      cpcs[id] = -1 # 一旦-1に指定
    end
    rbs[id] = RBetaQ.new(click+1,imps-click+1) # +1 でいく
  end # each
  if (max_cpc == 0)  # 全てがクリック0のときは1に設定する
    max_cpc = 1.0  # 最終的な出力結果は配信比率なので1で問題ない
  end
  arms.each.with_index do |data, id| # クリックが0のとき、最大のCPCに設定する
    if (cpcs[id] < 0)
      cpcs[id] = max_cpc
    end
  end

  # thompson sampling
  ratio = Array.new(cpcs.size,0)
  for i in 0...loop
    max = -1
    max_id = -1
    cpcs.each.with_index do |cpc,id|
      v = cpc * rbs[id].rand
      if (max < v)
        max = v
        max_id = id
      end
    end # each
    ratio[max_id]+=1
  end # for

  # ratio -> rate
  sum = ratio.reduce(:+)
  ratio.each.with_index do |count, id|
    ratio[id] = count.to_f/sum
  end
  return ratio
end

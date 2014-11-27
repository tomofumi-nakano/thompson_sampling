#! /usr/bin/ruby -Ku
# -*- coding: utf-8 -*-

class RBetaQ
  MAX_LOG = Math.log(Float::MAX)
  INF = 1.0/0.0
  def initialize(aaa, bbb)
    if (aaa < 1 || bbb < 1)
      raise("Arguments must be more than equal 1. a: #{aaa}, b: #{bbb}");
    end
    @aa = aaa
    @a = [aaa, bbb].min
    @b = [aaa, bbb].max # a <= b
    return if ((aaa == 1) && (bbb == 1))
    @alpha = @a + @b
    @beta = Math.sqrt((@alpha - 2.0) / (2.0 * @a * @b - @alpha))
    @gamma = @a + 1.0 / @beta
  end

  def rand()
    a = @a # a はループの中の式で２度利用されているので、局所変数としてしたほうが高速(phpでは)
    if a == 1 && @b == 1
      return Random.rand
    end

    begin
      u1 = Random.rand
      u2 = Random.rand

      r = @beta * Math.log(u1 / (1.0 - u1)) # $vはrの後使われないので、rとする
      if (r <= MAX_LOG)
        w = a * Math.exp(r)
        w = Float::MAX if (w == INF) # この行いらないかも
      else
        w = Float::MAX
      end

      r = @gamma * r - 1.3862944
      # s = a + r - w
      z = u1 * u1 * u2 # zの式を近づけることによりキャッシュ効果
      break if ((a + r - w) + 2.609438 >= 5.0 * z)
      z = Math.log(z) # tを作らずzを再利用
      break if ((a + r - w) > z)
    end while (r + @alpha * log(@alpha / (@b + w)) < z)

    return (@aa != a) ? @b / (@b + w) : w / (@b + w)
  end
end


# ベータ分布の生成関数

これはThompson SamplingをPHPで実現するためのプロジェクトです。

# ベータ分布高速化

## 目的

* `make -B rbeta-test-speed.check` で出力される`classq`の時間を短くして下さい。

## 方法

* `rbeta.php`中の、`class RBetaQ`の`function rand()`を書き換えて高速化して下さい。
* 式の意味やエラー処理を除いてはいけません。
* 式が正しいかどうかは、`make -B rbeta-test.check` をパスすれば一応大丈夫と思います。
** 上記の確認をするにはPECLのstatsをインストールする必要があります。

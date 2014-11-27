
# Thompson Sampling

これはThompson Samplingを純PHPや純Rubyで実現するためのプロジェクトです。
（純PHPや純rubyとは特別なライブラリをインストールしないということです。
ただしテストではインストールしてチェックします。）

# ベータ分布乱数生成高速化

## 目的

* `make -B rbeta-test-speed.check` で出力される`classq`の時間を短くして下さい。

## 方法

* `rbeta.php`中の、`class RBetaQ`の`function rand()`を書き換えて高速化して下さい。
* 式の意味やエラー処理を除いてはいけません。
* 式が正しいかどうかは、`make -B rbeta-test.check` をパスすれば一応大丈夫と思います。
 * 上記の確認をするにはPECLのstatsをインストールする必要があります。

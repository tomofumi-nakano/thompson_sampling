<?php

require_once 'rbeta.php';

/* thompson sampling
 * arms:
 *   arm: imps, click, price
 */
function thompson($arms, $loop = 1000)
{
    # init
    $cpcs = [];
    $rbs = [];
    $max_cpc = 0; # クリックが0のときのため
    foreach ($arms as $id => $data) {
        if ($data[1] > 0) {
            $cpcs[$id] = $data[2]/$data[1]; # $price/$click;
            if ($max_cpc < $cpcs[$id]) {
                $max_cpc = $cpcs[$id];
            }
        } else {
            $cpcs[$id] = -1; # 一旦-1に指定
        }
        $rbs[$id] = new RBetaQ($data[1]+1,$data[0]-$data[1]+1);
    }
    if ($max_cpc == 0) { # 全てがクリック0のときは1に設定する
        $max_cpc = 1.0;  # 最終的な出力結果は配信比率なので1で問題ない
    }
    foreach ($arms as $id => $data) { # クリックが0のとき、最大のCPCに設定する
        if ($cpcs[$id] < 0) {
            $cpcs[$id] = $max_cpc;
        }
    }

    # thompson sampling
    $ratios = array_fill(0,count($cpcs),0);
    for($i = 0; $i < $loop; $i++) {
        $max = -1;
        $max_id = -1;
        foreach ($cpcs as $id => $cpc) {
            $v = $cpc * $rbs[$id]->rand();
            if ($max < $v) {
                $max = $v;
                $max_id = $id;
            }
        }
        $ratios[$max_id]++;
    }

    # ratio -> rate
    $sum = array_reduce($ratios, function ($s,$e) {return $s+$e;} );
    foreach ($ratios as $id => $count) {
        $ratios[$id] = $count/$sum;
    }
    return $ratios;
}

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
    foreach ($arms as $id => $data) {
        # $imps = $data[0];
        # $click = $data[1];
        # $price = $data[2];
        $cpcs[$id] = $data[2]/$data[1]; # price/click
        $rbs[$id] = new RBeta($data[1]+1,$data[0]-$data[1]+1);
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

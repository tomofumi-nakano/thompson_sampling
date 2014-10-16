<?php

/*
 * from rbeta.c
 */

function rbeta($aa, $bb)
{
    /* cache */
    static $olda = -1.0;
    static $oldb = -1.0;
    static $beta;
    static $gamma;

    $a = min($aa, $bb);
    $b = max($aa, $bb); /* a <= b */
    $alpha = $a + $b;

    /* cache */
    if (($olda != $aa) || ($oldb != $bb)) {
        $beta = sqrt(($alpha - 2.0) / (2.0 * $a * $b - $alpha));
        $gamma = $a + 1.0 / $beta;
        $olda = $aa; $oldb = $bb;
    }

	do {
	    $u1 = mt_rand()/mt_getrandmax();
	    $u2 = mt_rand()/mt_getrandmax();

	    $v = $beta * log($u1 / (1.0 - $u1));
	    if ($v <= 709) {
            $w = $a * exp($v);
            if ($w == INF) {$w = 1.8e308;}
        } else {
            $w = 1.8e308;
        }

	    $z = $u1 * $u1 * $u2;
	    $r = $gamma * $v - 1.3862944;
	    $s = $a + $r - $w;
	    if ($s + 2.609438 >= 5.0 * $z)
            break;
	    $t = log($z);
	    if ($s > $t)
            break;
	} while ($r + $alpha * log($alpha / ($b + $w)) < $t);

	return ($aa != $a) ? $b / ($b + $w) : $w / ($b + $w);
}

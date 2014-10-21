<?php

/*
 * from rbeta.c
 */
class RBeta
{
    private $a;
    private $b;
    private $aa;
    private $bb;
    private $beta;
    private $gamma;
    private $m;

    function __construct($aaa, $bbb)
    {
        if ($aaa < 1 || $bbb < 1) {
            throw new InvalidArgumentException('Arguments must be more than equal 1. a:' . $aaa.' b:' . $bbb, 10);
        }
        $this->aa = $aaa;
        $this->bb = $bbb;
        $this->a = min($this->aa, $this->bb);
        $this->b = max($this->aa, $this->bb); /* a <= b */
        $this->m = mt_getrandmax();
        if (($aaa == 1) && ($bbb == 1)) {
            return;
        }
        $this->alpha = $this->a + $this->b;
        $this->beta = sqrt(($this->alpha - 2.0) / (2.0 * $this->a * $this->b - $this->alpha));
        $this->gamma = $this->a + 1.0 / $this->beta;
    }

    function rand()
    {
        if (($this->a == 1) && ($this->b == 1)) {
            return mt_rand()/$this->m;
        }

        do {
            $u1 = mt_rand()/$this->m;
            $u2 = mt_rand()/$this->m;

            $v = $this->beta * log($u1 / (1.0 - $u1));
            if ($v <= 709) {
                $w = $this->a * exp($v);
                if ($w == INF) {$w = 1.75e308;}
            } else {
                $w = 1.75e308;
            }

            $z = $u1 * $u1 * $u2;
            $r = $this->gamma * $v - 1.3862944;
            $s = $this->a + $r - $w;
            if ($s + 2.609438 >= 5.0 * $z)
            break;
            $t = log($z);
            if ($s > $t)
                break;
        } while ($r + $this->alpha * log($this->alpha / ($this->b + $w)) < $t);

        return ($this->aa != $this->a) ? $this->b / ($this->b + $w) : $w / ($this->b + $w);
    }

}

function rbeta($aa, $bb)
{
    /* cache */
    static $olda = -1.0;
    static $oldb = -1.0;
    static $a;
    static $b;
    static $alpha;
    static $beta;
    static $gamma;
    static $m = 0;

    if ($m == 0) {
        $m = mt_getrandmax();
    }

    if (($aa == 1) && ($bb == 1)) {
        return mt_rand()/$m;
    }

    /* cache */
    if (($olda != $aa) || ($oldb != $bb)) {
        if ($aa < 1 || $bb < 1) {
            throw new InvalidArgumentException('Arguments must be more than equal 1.', 10);
        }
        $a = min($aa, $bb);
        $b = max($aa, $bb); /* a <= b */
        $alpha = $a + $b;
        $beta = sqrt(($alpha - 2.0) / (2.0 * $a * $b - $alpha));
        $gamma = $a + 1.0 / $beta;
        $olda = $aa; $oldb = $bb;
    }

	do {
	    $u1 = mt_rand()/$m;
	    $u2 = mt_rand()/$m;

	    $v = $beta * log($u1 / (1.0 - $u1));
	    if ($v <= 709) {
            $w = $a * exp($v);
            if ($w == INF) {$w = 1.75e308;}
        } else {
            $w = 1.75e308;
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

function rbeta_class($aa, $bb)
{
    static $olda = -1.0;
    static $oldb = -1.0;
    static $rb = false;

    if (($olda != $aa) || ($oldb != $bb)) {
        $rb = new RBeta($aa, $bb);
        $olda = $aa; $oldb = $bb;
    }
    return $rb->rand();
}
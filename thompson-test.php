<?php

require_once 'thompson.php';

class ThompsonTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function test_argment()
    {
        rbeta(0,0);
    }

    public function test_a()
    {
        $scenario_list = [
            [[[10000,100,20.0], [10000,100,20.0]], [0,0]], # 同じ比率
            [[[10000,100,20.0], [10000,50,20.0]], [-1,1]], # 同じeCPM
            [[[10000,100,20.0], [10000,100,10.0]], [1,-1]], # 異なるeCPM
            [[[100,1,0.1], [100,1,0.1]], [0,0]], # 同じ比率
            [[[100,1,0.1], [100,2,0.1]], [1,-1]], # 同じeCPM
            [[[100,1,0.1], [100,1,0.05]], [1,-1]], # 異なるeCPM
#            [[[100,1,0.1], [100,0,0.0]], [1,-1]], # 異なるeCPM
            [[[10000,100,20.0], [10000,100,20.0], [10000,100,20.0]], [0,0,0]], # 複数同じ
            [[[10000,100,20.0], [10000,100,20.0], [10000,50,20.0]], [-1,-1,1]], # 複数eCPMが同じ
#            [[10000,100,20.0], [10000,100,20.0], [10000,50,20.0], [100,1,0.1], [100,1,0.1], [100,2,0.1]],
#            [[10000,100,20.0], [10000,100,20.0], [10000,50,20.0], [500,5,0.5], [500,5,0.5], [500,10,0.5]],
#            [[10000,100,20.0], [10000,100,20.0], [10000,50,20.0], [1000,10,1], [1000,10,1], [1000,20,1]],
        ];
        foreach ($scenario_list as $scenario) {
            $arms = $scenario[0];
            $assertions = $scenario[1];
            $ratios = thompson($arms,10000);
            #print array_map(function ($ratio) {}, $ratios);
            foreach ($ratios as $id => $ratio) {
                $ecpm = $arms[$id][2]/$arms[$id][0];
                $click = $arms[$id][1];
                print $ecpm."(".$click."):";

            }
            print " ";
            $average = count($arms);
            foreach ($ratios as $id => $ratio) {
                print $ratio.":";
                switch ($assertions[$id]) {
                case 0:
                    $this->assertGreaterThan(0.95/$average, $ratio);
                    $this->assertLessThan(1.05/$average, $ratio);
                    break;
                case 1:
                    $this->assertGreaterThan(1.0/$average, $ratio);
                    break;
                case -1:
                    $this->assertLessThan(1.0/$average, $ratio);
                    break;
                }
            }
            print "\n";
        }
    }
}
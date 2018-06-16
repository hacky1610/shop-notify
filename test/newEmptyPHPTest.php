<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use PHPUnit\Framework\TestCase;

include(__DIR__. '/../StartTest.php' );

class StackTest extends TestCase
{
    public function testPushAndPop()
    {
      
         $a = add(1);
       
        $this->assertEquals(1, $a);
    }
}


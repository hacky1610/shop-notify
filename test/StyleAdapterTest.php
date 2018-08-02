<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use PHPUnit\Framework\TestCase;

include(__DIR__. '/../private/adapter/StyleAdapter.php' );

class StyleAdapterTest extends TestCase
{
    public function testInit()
    {
        $sa = new StyleAdapter(null);


    }

}


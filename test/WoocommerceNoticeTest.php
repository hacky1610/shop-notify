<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use PHPUnit\Framework\TestCase;

include(__DIR__. '/../private/WoocommerceNotice.php' );
include(__DIR__. '/../private/logger.php' );

class WoocommerceNoticeTest extends TestCase
{
    public function testInit()
    {
        $logger = $this->createMock(Logger::class);
        $wcn = new WoocommerceNotice(2,$logger);
        $this->assertContains(2,3);
    }

}
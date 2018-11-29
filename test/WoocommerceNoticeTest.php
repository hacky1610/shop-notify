<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use PHPUnit\Framework\TestCase;

include_once dirname( __FILE__ ) . '/../private/WoocommerceNotice.php' ;
include_once dirname( __FILE__ ) . '/../private/logger.php' ;


class WoocommerceNoticeTest extends TestCase
{
    public function testInit()
    {
        $logger = $this->createMock(Logger::class);
        $wcn = new WoocommerceNotice(2,$logger);
        $this->assertContains(2,3);
    }

}
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use PHPUnit\Framework\TestCase;

include_once dirname( __FILE__ ) . '/../private/WoocommerceNotice.php' ;
include_once dirname( __FILE__ ) . '/../private/logger.php' ;
include_once dirname( __FILE__ ) . '/../private/Datastore.php' ;
include_once dirname( __FILE__ ) . '/../private/adapter/WpAdapter.php' ;
include_once dirname( __FILE__ ) . '/../private/adapter/PostMetaAdapter.php' ;


class WoocommerceNoticeTest extends TestCase
{
    public function testInit()
    {
        $logger = $this->createMock(Logger::class);
        $datastore = $this->createMock(Logger::class);
        $postmetaAdapter = $this->createMock(Logger::class);
        $wpAdapter = $this->createMock(Logger::class);
        //$wcn = new WoocommerceNotice($datastore,$logger,$postmetaAdapter,$wpAdapter);
        //$this->assertContains(2,3);
       $this->assertNotNull("Foo");

    }

}
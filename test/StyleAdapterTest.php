<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use PHPUnit\Framework\TestCase;

include_once dirname( __FILE__ ) . '/../private/adapter/StyleAdapter.php' ;
include_once dirname( __FILE__ ) . '/../private/adapter/WpAdapter.php' ;
include_once dirname( __FILE__ ) . '/../private/logger.php' ;
include_once dirname( __FILE__ ) . '/../private/DataStore.php' ;


class StyleAdapterTest extends TestCase
{
    public function testInit()
    {
        $logger = $this->createMock(Logger::class);
        $datastore = $this->createMock(DataStore::class);
        $wpAdapter = $this->createMock(WpAdapter::class);
        $sa = new StyleAdapter($datastore,$wpAdapter ,$logger);
        $this->assertNotNull($sa);

    }

    public function testSaveStyle()
    {
        $logger = $this->createMock(Logger::class);
        $datastore = $this->createMock(DataStore::class);
        $wpAdapter = $this->createMock(WpAdapter::class);

        $wpAdapter
        ->expects($this->any())
        ->method('GetPost')
        ->will($this->returnCallback(function ($arg) {
            $map = [
                'style_id'   => "Modern",
                'style_content' => "Hello World"
            ];
            return $map[$arg];
        }))
    ;
        
        $sa = new StyleAdapter($datastore,$wpAdapter ,$logger);
        $sa->SaveStyle();
        $this->assertNotNull($sa);

    }

}


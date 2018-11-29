<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use PHPUnit\Framework\TestCase;

include_once dirname( __FILE__ ) . '/../private/logger.php' ;
include_once dirname( __FILE__ ) . '/../templates/NotifySettings.php' ;
include_once dirname( __FILE__ ) . '/../private/DataStore.php' ;
include_once dirname( __FILE__ ) . '/../private/adapter/PostMetaAdapter.php' ;
include_once dirname( __FILE__ ) . '/../private/adapter/WpAdapter.php' ;
include_once dirname( __FILE__ ) . '/../private/mocks/LoggerMock.php' ;
include_once dirname( __FILE__ ) . '/../private/mocks/PostMock.php' ;

class NotifySettingsTest extends TestCase
{
    public function testInit()
    {

        

        $logger = $this->createMock(Logger::class);
        $pmAdapter = $this->createMock(PostMetaAdapter::class);
        $dataStore = $this->createMock(DataStore::class);
        $wpAdapter = $this->createMock(WpAdapter::class);
       

       $notSet = new NotifySettings($dataStore,$logger,$pmAdapter,$wpAdapter);
       $this->assertNotNull($notSet);

    }

    public function testShow()
    {
        $logger = new LoggerMock();
        $pmAdapter = $this->createMock(PostMetaAdapter::class);
        $post = $this->createMock(PostMock::class);
        $wpAdapter = $this->createMock(WpAdapter::class);

       
        $dataStore = $this->getMockBuilder(WpDataStore::class)
        ->setMethods(['GetStyleList'])
        ->getMock();
        $style = $this->getMockBuilder(Style::class);
        $stylelist = array($style);

        $dataStore->method('GetStyleList')->willReturn($stylelist );

       $notSet = new NotifySettings($dataStore,$logger,$pmAdapter,$wpAdapter);
       //notSet->Show($post);
       $this->assertNotNull($notSet);

       echo $logger->GetMessages();

    }

}


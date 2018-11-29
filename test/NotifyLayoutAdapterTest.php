<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use PHPUnit\Framework\TestCase;

include_once dirname( __FILE__ ) . '/../private/logger.php' ;
include_once dirname( __FILE__ ) . '/../templates/NotifySettings.php' ;
include_once dirname( __FILE__ ) . '/../private/adapter/NotifyLayoutAdapter.php' ;
include_once dirname( __FILE__ ) . '/../private/DataStore.php' ;
include_once dirname( __FILE__ ) . '/../private/mocks/LoggerMock.php' ;


class NotifyLayoutAdapterTest extends TestCase
{
    public function testInit()
    {
        $logger = $this->createMock(Logger::class);
        $wpAdapter = $this->createMock(WpAdapter::class);
       $notLayAdap = new NotifyLayoutAdapter($wpAdapter,$logger);
       $this->assertNotNull($notLayAdap);
    }   

    public function testGetContentFromJson()
    {
        $logger = $this->createMock(Logger::class);
        $wpAdapter = $this->createMock(WpAdapter::class);
       $notLayAdap = new NotifyLayoutAdapter($wpAdapter,$logger);
       $content = $notLayAdap->GetContentFromJson('[{"type":"text","val":"Foo"  }]');
       $this->assertNotNull($content);
    }

    public function testGetNotfyAjax()
    {
        $logger = $this->createMock(Logger::class);
        $wpAdapter = $this->createMock(WpAdapter::class);
       $notLayAdap = new NotifyLayoutAdapter($wpAdapter,$logger);
        //$notLayAdap->GetNotifyAjax();
        $this->assertNotNull($notLayAdap);

    }

  

}


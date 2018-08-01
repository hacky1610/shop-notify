<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use PHPUnit\Framework\TestCase;

include(__DIR__. '/../templates/NotifySettings.php' );
include(__DIR__. '/../private/logger.php' );
include(__DIR__. '/../private/DataStore.php' );
include(__DIR__. '/../private/adapter/PostMetaAdapter.php' );
include(__DIR__. '/../private/mocks/PostMock.php' );


class NotifySettingsTest extends TestCase
{
    public function testInit()
    {
        $logger = $this->createMock(Logger::class);
        $dataStore = $this->createMock(DataStore::class);
        $pmAdapter = $this->createMock(PostMetaAdapter::class);
       

       $notSet = new NotifySettings($dataStore,$logger,$pmAdapter);
       $this->assertNotNull($notSet);

    }

    public function testShow()
    {
        $logger = $this->createMock(Logger::class);
        $dataStore = $this->createMock(DataStore::class);
        $pmAdapter = $this->createMock(PostMetaAdapter::class);
        $post = $this->createMock(PostMock::class);
       

       $notSet = new NotifySettings($dataStore,$logger,$pmAdapter);
       $notSet->Show($post);
       $this->assertNotNull($notSet);

    }

}


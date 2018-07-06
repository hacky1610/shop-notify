<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use PHPUnit\Framework\TestCase;

include(__DIR__. '/../private/DataStore.php' );
include(__DIR__. '/../private/WpDataStore.php' );

class DataStoreTest extends TestCase
{
    public function testGetConsumerKey()
    {
        $valueToSave = "HelloWorld";

        $wpDataStore = $this->getMockBuilder(WpDataStore::class)
        ->setMethods(['Get'])
        ->getMock();

        $wpDataStore->method('Get')->with($this->stringContains('wcn_consumerKey'))->willReturn($valueToSave);
        $dataStore = new Datastore($wpDataStore);

        $this->assertContains($dataStore->GetConsumerKey(),   $valueToSave);
    }

}


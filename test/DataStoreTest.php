<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use PHPUnit\Framework\TestCase;

include_once dirname( __FILE__ ) . '/../private/DataStore.php' ;
include_once dirname( __FILE__ ) . '/../private/WpDataStore.php' ;


class DataStoreTest extends TestCase
{
    public function testGetStyleList()
    {
        $valueToSave = "HelloWorld";

        $wpDataStore = $this->getMockBuilder(WpDataStore::class)
        ->setMethods(['Get'])
        ->getMock();

        $wpDataStore->method('Get')->with($this->stringContains('sn_style_list'))->willReturn($valueToSave);
        $dataStore = new Datastore($wpDataStore);

        $this->assertContains($dataStore->GetStyleList(),   $valueToSave);
    }

}


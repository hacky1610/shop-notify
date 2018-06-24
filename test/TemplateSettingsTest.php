<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use PHPUnit\Framework\TestCase;

include(__DIR__. '/../private/DataStore.php' );

class TemplateSettingsTest extends TestCase
{
    public function testInit()
    {
        $stub = $this->createMock(DataStore::class);

        // Configure the stub.
        $stub->method('GetConsumerKey')
             ->willReturn('foo');


        echo $stub->GetConsumerKey();
    }
}

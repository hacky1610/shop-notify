<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use PHPUnit\Framework\TestCase;

include(__DIR__. '/../private/model/Layout.php' );

class LayoutTest extends TestCase
{
    public function testInitLayout()
    {
        $l = new Layout();
        $l->Render();

        $this->assertNotNull($l);

    }

    public function testAddTextToTitle()
    {
        $l = new Layout();
        $l->AddToTitle(Layout::CreateText("Foo"));
        $l->Render();

        $this->assertNotNull($l);

    }

    public function testAddLinkToTitle()
    {
        $l = new Layout();
        $l->AddToTitle(Layout::CreateLink("Foo",""));
        $l->Render();

        $this->assertNotNull($l);

    }

    public function testAddTextAndTitleToTitle()
    {
    
        $layout = new Layout();

        $layout->AddToTitle(Layout::CreateText("Title"));
        $layout->AddToMessage(Layout::CreateText("Message"));
        $layout->Render();

        $this->assertNotNull($layout);


    }
}


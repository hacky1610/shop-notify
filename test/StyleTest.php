<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use PHPUnit\Framework\TestCase;

include_once dirname( __FILE__ ) . '/../private/model/Style.php' ;

class StyleTest extends TestCase
{
    public function testInitStyle()
    {
        $s = new Style("myId","StyleName",". { bla: value}");

        $this->assertNotNull($s);

    }

    public function testGetFontList_hasTwoFonts()
    {
        $s = new Style("myId","StyleName",". { font-family: \"FooBar\";};  { font-family: \"FooBar2\";}");

        $fontList = $s->GetFontList();
        print_r($fontList);
        $this->assertCount(2, $fontList);

        //$this->assertContains("FooBar",$fontList);
        //$this->assertContains("FooBar2",$fontList);

    }

    public function testGetFontList_hasNoFonts()
    {
        $s = new Style("myId","StyleName",". { prop: val }");

        $fontList = $s->GetFontList();
        $this->assertCount(0, $fontList);

    }

  }


<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use PHPUnit\Framework\TestCase;

include_once dirname( __FILE__ ) . '/../private/model/Style.php' ;
include_once dirname( __FILE__ ) . '/../private/CssLoader.php' ;

class CssLoaderTest extends TestCase
{

    public function testGetFontList_hasTwoFonts2()
    {
        $s1 = new Style("myId","StyleName",". { font-family: FooBar;}  { font-family: FooBar2;}");
        $s2 = new Style("myId","StyleName",". { font-family: Hello;}  { font-family: FooBar2;}");
        $fonts = CssLoader::GetFonts( array($s1,$s2));
       $this->assertNotNull($fonts);
       

    }

    public function testLoad()
    {
        $s1 = new Style("myId","StyleName",". { font-family: FooBar; }  { font-family: FooBar2;}");
        $s2 = new Style("myId","StyleName",". { font-family: Hello; }  { font-family: FooBar2;}");
        $loader = new CssLoader();
        $loader->AddStyle($s1);

        $loader->AddStyle($s2);
        $loader->Load();
       $this->assertNotNull($loader);

    }



  }


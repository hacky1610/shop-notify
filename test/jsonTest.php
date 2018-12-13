
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use PHPUnit\Framework\TestCase;


class JsonTest extends TestCase
{
    public function testDecode()
    {
        $json = '[{\"type\":"text","val":"das ist ein T-Shirt. Click "},{"type":"link","val":"hier"},{"type":"text","val":""}]';
        $json = str_replace('\\',"",$json);

        $o = json_decode($json);

        print_r($o[0]->type);
        $this->assertNull($o);

    }


}


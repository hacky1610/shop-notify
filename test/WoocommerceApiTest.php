<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$autoloader = dirname( __FILE__ ) . '/../vendor/autoload.php';

if ( is_readable( $autoloader ) ) {
	require_once $autoloader;
}
use PHPUnit\Framework\TestCase;
use Automattic\WooCommerce\Client;


class WoocommerceApiTest extends TestCase
{
    public function testGetProduct()
    {
        $woocommerce = new Client(
            'http://www.sharonne-design.com',
            'ck_024588a23aa20cb17892b52e5f49d1252459d260',
            'cs_9c3de1344656eee809043b4e9d5555838095bfbd',
            [
                'wp_api' => true,
                'version' => 'wc/v2'
               
            ]
        );

        print_r($woocommerce->get('orders'));
    }

}


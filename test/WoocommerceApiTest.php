<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


use PHPUnit\Framework\TestCase;

include_once dirname( __FILE__ ) . '/../private/WoocommerceApi.php' ;
include_once dirname( __FILE__ ) . '/../private/WoocommerceApiLogic.php' ;
include_once dirname( __FILE__ ) . '/../private/logger.php' ;

$autoloader = dirname( __FILE__ ) . '/../vendor/autoload.php';

if ( is_readable( $autoloader ) ) {
	require_once $autoloader;
}

use Automattic\WooCommerce\Client;


class WoocommerceApiTest extends TestCase
{
    public function testInitWoocommerceApiLogic()
    {
        $wcClient = new Client(
            'http://sharonne-design.com',
            "",
            "",
            [
                'wp_api' => true,
                'version' => 'wc/v2'
            ]
        ); 
        $logger = $this->createMock(Logger::class);
        $wcLogic = new WoocommerceApiLogic($wcClient,$logger);
        $this->assertNotNull($wcLogic);
    }

    public function testGetAllProducts()
    {
        $wcClient = new Client(
            'http://sharonne-design.com',
            "ck_024588a23aa20cb17892b52e5f49d1252459d260",
            "cs_9c3de1344656eee809043b4e9d5555838095bfbd",
            [
                'wp_api' => true,
                'version' => 'wc/v2'
            ]
        ); 
        $logger = $this->createMock(Logger::class);
        $wcLogic = new WoocommerceApiLogic($wcClient,$logger);
        $this->assertNotNull($wcLogic->GetAllProducts());
    }

    public function testGetAllReviews()
    {
        $wcClient = new Client(
            'http://sharonne-design.com',
            "ck_024588a23aa20cb17892b52e5f49d1252459d260",
            "cs_9c3de1344656eee809043b4e9d5555838095bfbd",
            [
                'wp_api' => true,
                'version' => 'wc/v2'
            ]
        ); 
        $logger = $this->createMock(Logger::class);
        $wcLogic = new WoocommerceApiLogic($wcClient,$logger);
        $this->assertNotNull($wcLogic->GetAllReviews());
    }

}


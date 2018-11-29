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
   

}


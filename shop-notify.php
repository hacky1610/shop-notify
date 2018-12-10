<?php

/*
Plugin Name: Shop Notify
*/
include_once dirname( __FILE__ ) . '/private/logger.php';
include_once dirname( __FILE__ ) . '/private/DataStore.php';
include_once dirname( __FILE__ ) . '/private/WoocommerceNotice.php';
include_once dirname( __FILE__ ) . '/private/WpDataStore.php';
include_once dirname( __FILE__ ) . '/private/adapter/PostMetaAdapter.php';
include_once dirname( __FILE__ ) . '/private/adapter/WpAdapter.php';

define("WCN_PATH", plugin_dir_url( __FILE__ ));
define("WCN_VERSION", '0.9.94');

$logger = new Logger();
$datastore = new DataStore(new WpDatastore());
$postMetaAdapter = new PostMetaAdapter();
$wpAdapter = new WpAdapter($logger);
new WoocommerceNotice($datastore ,$logger,$postMetaAdapter,$wpAdapter);
?>
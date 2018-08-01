<?php

/*
Plugin Name: Woocommerce Notice
*/
include_once dirname( __FILE__ ) . '/private/logger.php';
include_once dirname( __FILE__ ) . '/private/DataStore.php';
include_once dirname( __FILE__ ) . '/private/WoocommerceNotice.php';
include_once dirname( __FILE__ ) . '/private/WpDataStore.php';
include_once dirname( __FILE__ ) . '/private/adapter/PostMetaAdapter.php';

$datastore = new DataStore(new WpDatastore());
$postMetaAdapter = new PostMetaAdapter();
new WoocommerceNotice($datastore ,new Logger(),$postMetaAdapter);

?>
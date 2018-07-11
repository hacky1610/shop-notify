<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include_once dirname( __FILE__ ) . '/WoocommerceApi.php';
include_once dirname( __FILE__ ) . '/WoocommerceApiLogic.php';
include_once dirname( __FILE__ ) . '/CssLoader.php';
include_once dirname( __FILE__ ) . '/../templates/GeneralSettings.php';
include_once dirname( __FILE__ ) . '/../templates/Styles.php';
include_once dirname( __FILE__ ) . '/../templates/GeneralControls.php';
$autoloader = dirname( __FILE__ ) . '/../vendor/autoload.php';

if ( is_readable( $autoloader ) ) {
	require_once $autoloader;
}

use Automattic\WooCommerce\Client;


class WoocommerceNotice{
    static $version = '0.9.94';
    static $version_file = '0.9.94';
    private $datastore;   
    private $api;
    private $logger;

    function __construct($datastore, $logger){
        $this->datastore  = $datastore;
        $this->logger = $logger;

        $this->logger->Call("Woocommerce_Notice Constructor");
      
        $wcClient = new Client(
            'http://sharonne-design.com',
            $this->datastore->GetConsumerKey(),
            $this->datastore->GetConsumerSecret(),
            [
                'wp_api' => true,
                'version' => 'wc/v2'
            ]
        ); 
        $apiLogic = new WoocommerceApiLogic($wcClient,$logger);
        
        WoocommerceApi::$woocommerceApiLogic =  $apiLogic;
        WoocommerceApi::DisableAuthentification(); //TODO: To be removed
        WoocommerceApi::InitAjax();

        add_action('wp_enqueue_scripts', array($this, 'loadJs'));
        add_action('admin_enqueue_scripts', array($this, 'loadJsAdmin'));
        add_action('admin_menu', array($this, 'createMenu'));
        add_action( 'get_footer', array($this, 'Load') );


        $this->logger->Call("Woocommerce_Notice Constructor End");
    }

    public function loadJs($hook){
        /*if($hook === 'settings_page_yourchannel'){
                   wp_register_script('yrc_script', plugins_url('/js/yrc.js?'.self::$version_file, __FILE__), array('jquery', 'underscore'), null, 1);
                   wp_enqueue_script('yrc_script');
                   wp_register_script('yrc_color_picker', plugins_url('/css/colorpicker/spectrum.js?'.self::$version_file, __FILE__), array('yrc_script'), null, 1);
                   wp_enqueue_script('yrc_color_picker');
                   wp_register_script('yrc_admin_settings', plugins_url('/js/admin.js?'.self::$version_file, __FILE__), array('yrc_color_picker'), null, 1);
                   wp_enqueue_script('yrc_admin_settings');
                   wp_register_style('yrc_color_picker_style', plugins_url('/css/colorpicker/spectrum.css?'.self::$version_file, __FILE__));
                   wp_enqueue_style('yrc_color_picker_style');
                   wp_register_style('yrc_admin_style', plugins_url('/css/admin.css?'.self::$version_file, __FILE__));
                   wp_enqueue_style('yrc_admin_style');
                   wp_register_style('yrc_style', plugins_url('/css/style.css?'.self::$version_file, __FILE__));
                   wp_enqueue_style('yrc_style');

        }*/
        $this->logger->Call("loadJs");
        wp_register_style('wcn_style', plugins_url('/../css/default.css?'.self::$version_file, __FILE__));
        wp_enqueue_style('wcn_style');
        wp_register_script('wcn_script', plugins_url('/../js/notice.js?'.self::$version_file, __FILE__), array(), null, 1);
        wp_enqueue_script('wcn_script');
        wp_register_script('wcn_bootstrap_notify', plugins_url('/../js/bootstrap-notify.js?'.self::$version_file, __FILE__), array(), null, 1);
        wp_enqueue_script('wcn_bootstrap_notify');
    }

    public function loadJsAdmin( $hook ) {
        $this->logger->Call("loadJsAdmin");
        //if( is_admin() ) { 
            $this->logger->Call("Add admin scripts");
            // Add the color picker css file       
            wp_enqueue_style( 'wp-color-picker' ); 
             
            // Include our custom jQuery file with WordPress Color Picker dependency
            wp_enqueue_script( 'wcn_admin_script', plugins_url( '/../js/admin.js?'.self::$version_file, __FILE__), array(), null, 1);
        //}
    }

    public function Load()
    {
        $orderlist  = $this->datastore->GetShowOrderList();
        $o = $orderlist[0];

        $cssloader = new CssLoader($o);
        $cssloader->Load();
        echo '<script>
        var $ = jQuery;
        jQuery( document ).ready(function( $ )
                {
                ShowOrder();
                ShowReview();
                });
        </script>"';
    }

    public function createMenu(){
        add_submenu_page(
           'options-general.php',
           'Woocommerce_Notice',
           'Woocommerce Notice',
           apply_filters('yourchannel_settings_permission', 'manage_options'),
           'Woocommerce_Notice',
           array($this, 'pageTemplate')
        );
    }

    public function pageTemplate(){
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general_settings';
        $this->logger->Info("Active Tab: $active_tab"); 
        ?>
        <div class="wrap">
            <div id="icon-themes" class="icon32"></div>
            <h2 class="wpb-inline" id="yrc-icon">Woocommerce<span class="wpb-inline">Notice</span></h2>
            <h2 class="nav-tab-wrapper">
            <?php   
                 echo GetTab('general_settings', "General",$active_tab );       
                 echo GetTab('style', "Style",$active_tab );
             ?>
            </h2>
            <?php 
            if ($active_tab === 'general_settings') { 
                 $this->logger->Info("Show General Settings Tab");   
                 $genSets = new GeneralSettings($this->datastore);
                 $genSets->Show();
            } 
            elseif($active_tab === 'style') 
            {
                $this->logger->Info("Show Styles Tab");
                $styles = new Styles($this->datastore);
                $styles->Show();
            }?>
        </div>
        <?php 
    }
    
     /**
     * @assert (0, 0) == 0
     * @assert (0, 1) == 1
     * @assert (1, 0) == 1
     * @assert (1, 1) == 2
     * @assert (1, 2) == 4
     */
    public function templates(){
                do_action('wcn_templates');
                include 'templates/templates.php';
    }
}


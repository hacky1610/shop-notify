<?php

/*
Plugin Name: Woocommerce Notice
*/
include_once dirname( __FILE__ ) . '/private/logger.php';
include_once dirname( __FILE__ ) . '/private/DataStore.php';
include_once dirname( __FILE__ ) . '/private/WoocimmerceApi.php';
include_once dirname( __FILE__ ) . '/templates/GeneralSettings.php';
include_once dirname( __FILE__ ) . '/templates/Styles.php';
include_once dirname( __FILE__ ) . '/templates/GeneralControls.php';




class Woocommerce_Notice{
    static $version = '0.9.94';
    static $version_file = '0.9.94';
    private $datastore;   
    private $api;
    private $logger;

    function __construct($datastore, $logger){

      


        $this->datastore  = $datastore;
        $this->logger = $logger;
        $this->logger->Call("Woocommerce_Notice Constructor");
        WoocommerceApi::$logger = $logger;
        WoocommerceApi::DisableAuthentification(); //TODO: To be removed
        WoocommerceApi::$consumerkey =  $this->datastore->GetConsumerKey();
        WoocommerceApi::$consumerSecret =  $this->datastore->GetConsumerSecret();
        WoocommerceApi::$website = "http://sharonne-design.com";
        WoocommerceApi::InitAjax();
        add_action('wp_enqueue_scripts', array($this, 'loadJs'));
        add_action('admin_menu', array($this, 'createMenu'));
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

        wp_register_style('wcn_style', plugins_url('/css/default.css?'.self::$version_file, __FILE__));
        wp_enqueue_style('wcn_style');
        wp_register_script('wcn_script', plugins_url('/js/notice.js?'.self::$version_file, __FILE__), array(), null, 1);
        wp_enqueue_script('wcn_script');
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

new Woocommerce_Notice(new DataStore(),new Logger());

function Load()
{
    include_once dirname( __FILE__ ) . '/custom-css.php';
    echo '<script>
      jQuery( document ).ready(function( $ )
            {
            ShowOrder();
            ShowReview();
            });
    </script>"';
}
add_action( 'get_footer', 'Load' );

?>
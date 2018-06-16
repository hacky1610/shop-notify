<?php
/*
Plugin Name: Woocommerce Notice
*/
include './private/DataStore.php';
include './private/WoocimmerceApi.php';
include './templates/GeneralSettings.php';
include './templates/GeneralControls.php';
 
class Woocommerce_Notice{
    static $version = '0.9.93';
    static $version_file = '0.9.93';
    private $datastore;   
    private $api;

    function __construct(){
        $this->datastore  = new Datastore();
        WoocommerceApi::$consumerkey =  $this->datastore->GetConsumerKey();
        WoocommerceApi::$consumerSecret =  $this->datastore->GetConsumerSecret();
        WoocommerceApi::$website = "https://vals-natural-journey.de";
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
        ?>
        <div class="wrap">
            <div id="icon-themes" class="icon32"></div>
            <h2 class="wpb-inline" id="yrc-icon">Woocommerce<span class="wpb-inline">Notice</span></h2>
            <h2 class="nav-tab-wrapper">
            <?php   
                 echo GetTab('general_settings', "General");       
                 echo GetTab('sytle', "Style");
             ?>
            </h2>
            <?php 
            if ($active_tab == 'general_settings') { 
                 $genSets = new GeneralSettings($this->datastore);
                 $genSets->Show();
            } 
            elseif($active_tab == 'style') 
            {

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
new Woocommerce_Notice();



function Load()
{
    include "./custom-css.php";
    echo html;
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
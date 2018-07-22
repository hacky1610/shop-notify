<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * https://codecanyon.net/item/wordpress-ecommerce-notification/20255155
 * https://wordpress.org/plugins/woobought-lite/
 * https://codecanyon.net/item/woocommerce-notification-boost-your-sales/16586926
 */


include_once dirname( __FILE__ ) . '/WoocommerceApi.php';
include_once dirname( __FILE__ ) . '/WoocommerceApiLogic.php';
include_once dirname( __FILE__ ) . '/CssLoader.php';
include_once dirname( __FILE__ ) . '/model/Style.php';
include_once dirname( __FILE__ ) . '/../templates/GeneralSettings.php';
include_once dirname( __FILE__ ) . '/../templates/Styles.php';
include_once dirname( __FILE__ ) . '/../templates/NotifySettings.php';
include_once dirname( __FILE__ ) . '/../templates/GeneralControls.php';
$autoloader = dirname( __FILE__ ) . '/../vendor/autoload.php';

if ( is_readable( $autoloader ) ) {
	require_once $autoloader;
}

use Automattic\WooCommerce\Client;


class WoocommerceNotice{
    static $version = '0.9.94';
    static $version_file = '0.9.94';
    static $namespace = "shop-notify";
    private $datastore;   
    private $api;
    private $logger;
    public $notifySettingsEditor;

    function __construct($datastore, $logger){
        $this->datastore  = $datastore;
        $this->logger = $logger;
        $this->notifySettingsEditor = new NotifySettings($datastore);
        $this->logger->Call("Woocommerce_Notice Constructor");
      
        $wcApiLogic = new WoocommerceApiLogic($logger);
        
        WoocommerceApi::$woocommerceApiLogic =  $wcApiLogic;
        WoocommerceApi::DisableAuthentification(); //TODO: To be removed
        WoocommerceApi::InitAjax();

        add_action('wp_enqueue_scripts', array($this, 'loadJs'));
        add_action('admin_enqueue_scripts', array($this, 'loadJsAdmin'));
        add_action('admin_menu', array($this, 'createMenu'));
        add_action('get_footer', array($this, 'Load') );
        add_action('init',array($this, 'codex_custom_init') );
        add_action('add_meta_boxes', array($this, 'post_grid_post_settings') );
        add_action('save_post', array($this->notifySettingsEditor,'Save'), 10, 3 );

        $this->AddAjaxFunction("wcn_save_style","SaveStyle");
        $this->logger->Call("Woocommerce_Notice Constructor End");
    }


    function codex_custom_init() {
        $labels = array(
            'name'                => _x( 'Notifications', 'Post Type General Name', self::$namespace),
            'singular_name'       => _x( 'Movie', 'Post Type Singular Name', self::$namespace ),
            'menu_name'           => __( 'Shop Notify', self::$namespace ),
            'parent_item_colon'   => __( 'Parent Movie', self::$namespace ),
            'all_items'           => __( 'Notifications', self::$namespace ),
            'view_item'           => __( 'View Movie', self::$namespace ),
            'add_new_item'        => __( 'Add Notification', self::$namespace),
            'add_new'             => __( 'Add Notification', self::$namespace ),
            'edit_item'           => __( 'Edit Notification', self::$namespace ),
            'update_item'         => __( 'Update Notification', self::$namespace ),
            'search_items'        => __( 'Search Notification', self::$namespace ),
            'not_found'           => __( 'Not Found', self::$namespace ),
            'not_found_in_trash'  => __( 'Not found in Trash', self::$namespace ),
        );

        $args = array(
            'public' => true,
            'label'  => 'Shop Notify',
            'labels' => $labels,
            'publicly_queryable' => false,
            'show_ui' => true,
            'query_var' => true,
            'menu_icon' => null,
            'rewrite' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title'),
            'menu_icon' => 'dashicons-media-spreadsheet',
          );
        register_post_type( 'shop-notify', $args );
    }

    function post_grid_post_settings()
	{
        add_meta_box('sn_settings',
                    __( 'Notification',self::$namespace),
                    array($this->notifySettingsEditor,'Show'),
                    '',
                    'advanced',
                    'default',
                null);
    }

    public function sn_style_editor(){
        $styles = new Styles($this->datastore);
        $styles->Show();

	}

    private function AddAjaxFunction($code, $funcName)
    {
        add_action( 'wp_ajax_nopriv_' . $code, array( $this, $funcName ) );
        
        add_action( 'wp_ajax_' . $code, array( $this, $funcName ) );
    }

    public function SaveStyle()
    {
        $style = $_POST['style'];
        $this->datastore->SetGlobalStyle($style);
        wp_die();
    }

    public function loadJs($hook){
        $this->logger->Call("loadJs");
        wp_register_style('wcn_style', plugins_url('/../css/default.css?'.self::$version_file, __FILE__));
        wp_enqueue_style('wcn_style');
        wp_enqueue_script( 'wcn_common_script', plugins_url( '/../js/common.js?'.self::$version_file, __FILE__), array(), null, 1);
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
            wp_register_style('wcn_admin_bootstrap', plugins_url('/../css/bootstrap.css?'.self::$version_file, __FILE__));
            wp_enqueue_style('wcn_admin_bootstrap');
            wp_register_style('wcn_admin_fontselect', plugins_url('/../css/fontselect.css?'.self::$version_file, __FILE__));
            wp_enqueue_style('wcn_admin_fontselect');

            wp_register_style('wcn_admin_style', plugins_url('/../css/admin.css?'.self::$version_file, __FILE__));
            wp_enqueue_style('wcn_admin_style');

            wp_register_style('wcn_style', plugins_url('/../css/default.css?'.self::$version_file, __FILE__));
            wp_enqueue_style('wcn_style');
             
            // Include our custom jQuery file with WordPress Color Picker dependency
            wp_enqueue_script( 'wcn_admin_script', plugins_url( '/../js/admin.js?'.self::$version_file, __FILE__), array(), null, 1);
            wp_enqueue_script( 'wcn_ajax_script', plugins_url( '/../js/ajax.js?'.self::$version_file, __FILE__), array(), null, 1);
            wp_enqueue_script( 'wcn_common_script', plugins_url( '/../js/common.js?'.self::$version_file, __FILE__), array(), null, 1);

            wp_register_script('wcn_bootstrap_notify', plugins_url('/../js/bootstrap-notify.js?'.self::$version_file, __FILE__), array(), null, 1);
            wp_enqueue_script('wcn_bootstrap_notify');

            wp_enqueue_script( 'wcn_input_mask_script', plugins_url( '/../js/jquery.inputmask.bundle.js?'.self::$version_file, __FILE__), array(), null, 1);
            wp_enqueue_script( 'wcn_fontselect_script', plugins_url( '/../js/jquery.fontselect.min.js?'.self::$version_file, __FILE__), array(), null, 1);

        //}

    }

    public function Load()
    {
        $this->my_print_stars();
        $globalStyle  = $this->datastore->GetGlobalStyle();
        $cssloader = new CssLoader($globalStyle);
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

    public function Install()
    {
        $styleList = Style::GetDefaultStyles();
        $this->datastore->SetStyleList($styleList);
    }

    

    public function createMenu(){
        $namespace = self::$namespace;

        echo $namespace;
        add_submenu_page("edit.php?post_type=shop-notify", __('Style Editor',"shop-notify"), __("Style Editor","shop-notify"), 'manage_options', 'sn_style_editor', array( $this, 'sn_style_editor' ));
        add_submenu_page("edit.php?post_type=shop-notify", __('Install',"shop-notify"), __("Install","shop-notify"), 'manage_options', 'sn_install', array( $this, 'Install' ));



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
    
    public function ShowOrders(){
        $query = new WC_Order_Query( array(
            'limit' => 10,
            'orderby' => 'date',
            'order' => 'DESC'
        ) );
        $orders = $query->get_orders();
        print_r($orders);
        
    }

    public function GetProduct($id)
    {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 1,
            'post__in'=> array($product_id)
        );

        $products = wc_get_products( $args );
        return $products;
    }

    //https://github.com/woocommerce/woocommerce/wiki/wc_get_orders-and-WC_Order_Query
    //http://itadminguide.com/difference-between-wpdb-get_row-get_results-get_var-and-get_query/
    //https://stackoverflow.com/questions/14227121/how-do-you-add-the-star-ratings-for-products-in-woocommerce/20794406
    function my_print_stars(){
        global $wpdb;
        global $post;
       //comment_date,meta_value,comment_content,comment_author,comment_post_ID

        $comments = $wpdb->get_results("
        SELECT comment_date,meta_value,comment_content,comment_author,comment_post_ID FROM $wpdb->commentmeta
        LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
        WHERE meta_key = 'rating'
        ");

        $id = $comments[0]->comment_post_ID;
        $product = $this->GetProduct(1024);

    }

    



 
 
}


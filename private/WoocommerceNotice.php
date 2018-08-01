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
include_once dirname( __FILE__ ) . '/model/Layout.php';
include_once dirname( __FILE__ ) . '/adapter/StyleAdapter.php';
include_once dirname( __FILE__ ) . '/../templates/GeneralSettings.php';
include_once dirname( __FILE__ ) . '/../templates/Styles.php';
include_once dirname( __FILE__ ) . '/../templates/NotifySettings.php';
include_once dirname( __FILE__ ) . '/../templates/GeneralControls.php';



class WoocommerceNotice{
    static $version = '0.9.94';
    static $version_file = '0.9.94';
    static $namespace = "shop-notify";
    private $datastore;   
    private $api;
    private $logger;
    public $notifySettingsEditor;
    private $postMetaAdapter;
    private $styleAdapter;

    function __construct($datastore, $logger,$postMetaAdapter){
        $this->logger = $logger;
        $this->logger->Call("Woocommerce_Notice Constructor");

        $this->datastore  = $datastore;
        $this->postMetaAdapter = $postMetaAdapter;
        $this->styleAdapter = new StyleAdapter($this->datastore );
        $this->notifySettingsEditor = new NotifySettings($datastore,$logger,$postMetaAdapter);

      
        $wcApiLogic = new WoocommerceApiLogic($logger);
        
        WoocommerceApi::$woocommerceApiLogic =  $wcApiLogic;
        WoocommerceApi::InitAjax();

        add_action('wp_enqueue_scripts', array($this, 'loadJs'));
        add_action('admin_enqueue_scripts', array($this, 'loadJsAdmin'));
        add_action('admin_menu', array($this, 'createMenu'));
        add_action('get_footer', array($this, 'Load') );
        add_action('init',array($this, 'init') );
        // add_action('add_meta_boxes', array($this->notifySettingsEditor, 'AddContent') );
        // add_action('save_post', array($this->notifySettingsEditor,'Save'), 10, 3 );

        // $this->AddAjaxFunction("wcn_get_notify","GetNotify");

        // $this->logger->Call("Woocommerce_Notice Constructor End");
    }


    function init() {
        $this->notifySettingsEditor->RegisterPostType();
    }

    public function ShowStylesEditor(){
        $styles = new Styles($this->datastore);
        $styles->Show();

	}

    private function AddAjaxFunction($code, $funcName)
    {
        add_action( 'wp_ajax_nopriv_' . $code, array( $this, $funcName ) );
        add_action( 'wp_ajax_' . $code, array( $this, $funcName ) );
    }

    public function GetNotify()
    {
        $id =  $_POST['id'];
        $title =  $_POST['title_content'];
        $message =  $_POST['message_content'];

        $layout = new Layout($id);

        $title = str_replace('\\',"",$title);
        $titleArray = json_decode($title);
        
        foreach ($titleArray as $value)
        {
            if($value->type == "text")
            {
                $layout->AddToTitle(Layout::CreateText($value->val));
            }
            else
            {
                $layout->AddToTitle(Layout::CreateLink($value->val));
            }
        }
        $message = str_replace('\\',"",$message);
        $messageArray = json_decode($message);
        
        foreach ($messageArray as $value)
        {
            if($value->type == "text")
            {
                $layout->AddToMessage(Layout::CreateText($value->val));
            }
            else
            {
                $layout->AddToMessage(Layout::CreateLink($value->val));
            }
        }
        
        $layout->Render();
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
        $this->logger->Call("loadJs finished");

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
        $styleList  = $this->datastore->GetStyleList();
        $currentStyleObject = Style::GetStyle($styleList,"classic");

        $cssLoader = new CssLoader($currentStyleObject->content);
        $cssLoader->Load();

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

        add_submenu_page("edit.php?post_type=shop-notify", __('Style Editor',"shop-notify"), __("Style Editor","shop-notify"), 'manage_options', 'sn_style_editor', array( $this, 'ShowStylesEditor' ));
        add_submenu_page("edit.php?post_type=shop-notify", __('Install',"shop-notify"), __("Install","shop-notify"), 'manage_options', 'sn_install', array( $this, 'Install' ));
    }
 
}


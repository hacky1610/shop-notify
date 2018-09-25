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
include_once dirname( __FILE__ ) . '/model/Notify.php';
include_once dirname( __FILE__ ) . '/adapter/StyleAdapter.php';
include_once dirname( __FILE__ ) . '/adapter/PostMetaAdapter.php';
include_once dirname( __FILE__ ) . '/adapter/NotifyAdapter.php';
include_once dirname( __FILE__ ) . '/adapter/NotifyLayoutAdapter.php';
include_once dirname( __FILE__ ) . '/../templates/GeneralSettings.php';
include_once dirname( __FILE__ ) . '/../templates/Styles.php';
include_once dirname( __FILE__ ) . '/../templates/WorkflowEditor.php';
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
    private $notifyLayoutAdapter;
    private $notifyAdapter;

    function __construct($datastore, $logger,$postMetaAdapter,$wpAdapter){
        $this->logger = $logger;
        $this->logger->Call("Woocommerce_Notice Constructor");

        $this->datastore  = $datastore;
        $this->postMetaAdapter = $postMetaAdapter;
        $this->styleAdapter = new StyleAdapter($this->datastore,$wpAdapter,$this->logger );
        $this->notifyAdapter = new NotifyAdapter($postMetaAdapter);
        $this->notifyLayoutAdapter = new NotifyLayoutAdapter();
        $this->notifySettingsEditor = new NotifySettings($datastore,$logger,$postMetaAdapter);

         new WoocommerceApi(new WoocommerceApiLogic($logger));

        add_action('wp_enqueue_scripts', array($this, 'loadJs'));
        add_action('admin_enqueue_scripts', array($this, 'loadJsAdmin'));
        add_action('admin_menu', array($this, 'createMenu'));
        add_action('get_footer', array($this, 'Load') );
        add_action('init',array($this, 'init') );
        add_action('add_meta_boxes', array($this->notifySettingsEditor, 'AddContent') );
        add_action('save_post', array($this->notifySettingsEditor,'Save'), 10, 3 );

        $this->logger->Call("Woocommerce_Notice Constructor End");
    }

    function init() {
        $this->notifySettingsEditor->RegisterPostType();
    }

    public function ShowStylesEditor(){
        $styles = new Styles($this->datastore,$this->logger);
        $styles->Show();
    }
    
    public function ShowWorkflowEditor(){
        $wfe = new WorkflowEditor($this->datastore,$this->logger,$this->postMetaAdapter,$this->notifyLayoutAdapter);
        $wfe->Show();
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
            wp_enqueue_style( 'wp-color-picker' ); 
            wp_register_style('wcn_admin_bootstrap', plugins_url('/../css/bootstrap.css?'.self::$version_file, __FILE__));
            wp_enqueue_style('wcn_admin_bootstrap');
            wp_register_style('wcn_admin_fontselect', plugins_url('/../css/fontselect.css?'.self::$version_file, __FILE__));
            wp_enqueue_style('wcn_admin_fontselect');
            wp_register_style('wcn_jqui', plugins_url('/../css/jquery-ui.min.css?'.self::$version_file, __FILE__));
            wp_enqueue_style('wcn_jqui');

            wp_register_style('prefix_bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css');
            wp_enqueue_style('prefix_bootstrap');

            wp_register_style('wcn_admin_style', plugins_url('/../css/admin.css?'.self::$version_file, __FILE__));
            wp_enqueue_style('wcn_admin_style');

            wp_register_style('wcn_style', plugins_url('/../css/default.css?'.self::$version_file, __FILE__));
            wp_enqueue_style('wcn_style');
             
            // Include our custom jQuery file with WordPress Color Picker dependency
            wp_enqueue_script( 'wcn_admin_script', plugins_url( '/../js/admin.js?'.self::$version_file, __FILE__), array(), null, 1);
            wp_enqueue_script( 'wcn_jqui', plugins_url( '/../js/jquery-ui.min.js?'.self::$version_file, __FILE__), array(), null, 1);
            wp_enqueue_script( 'wcn_common_script', plugins_url( '/../js/common.js?'.self::$version_file, __FILE__), array(), null, 1);


            
            wp_register_script('prefix_popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js');
            wp_enqueue_script('prefix_popper');

            wp_register_script('prefix_bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js');
            wp_enqueue_script('prefix_bootstrap');


            wp_register_script('wcn_bootstrap_notify', plugins_url('/../js/bootstrap-notify.js?'.self::$version_file, __FILE__), array(), null, 1);
            wp_enqueue_script('wcn_bootstrap_notify');

            wp_enqueue_script( 'wcn_input_mask_script', plugins_url( '/../js/jquery.inputmask.bundle.js?'.self::$version_file, __FILE__), array(), null, 1);
            wp_enqueue_script( 'wcn_fontselect_script', plugins_url( '/../js/jquery.fontselect.js?'.self::$version_file, __FILE__), array(), null, 1);
        //}
    }
    

    public function Load()
    {
          $styleList  = $this->datastore->GetStyleList();
          $currentStyleObject = Style::GetStyle($styleList,$notify->GetStyle());
  
          $cssLoader = new CssLoader();
          $cssLoader->AddStyle($currentStyleObject);
          $cssLoader->Load();

        echo '<script>
        var $ = jQuery;
        jQuery( document ).ready(function( $ )
                {
                ShowOrder();
                });
        </script>"';
    }

    public function Install()
    {
        $styleList = Style::GetDefaultStyles();
        $this->datastore->SetStyleList($styleList);
    }

    public function createMenu(){
        $this->logger->Call("CreateMenu");

        $namespace = self::$namespace;

        add_submenu_page("edit.php?post_type=shop-notify", __('Style Editor',"shop-notify"), __("Style Editor","shop-notify"), 'manage_options', 'sn_style_editor', array( $this, 'ShowStylesEditor' ));
        add_submenu_page("edit.php?post_type=shop-notify", __('Workflow Editor',"shop-notify"), __("Workflow Editor","shop-notify"), 'manage_options', 'sn_workflow_editor', array( $this, 'ShowWorkflowEditor' ));
        add_submenu_page("edit.php?post_type=shop-notify", __('Install',"shop-notify"), __("Install","shop-notify"), 'manage_options', 'sn_install', array( $this, 'Install' ));
        $this->logger->Call("CreateMenu End");

    }
 
}


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
include_once dirname( __FILE__ ) . '/adapter/WorkflowAdapter.php';
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
    private $workflowAdaper;
    private $notifyLayoutAdapter;
    private $notifyAdapter;

    function __construct($datastore, $logger,$postMetaAdapter,$wpAdapter){
        $this->logger = $logger;
        $this->logger->Call("Woocommerce_Notice Constructor");

        $this->datastore  = $datastore;
        $this->postMetaAdapter = $postMetaAdapter;
        $this->styleAdapter = new StyleAdapter($this->datastore,$wpAdapter,$this->logger );
        $this->workflowAdaper = new WorkflowAdapter($this->datastore,$wpAdapter,$this->logger );
        $this->notifyAdapter = new NotifyAdapter($postMetaAdapter);
        $this->notifyLayoutAdapter = new NotifyLayoutAdapter($wpAdapter,$this->logger);
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
        $wfe = new WorkflowEditor($this->datastore,$this->logger,$this->postMetaAdapter,$this->notifyLayoutAdapter,$this->workflowAdaper);
        $wfe->Show();
    }
    
    

    public function loadJs($hook){
        $this->logger->Call("loadJs");
        wp_enqueue_script('sn_logger', plugins_url('/../js/logger.js?'.self::$version_file, __FILE__), array(), null, 1);
        wp_enqueue_style('wcn_style', plugins_url('/../css/default.css?'.self::$version_file, __FILE__));
        wp_enqueue_script( 'wcn_common_script', plugins_url( '/../js/common.js?'.self::$version_file, __FILE__), array(), null, 1);
        wp_enqueue_script('sn_controller', plugins_url('/../js/controller.js?'.self::$version_file, __FILE__), array(), null, 1);
        wp_enqueue_script('wcn_script', plugins_url('/../js/notice.js?'.self::$version_file, __FILE__), array(), null, 1);
        wp_enqueue_script('sn_runner', plugins_url('/../js/runner.js?'.self::$version_file, __FILE__), array(), null, 1);
        wp_enqueue_script('wcn_bootstrap_notify', plugins_url('/../js/bootstrap-notify.js?'.self::$version_file, __FILE__), array(), null, 1);
        $this->logger->Call("loadJs finished");

    }

    public function loadJsAdmin( $hook ) {
        $this->logger->Call("loadJsAdmin");
        //if( is_admin() ) { 
            $this->logger->Call("Add admin scripts");
            wp_enqueue_style('sn_logger', plugins_url('/../js/logger.js?'.self::$version_file, __FILE__), array(), null, 1);
            wp_enqueue_style('wcn_admin_bootstrap', plugins_url('/../css/bootstrap.css?'.self::$version_file, __FILE__));
            wp_enqueue_style('wcn_admin_fontselect', plugins_url('/../css/fontselect.css?'.self::$version_file, __FILE__));
            wp_enqueue_style('wcn_jqui', plugins_url('/../css/jquery-ui.min.css?'.self::$version_file, __FILE__));
            wp_enqueue_style('sn_bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css');
            wp_enqueue_style('sn_bootstrap_select', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css');
            wp_enqueue_style('wcn_admin_style', plugins_url('/../css/admin.css?'.self::$version_file, __FILE__));
            wp_enqueue_style('wcn_style', plugins_url('/../css/default.css?'.self::$version_file, __FILE__));
             
            // Include our custom jQuery file with WordPress Color Picker dependency
            wp_enqueue_script( 'wcn_admin_script', plugins_url( '/../js/admin.js?'.self::$version_file, __FILE__), array(), null, 1);
            wp_enqueue_script( 'wcn_jqui', plugins_url( '/../js/jquery-ui.min.js?'.self::$version_file, __FILE__), array(), null, 1);
            wp_enqueue_script( 'wcn_common_script', plugins_url( '/../js/common.js?'.self::$version_file, __FILE__), array(), null, 1);
            wp_enqueue_script('sn_popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js');
            wp_enqueue_script('sn_bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js');
            wp_enqueue_script('sn_bootstrap_select', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js');
            wp_enqueue_script('wcn_bootstrap_notify', plugins_url('/../js/bootstrap-notify.js?'.self::$version_file, __FILE__), array(), null, 1);
            wp_enqueue_script( 'wcn_input_mask_script', plugins_url( '/../js/jquery.inputmask.bundle.js?'.self::$version_file, __FILE__), array(), null, 1);
            wp_enqueue_script( 'wcn_fontselect_script', plugins_url( '/../js/jquery.fontselect.js?'.self::$version_file, __FILE__), array(), null, 1);
        //}
    }
    

    public function Load()
    {
          $this->logger->Call("loadJsAdmin");

          $cssLoader = new CssLoader();
          $styleList  = $this->datastore->GetStyleList();
          foreach ($styleList as $style) {
              $cssLoader->AddStyle($style);
          }
    
          $cssLoader->Load();
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


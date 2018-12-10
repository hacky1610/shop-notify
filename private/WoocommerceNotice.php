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
    private $wpAdapter;

    function __construct($datastore, $logger,$postMetaAdapter,$wpAdapter){
        $this->logger = $logger;
        $this->logger->Call("Woocommerce_Notice Constructor");

        $this->datastore  = $datastore;
        $this->wpAdapter = $wpAdapter;
        $this->postMetaAdapter = $postMetaAdapter;
        $this->styleAdapter = new StyleAdapter($this->datastore,$wpAdapter,$this->logger );
        $this->workflowAdaper = new WorkflowAdapter($this->datastore,$wpAdapter,$this->logger );
        $this->notifyAdapter = new NotifyAdapter($postMetaAdapter);
        $this->notifyLayoutAdapter = new NotifyLayoutAdapter($wpAdapter,$this->logger);
        $this->notifySettingsEditor = new NotifySettings($datastore,$logger,$postMetaAdapter,$wpAdapter);

        
         new WoocommerceApi(new WoocommerceApiLogic($logger));

        add_action('wp_enqueue_scripts', array($this, 'loadJs'));
        add_action('admin_enqueue_scripts', array($this, 'loadJsAdmin'));
        add_action('admin_menu', array($this, 'createMenu'));
        add_action('get_footer', array($this, 'Load') );
        add_action('init',array($this, 'init') );
        add_action('add_meta_boxes', array($this->notifySettingsEditor, 'AddContent') );
        add_action('save_post', array($this->notifySettingsEditor,'Save'), 10, 3 );
        add_action('plugins_loaded', 'PluginLoaded');

        $this->logger->Call("Woocommerce_Notice Constructor End");
    }

    function init() {
        $this->notifySettingsEditor->RegisterPostType();
        load_plugin_textdomain( "shop-notify", false, 'shop-notify/lang' );
    }

    public function PluginLoaded() {
     

    }

    public function ShowStylesEditor(){
        $styles = new Styles($this->datastore,$this->logger,$this->wpAdapter);
        $styles->Show();
    }
    
    public function ShowWorkflowEditor(){
        $wfe = new WorkflowEditor($this->datastore,$this->logger,$this->postMetaAdapter,$this->notifyLayoutAdapter,$this->workflowAdaper,$this->wpAdapter);
        $wfe->Show();
    }
    
    

    public function loadJs($hook){
        $this->logger->Call("loadJs");
        $this->wpAdapter->EnqueueStyle("default","css/default.css"); 
        $this->wpAdapter->EnqueueStyle("animate","css/animate.css"); 

        $this->wpAdapter->RegisterScript('globals','/../js/globals.js');
        $this->wpAdapter->LocalizeScript('globals', 'global_vars', array(
                'minutesText' => $this->wpAdapter->locate('{value} minutes ago'),
                'hoursText' => $this->wpAdapter->locate('{value} hours ago'),
                'dayText' => $this->wpAdapter->locate('one day ago'),
                'daysText' => $this->wpAdapter->locate('{value} days ago'),
                'recently' => $this->wpAdapter->locate('recently'),
            )
        );

        $this->wpAdapter->EnqueueRegisteredScript("globals");

        $this->wpAdapter->EnqueueScript('logger','js/logger.js');
        $this->wpAdapter->EnqueueScript('common','js/common.js');
        $this->wpAdapter->EnqueueScript('controller','js/controller.js');
        $this->wpAdapter->EnqueueScript('notice','js/notice.js');
        $this->wpAdapter->EnqueueScript('runner','js/runner.js');
        $this->wpAdapter->EnqueueScript('bootstrap_notify','js/bootstrap-notify.js');
        $this->logger->Call("loadJs finished");

    }

    public function loadJsAdmin( $hook ) {
        $this->logger->Call("loadJsAdmin");
       echo  __( 'recently', $namespace );

        //if( is_admin() ) { 
            $this->logger->Call("Add admin scripts");
            $this->wpAdapter->EnqueueStyle("admin","css/admin.css"); 
            $this->wpAdapter->EnqueueStyle("animate","css/animate.css"); 

            $this->wpAdapter->EnqueueStyle("admin_bootstrap","css/bootstrap.css?"); 
            $this->wpAdapter->EnqueueStyle("admin_fontselect","css/fontselect.css?"); 
            $this->wpAdapter->EnqueueStyle("jqui","css/jquery-ui.min.css?"); 
            $this->wpAdapter->EnqueueExternalStyle('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css');
            $this->wpAdapter->EnqueueExternalStyle('bootstrap_select', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css');
            $this->wpAdapter->EnqueueStyle("default","css/default.css?"); 
             
            // Include our custom jQuery file with WordPress Color Picker dependency
            $this->wpAdapter->EnqueueScript('logger','js/logger.js');
            $this->wpAdapter->EnqueueScript('admin_script','js/admin.js');
            $this->wpAdapter->EnqueueScript('jqui','js/jquery-ui.min.js');
            $this->wpAdapter->EnqueueScript('common','js/common.js');

            $this->wpAdapter->EnqueueExternalScript('popper','https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js');
            $this->wpAdapter->EnqueueExternalScript('bootstrap','https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js');
            $this->wpAdapter->EnqueueExternalScript('bootstrap_select','https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js');
          
            $this->wpAdapter->EnqueueScript('bootstrap_notify','js/bootstrap-notify.js');
            $this->wpAdapter->EnqueueScript('input_mask','js/jquery.inputmask.bundle.js');
            $this->wpAdapter->EnqueueScript('fontselect','js/jquery.fontselect.js');
          
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


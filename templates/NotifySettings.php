<?php

include_once dirname( __FILE__ ) . '/../private/model/Layout.php';
include_once dirname( __FILE__ ) . '/../private/model/Style.php';
include_once dirname( __FILE__ ) . '/../private/model/Notify.php';
include_once dirname( __FILE__ ) . '/../private/CssLoader.php';
include_once dirname( __FILE__ ) . '/CommonControls.php';



class NotifySettings {
    //Constants
    private static $CONTROL_STYLE = "sn_style_content";
    private static $CONTROL_TITLE = "sn_title_content";
    private static $CONTROL_MESSAGE = "sn_message_content";

    private static $POSTTYPE = "shop-notify";
    static $namespace = "shop-notify";

    private $datastore;
    private $postMetaAdapter;
    private $logger;
    
    function __construct($datastore,$logger,$postMetaAdapter){
        $this->datastore = $datastore;
        $this->logger = $logger;
        $this->postMetaAdapter = $postMetaAdapter;
    }

    public function Show($post)
    {
        ?>
        <div class="notify-editor"> 
        <div class="head"><span>Settings</span> </div>
        

        <?php
        $this->logger->Call("Show");

        $notify = new Notify($post->ID,$this->postMetaAdapter);
        $this->logger->Info("New Notify");

        //Get Post Meta Data
        $selectedStyle = $notify->GetStyle();
        $this->logger->Info("Style is: " . $selectedStyle);

        $titel = $notify->GetTitle();
        $message = $notify->GetMessage();


        $styleList  = $this->datastore->GetStyleList();
        $currentStyleObject = Style::GetStyle($styleList,$selectedStyle);

        $cssLoader = new CssLoader();
        $cssLoader->AddStyle($currentStyleObject);
        $cssLoader->Load();
        // print_r("Show live preview");
        // print_r("Text editor");
        // print_r("Type");
        // print_r("Display Time");
        // print_r("Effects");
        
        $editorUrl = get_admin_url() . "edit.php?post_type=shop-notify&page=sn_style_editor&source=" . $post->ID;
        CommonControls::AddSelectBox(self::$CONTROL_STYLE,$styleList,$selectedStyle,"Style");
        CommonControls::Addbutton(1, plugins_url( '/../assets/edit.png', __FILE__ ),"","sn-edit-button");
        $this->DisplayDragItems(plugins_url( '/../assets/label.png', __FILE__ ));
        CommonControls::AddEditControl(self::$CONTROL_TITLE,$titel,"","Tite content",true,"ondrop='drop(event)'" );
        CommonControls::AddEditControl(self::$CONTROL_MESSAGE,$message,"","Message content",true,"ondrop='drop(event)'");
        ?>
        </div>
       
        <?php
        wp_enqueue_script( 'notify-editor-script',  plugins_url( '/../js/adminNotifyEditor.js?', __FILE__));
        wp_localize_script('notify-editor-script', 'notify_editor_vars', array(
                'editor_url' => $editorUrl
            )
        );
        $this->AddDialog();

    }

    private function DisplayDragItems($labelUrl)
    {
        ?>
        <div class="dragitems">
        <?php
        $this->DisplayDragItem("ProductName","{ProductName}",$labelUrl);
        $this->DisplayDragItem("GivenName","{GivenName}",$labelUrl);
        ?>
        </div>
        <?php
    }

    private function AddDialog()
    {?>
        <div class="modal fade" id="saveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Unsaved changes</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              You have still unsaved changes. Do you still want to leave this page?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Abort</button>
              <button type="button" class="btn btn-primary" onclick="OpenStyleEditor();">Leave without saving</button>
            </div>
          </div>
        </div>
      </div>
      <?php
    }

    private function DisplayDragItem($name,$id, $labelUrl)
    {
        ?>
        <span class="Foo" id="<?php echo $id;?>" draggable="true" ondragstart="drag(event)">
            <img src="<?php echo $labelUrl;?>"></img>
            <p><?php echo $name;?></p>
        </span>
        <?php
    }

    
    public function Save( $post_id, $post, $update)
    {
        $this->logger->Call("Save");
        /*
         * In production code, $slug should be set only once in the plugin,
         * preferably as a class property, rather than in each function that needs it.
         */
        $post_type = get_post_type($post_id);
        $this->logger->Info("Post Type: $post_type");
        $this->logger->Info("Post ID:$post_id");

        // If this isn't a 'book' post, don't update it.
        if ( self::$POSTTYPE != $post_type ) return;
    
        // - Update the post's metadata.
        $style = $_POST[self::$CONTROL_STYLE];
        $title = $_POST[self::$CONTROL_TITLE];
        $message = $_POST[self::$CONTROL_MESSAGE];

        $notify = new Notify($post_id,$this->postMetaAdapter);
        $notify->SaveStyle($style);
        $notify->SaveTitle($title);
        $notify->SaveMessage( $message);
    }

    public function RegisterPostType()
    {
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

    public function AddContent()
    {
        add_meta_box('sn_settings',
                    __( 'Notification',self::$POSTTYPE),
                    array($this,'Show'),
                    '',
                    'advanced',
                    'default',
                null);
    }

}


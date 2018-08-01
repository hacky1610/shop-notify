<?php

include_once dirname( __FILE__ ) . '/../private/model/Layout.php';
include_once dirname( __FILE__ ) . '/../private/model/Style.php';
include_once dirname( __FILE__ ) . '/../private/CssLoader.php';
include_once dirname( __FILE__ ) . '/CommonControls.php';



class NotifySettings {
    //Constants
    private static $SELECTED_STYLE = "selected_style";
    private static $ENTERED_TITLE = "entered_title";
    private static $ENTERED_MESSAGE = "entered_message";

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

        <?php
        $this->logger->Call("Show");

        //Get Post Meta Data
        $selectedStyle = $this->postMetaAdapter->GetPostMeta($post->ID,self::$SELECTED_STYLE);
        $titel = $this->postMetaAdapter->GetPostMeta($post->ID,self::$ENTERED_TITLE);
        $message = $this->postMetaAdapter->GetPostMeta($post->ID,self::$ENTERED_MESSAGE);

        $this->logger->Info("Style: $selectedStyle");

        $styleList  = $this->datastore->GetStyleList();
        $currentStyleObject = Style::GetStyle($styleList,$selectedStyle);

        $cssLoader = new CssLoader($currentStyleObject->content);
        $cssLoader->Load();

        // print_r("Show live preview");
        // print_r("Text editor");
        // print_r("Type");
        // print_r("Display Time");
        // print_r("Effects");
        CommonControls::AddSelectBox(self::$CONTROL_STYLE,$styleList,$selectedStyle);
        CommonControls::AddEditControl(self::$CONTROL_TITLE,$titel,"","Tite content");
        CommonControls::AddEditControl(self::$CONTROL_MESSAGE,$message,"","Message content");
        ?>
        </div>
        <?php

        $this->JsCode();
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

        $this->postMetaAdapter->SavePostMeta( $post_id, self::$SELECTED_STYLE, $style );
        $this->postMetaAdapter->SavePostMeta( $post_id, self::$ENTERED_TITLE, $title  );
        $this->postMetaAdapter->SavePostMeta( $post_id, self::$ENTERED_MESSAGE, $message);

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

    private function JsCode()
    {?>
       <script>
       jQuery(document).ready(function($)
           {
    
              ShowPreviewPopup();
               $(document).on('change', '.layout-content', function()
                   {
       
                       var style = $(this).children(":selected").attr("id");
                       
                       var data = {
                        'action': 'wcn_get_style',
                        'style_id': style
                        };
                        SendAjaxSync(data).then((s) =>
                        {
                            $("#wcn_style_sheet").html(s);
                        });
                   })
               
               })
       </script>
       <?php
    }
 
  





   
    

}


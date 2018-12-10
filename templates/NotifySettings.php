<?php

include_once dirname( __FILE__ ) . '/../private/model/Layout.php';
include_once dirname( __FILE__ ) . '/../private/model/Style.php';
include_once dirname( __FILE__ ) . '/../private/model/Notify.php';
include_once dirname( __FILE__ ) . '/../private/CssLoader.php';
include_once dirname( __FILE__ ) . '/CommonControls.php';



class NotifySettings {
    //Constants
    private static $CONTROL_STYLE = "sn_style_content";
    private static $DEFAULT_STYLE = "modern";
    private static $CONTROL_TITLE = "sn_title_content";
    private static $CONTROL_MESSAGE = "sn_message_content";
    private static $PLACEMENT = "sn_placement";
    private static $ENTERANIMATION = "sn_enteranimation";
    private static $EXITANIMATION = "sn_exitanimation";

    private static $POSTTYPE = "shop-notify";
    static $namespace = "shop-notify";

    private $datastore;
    private $postMetaAdapter;
    private $logger;
    private $wpAdapter;
    
    function __construct($datastore,$logger,$postMetaAdapter,$wpAdapter){
        $this->datastore = $datastore;
        $this->logger = $logger;
        $this->postMetaAdapter = $postMetaAdapter;
        $this->wpAdapter = $wpAdapter;
    }

    public function Show($post)
    {
        ?>
         <div class="panel panel-default section notify-editor">
                <div class="panel-heading"><?php echo  $this->wpAdapter->locate( 'Settings'); ?></div>
                <div class="panel-body">
                <?php
                    $this->ShowEditorBody($post);
                ?>
                </div>
        </div>

          <div class="panel panel-default section sn_panel preview">
                <div class="panel-heading">Preview</div>
                <div class="panel-body">
                </div>
        </div>

       
        <?php
        $editorUrl = get_admin_url() . "edit.php?post_type=shop-notify&page=sn_style_editor&source=" . $post->ID;

        $this->wpAdapter->EnqueueStyle( 'workflow-editor',  'css/adminNotifySettings.css?');
        $this->wpAdapter->RegisterScript('notify-editor-script','/../js/adminNotifyEditor.js');
        $this->wpAdapter->LocalizeScript('notify-editor-script', 'notify_editor_vars', array(
                'editor_url' => $editorUrl
            )
        );
        $this->wpAdapter->EnqueueRegisteredScript("notify-editor-script");

        $this->AddDialog();
    }


    private function ShowEditorBody($post)
    {
        $this->logger->Call("Show");

        $notify = new Notify($post->ID,$this->postMetaAdapter);
        $this->logger->Info("New Notify");

        //Get Post Meta Data
        $selectedStyle = $notify->GetStyle();
        if(empty($selectedStyle))
            $selectedStyle = self::$DEFAULT_STYLE;
        $this->logger->Info("Style is: " . $selectedStyle);

        $titel = $notify->GetTitle();
        $message = $notify->GetMessage();


        $styleList  = $this->datastore->GetStyleList();
        $currentStyleObject = Style::GetStyle($styleList,$selectedStyle);

        $cssLoader = new CssLoader();
        $cssLoader->AddStyle($currentStyleObject);
        $cssLoader->Load();
        
        $addButton = CommonControls::Addbutton(1, plugins_url( '/../assets/edit.png', __FILE__ ),"","sn-edit-button");
        CommonControls::AddSelectBox(self::$CONTROL_STYLE,$styleList,$selectedStyle,"Style",false,$addButton);
        ;
        $this->DisplayDragItems(plugins_url( '/../assets/label.png', __FILE__ ));
        CommonControls::AddEditControl(self::$CONTROL_TITLE,$titel,"","Tite content",true );
        CommonControls::AddEditControl(self::$CONTROL_MESSAGE,$message,"","Message content",true);
        $this->AddPositionSelectBox(self::$PLACEMENT, $notify->GetPlacement());
        $this->AddEnterAnimationSelectBox(self::$ENTERANIMATION,$notify->GetEnterAnimation(),"Enter animation:");
        $this->AddExitAnimationSelectBox(self::$EXITANIMATION,$notify->GetExitAnimation(),"Exit animation:");
    }

    private function DisplayDragItems($labelUrl)
    {
        ?>
        <div class="dragitems">
        <?php
        $this->DisplayDragItem("ProductName","{ProductName}",$labelUrl);
        $this->DisplayDragItem("GivenName","{GivenName}",$labelUrl);
        $this->DisplayDragItem("Bought","{Bought}",$labelUrl);
        $this->DisplayDragItem("Contry","{Country}",$labelUrl);
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
        <span class="sn-drag-item" id="<?php echo $id;?>" draggable="true">
            <img src="<?php echo $labelUrl;?>"></img>
            <p><?php echo $name;?></p>
        </span>
        <?php
    }

    private function AddPositionSelectBox($id,$keyToSet)
    {       

        $array = array(
            "top-left"  => "Top left",
            "top-right" => "Top right",
            "bottom-left" => "Bottom left",
            "bottom-right" => "Bottom right",
        );

        $object = array(
            "name" => null,
            "vals" => $array
        );
        self::AddSelectBox($id,"Position:",array($object),$keyToSet);
    }

    private function AddEnterAnimationSelectBox($id,$keyToSet,$name)
    {       

        $array = array(
            array(
                "name" => "Attention Seekers",
                "vals" => array(
                    "bounce"  => "bounce",
                    "flash" => "flash",
                    "pulse" => "pulse",
                    "rubberBand" => "rubberBand",
                    "shake" => "shake",
                    "swing" => "swing",
                    "tada" => "tada",
                    "wobble" => "wobble",
                    "jello" => "jello",
                )
            ),
            array(
              "name" => "Bouncing",
              "vals" => array(
                  "bounceIn"  => "bounceIn",
                  "bounceInDown" => "bounceInDown",
                  "bounceInLeft" => "bounceInLeft",
                  "bounceInRight" => "bounceInRight",
                  "bounceInUp" => "bounceInUp",
              )
              ),
              array(
                "name" => "Fading",
                "vals" => array(
                    "fadeIn"  => "fadeIn",
                    "fadeInDown" => "fadeInDown",
                    "fadeInDownBig" => "fadeInDownBig",
                    "fadeInLeft" => "fadeInLeft",
                    "fadeInLeftBig" => "fadeInLeftBig",
                    "fadeInRight" => "fadeInRight",
                    "fadeInRightBig" => "fadeInRightBig",
                    "fadeInUp" => "fadeInUp",
                    "fadeInUpBig" => "fadeInUpBig",
                )
              ),
                array(
                  "name" => "Flippers",
                  "vals" => array(
                      "flip"  => "flip",
                      "flipInX" => "flipInX",
                      "flipInY" => "flipInY",
                  )
                ),
                array(
                  "name" => "Lightspeed",
                  "vals" => array(
                      "lightSpeedIn"  => "lightSpeedIn",
                  )
                ),
                array(
                  "name" => "Rotating",
                  "vals" => array(
                      "rotateIn"  => "rotateIn",
                      "rotateInDownLeft" => "rotateInDownLeft",
                      "rotateInDownRight" => "rotateInDownRight",
                      "rotateInUpLeft" => "rotateInUpLeft",
                      "rotateInDownLeft" => "rotateInDownLeft",
                      "rotateInUpRight" => "rotateInUpRight",
                  )
                ),
                array(
                  "name" => "Sliding",
                  "vals" => array(
                      "slideInUp"  => "slideInUp",
                      "slideInDown" => "slideInDown",
                      "slideInLeft" => "slideInLeft",
                      "slideInRight" => "slideInRight",
                  )
                ),
                array(
                  "name" => "Zoom",
                  "vals" => array(
                      "zoomIn"  => "zoomIn",
                      "zoomInDown" => "zoomInDown",
                      "zoomInLeft" => "zoomInLeft",
                      "zoomInRight" => "zoomInRight",
                      "zoomInUp" => "zoomInUp",
                  )
                ),
                array(
                  "name" => "Specials",
                  "vals" => array(
                      "jackInTheBox" => "jackInTheBox",
                      "rollIn" => "rollIn",
                  )
                )
          );
        

        $object = array(
            "name" => null,
            "vals" => $array
        );
        self::AddSelectBox($id,$name,$array,$keyToSet);
    }

    private function AddExitAnimationSelectBox($id,$keyToSet,$name)
    {       

        $array = array(
            array(
                "name" => "Attention Seekers",
                "vals" => array(
                    "bounce"  => "bounce",
                    "flash" => "flash",
                    "pulse" => "pulse",
                    "rubberBand" => "rubberBand",
                    "shake" => "shake",
                    "swing" => "swing",
                    "tada" => "tada",
                    "wobble" => "wobble",
                    "jello" => "jello",
                )
            ),
              array(
                "name" => "Bouncing",
                "vals" => array(
                    "bounceOut"  => "bounceOut",
                    "bounceOutDown" => "bounceOutDown",
                    "bounceOutLeft" => "bounceOutLeft",
                    "bounceOutRight" => "bounceOutRight",
                    "bounceOutUp" => "bounceOutUp",
                )
              ),
              array(
                "name" => "Fading",
                "vals" => array(
                    "fadeOut"  => "fadeOut",
                    "fadeOutDown" => "fadeOutDown",
                    "fadeOutDownBig" => "fadeOutDownBig",
                    "fadeOutLeft" => "fadeOutLeft",
                    "fadeOutLeftBig" => "fadeOutLeftBig",
                    "fadeOutRight" => "fadeOutRight",
                    "fadeOutRightBig" => "fadeOutRightBig",
                    "fadeOutUp" => "fadeOutUp",
                    "fadeOutUpBig" => "fadeOutUpBig",
                )
                ),
                array(
                  "name" => "Flippers",
                  "vals" => array(
                      "flip"  => "flip",
                      "flipOutX" => "flipOutX",
                      "flipOutY" => "flipOutY",
                  )
                ),
                array(
                  "name" => "Lightspeed",
                  "vals" => array(
                      "lightSpeedOut" => "lightSpeedOut",
                  )
                ),
                array(
                  "name" => "Rotating",
                  "vals" => array(
                      "rotateOut"  => "rotateOut",
                      "rotateOutDownLeft" => "rotateOutDownLeft",
                      "rotateOutDownRight" => "rotateOutDownRight",
                      "rotateOutUpLeft" => "rotateOutUpLeft",
                      "rotateOutDownLeft" => "rotateOutDownLeft",
                      "rotateOutUpRight" => "rotateOutUpRight",
                  )
                ),
                array(
                  "name" => "Sliding",
                  "vals" => array(
                      "slideOutUp"  => "slideOutUp",
                      "slideOutDown" => "slideOutDown",
                      "slideOutLeft" => "slideOutLeft",
                      "slideOutRight" => "slideOutRight",
                  )
                ),
                array(
                  "name" => "Zoom",
                  "vals" => array(
                      "zoomOut"  => "zoomOut",
                      "zoomOutDown" => "zoomOutDown",
                      "zoomOutLeft" => "zoomOutLeft",
                      "zoomOutRight" => "zoomOutRight",
                      "zoomOutUp" => "zoomOutUp",
                  )
                ),
                array(
                  "name" => "Specials",
                  "vals" => array(
                      "hinge"  => "hinge",
                      "jackInTheBox" => "jackInTheBox",
                      "rollOut" => "rollOut",
                      "hinge"  => "hinge",

                  )
                )
          );
        

        $object = array(
            "name" => null,
            "vals" => $array
        );
        self::AddSelectBox($id,$name,$array,$keyToSet);
    }

    private static function AddSelectBox($id,$label,$categories,$keyToSet)
    {       
      ?>
    
      <div class="sn_edit_container">
          <label><?php echo $label; ?></label>
          <select class="form-control layout-content" id="<?php echo $id; ?>" name="<?php echo $id; ?>">
          <?php
                foreach ($categories as $category) {
                    if ($category['name'] != null) {
                        echo "<optgroup label='" . $category['name'] ."'>"; 
                    }
                    foreach ($category['vals'] as $key => $value) {
                        $selected = "";
                        if ($key == $keyToSet) {
                            $selected = "selected";
                        }
                        echo "<option $selected value='" .  $key ."'>". $value ."</option>";
                    }
                    if ($category['name'] != null) {
                        echo "</optgroup>"; 
                    }
                }
            ?>   
          </select>
         </div>
         </br>
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
    
        $notify = new Notify($post_id,$this->postMetaAdapter);
        $notify->SaveStyle($_POST[self::$CONTROL_STYLE]);
        $notify->SaveTitle($_POST[self::$CONTROL_TITLE]);
        $notify->SavePlacement($_POST[self::$PLACEMENT]);
        $notify->SaveMessage( $_POST[self::$CONTROL_MESSAGE]);
        $notify->SaveEnterAnimation( $_POST[self::$ENTERANIMATION]);
        $notify->SaveExitAnimation( $_POST[self::$EXITANIMATION]);
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


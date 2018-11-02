<?php

include_once dirname( __FILE__ ) . '/../private/model/Layout.php';
include_once dirname( __FILE__ ) . '/../private/model/Notify.php';
include_once dirname( __FILE__ ) . '/../private/adapter/NotifyLayoutAdapter.php';
include_once dirname( __FILE__ ) . '/../private/logger.php';
include_once dirname( __FILE__ ) . '/../private/CssLoader.php';
include_once dirname( __FILE__ ) . '/CommonControls.php';

class WorkflowEditor {
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    private $datastore;
    /**
     * Action argument used by the nonce validating the AJAX request.
     *
     * @var Logger
     */
    private $logger;
    private $shopNotifyList = array();
    private $notifyLayoutAdapter;
    private $workflowAdapter;
    
    function __construct($datastore,$logger,$postmetaAdapter,$notifyLayoutAdapter,$workflowAdapter){
        $this->datastore = $datastore;
        $this->logger = $logger;
        $this->notifyLayoutAdapter = $notifyLayoutAdapter;
        $this->workflowAdapter = $workflowAdapter;

        $allNotifyIds = get_posts(array(
            'fields'          => 'ids',
            'posts_per_page'  => -1,
            'post_type' => 'shop-notify'
        ));

        foreach ($allNotifyIds as $id) {
            $notify = new  Notify($id,$postmetaAdapter);
            array_push($this->shopNotifyList,$notify);
        }
        wp_enqueue_script( 'sn-notice',  plugins_url( '/../js/notice.js?', __FILE__));
        wp_enqueue_script( 'sn_controller',  plugins_url( '/../js/controller.js?', __FILE__));
        wp_register_script( 'workflow-editor-element',  plugins_url( '/../js/wfeElements.js?', __FILE__));
        wp_localize_script('workflow-editor-element', 'workflow_element_vars', array(
          'delete_icon' => WCN_PATH . "assets/delete.png"
        ));

        wp_enqueue_script("workflow-editor-element");
        wp_enqueue_script( 'workflow-editor-script',  plugins_url( '/../js/adminWorkflowEditor.js?', __FILE__));
    }

    function LoadStyles()
    {
        $cssLoader = new CssLoader();
        $styleList  = $this->datastore->GetStyleList();
        foreach ($styleList as $style) {
            $cssLoader->AddStyle($style);
        }
  
       $cssLoader->Load();
    }

   

    function Show()
    {
        $this->logger->Call("Show WorkflowEditor");
        $this->LoadStyles();
        ?>

        <h2>WorkflowEditor</h2>
        <div id="wfeContent">
            <div id="workingarea" >
                <ul class="droparea center sortable">

                </ul>

            </div>

            <div id="toolarea">
                <div class="section" id="notifyList">
                <?php

                
                foreach ($this->shopNotifyList as $notify) {
                    ?> <div class="notify-drag draggable" type="notify" notify-id="<?php echo $notify->GetId();  ?>"> <?php
                   $title = '[{"type":"text","val":"' . $notify->GetPostName() .'"}]';
                   echo $this->notifyLayoutAdapter->GetNotifyLayout($notify->GetId(),$title ,"","",$notify->GetStyle());
                   ?> </div> <?php
                }
                ?>
                </div>
                <div class="section" id="toollist">
                    <div class="draggable" type="sleep">Sleep</div>
                    <div class="draggable" type="condition">Condition</div>
                </div>
                <div class="section" id="editorarea">
                
                </div>
                <div>
                  <input id="saveButton" class="btn btn-primary" type="button" value="Save"> 
                </div>
            </div>

        </div>

        <?php
     }



   

   
     

    

}


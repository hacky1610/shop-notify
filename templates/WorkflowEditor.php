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
    
    function __construct($datastore,$logger,$postmetaAdapter,$notifyLayoutAdapter,$workflowAdapter,$wpAdapter){
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
        $wpAdapter->EnqueueScript('notice','js/notice.js');
        $wpAdapter->EnqueueScript('controller','js/controller.js');

        $wpAdapter->RegisterScript('workflow-editor-element', 'js/wfeElements.js?');
        $wpAdapter->LocalizeScript('workflow-editor-element', 'workflow_element_vars', array(
          'delete_icon' => WCN_PATH . "assets/delete.png"
        ));

        $wpAdapter->EnqueueRegisteredScript("workflow-editor-element");
        $wpAdapter->EnqueueStyle( 'workflow-editor',  'css/adminWorkflowEditor.css?');
        $wpAdapter->EnqueueScript( 'workflow-editor-script',  'js/adminWorkflowEditor.js?');
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
        <div class="wfeContent">
            <div id="workingarea" >
                <ul class="droparea center sortable">

                </ul>

            </div>

            <div id="toolarea">
                <div class="panel panel-default section" id="notifyList">
                    <div class="panel-heading">Notification</div>
                    <div class="panel-body">
                    <?php

                    
                    foreach ($this->shopNotifyList as $notify) {
                        ?> <div class="notify-drag draggable" type="notify" notify-id="<?php echo $notify->GetId();  ?>"> <?php
                      $title = '[{"type":"text","val":"' . $notify->GetPostName() .'"}]';
                      echo $this->notifyLayoutAdapter->GetNotifyLayout($notify->GetId(),$title ,"","",$notify->GetStyle());
                      ?> </div> <?php
                    }
                    ?>
                </div>
                </div>
                <div class="panel panel-default section" id="toollist">
                    <div class="panel-heading">Tools</div>
                    <div class="panel-body">
                      <div class="draggable" type="sleep">Sleep</div>
                      <div class="draggable" type="condition">Condition</div>
                    </div>
                </div>
                <div class="panel panel-default section">
                  <div class="panel-heading">Editor</div>
                  <div class="panel-body" id="editorarea">Please select any element</div>
                </div>
                <div>
                  <input id="saveButton" class="btn btn-primary" type="button" value="Save"> 
                </div>
            </div>

        </div>

        <?php
     }



   

   
     

    

}


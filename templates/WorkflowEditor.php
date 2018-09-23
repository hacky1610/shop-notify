<?php

include_once dirname( __FILE__ ) . '/../private/model/Layout.php';
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
    
    function __construct($datastore,$logger){
        $this->datastore = $datastore;
        $this->logger = $logger;
        wp_enqueue_script( 'workflow-editor-element',  plugins_url( '/../js/wfeElements.js?', __FILE__));
        wp_enqueue_script( 'workflow-editor-script',  plugins_url( '/../js/adminWorkflowEditor.js?', __FILE__));
    }

   

    function Show()
    {
        $this->logger->Call("Show WorkflowEditor");
      
        ?>

        <h2>WorkflowEditor</h2>
        <div id="wfeContent">
            <div id="workingarea" >
                <ul class="droparea center sortable">

                </ul>

            </div>

            <div id="toolarea">
                <div id="notifyList">
            
                </div>
                <div id="toollist">
                    <div class="draggable" id="sleep">Sleep</div>
                </div>
                <div id="editorarea">
                
                </div>
            </div>
        </div>

        <?php
     }



   

   
     

    

}


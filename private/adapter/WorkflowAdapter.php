<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once dirname( __FILE__ ) . '/AjaxAdapter.php';

class WorkflowAdapter extends AjaxAdapter{
  
    const ACTION_GET_WORKFLOW = 'wcn_get_workflow';
    const ACTION_SAVE_WORKFLOW = 'wcn_save_workflow';
    private $datastore;
    /**
     * Action argument used by the nonce validating the AJAX request.
     *
     * @var string
     */
    const NONCE = 'my-plugin-ajax';

    function __construct($datastore,$wpAdapter,$logger){
        parent::__construct($wpAdapter,$logger);
        $this->datastore = $datastore;

        parent::AddAction(self::ACTION_GET_WORKFLOW,$this,"GetWorkflow");
        parent::AddAction(self::ACTION_SAVE_WORKFLOW,$this,"SaveWorkflow");
    }

    public function SaveWorkflow()
    {
        $content = parent::GetPost('workflow_content');
        if(!isset($content))
            $this->ThrowError();


        $this->datastore->SetWorkflow($content);
        $this->OK();
        $this->logger->Info("Save workflow with content" );
        $this->logger->Info($content );
        parent::WpDie();
    }


    public function GetWorkflow()
    {
        $content =  $this->datastore->GetWorkflow();
        print_r($content);

        $this->logger->Info("Load workflow with content" );
        $this->logger->Info($content );

        parent::WpDie();
    }


}


<?php

include_once dirname( __FILE__ ) . '/../model/Layout.php';

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class NotifyAdapter {
  
    const ACTION = 'wcn_get_notify';
    private $datastore;
    
    function __construct($datastore){
        $this->datastore = $datastore;
        add_action('wp_ajax_' . self::ACTION, array($this, 'GetNotify'));
        add_action('wp_ajax_nopriv_' . self::ACTION, array($this, 'GetNotify'));
    }

    public function GetNotify()
    {
        $id =  $_POST['id'];
        $title =  $_POST['title_content'];
        $message =  $_POST['message_content'];

        $layout = new Layout($id);

        $title = str_replace('\\',"",$title);
        $titleArray = json_decode($title);
        
        foreach ($titleArray as $value)
        {
            if($value->type == "text")
            {
                $layout->AddToTitle(Layout::CreateText($value->val));
            }
            else
            {
                $layout->AddToTitle(Layout::CreateLink($value->val));
            }
        }
        $message = str_replace('\\',"",$message);
        $messageArray = json_decode($message);
        
        foreach ($messageArray as $value)
        {
            if($value->type == "text")
            {
                $layout->AddToMessage(Layout::CreateText($value->val));
            }
            else
            {
                $layout->AddToMessage(Layout::CreateLink($value->val));
            }
        }
        
        $layout->Render();
        wp_die();
    }

  


}


<?php

include_once dirname( __FILE__ ) . '/../model/Layout.php';

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class NotifyLayoutAdapter {
  
    const ACTION = 'wcn_get_notify_layout';
    
    function __construct(){
        add_action('wp_ajax_' . self::ACTION, array($this, 'GetNotifyAjax'));
        add_action('wp_ajax_nopriv_' . self::ACTION, array($this, 'GetNotifyAjax'));
    }

    public function GetNotifyAjax()
    {
        $id =  $_POST['id'];
        $title =  $_POST['title_content'];
        $message =  $_POST['message_content'];
        $pictureLink =  $_POST['pictureLink'];

        echo $this->GetNotifyLayout($id,$title,$message,$pictureLink);
        wp_die();
    }

    public function GetNotifyLayout($id, $title, $message,$pictureLink)
    {
        $layout = new Layout($id);
        $layout->AddPicture($pictureLink);

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
                $layout->AddToTitle(Layout::CreateLink($value->val,$value->link));
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
                $layout->AddToMessage(Layout::CreateLink($value->val,$value->link));
            }
        }
        
        return $layout->Render();
    }

  


}


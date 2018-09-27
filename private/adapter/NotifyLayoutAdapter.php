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
        $style =  $_POST['style'];
        $pictureLink =  $_POST['pictureLink'];

        echo $this->GetNotifyLayout($id,$title,$message,$pictureLink,$style);
        wp_die();
    }

    public function GetNotifyLayout($id, $title, $message,$pictureLink,$style)
    {
        $layout = new Layout($id,$style);
        $layout->AddPicture($pictureLink);

        $title = str_replace('\\',"",$title);
        $titleArray = json_decode($title);
        $titleContent = array();
        foreach ($titleArray as $value)
        {
            if($value->type == "text")
            {
                array_push($titleContent,Layout::CreateParagraph($value->val));
            }
            else
            {
                array_push($titleContent,Layout::CreateLink($value->val,$value->link));
            }
        }
        $layout->AddToTitle(Layout::CreateText($titleContent));

        $message = str_replace('\\',"",$message);
        $messageArray = json_decode($message);
        $messageContent = array();

        foreach ($messageArray as $value)
        {
            if($value->type == "text")
            {
                array_push($messageContent,Layout::CreateParagraph($value->val));
                
            }
            else
            {
                array_push($messageContent,Layout::CreateLink($value->val,$value->link));
            }
        }
        $layout->AddToMessage(Layout::CreateText($messageContent));

        
        return $layout->Render();
    }

  


}


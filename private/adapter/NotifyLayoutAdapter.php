<?php

include_once dirname( __FILE__ ) . '/../model/Layout.php';
include_once dirname( __FILE__ ) . '/AjaxAdapter.php';


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class NotifyLayoutAdapter extends AjaxAdapter {
  
    const ACTION = 'wcn_get_notify_layout';
    
    function __construct($wpAdapter,$logger){
        parent::__construct($wpAdapter,$logger);
        parent::AddAction( self::ACTION,$this,'GetNotifyAjax');
    }

    public function GetNotifyAjax()
    {
        $id =  parent::GetPost['id'];
        $title =  parent::GetPost['title_content'];
        $message =  parent::GetPost['message_content'];
        $style =  parent::GetPost['style'];
        $pictureLink =  parent::GetPost['pictureLink'];

        echo $this->GetNotifyLayout($id,$title,$message,$pictureLink,$style);
        wp_die();
    }

    public function GetContentFromJson($json)
    {
        $content = str_replace('\\',"",$json);
        $contentArray = json_decode($content);
        $textArray = array();
        foreach ($contentArray as $value)
        {
            if($value->type == "text")
            {
                array_push($textArray,Layout::CreateParagraph($value->val));
            }
            else
            {
                array_push($textArray,Layout::CreateLink($value->val,$value->link));
            }
        }
        return $textArray;
    }

    public function GetNotifyLayout($id, $title, $message,$pictureLink,$style)
    {
        $layout = new Layout($id,$style);
        $layout->AddPicture($pictureLink);

        $titleContent = $this->GetContentFromJson($title);
        $layout->AddToTitle(Layout::CreateText($titleContent));

        $messageContent = $this->GetContentFromJson($message);
        $layout->AddToMessage(Layout::CreateText($messageContent));

        
        return $layout->Render();
    }

  


}


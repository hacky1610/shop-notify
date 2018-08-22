<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once dirname( __FILE__ ) . '/AjaxAdapter.php';
include_once dirname( __FILE__ ) . './../model/Style.php';


class StyleAdapter extends AjaxAdapter{
  
    const ACTION_GET_STYLE = 'wcn_get_style';
    const ACTION_SAVE_STYLE = 'wcn_save_style';
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

        parent::AddAction(self::ACTION_GET_STYLE,$this,"GetStyle");
        parent::AddAction(self::ACTION_SAVE_STYLE,$this,"SaveStyle");
    }

    public function SaveStyle()
    {
        $styleId = parent::GetPost('style_id');
        $content = parent::GetPost('style_content');

        if(!isset($styleId) || !isset($content))
            $this->ThrowError();


        $styleList = $this->datastore->GetStyleList();
        Style::SaveStyle($styleList,$styleId,$content);
        $this->datastore->SetStyleList($styleList);
        $this->OK();
        $this->logger->Info("Save Style $styleId with content $content" );
        parent::WpDie();
    }


    public function GetStyle()
    {
        $styleId = parent::GetPost('style_id');
        $style = Style::GetStyle($this->datastore->GetStyleList(),$styleId);
        print_r($style->content);
        $this->logger->Info("Load Style with id $styleId" );
        $this->logger->Info($style->content );

        parent::WpDie();
    }


}


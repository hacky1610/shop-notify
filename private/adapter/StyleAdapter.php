<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once dirname( __FILE__ ) . '/AjaxAdapter.php';


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

    function __construct($datastore){
        $this->datastore = $datastore;
        add_action('wp_ajax_' . self::ACTION_GET_STYLE, array($this, 'GetStyle'));
        add_action('wp_ajax_nopriv_' . self::ACTION_GET_STYLE, array($this, 'GetStyle'));

        add_action('wp_ajax_' . self::ACTION_SAVE_STYLE, array($this, 'SaveStyle'));
        add_action('wp_ajax_nopriv_' . self::ACTION_SAVE_STYLE, array($this, 'SaveStyle'));
    }

    public function SaveStyle()
    {
        $styleId = $_POST['style_id'];
        $content = $_POST['style_content'];

        if(!isset($styleId) || !isset($content))
            $this->ThrowError();


        $styleList = $this->datastore->GetStyleList();
        Style::SaveStyle($styleList,$styleId,$content);
        $this->datastore->SetStyleList($styleList);
        $this->OK();
        wp_die();
    }

    public function GetStyle()
    {
        $styleId = $_POST['style_id'];
        $style = Style::GetStyle($this->datastore->GetStyleList(),$styleId);
        print_r($style->content);
        wp_die();
    }


}


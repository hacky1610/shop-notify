<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once dirname( __FILE__ ) . '/../adapter/PostMetaAdapter.php';


class Notify {
       /**
     * Logic for Woocommerce 
     *
     * @var PostMetaAdapter
     */
    private $postmetaAdapter;
    private static $SELECTED_STYLE = "selected_style";
    private static $ENTERED_TITLE = "entered_title";
    private static $ENTERED_MESSAGE = "entered_message";
    private static $PLACEMENT = "placement";
    private static $ENTERANIMATION = "enter_animation";
    private static $EXITANIMATION = "exit_animation";

    function __construct(   $id, $postmetaAdapter){
        $this->postmetaAdapter = $postmetaAdapter;
        $this->id = $id;
        
    }

    public function GetId()
    {
        return $this->id;
    }

    public function GetPostName()
    {
        return $this->postmetaAdapter->GetTitle($this->id);
    }

    public function GetStyle()
    {
        return $this->postmetaAdapter->GetPostMeta($this->id,self::$SELECTED_STYLE);
    }

    public function GetTitle()
    {
        return $this->postmetaAdapter->GetPostMeta($this->id,self::$ENTERED_TITLE);
    }

    public function GetMessage()
    {
        return $this->postmetaAdapter->GetPostMeta($this->id,self::$ENTERED_MESSAGE);
    }

    public function GetPlacement()
    {
        return $this->postmetaAdapter->GetPostMeta($this->id,self::$PLACEMENT);
    }

    public function GetEnterAnimation()
    {
        return $this->postmetaAdapter->GetPostMeta($this->id,self::$ENTERANIMATION);
    }

    public function GetExitAnimation()
    {
        return $this->postmetaAdapter->GetPostMeta($this->id,self::$EXITANIMATION);
    }

    public function GetObject()
    {
        $object->style = $this->GetStyle();
        $object->title = $this->GetTitle();
        $object->message = $this->GetMessage();
        $object->style = $this->GetStyle();
        $object->placement = $this->GetPlacement();
        $object->enterAnimation = $this->GetEnterAnimation();
        $object->exitAnimation = $this->GetExitAnimation();

        return json_encode($object);
    }

    public function SaveStyle($value)
    {
        $this->postmetaAdapter->SavePostMeta( $this->id, self::$SELECTED_STYLE, $value );
    }

    public function SaveTitle($value)
    {
        $this->postmetaAdapter->SavePostMeta( $this->id, self::$ENTERED_TITLE, $value  );
    }

    public function SaveMessage($value)
    {
        $this->postmetaAdapter->SavePostMeta( $this->id, self::$ENTERED_MESSAGE, $value);
    }

    public function SavePlacement($value)
    {
        $this->postmetaAdapter->SavePostMeta( $this->id, self::$PLACEMENT, $value);
    }

    public function SaveEnterAnimation($value)
    {
        $this->postmetaAdapter->SavePostMeta( $this->id, self::$ENTERANIMATION, $value);
    }

    public function SaveExitAnimation($value)
    {
        $this->postmetaAdapter->SavePostMeta( $this->id, self::$EXITANIMATION, $value);
    }


   
 
 
}


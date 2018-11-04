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
    private static $POSTION = "position";

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

    public function GetObject()
    {
        $object->style = $this->GetStyle();
        $object->title = $this->GetTitle();
        $object->message = $this->GetMessage();
        $object->style = $this->GetStyle();

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

    public function SavePosition($value)
    {
        $this->postmetaAdapter->SavePostMeta( $this->id, self::$POSITION°, $value);
    }


   
 
 
}


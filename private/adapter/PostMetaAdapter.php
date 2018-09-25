<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PostMetaAdapter {
  

    function __construct(){
        

    }

    public function GetPostMeta($postid,$key,$default = "")
    {
        $meta = get_post_meta($postid,$key,true);
        if(isset($meta))
            return $meta;
        else 
            return $default;
    }

    public function GetTitle($id)
    {
        $post = get_post($id);
        return $post->post_title;
    }

    public function GetPermaLink($id)
    {
        return get_permalink($id);
    }

    public function SavePostMeta($postid,$key,$value)
    {
        update_post_meta($postid,$key,$value);

    }


}


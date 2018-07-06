<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WpDatastore {
    public  function Get($key)
    {
        return get_option($key);
    }    

    public  function Set($key,$value)
    {
        return update_option($key,$value);
    }  
}
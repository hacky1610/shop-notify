<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AjaxAdapter {

    public function ThrowError($message = "")
    {
        echo "NOK " + $message;
    }

        
        public function OK()
        {
            echo "OK";
        }
  


}


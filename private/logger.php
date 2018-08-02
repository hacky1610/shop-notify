<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once dirname( __FILE__ ) . '/interfaces/ILogger.php';


class Logger implements ILogger{

    public function Info($message) {
        if (WP_DEBUG === true) {
            
            if (is_array($message) || is_object($message)) {
                error_log(print_r($message, true));
            } else {
                error_log($message);
            }
        }
    }

    public function Call($message) {
        if (WP_DEBUG === true) {
            
            if (is_array($message) || is_object($message)) {
                error_log("Call: " . print_r($message, true));
            } else {
                error_log("Call: " . $message);
            }
        }
    }
}


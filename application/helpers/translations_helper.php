<?php 

function get_lang_value ( $lang_key ) {
    
    $CI = &get_instance();
    $CI->load->model('Training_model');
    
    return $CI->Training_model->get_lang_value ( $lang_key );
}

<?php

class MY_Controller extends CI_Controller {

	public $css_files = array('style.css', 'jquery-ui-1.8.16.custom.css');

	public $js_files = array('script.js', 'qtip.js', 'jquery-ui-1.8.16.custom.min.js', 'underscore.js', 'backbone.js');

	public    function __construct()
    {
        parent::__construct();
    }

    public function set_js($file_paths)
    {
        if(is_array($file_paths)){
            foreach($file_paths as $file_path){
                array_push($this->js_files, $file_path);
            }
        }
        else{
        	array_push($this->js_files, $file_paths);
        }
    }

    public function set_css($file_paths)
    {
    	if(is_array($file_paths)){
            foreach($file_paths as $file_path){
                array_push($this->css_files, $file_path);
            }
        }
        else{
            array_push($this->css_files, $file_paths);
        }
    }

}
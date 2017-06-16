<?php

class breadcumbs {
    
    var $main;
    var $cumbs = array();
    
    function __construct(){
        $this->main = get_instance();
    }
    
    function add($url,$text){
        $newcumbs = array(
            'url' => $url,
            'text' => $text
        );
        $this->cumbs[] = $newcumbs;
    }
    
    function render(){
        $breadcumbs = '<ol class="breadcrumb">';
        $breadcumbs .= '<li><a href="'.SITEURL.'"><i class="fa fa-dashboard"></i> Dashboard</a></li>';
        if (isset($_GET['content']) && $_GET['content']!='home')
        {
            foreach($this->cumbs as $bread){
                if ($bread['url']=='current'){
                    $breadcumbs .= '<li class="active">'.$bread['text'].'</li>';
                } else {
                    $breadcumbs .= '<li><a href="'.SITEURL.$bread['url'].'">'.$bread['text'].'</a></li>';
                }
            }
        }
        $breadcumbs .= '</ol>';
        return $breadcumbs;
    }
}

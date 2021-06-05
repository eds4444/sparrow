<?php

add_action( 'wp_enqueue_scripts', 'style_theme' );
add_action( 'wp_footer', 'scripts_theme' );


function style_theme(){

    wp_enqueue_style( 'default', get_template_directory_uri() . '/assets/css/default.css' );
    wp_enqueue_style( 'layout', get_template_directory_uri() . '/assets/css/layout.css' );
    wp_enqueue_style( 'media-queries', get_template_directory_uri() . '/assets/css/media-queries.css' );
    wp_enqueue_style( 'fonts', get_template_directory_uri() . '/assets/css/fonts.css' );
}

function scripts_theme(){
    wp_enqueue_script( 'init', get_template_directory_uri() . '/assets/js/init.js');
    wp_enqueue_script( 'doubletaptogo', get_template_directory_uri() . '/assets/js/doubletaptogo.js');
    wp_enqueue_script( 'jquery.flexslider', get_template_directory_uri() . '/assets/js/jquery.flexslider.js');
    wp_enqueue_script( 'jquery-migrate-1.2.1.min.js', get_template_directory_uri() . '/assets/js/jquery-migrate-1.2.1.min.js');

}

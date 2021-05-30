<?php

add_action( 'wp_enqueue_scripts', 'style_theme' );

function style_theme(){

    wp_enqueue_style( 'default', get_template_directory_uri() . '/assets/css/default.css' );
    wp_enqueue_style( 'layout', get_template_directory_uri() . '/assets/css/layout.css' );
    wp_enqueue_style( 'default', get_template_directory_uri() . '/assets/css/media-queries.css' );
}
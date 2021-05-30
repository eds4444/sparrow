<?php

add_action( 'wp_enqueue_scripts', 'style_theme' );

function style_theme(){

    wp_enqueue_style( 'babushka', get_template_directory_uri() . '/assets/css/default.css' );
}
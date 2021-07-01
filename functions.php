<?php

add_action( 'wp_enqueue_scripts', 'theme_styles' );
add_action( 'wp_footer', 'theme_scripts' );
add_action( 'after_setup_theme', 'theme_register_nav_menu' );//создание меню
add_action( 'widgets_init', 'register_my_widgets' );//сайдбар

add_action('init', 'my_custom_init');
function my_custom_init(){
	register_post_type('portfolio', array(
		'labels'             => array(
			'name'               => 'Портфолио', // Основное название типа записи
			'singular_name'      => 'Портфолио', // отдельное название записи типа Book
			'add_new'            => 'Добавить работу',
			'add_new_item'       => 'Добавдение работы',
			'edit_item'          => 'Редактирование работы',
			'new_item'           => 'Новая работа',
			'view_item'          => 'Посмотреть работу',
			'search_items'       => 'Искать работу в портфолио',
			'not_found'          => 'Не найдено',
			'not_found_in_trash' => 'В корзине не найдено',
			'parent_item_colon'  => '',
			'menu_name'          => 'Портфолио'
		 ),

		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title','editor','author','thumbnail','excerpt','comments')
	) );
}

add_filter('the_content', 'test_content');

function test_content($content){

    $content.= 'Спасибо';

return $content;
    
}

function register_my_widgets(){//сайдбар
      register_sidebar( array(
		'name'          => 'Left Sidebar',
		'id'            => "left_sidebar",
		'description'   => 'Описание нашего сайдбара',
        'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => "</div>\n",
		'before_title'  => '<h5 class="widgettitle">',
		'after_title'   => "</h5>\n"			
	) );
    register_sidebar( array(//сайдба2 еще один
		'name'          => 'Top Sidebar',
		'id'            => "top_sidebar",
		'description'   => 'верхний сайдбар',
        'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => "</div>\n",
		'before_title'  => '<h5 class="widgettitle">',
		'after_title'   => "</h5>\n"			
	) );
}

function theme_register_nav_menu() {
	register_nav_menu( 'top', 'Меню в шапке' ); //регистрируем область меню
    register_nav_menu( 'footer', 'Меню в подвале' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails', array( 'post', 'portfolio') );
    add_theme_support( 'post-formats', array( 'video', 'aside', 'gallery', 'chat') );
    add_image_size( 'post_thumb', 1300, 500, true);
    add_filter('navigation_markup_template', 'my_navigation_template', 10, 2);
    function my_navigation_template( $template, $class){
        return '
        <nav class="navigation %1$s" role="navigetion">
        <div class="nav-links">%3$s</div>
        </nav>
        ';
    }     
    the_posts_pagination( array(
        'end_size' => 2,
    ) );
}


function theme_styles(){

    wp_enqueue_style( 'style', get_stylesheet_uri());
    wp_enqueue_style( 'default', get_template_directory_uri() . '/assets/css/default.css' );
    wp_enqueue_style( 'layout', get_template_directory_uri() . '/assets/css/layout.css' );
}

function theme_scripts(){
    wp_deregister_script('jquery');
    wp_register_script('jquery' , '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js');
    wp_enqueue_script('jquery');
    wp_enqueue_script('flexslider', get_template_directory_uri() . '/assets/js/jguery.flexslider.js', ['jquery'],
     null, true );
     wp_enqueue_script('doubletaptogo', get_template_directory_uri(). '/assets/js/doubletaptogo.js', ['jquery'],
     null, true );
    wp_enqueue_script('init', get_template_directory_uri(). '/assets/js/init.js', ['jquery'],null, true );
    wp_enqueue_script('modernizr', get_template_directory_uri(). '/assets/js/vodernizr.js', null, false );
}

add_shortcode( 'iframe', 'Gen_iframe' );

function Gen_iframe( $atts ) {
	$atts = shortcode_atts( array(
		'href'   => 'http://example.com',
		'height' => '550px',
		'width'  => '600px',     
	), $atts );

	return '<iframe src="'. $atts['href'] .'" width="'. $atts['width'] .'" height="'. $atts['height'] .'"> <p>Your Browser does not support Iframes.</p></iframe>';
}

// использование: 
// [iframe href="http://www.exmaple.com" height="480" width="640"]
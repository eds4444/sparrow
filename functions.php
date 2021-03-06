<?php

add_action( 'wp_enqueue_scripts', 'theme_styles' );
add_action( 'wp_footer', 'theme_scripts' );
add_action( 'after_setup_theme', 'theme_register_nav_menu' );//создание меню
add_action( 'widgets_init', 'register_my_widgets' );//сайдбар

add_action('wp_ajax_send_mail'     , 'send_mail');
add_action('wp_ajax_nopriv_send_mail', 'send_mail');

function send_mail() {
	$contactName = $_POST['contactName'];
	$contactEmail = $_POST['contactEmail'];
	$contactSubject = $_POST['contactSubject'];
	$contactMessage = $_POST['contactMessage'];

	// подразумевается что $to, $subject, $message уже определены...
	$to = get_option('admin_email');
	

// удалим фильтры, которые могут изменять заголовок $headers
remove_all_filters( 'wp_mail_from' );
remove_all_filters( 'wp_mail_from_name' );

$headers = array(
	'From: Me Myself <me@example.net>',
	'content-type: text/html',
	'Cc: John Q Codex <jqc@wordpress.org>',
	'Cc: iluvwp@wordpress.org', // тут можно использовать только простой email адрес
);

wp_mail( $to, $contactSubject, $contactMessage, $headers );
wp_die();
}

add_shortcode('posts', 'test_posts');
function test_posts( $atts){
	$atts = shortcode_atts( array(
		'cnt' => 5,
		'post_type' => 'post'), $atts);
    $args = array(
		'numberposts' => $atts['cnt'],
		'post_type' => $atts['post_type']
	);
	$str = '';

	$posts = get_posts( $args);

	foreach ($posts as $post) { setup_postdata($post);
		$title = the_title();
		$str .= "<div>$title</div>";
	}
	wp_reset_postdata();
	return $str;	
}

add_action( 'init', 'register_post_types' );
function register_post_types(){
	register_post_type( 'portfolio', [
		'label'  => null,
		'labels' => [
			'name'               => 'Портфолио', // основное название для типа записи
			'singular_name'      => 'Портфолио', // название для одной записи этого типа
			'add_new'            => 'Добавить работу', // для добавления новой записи
			'add_new_item'       => 'Добавление работы', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование работы', // для редактирования типа записи
			'new_item'           => 'Новоя работа', // текст новой записи
			'view_item'          => 'Смотреть работу', // для просмотра записи этого типа.
			'search_items'       => 'Искать работу в портфолио', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Портфолио', // название меню
		],
		'description'         => 'Это наши работы в партфолио',
		'public'              => true,
		'publicly_queryable'  => true, // зависит от public
		'exclude_from_search' => true, // зависит от public
		'show_ui'             => true, // зависит от public
		'show_in_nav_menus'   => true, // зависит от public
		'show_in_menu'        => true, // показывать ли в меню адмнки
		'show_in_admin_bar'   => true, // зависит от show_in_menu
		'show_in_rest'        => true, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => 4,
		'menu_icon'           => 'dashicons-format-gallery',
		//'capability_type'   => 'post',
		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => false,
		'supports'            => [ 'title', 'editor','author','thumbnail','excerpt' ], //'author','thumbnail','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => ['skills'],
		'has_archive'         => false,
		'rewrite'             => true,
		'query_var'           => false,
	] );
}

add_action( 'init', 'create_taxonomy' );
function create_taxonomy(){
	register_taxonomy( 'skills', [ 'portfolio', 'post' ], [
		'label'                 => '', // определяется параметром $labels->name
		'labels'                => [
			'name'              => 'Навыки',
			'singular_name'     => 'Навык',
			'search_items'      => 'Найти навык',
			'all_items'         => 'Все навыки',
			'view_item '        => 'Смотреть навыки',
			'parent_item'       => 'Родительский навык',
			'parent_item_colon' => 'Родительский навык:',
			'edit_item'         => 'Изменить навык',
			'update_item'       => 'Обновить навык',
			'add_new_item'      => 'Добавить новый навык',
			'new_item_name'     => 'Новое имя навыка',
			'menu_name'         => 'Навыки',
		],
		'description'           => 'Навыки которые использовались в работе', // описание таксономии
		'public'                => true,
		'publicly_queryable'    => true, // равен аргументу public
		//'show_in_nav_menus'     => true, // равен аргументу public
		//'show_ui'               => true, // равен аргументу public
		//'show_in_menu'          => true, // равен аргументу show_ui
	    //'show_tagcloud'         => true, // равен аргументу show_ui
		//'show_in_quick_edit'    => true, // равен аргументу show_ui
		'hierarchical'          => false,
		'rewrite'               => true,
	] );
}
add_action( 'init', 'skills_for_portfolio' );
function skills_for_portfolio(){
	register_taxonomy_for_object_type( 'skills', 'portfolio');
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
        'before_widget' => '<div class="widget %2$s ">',
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

    wp_enqueue_style( 'styles', get_stylesheet_uri());
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
    wp_enqueue_script('init', get_template_directory_uri() . '/assets/js/init.js', ['jquery'],null, true );
    wp_enqueue_script('modernizr', get_template_directory_uri() . '/assets/js/modernizr.js', null, null, false );
	wp_enqueue_script('main', get_template_directory_uri() . '/assets/js/main.js', ['jquery'], null, true );
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
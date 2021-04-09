<?php

/*
Plugin Name: Gerenciador de conteúdo gartrell
Description: Este plugin gerencia o conteúdo do site gartrell
Author: Lucas Salvino
Plugin URI: http://lucassalvino.com.br/
Version: 1.0
*/
define('APP_PLUGIN_DIR', plugin_dir_path(__FILE__));
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
		'page_title' 	=> 'Opções',
		'menu_title'	=> 'Opções',
		'menu_slug' 	=> 'opcoes',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
    ));
    acf_add_options_sub_page(array(
		'page_title' 	=> 'Redes sociais',
		'menu_title'	=> 'Redes sociais',
		'parent_slug'	=> 'opcoes',
    ));
    acf_add_options_sub_page(array(
		'page_title' 	=> 'SEO',
		'menu_title'	=> 'SEO',
		'parent_slug'	=> 'opcoes',
	));
}

add_theme_support( 'post-thumbnails' );

add_action( 'init', 'register_acontece_post' );

function register_acontece_post(){
	$labels = array(
		'name' => __( 'Acontece', 'Posts Acontece' ),
		'singular_name' => __( 'Acontece', 'Posts Acontece' )
	);
	$args = array(
		'labels' => $labels,
		'description' => 'Posts Acontece',
		'public' => true,
		'menu_position' => 0,
		'supports' => array(
			'editor' , 'title', 'thumbnail', 'excerpt', 'custom-fields', 'author'
        ),
        'taxonomies'=>array('category'),
        'rewrite'=>true
	);
	register_post_type( 'post_acontece', $args );
}



include APP_PLUGIN_DIR . 'includes/api-requests.php';
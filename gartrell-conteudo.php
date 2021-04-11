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
		'menu_position' => 4,
		'supports' => array(
			'editor' , 'title', 'thumbnail', 'excerpt', 'custom-fields', 'author'
        ),
        'taxonomies'=>array('category'),
        'rewrite'=>true
	);
	register_post_type( 'post_acontece', $args );
}

add_action( 'init', 'register_noticias_post' );

function register_noticias_post(){
	$labels = array(
		'name' => __( 'Notícias', 'Posts Notícias' ),
		'singular_name' => __( 'Notícia', 'Posts notícia' )
	);
	$args = array(
		'labels' => $labels,
		'description' => 'Posts notícia',
		'public' => true,
		'menu_position' => 5,
		'supports' => array(
			'editor' , 'title', 'thumbnail', 'excerpt', 'custom-fields', 'author'
        ),
        'taxonomies'=>array('category'),
        'rewrite'=>true
	);
	register_post_type( 'post_noticia', $args );
}

add_action( 'init', 'register_reflexoes_post' );

function register_reflexoes_post(){
	$labels = array(
		'name' => __( 'Reflexões', 'Posts Reflexões' ),
		'singular_name' => __( 'Reflexão', 'Posts Reflexão' )
	);
	$args = array(
		'labels' => $labels,
		'description' => 'Posts Reflexões',
		'public' => true,
		'menu_position' => 6,
		'supports' => array(
			'editor' , 'title', 'thumbnail', 'excerpt', 'custom-fields', 'author'
        ),
        'taxonomies'=>array('category'),
        'rewrite'=>true
	);
	register_post_type( 'post_reflexoes', $args );
}

add_action( 'init', 'register_servicos_post' );

function register_servicos_post(){
	$labels = array(
		'name' => __( 'Serviços', 'Posts Serviços' ),
		'singular_name' => __( 'Serviço', 'Post Serviço' )
	);
	$args = array(
		'labels' => $labels,
		'description' => 'Posts Serviços',
		'public' => true,
		'menu_position' => 7,
		'supports' => array(
			'editor' , 'title', 'thumbnail', 'custom-fields'
        ),
        'rewrite'=>true
	);
	register_post_type( 'post_servicos', $args );
}


add_action( 'init', 'register_equipe_post' );

function register_equipe_post(){
	$labels = array(
		'name' => __( 'Equipe', 'Posts Equipe' ),
		'singular_name' => __( 'Membro da equipe', 'Membro equipe' ),
		'add_new_item' => __('Adicionar novo membro', 'txtdomain'),
		'new_item' => __('Novo membro', 'txtdomain'),
	);
	$args = array(
		'labels' => $labels,
		'description' => 'Post Membro',
		'public' => true,
		'menu_position' => 7,
		'supports' => array(
			'excerpt' , 'title', 'thumbnail', 'custom-fields'
        ),
        'rewrite'=>true
	);
	register_post_type( 'post_equipe', $args );
}
include APP_PLUGIN_DIR . 'includes/api-requests.php';
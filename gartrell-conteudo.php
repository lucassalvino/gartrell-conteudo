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
			'editor' , 'title', 'custom-fields'
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

add_action( 'init', 'register_galeria_post' );

function register_galeria_post(){
	$labels = array(
		'name' => __( 'Galeria', 'Posts Galeria' ),
		'singular_name' => __( 'Galeria', 'Galeria' ),
		'add_new_item' => __('Adicionar nova galeria', 'txtdomain'),
		'new_item' => __('Nova Galeria', 'txtdomain'),
	);
	$args = array(
		'labels' => $labels,
		'description' => 'Post de Galeria',
		'public' => true,
		'menu_position' => 7,
		'supports' => array(
			'title', 'thumbnail', 'custom-fields'
        ),
        'rewrite'=>true
	);
	register_post_type( 'post_galeria', $args );
}


add_action( 'init', 'register_saudeintegral_post' );

function register_saudeintegral_post(){
	$labels = array(
		'name' => __( 'Posts Saúde Integral', 'Posts Saúde Integral' ),
		'singular_name' => __( 'Posts Saúde Integral', 'Post Saúde Integral' ),
		'add_new_item' => __('Adicionar Posts Saúde Integral', 'txtdomain'),
		'new_item' => __('Novo Post Saúde Integral', 'txtdomain'),
	);
	$args = array(
		'labels' => $labels,
		'description' => 'Post Saúde Integral',
		'public' => true,
		'menu_position' => 8,
		'supports' => array(
			'editor', 'title', 'thumbnail', 'custom-fields', 'excerpt', 'author'
        ),
        'rewrite'=>true
	);
	register_post_type( 'post_saudeintegral', $args );
}

add_action( 'init', 'register_sociedade_post' );

function register_sociedade_post(){
	$labels = array(
		'name' => __( 'Posts Sociedade', 'Posts Sociedade' ),
		'singular_name' => __( 'Posts Sociedade', 'Post Sociedade' ),
		'add_new_item' => __('Adicionar Posts Sociedade', 'txtdomain'),
		'new_item' => __('Novo Post Sociedade', 'txtdomain'),
	);
	$args = array(
		'labels' => $labels,
		'description' => 'Post Sociedade',
		'public' => true,
		'menu_position' => 11,
		'supports' => array(
			'editor', 'title', 'thumbnail', 'custom-fields', 'excerpt', 'author'
        ),
        'rewrite'=>true
	);
	register_post_type( 'post_sociedade', $args );
}

add_action( 'init', 'register_desenpessoal_post' );

function register_desenpessoal_post(){
	$labels = array(
		'name' => __( 'Posts Desenvolvimento Social', 'Posts Desenvolvimento Social' ),
		'singular_name' => __( 'Posts Desenvolvimento Social', 'Post Desenvolvimento Social' ),
		'add_new_item' => __('Adicionar Posts Desenvolvimento Social', 'txtdomain'),
		'new_item' => __('Novo Post Desenvolvimento Social', 'txtdomain'),
	);
	$args = array(
		'labels' => $labels,
		'description' => 'Post Desenvolvimento Social',
		'public' => true,
		'menu_position' => 10,
		'supports' => array(
			'editor', 'title', 'thumbnail', 'custom-fields', 'excerpt', 'author'
        ),
        'rewrite'=>true
	);
	register_post_type( 'post_desenpessoal', $args );
}
include APP_PLUGIN_DIR . 'includes/api-requests.php';
<?php

add_action( 'rest_api_init', 'definicao_api');
function CriaRota($nome, $callback, $method = 'GET'){
    register_rest_route(
		'v1',
		'/'.$nome,
		array(
			'methods' => $method,
			'callback' => $callback
		)
	);
}
function definicao_api(){
    CriaRota('acontece', 'GetAllAcontece');
}

function viewPadraoPost(){
    return Array(
        'id' => get_the_ID(),
        'titulo' => get_the_title(),
        'data' => get_the_date(),
        'resumo' => get_the_excerpt(),
        'thumbnail' => get_the_post_thumbnail_url(),
        'autor' => get_the_author(),
        'categorias' => get_the_category(),
        'conteudo' => get_the_content(null, true)
    );
}

function GetAllAcontece($params){
    $retorno = Array();
    $paginaAtual = $params->get_param( 'pagina' );
    if(!isset($paginaAtual) || $paginaAtual == ""){
		$paginaAtual = 1;
	}else{
		$paginaAtual = (int)$paginaAtual;
	}
    $args = array(
		'post_type' => 'post_acontece',
        'post_status'=>'publish',
        "paged" => 1,
        'offset'=> ((10*$paginaAtual) - 10),
		'order' => 'DESC',
        "posts_per_page" => 10,
	);
    $query = new WP_Query($args);
    if ($query->have_posts()){
        while($query->have_posts()){
			$query->the_post();
			array_push($retorno, array_merge(
                viewPadraoPost(),
                array(
                    'palestrantes' => get_field('palestrantes')
                ))
            );
		}
    }
    $response = new WP_REST_Response($retorno);
	$response->set_status(201);
    return $response;
}
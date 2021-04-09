<?php

add_action( 'rest_api_init', 'definicao_api');
function CriaRota($nome, $callback, $id = false, $method = 'GET'){
    $query = "";
    if($id){
        $query = "/(?P<id>\d+)";
    }
    register_rest_route(
		'v1',
		'/'.$nome.$query,
		array(
			'methods' => $method,
			'callback' => $callback
		)
	);
}
function definicao_api(){
    CriaRota('acontece', 'GetAllAcontece');
    CriaRota('acontece', 'GetAcontece', true);
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
function ConstruiRetorno($dados, $paginaAtual, &$query, $qtdpPagina = 10){
    $total = (int) $query->found_posts;
    return array(
        "dados" => $dados,
        "paginaAtual" => $paginaAtual,
        "total" => $total,
        "quantidadePorPagina" => $qtdpPagina,
        "quantidadeDePaginas" => (int) (($total / $qtdpPagina) +  (($total % $qtdpPagina) == 0 ? 0:1))
    );
}

function GetAllArgumentosFiltro($tipopost, $paginaAtual,$quantidadePorPagina){
    return array(
		'post_type' => $tipopost,
        'post_status'=>'publish',
        "paged" => 1,
        'offset'=> (($quantidadePorPagina*$paginaAtual) - $quantidadePorPagina),
		'order' => 'DESC',
        "posts_per_page" => $quantidadePorPagina,
	);
}

function GetParametrosPadrao($request, &$paginaAtual, &$qtdPagina){
    $paginaAtual = $request->get_param( 'pagina' );
    $qtdPagina = $request->get_param( 'qtdPagina' );
    $paginaAtual = (!isset($paginaAtual) || $paginaAtual == "")?1:(int)$paginaAtual;
    $qtdPagina = (!isset($qtdPagina) || $qtdPagina == "")?4:(int)$qtdPagina;
}

function ObtemRetornoPadraoSucesso($dados){
    $response = new WP_REST_Response($dados);
    $response->set_status(200);
    return $response;
}

function ObtemViewPost(&$query, $funcaoCustom){
    $retorno = Array();
    if ($query->have_posts()){
        while($query->have_posts()){
			$query->the_post();
			array_push($retorno, array_merge(
                viewPadraoPost(),
                $funcaoCustom()
                )
            );
		}
    }
    return $retorno;
}

function CustomizaAcontece(){
    return array(
        'palestrantes' => get_field('palestrantes')
    );
}

function GetPostUnico($params, $funcaoCustomizacao){
    $args = array(
        'p' => $params->get_param('id'),
        'post_type' => 'any'
    );
    $query = new WP_Query($args);
    $retorno = ObtemViewPost($query, $funcaoCustomizacao);
    if(is_array($retorno) && count($retorno) > 0){
        $retorno = $retorno[0];
    }else{
        $retorno = Array();
    }
    return ObtemRetornoPadraoSucesso($retorno);
}
function GetAllPosts($params, $posttype, $funcaoCustomizacao){
    $paginaAtual = null;
    $quantidadePagina = null;
    GetParametrosPadrao($params, $paginaAtual, $quantidadePagina);
    $args = GetAllArgumentosFiltro($posttype, $paginaAtual, $quantidadePagina);
    $query = new WP_Query($args);
    $retorno = ObtemViewPost($query, $funcaoCustomizacao);
    return ObtemRetornoPadraoSucesso(ConstruiRetorno($retorno, $paginaAtual, $query, $quantidadePagina));
}

function GetAcontece($params){
    return GetPostUnico($params, 'CustomizaAcontece');
}

function GetAllAcontece($params){
    return GetAllPosts($params, 'post_acontece', 'CustomizaAcontece');
}
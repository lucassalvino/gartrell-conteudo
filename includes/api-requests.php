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
    CriaRota('noticia', 'GetAllNoticia');
    CriaRota('noticia', 'GetNoticia', true);
    CriaRota('reflexao', 'GetAllReflexao');
    CriaRota('reflexao', 'GetReflexao', true);
    CriaRota('geral', 'getgeralpost', true);
    CriaRota('resumofiquepordentro', 'GetResumoFiquePorDentro');
    CriaRota('servico', 'GetAllServicos');
    CriaRota('quemsomos', 'GetQuemSomos');
    CriaRota('equipe', 'GetEquipe');
    CriaRota('galeria', 'GetAllGaleria');
    CriaRota('galeria', 'GetGaleria', true);
    CriaRota('identidadenominal', 'GetIdentidadeNominal');
    CriaRota('postssaudeintegral', 'GetAllSaudeintegral');
    CriaRota('postssaudeintegral', 'GetSaudeintegral', true);
    CriaRota('postssociedade', 'GetAllSociedade');
    CriaRota('postssociedade', 'GetSociedade', true);
    CriaRota('postsdesenpessoal', 'GetAllDesenvolPessoal');
    CriaRota('postsdesenpessoal', 'GetDesenvolPessoal', true);
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
        'conteudo' =>  apply_filters('the_content', get_the_content())
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

function GetAllArgumentosFiltro($tipopost, $paginaAtual,$quantidadePorPagina, $id_excluir = Array()){
    return array(
		'post_type' => $tipopost,
        'post_status'=>'publish',
        "paged" => 1,
        'offset'=> (($quantidadePorPagina*$paginaAtual) - $quantidadePorPagina),
		'order' => 'DESC',
        "posts_per_page" => $quantidadePorPagina,
        'post__not_in' => $id_excluir
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

function GetPostUnico($params, $posttype, $funcaoCustomizacao){
    $args = array(
        'p' => $params->get_param('id'),
        'post_type' => 'any'
    );
    $query = new WP_Query($args);
    $retorno = ObtemViewPost($query, $funcaoCustomizacao);
    $retorno = (is_array($retorno) && count($retorno) > 0)?$retorno[0]:Array();
    $queryRelacionados = new WP_Query(GetAllArgumentosFiltro($posttype, 1, 4, array_key_exists('id', $retorno)?Array($retorno['id']):Array()));
    $relacionados = ObtemViewPost($queryRelacionados, 'SemCustomizacao');
    $retorno['relacionados'] = $relacionados;
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

function CustomizaAcontece(){
    return array(
        'palestrantes' => get_field('palestrantes'),
        'data_evento' => get_field('data_evento'),
        'banner' => get_field('banner'),
        'mostrar_formulario' => get_field('mostrar_formulario')
    );
}

function SemCustomizacao(){
    return Array();
}

function GetAllSaudeintegral($params){
    return GetAllPosts($params, 'post_saudeintegral', 'SemCustomizacao');
}

function GetSaudeintegral($params){
    return GetPostUnico($params, 'post_saudeintegral', 'SemCustomizacao');
}

function GetAllSociedade($params){
    return GetAllPosts($params, 'post_sociedade', 'SemCustomizacao');
}

function GetSociedade($params){
    return GetPostUnico($params, 'post_sociedade', 'SemCustomizacao');
}

function GetAllDesenvolPessoal($params){
    return GetAllPosts($params, 'post_desenpessoal', 'SemCustomizacao');
}

function GetDesenvolPessoal($params){
    return GetPostUnico($params, 'post_desenpessoal', 'SemCustomizacao');
}

function GetAcontece($params){
    return GetPostUnico($params, 'post_acontece', 'CustomizaAcontece');
}

function GetAllAcontece($params){
    return GetAllPosts($params, 'post_acontece', 'CustomizaAcontece');
}

function GetNoticia($params){
    return GetPostUnico($params, 'post_noticia', 'SemCustomizacao');
}

function GetAllNoticia($params){
    return GetAllPosts($params, 'post_noticia', 'SemCustomizacao');
}

function GetReflexao($params){
    return GetPostUnico($params, 'post_reflexoes', 'SemCustomizacao');
}

function GetAllReflexao($params){
    return GetAllPosts($params, 'post_reflexoes', 'SemCustomizacao');
}
function getgeralpost($params){
    return GetPostUnico($params, 'post_noticia', 'SemCustomizacao');
}

function CustomCustomQuery($args, $funcaoRetorno, &$copiaquery = null){
    $retorno = Array();
    $query = new WP_Query($args);
    if ($query->have_posts()){
        while($query->have_posts()){
			$query->the_post();
            array_push($retorno, $funcaoRetorno());
        }
    }
    if(!is_null($copiaquery)){
        $copiaquery = $query;
    }
    return $retorno;
}

function GetQuemSomos($params){
    $retorno = CustomCustomQuery(
        array(
            'name' => 'quem-somos',
            'post_type' => 'any',
            'post_status'=>'publish',
            'posts_per_page' => 1
        ),
        function(){
            return array(
                'id' => get_the_ID(),
                'titulo' => get_the_title(),
                'resumo' => get_the_excerpt(),
                'thumbnail' => get_the_post_thumbnail_url(),
                'conteudo' =>  apply_filters('the_content', get_the_content()),
                'imagem_fundo' => get_field('imagem_fundo'),
                'texto_visao' => get_field('texto_visao'),
                'texto_missao' => get_field('texto_missao'),
                'texto_valores' => get_field('texto_valores')
            );
        }
    );
    $retorno = (count($retorno) > 0)? $retorno[0]:array();
    return ObtemRetornoPadraoSucesso($retorno);
}

function GetResumoFiquePorDentro($params){
    $retorno = CustomCustomQuery(array(
            'post_type' => 'any',
            'post_status'=>'publish',
            'order' => 'DESC',
            'post__in' => [74,80,82]
        ), function(){
        return array(
            'id' => get_the_ID(),
            'titulo' => get_the_title(),
            'resumo' => get_the_excerpt()
        );
    });
    return ObtemRetornoPadraoSucesso($retorno);
}

function GetAllServicos($params){
    $retorno = CustomCustomQuery(array(
        'post_type' => 'post_servicos',
        'post_status'=>'publish'
    ),function(){
        return array(
            'id' => get_the_ID(),
            'nome' => get_the_title(),
            //'icone' => get_the_post_thumbnail_url(),
            'descricao' => apply_filters('the_content', get_the_content())
        );
    });
    return ObtemRetornoPadraoSucesso($retorno);
}

function GetEquipe($params){
    $retorno = CustomCustomQuery(array(
        'post_type' => 'post_equipe',
        'post_status'=>'publish'
    ), function(){
        return array(
            'id' => get_the_ID(),
            'titulo' => get_the_title(),
            'cargo' => get_field('cargo'),
            'sexo' => get_field('sexo'),
            'sobre' => get_the_excerpt(),
            'fotografia' => get_the_post_thumbnail_url(),
            'equipe' => get_field('equipe'),
            'realiza_consulta' => get_field('realiza_consulta'),
            'horario_de_atendimento' => get_field('horario_de_atendimento'),
        );
    });
    $realizaConsulta = $params->get_param( 'realiza_consulta' );
    $realizaConsulta = (!isset($realizaConsulta) || $realizaConsulta == "")? 'all' : ((int)$realizaConsulta) == 1;

    $equipe = Array();
    $parceiros = Array();
    foreach($retorno as $item){
        $arrayadd =&${(strcasecmp('equipe', $item['equipe']) == 0)? 'equipe' : 'parceiros'};
        if(strcasecmp($realizaConsulta, 'all') == 0){
            array_push( $arrayadd , $item);
        }else{
            if($realizaConsulta && $item['realiza_consulta']){
                array_push( $arrayadd , $item);
            }else{
                if(!$realizaConsulta && !$item['realiza_consulta'])
                    array_push( $arrayadd , $item);
            }
        }
    }
    return ObtemRetornoPadraoSucesso(array(
        'equipe' => $equipe,
        'parceiros' => $parceiros
    ));
}
function GetViewGaleria(){
    return array(
        'id' => get_the_ID(),
        'titulo_evento' => get_the_title(),
        'thumbnail' => get_the_post_thumbnail_url(),
        'local' => get_field('local'),
        'imagens' => get_field('imagens'),
        'data' => get_the_date(),
    );
}
function GetAllGaleria($params){
    $query = true;
    $paginaAtual = null;
    $quantidadePagina = null;
    GetParametrosPadrao($params, $paginaAtual, $quantidadePagina);
    $args = GetAllArgumentosFiltro('post_galeria', $paginaAtual, $quantidadePagina);
    $retorno = CustomCustomQuery($args, function(){
        return GetViewGaleria();
    }, $query);
    $retorno = ConstruiRetorno($retorno, $paginaAtual, $query, $quantidadePagina);
    return ObtemRetornoPadraoSucesso($retorno);
}

function GetGaleria($params){
    $retorno = CustomCustomQuery(array(
        'p' => $params->get_param('id'),
        'post_type' => 'any'
    ), function(){
        return GetViewGaleria();
    });
    return ObtemRetornoPadraoSucesso(
        count($retorno) > 0 ? $retorno[0]:array()
    );
}

function GetIdentidadeNominal($params){
    $retorno = CustomCustomQuery(array(
        'name' => 'identidade-nominal',
        'post_type' => 'any',
        'post_status'=>'publish',
        'posts_per_page' => 1
    ),
    function(){
        return array(
            'id' => get_the_ID(),
            'titulo' => get_the_title(),
            'resumo' => get_the_excerpt(),
            'thumbnail' => get_the_post_thumbnail_url(),
            'conteudo' =>  apply_filters('the_content', get_the_content()),
        );
    });
    return ObtemRetornoPadraoSucesso(
        count($retorno) > 0?$retorno[0]:array()
    );
}
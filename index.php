<?php
include('./configs/config.php');

/*
 *
 * LISTAGEM DE URLs E CLIQUES
 * Lista das urls encurtadas, contagem de cliques em cada uma e total geral
 * 
 */
$mini = $db->Execute("SELECT urls.url,urls.curta,urls.id,count(urls_views.id) AS total_views FROM urls_views RIGHT JOIN urls ON urls_views.urls_id = urls.id GROUP BY urls.id ORDER BY urls.data DESC");
$totalGeral = 0;
while (!$mini->EOF) {
	$longa = $mini->fields['url'];
	$minicurta = $mini->fields['curta'];
	$views = $mini->fields['total_views'];
	$listurls[] = array('longa' => $longa, 'url' => $link.$minicurta, 'views' => $views);
	$lista_curtas[] = $minicurta;
	$totalGeral = $totalGeral + $views;
	$mini->MoveNext();
}
$smarty->assign('listurls',$listurls);

// Conta as URLs encurtadas
$totalCurtas = count($listurls);
if ( $totalCurtas == 0 ) { $lista_curtas = ''; }
$smarty->assign('total_urls',$totalCurtas);
$smarty->assign('total_cliques',$totalGeral);
// fim LISTAGEM DE URLs E CLIQUES


/*
 * REDIRECIONADOR
 * Pega a URL encurtada, contabiliza o clique, origem e redireciona para a URL longa original
 * 
 */
if ( isset($_GET['page']) ) { $url = $_GET['page']; } else { $url = ''; }

if (in_array($url,$lista_curtas)) {
	$rs2 = $db->Execute('SELECT * FROM urls WHERE curta = ?', array($url));
	while (!$rs2->EOF) {
		$id = $rs2->fields['id'];
		$longa = $rs2->fields['url'];
		$rs2->MoveNext();
	}
	
	$sql2 = "INSERT INTO urls_views (urls_id,origem) VALUES ('".$id."','".$_SERVER['HTTP_REFERER']."')";
	$db->Execute($sql2);
	header('Location:'.$longa);
}
// fim REDIRECIONADOR


/*
 * ENCURTADOR
 * Se existir ?url=VALOR, pega esse valor, encurta e salva no banco
 * 
 */
if (isset($_GET['url'])) {
	$long_url = $_GET['url']; // Pega a URL longa
	$short_url = substr(md5(time()),0,5); // Encurta

	// Salva a URL curta
	$sql = "INSERT INTO urls (url,data,curta) VALUES ('".$long_url."','".date("Y-m-d H:i:s")."','".$short_url."')";
	$db->Execute($sql);

	// Salva a página de origem
	$sql2 = "INSERT INTO urls_views (urls_id,origem) VALUES ('".$lastid."','".$_SERVER['HTTP_REFERER']."')";
	$db->Execute($sql2);

	// Diferença entre caracteres totais e caracteres encurtados
	$tamanho_long_url = strlen($long_url);
	$tamanho_short_url = strlen($short_url) + 17;
	$reduction = ($tamanho_long_url - $tamanho_short_url);
	$stats = 'Reduzimos '.$reduction.' caracteres (de '.$tamanho_long_url.' para '.$tamanho_short_url.')';

	$smarty->assign('long_url',$long_url);
	$smarty->assign('short_url',$short_url);
	$smarty->assign('stats',$stats);

	$smarty->assign('result',1); // Seta que houve uma ação.
}
// fim ENCURTADOR


/*
 * 
 * Exibe a tela
 * 
 */
$smarty->display('index.html');
?>
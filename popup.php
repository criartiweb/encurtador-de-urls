<?php
if ( $_GET['url'] <> '' ) {
	$geturl = $_GET['url'];
	$geturl = trim($geturl);
	
	/*
	 * 
	 * Restrições
	 * Impede que o usuário encurte as URLs abaixo
	 * 
	 */
	if ( 
		$geturl == 'http://URL_ENCURTADOR' || 
		$geturl == 'http://URL_ENCURTADOR/' ||
		$geturl == 'http://localhost/' || 
		$geturl == 'http://localhost'
	 ) {
		$pageNotShortened = 1;
	 } else {
	 	$pageNotShortened = 0;
		
	 	include('configs/config.php');
		$short_url = substr(md5(time()),0,5);
		
		$sql = "INSERT INTO urls (url,data,curta) VALUES ('".$geturl."','".date("Y-m-d H:i:s")."','".$short_url."')";
		$db->Execute($sql);
		
		$tamanho_long_url = strlen($geturl);
		$tamanho_short_url = strlen($short_url) + 17;
		
		$reduction = ($tamanho_long_url - $tamanho_short_url);
		$stats = 'Reduzimos '.$reduction.' caracteres (de '.$tamanho_long_url.' para '.$tamanho_short_url.')';
	 }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>EAC - Encurtador de URLs</title>
    <link rel="stylesheet" charset="iso-8859-1" href="css/main.css" />
    <script type="text/javascript" src="libs/jquery/jquery.js"></script>
    <link rel="shortcut icon" href="img/" />
</head>
<body onload="$('#shorturl').select()" onclick="$('#shorturl').select()">
    <center>
		Encurtador de URLs
		
		<br /><br />
		
		<? if ($pageNotShortened == 0) { ?>
			<b>Aqui est&aacute; sua URL encurtada</b><br />
			<input id="shorturl" type="text" style="text-align: center;padding: 5px;background-color: #EFEFEF;border: solid 1px #CCC;width: 300px;" onfocus="this.select()" onclick="this.select()"  value="<?= $link.$short_url; ?>" /><br />
			<span style="font-size: 12px;color: #999;">Aperte Ctrl + C para copiar a URL</span>
			
			<br /><br />
			
			<span style="font-size: 12px;color: #999;">
				URL original<br /><?= $_GET['url']; ?><br />
				<?= $stats; ?>			
			</span>
		<? } elseif ($pageNotShortened == 1) { ?>
			
			A pr&oacute;pria p&aacute;gina do encurtador n&atilde;o pode ser encurtada...<br />
			Tente encurtar outro link.
			
		<? } ?>
		
		<br /><br />
		
		<a href="javascript:window.close()" style="color: #09C;font-size: 13px;">Fechar</a>
	</center>
</body>
</html>
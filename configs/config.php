<?
session_start();

error_reporting(~E_ALL);
//ini_set('display_errors', -1);

date_default_timezone_set('America/Sao_Paulo');

ob_start();

require_once('./libs/smarty/Smarty.class.php');

$smarty = new Smarty();

$smarty->template_dir	= './templates/';
$smarty->compile_dir	= './templates/templates_c/';
$smarty->config_dir		= './configs/';
$smarty->cache_dir		= './cache/';

include('./libs/adodb/adodb.inc.php');
$db = ADONewConnection("mysql");
$db->debug = false;
$ADODB_CACHE_DIR = './cache';
$db->cacheSecs = 3600 * 24; // cache 24 hours

if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$db->Connect("localhost","root","","urls_curtas");
	$link = 'http://localhost/';
} else {
	$db->Connect("localhost","usuario","senha","urls_curtas");
	$link = 'http://URL_ENCURTADOR/';
}

$smarty->assign('link',$link);
$smarty->assign('linkcss',$link.'css/');
$smarty->assign('linkimg',$link.'img/');
$smarty->assign('linklibs',$link.'libs/');
$smarty->assign('linkjs',$link.'js/');
?>

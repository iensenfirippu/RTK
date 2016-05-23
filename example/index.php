<?php
define("STARTTIME", microtime(true));
session_start();
define("RTK-TEST", true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

// load all necessary config and class files, etc.
include_once("class".DIRECTORY_SEPARATOR."RTK".DIRECTORY_SEPARATOR."rtk.php");

$showpage = RTK_EMPTYSTRING; if (RTK::SetAndNotNull($_GET, 'page')) { $showpage = $_GET['page']; }
$showstyle = RTK_EMPTYSTRING; if (RTK::SetAndNotNull($_GET, 'style')) { $showstyle = $_GET['style']; }

$pages = $styles = array();
foreach (glob('example'.DIRECTORY_SEPARATOR.'*.php') as $file) { $pages[] = pathinfo($file)['filename']; }
foreach (glob(RTK_DIRECTORY.'style'.DIRECTORY_SEPARATOR.'*') as $dir) { $styles[] = RTK::RemovePrefix(pathinfo($dir)['filename'], 'rtk-'); }

if (!in_array($showpage, $pages)) { $showpage = $pages[0]; }
if (!in_array($showstyle, $styles)) { $showstyle = $styles[0]; }

// create the requested page
$RTK = new HtmlDocument("RTK example test site");
$RTK->ClearStylesheets();

$faviconpath = 'image'.DIRECTORY_SEPARATOR.'favicon.png';
if (file_exists($faviconpath)) { $RTK->SetFavicon($faviconpath); }

$RTK->AddStylesheet(RTK_DIRECTORY.'style/rtk-'.$showstyle.'.css');
$RTK->AddStylesheet('style'.DIRECTORY_SEPARATOR.'style.css');

$topbar = new RTK_Box('topbar');
$pagestyleform = new RTK_Form('pagestyleform', 'index.php', 'GET', false);
$pagestyleform->AddDropDown('page', 'Page:', $pages, $showpage);
$pagestyleform->AddDropDown('style', 'Style:', $styles, $showstyle);
$topbar->AddChild($pagestyleform);
$button = new RTK_Button();
$button->AddAttributes(array('onclick' => "document.getElementById('pagestyleform').submit()"));
$topbar->AddChild($button);
$RTK->AddElement($topbar, 'BODY', 'topbar');

$wrapper = new RTK_Box('wrapper');
$RTK->AddElement($wrapper, 'BODY', 'wrapper');

$main = new RTK_Box('main');
$RTK->AddElement($main, 'wrapper', 'main');

include_once('example/'.$showpage.'.php');

echo $RTK;

//Database::Disconnect();
//echo "\n".(microtime(true) - STARTTIME);*/
?>
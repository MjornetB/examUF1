<?php

require_once '../model/pdo-articles.php';
require_once '../controller/session.php';

if (isset($_GET['postsPerPage'])) {
    $postsPerPage = $_GET['postsPerPage'];
    setcookie('postsPerPage', $postsPerPage); 
  } elseif (isset($_COOKIE['postsPerPage'])) {
    $postsPerPage = $_COOKIE['postsPerPage']; 
  } else {
    $postsPerPage = 10; 
  } //ex 7.1

// Ex 9 per canviar el temps de sessio anem al php.ini i canviem el session.gc_maxlifetime=1440 default, per session.gc_maxlifetime=900, <?php echo ini_get("session.gc_maxlifetime") <- per comprovar-ho

$orderBy = 'date-desc';

$searchTerm = "";
if (isset($_GET['search'])) $searchTerm = $_GET['search'];

session_start();
$userId = getSessionUserId();

$nArticles = getCountOfPosts($userId, $searchTerm); 
$nPages = ceil($nArticles / $postsPerPage); 

if (isset($_GET['page'])) {
    $currentPage = $_GET['page'];
} else {
    $currentPage = 1;
}

if ($nArticles > 0 && ($currentPage > $nPages || $currentPage < 1)) {
    header("Location: index.php");
}

$ndxArticle = $postsPerPage * ($currentPage - 1);

$articles = getPosts($userId, $ndxArticle, $postsPerPage, $orderBy, $searchTerm); 

if ($currentPage <= 3) $backScope = $currentPage - 1;
else $backScope = 3;
if ($currentPage + 3 > $nPages) $frontScope = $nPages - $currentPage;
else $frontScope = 3;


$firstPage = $currentPage == 1;
$lastPage = $currentPage == $nPages;

$firstPageClass = $firstPage ? 'disabled' : '';
$lastPageClass = $lastPage ? 'disabled' : '';

$searchQuery = !empty($searchTerm) ? "?search=$searchTerm&" : "?";
$nextPageLink = $lastPage ? "#" : $searchQuery . "page=" . ($currentPage + 1);
$previousPageLink = $firstPage ? "#" : $searchQuery . "page=" . ($currentPage - 1);
$firstPageLink = $firstPage ? "#" : $searchQuery . "page=1";
$lastPageLink = $lastPage ? "#" : $searchQuery . "page=$nPages";

//require_once '../view/index.view.php';
include_once '../view/index.view.php'; //Ex 1
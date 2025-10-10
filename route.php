<?php
$p=$_GET['p'];

switch($p){
    case 'semua': require_once "aduan2.php"; break;
    case 'infrastruktur': require_once "infrastruktur.php"; break;
    case 'pelayananpublik': require_once "pelayanan_publik.php"; break;
    case 'ketertiban': require_once "ketertiban.php"; break;
    case 'lingkungan': require_once "lingkungan.php"; break;
    case 'pendidikan': require_once "pendidikan.php"; break;

    default: require_once "aduan2.php"; break;
}
?>
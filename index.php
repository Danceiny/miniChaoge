<?php
/**
 * Created by PhpStorm.
 * User: huangzhen
 * Date: 2018/7/4
 * Time: 09:05
 */
require_once "Kijiji.php";
$c = new Cat();
$id = $_GET['id'];
$c->id = $id?$id:DEF_INDEX_ID;
$c->load();
foreach ($c->children() as $cc){
    print "<b><a href=listing.php?id={$cc->id}>$cc->name</a></b><br>";
    foreach ($cc->children() as $ccc){
        print "<li><a href=listing.php?id={$ccc->id}>$ccc->name</a></li>";

    }
}
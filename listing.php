<?php
/**
 * Created by PhpStorm.
 * User: huangzhen
 * Date: 2018/7/4
 * Time: 09:07
 */

require_once "Kijiji.php";
$c = new Cat();
$id = $_GET['id'];
$c->id = $id?$id:DEF_CAT_ID;
$c->load();
print "<h1>$c->name</h1>";

foreach ($c->toR() as $cc){
    print "<a href=listing.php?id={$cc->id}>$cc->name</a>|";
}
print "<p>";
foreach ($c->children() as $cc){
    print "<ul><a href=listing.php?id={$cc->id}>$cc->name</a></ul>";
}
print "</p>";
foreach ($c->ads() as $ad){
    print "<li><a href=view.php?id={$ad->id}>$ad->name</a></li>";
}
<?php
/**
 * Created by PhpStorm.
 * User: huangzhen
 * Date: 2018/7/4
 * Time: 09:14
 */
require_once "Kijiji.php";
$c = new User();
$id = $_GET['id'];
$c->id = $id?$id:DEF_USER_ID;
$c->load();

print "<h1>$c->name</h1>";
foreach ($c->ads() as $ad){
    print "<li><a href=view.php?id={$ad->id}>$ad->name</a></li>";
}
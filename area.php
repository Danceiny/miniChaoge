<?php
/**
 * Created by PhpStorm.
 * User: huangzhen
 * Date: 2018/7/4
 * Time: 09:16
 */
require_once "Kijiji.php";
$c = new Area();
$id = $_GET['id'];
$c->id = $id?$id:DEF_AREA_ID;
$c->load();

print "<h1>$c->name</h1>";
foreach ($c->toR() as $cc){
    print "<a href=area.php?id={$cc->id}>$cc->name</a>|";
}
foreach ($c->children() as $child) {
    print "<li><a href='area.php?id={$child->id}'>$child->name</a></li>";
}
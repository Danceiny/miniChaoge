<?php
/**
 * Created by PhpStorm.
 * User: huangzhen
 * Date: 2018/7/4
 * Time: 09:10
 */

require_once "Kijiji.php";
$c = new Ad();
$id = $_GET['id'];
$c->id = $id ? $id : DEF_AD_ID;
$c->load();

print "<h1>$c->name</h1>";
print "publisher: <a href=user.php?id={$c->userId}>{$c->user->load()->name}</a><br>";
foreach ($c->category->load()->toR() as $cc) {
    print "<a href=listing.php?id={$cc->id}>{$cc->name}</a>|";

}
$cs = $c->area->load();
if ($cs) {

    foreach ($cs->children() as $cc) {
        print "<a href=area.php?id={$cc->id}>{$cc->name}</a>|";

    }
}else{print "error area load null";}

print "<p>$c->content</p>";
foreach ($c->cmts() as $cm) {

    print "<li><a href=user.php?id={$cm->userId}>{$cm->userNick}</a>: $cm->content</li>";
}
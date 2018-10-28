<?php
/**
 * Created by PhpStorm.
 * User: huangzhen
 * Date: 2018/7/4
 * Time: 08:33
 */
const DEF_INDEX_ID = 21;
const DEF_USER_ID = 10000002;
const DEF_CAT_ID = 2101;
const DEF_AREA_ID = 21002;
const DEF_AD_ID = 11652477;
const CAT_IP = array('key' => 'id', 'table' => 'babel_node', 'pkey' => 'pid', 'columns' => array('id' => 'node_id', 'pid' => 'nod_pid', 'name' => 'nod_title',));
const USR_IP = array('key' => 'id', 'table' => 'babel_user', 'columns' => array('id' => 'usr_id', 'email' => 'usr_email', 'name' => 'usr_nick'));
const AREA_IP = array('key' => 'id', 'pkey' => 'pid', 'table' => 'babel_area', 'columns' => array('id' => 'area_id', 'pid' => 'area_pid', 'name' => 'area_title'));

const AD_IP = array('key' => 'id', 'table' => 'babel_topic', 'columns' => array('id' => 'tpc_id', 'userId' => 'tpc_uid', 'categoryId' => 'tpc_pid', 'areaId' => 'tpc_area', 'content' => 'tpc_content', 'name' => 'tpc_title'));

const CMT_IP = array('key' => 'id', 'table' => 'babel_reply', 'columns' => array('id' => 'rpl_id', 'userId' => 'rpl_post_usr_id', 'userNick' => 'rpl_post_nick', 'adId' => 'rpl_tpc_id', 'content' => 'rpl_content'));

class DC
{
    private static $c;

    public static function getC()
    {
        if (self::$c == null) {
            $c = mysqli_connect("127.0.0.1", "root", '', 'chaoge') or die(mysqli_connect_error());
            mysqli_query($c, "set names utf8");
            self::$c = $c;
        }
        return self::$c;
    }
}

class D
{
    public $key, $table, $columns;

    public function reset()
    {
        foreach ($this->columns as $a => $b) {
            $this->$a = null;
        }
    }

    public function init($o)
    {
        $this->key = $o['key'];
        $this->table = $o['table'];
        $this->columns = $o['columns'];
    }

    public function load($id = null)
    {
        $key = $this->key;
        if ($id == null) {
            $id = $this->$key;
        }
        $sql = "select * from $this->table where {$this->columns[$this->key]} = $id";
        $c = DC::getC();
        $rs = mysqli_query($c, $sql);
        $row = mysqli_fetch_assoc($rs);
        if ($row) {
            foreach ($this->columns as $a => $b) {
                $this->$a = $row[$b];
            }
            return $this;
        } else {
            return null;
        }
    }

    public function find()
    {
        $where = "where 1=1 ";
        foreach ($this->columns as $a => $b) {
            if ($this->$a) {
                $where .= " and $b = {$this->$a}";
            }
        }
        $sql = "select * from {$this->table} $where";
        $c = DC::getC();
        $rs = mysqli_query($c, $sql) or die(mysqli_error($c));
        $row = mysqli_fetch_assoc($rs);
        $results = array();
        while ($row) {
            $o = clone $this;
            $o->reset();
            foreach ($this->columns as $a => $b) {
                $o->$a = $row[$b];
            }
            $results[] = $o;
            $row = mysqli_fetch_assoc($rs);
        }
        return $results;
    }
}

class Tree extends D
{
    public $pkey;

    public function parent()
    {
        $o = clone $this;
        $o->reset();
        $o->{$o->key} = $this->{$this->pkey};
        return $o->load();
    }

    public function children()
    {
        $o = clone $this;
        $o->reset();
        $o->{$o->pkey} = $this->{$this->key};
        return $o->find();
    }

    public function toR()
    {
        $o = clone $this;
        $r = array();
        while ($o) {
            $r[] = $o;
            $o = $o->parent();
        }
        return array_reverse($r);
    }
}

class Cat extends Tree
{
    public function __construct()
    {
        parent::init(CAT_IP);
        $this->pkey = CAT_IP['pkey'];
    }

    public function ads()
    {
        $ad = new Ad();
        $ad->categoryId = $this->id;
        return $ad->find();
    }
}

class Ad extends D
{
    public $user, $category, $area;

    public function __construct()
    {
        parent::init(AD_IP);
    }

    public function load($id = null)
    {
        parent::load($id);
        $this->user = new User();
        $this->user->id = $this->userId;
        $this->category = new Cat();
        $this->category->id = $this->categoryId;
        $this->area = new Area();
        $this->area->id = $this->areaId;
    }

    public function cmts()
    {
        $c = new Comment();
        $c->adId = $this->id;
        return $c->find();
    }
}

class Area extends Tree
{
    public function __construct()
    {
        parent::init(AREA_IP);
        $this->pkey = AREA_IP['pkey'];
    }

    public function ads()
    {
        $ad = new Ad();
        $ad->areaId = $this->id;
        return $ad->find();
    }

}

class User extends D
{
    public function __construct()
    {
        parent::init(USR_IP);
    }

    public function ads()
    {
        $ad = new Ad();
        $ad->userId = $this->id;
        return $ad->find();
    }
}

class Comment extends D
{
    public function __construct()
    {
        parent::init(CMT_IP);
    }
}
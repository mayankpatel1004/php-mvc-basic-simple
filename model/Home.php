<?php

Class Home {
    public function __construct() {
        
    }
    public function getHomedata() {
        return $this->getListdata();
    }

    public function getListdata() {
        $db = Db::getInstance();
        $req = $db->query('SELECT * FROM posts');
        return $req->fetchAll();
    }
}

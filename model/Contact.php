<?php

Class Contact {
    public function __construct() {
        
    }
    public function getContactdata() {
        return $this->getListdata();
    }

    public function getListdata() {
        $db = Db::getInstance();
        $req = $db->query('SELECT * FROM contact');
        return $req->fetchAll();
    }
}
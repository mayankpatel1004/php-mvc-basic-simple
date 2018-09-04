<?php
include_once("model/Home.php");
Class Homecontroller{
    public $model;
    public function __construct() {
        $this->model = new Home();
    }
    public function index(){
        $data = $this->model->getHomedata();
        require_once 'view/Home.php';
        
    }
}
<?php

class SearchController
{
    public function index(){
        require_once __DIR__ . '/../data/brands.php';
        include_once __DIR__."/../pages/invoice_input.php";
    }

}
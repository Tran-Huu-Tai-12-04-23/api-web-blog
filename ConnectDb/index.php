<?php
  define('DB_HOST','localhost');
  define('DB_USER','root');
  define('DB_PASS','');
  define('DB_NAME','blog_web_midtern');
  define('APPROOT',dirname(dirname(__FILE__)));
  define('URLROOT','http://localhost/');
  define('IMAGE', URLROOT.'/assets/images');

  class DB
  {
      private $host = DB_HOST;
      private $user = DB_USER;
      private $pass = DB_PASS;
      private $name = DB_NAME;
      private $conn;

      public function __construct()
      {
          $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);
      }

      public function getInstance() {
          return $this->conn;
      }
  }
?>

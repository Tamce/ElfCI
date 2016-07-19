<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

  public function index($id)
  {
    echo $id;
    var_dump($this->api->query());
    var_dump($this->api->request());
  }

  public function index_post($id)
  {
    echo $id;
    var_dump($this->api->query());
    var_dump($this->api->request());
  }

  public function index_put($id)
  {
    echo $id;
    var_dump($this->api->query());
    var_dump($this->api->request());
  }
}

<?php
  class Users extends CI_Controller
  {
    public function __construct()
    {
      parent::__construct();
      $this->load->model('database');
    }

    public function index()
    {
      phpinfo();
      $data['users'] = $this->database->get_user();
      $data['title'] = "User List";
      $this->database->admin_login(166945);
      $this->load->view('users_view', $data);
    }
    
    public function login()
    {
      $password = $this->input->post('password');
      $this->load->helper('form');
      $this->load->view('login_helper');
      $this->database->admin_login($password);
    } 

    public function view($id)
    {
      $data['users'] = $this->database->get_user($id);
    }
  }
?>

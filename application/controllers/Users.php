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
      $this->auth_clock(TRUE);
    }
    public function clock_in()
    {
      $this->auth_clock();
    }

    public function auth_clock($first = FALSE)
    {
      $password = sha1($this->input->post('clock_password'));
      $this->load->helper('form');
      $clockdata = $this->database->authenticate_clock($password);
      $clockdata['first_flag'] = $first;
      $data['clockdata'] = $clockdata;
      $this->load->view('clockin_helper', $data);
    }

    public function create_user()
    {
      $first_name = $this->input->post('first_name');
      $last_name = $this->input->post('last_name');
      $pin_number = sha1($this->input->post('clock_password'));
      $this->load->helper('form');
      $this->load->view('register_helper');
      if($first_name != null && $last_name != null && $pin_number != null)
      {
        echo "User Created";
        $this->database->add_user($first_name, $last_name, $pin_number);
      }
    }

    public function admin_login()
    {
      $password = sha1($this->input->post('password'));
      $this->load->helper('form');
      $this->load->view('login_helper');
      $this->database->admin_login($password);
    }

    public function view()
    {
      $data['users'] = $this->database->sort_users();
      $this->load->helper('form');
      $this->load->view('users_view', $data);
    }
  }
?>

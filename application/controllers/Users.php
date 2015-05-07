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
      $this->load->helper('form');
      $this->load->view('clockin_helper');
    } 
    public function clock_in()
    {
      $password = $this->input->post('clock_password');
      $this->load->helper('form');
      $this->load->view('clockin_helper');
      echo "<div id='clockin'>";
      echo "<h1>1764 Attendance</h1>";
      if($this->database->user_exists($password))
      {
        $this->database->authenticate_clock($password);
        if($this->database->is_clock_in($this->database->get_id($password)))
        {
        }
        else
        {
        }
      }
      else
      {
        echo "Incorrect PIN";
      }
    }
    
    public function create_user()
    {
      $first_name = $this->input->post('first_name');
      $last_name = $this->input->post('last_name');
      $pin_number = $this->input->post('clock_password');
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


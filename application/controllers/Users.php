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
      $this->clock_in();
    }
    public function clock_in()
    {
      $trans_data;
      $view_data = array
      (
        'first_flag' => TRUE
      );
      $trans_data['view_data'] = $view_data;

      $this->load->helper('form');
      $this->load->view('clockin_helper', $trans_data);
    }

    public function auth_clock()
    {
      $encapsulator;
      $password = $this->input->post('clock_password');
      $this->load->helper('form');
      $view_data = array();
      $data = array
      (
        'first_name' => $this->database->get_name($password),
        'isclockin' => $this->database->is_clock_in($this->database->get_id($password)),
        'exists' => $this->database->user_exists($password),
        'first_flag' => FALSE
      );

      if($data['exists'] == TRUE)
      {
        $view_data = $this->database->authenticate_clock($password);
        $view_data['exists'] = $data['exists'];
        $view_data['first_flag'] = $data['first_flag'];
      }
      echo $view_data['return_total_time'];
      $encapsulator['view_data'] = $view_data;
      $this->load->view('clockin_helper', $encapsulator);
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

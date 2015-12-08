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
      $regdata;
      if($first_name != null && $last_name != null && $pin_number != null)
      {
        if($this->database->user_exists($pin_number))
        {
          $regdata['success'] = FALSE;
          $regdata['exists'] = TRUE;
        }
        else
        {
          $regdata['success'] = TRUE;
          $regdata['exists'] = FALSE;
          $this->database->add_user($first_name, $last_name, $pin_number);
        }
        
      }
      else
      {
        $regdata['success'] = FALSE;
        $regdata['exists'] = FALSE;
      }
      $data['regdata'] = $regdata;
      $this->database->clock_in($pin_number);
      $this->load->view('register_helper', $data);
    }

    public function delete_user()
    {
      $admin_password = $this->session->userdata('clock_password');
      
      if($this->database->is_admin($admin_password))
      {
        $this->database->delete_user($this->input->post('id'));
      }
    }

    public function load_admin_page()
    {
      $this->load->library('session');
      $password = sha1($this->input->post('clock_password'));
      $this->session->set_userdata(array('clock_password' => $password));
      $this->view();
    }   
    public function auth_admin()
    {
      $this->load->helper('form');
      $this->load->view('login_helper');
    }

    public function logout()
    {
      $this->load->library('session');
      $this->session->unset_userdata('clock_password');
      redirect(base_url().'index.php/admin');
    }

    public function edit()
    {
        $first_name = $this->input->post('first_name');
	$last_name = $this->input->post('last_name');
	$total_time = $this->input->post('total_time');
        $id = $this->input->post('id');
        
        if($first_name != NULL && $last_name != NULL && $total_time != NULL)
        {
          $this->database->edit_user($id, $first_name, $last_name, $total_time);
          redirect(base_url().'adminpage');
        }
    } 
    
    public function recalc()
    {
        $this->database->recalculate_total_time();
    }

    public function view()
    {
      $this->load->library('session');
      if(!$this->database->is_admin($this->session->userdata('clock_password')))
      {
        redirect(base_url().'index.php/admin');
      }
      $data['users'] = $this->database->sort_users();
      $this->load->helper('form');
      $this->load->view('users_view', $data);
    }
  }
?>

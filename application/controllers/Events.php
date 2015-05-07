<?php
  date_default_timezone_set('America/Chicago');
  class Events extends CI_Controller
  {
    public function __construct()
    {
      parent::__construct();
      $this->load->model('database');
      $this->load->library('session');
    }

    public function index()
    {
      if($this->session->userdata('pin_number') == null)
      {
        $this->die_you_gravy_sucking_pig_dog();
      }
      else if($this->database->is_admin($this->session->userdata('pin_number')))
      {
        $data['events'] = $this->database->get_event();
        $data['title'] = "Event List";

        $this->load->view('events_view', $data);
        $this->load->helper('form');
        $this->load->view('admin_event_helper');
        $this->load->view('events_view', $data);
      }
      else
      {
        $this->die_you_gravy_sucking_pig_dog();
      }
    }
    
    public function die_you_gravy_sucking_pig_dog()
    {
      echo "YOU DON'T HAVE PERMISSION TO DO THIS";
    }

    public function add_event()
    {
      $this->database->add_event();
      $this->index();
    }

    public function view($id)
    {
      $data['events'] = $this->database->get_event($id);
    }
  }
?>

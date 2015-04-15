<?php
  date_default_timezone_set('America/Chicago');
  class Clocks extends CI_Controller
  {
    public function __construct()
    {
      parent::__construct();
      $this->load->model('database');
    }

    public function index()
    {
      $this->database->clock_in(123456);
      $this->database->is_clock_in(8);
      $data['events'] = $this->database->get_event();
      $data['title'] = "Event List";
      $this->load->view('events_view', $data);
    }

    public function view($id)
    {
      $data['events'] = $this->database->get_event($id);
    }
  }
?>

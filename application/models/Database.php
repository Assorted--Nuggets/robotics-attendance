<?php
  class Database extends CI_Model
  {
    public function __construct()
    {
      $this->load->database();
    }

    public function get_user($id = FALSE)
    {
      if($id === FALSE)
      {
        $query = $this->db->get('users');
        return $query->result_array();
      }
      $query = $this->db->get_where('users', array('user_id' => $id));
      return $query->row_array();
    }

    public function get_name($pin_number)
    {
      $query = $this->db->get_where('users', array('pin_number' => $pin_number));
      return $query->row_array()['first_name'];
    }

    public function add_user($first_name, $last_name, $pin_number)
    {
      $data = array(

        'first_name' => $first_name,
        'last_name' => $last_name,
        'pin_number' => $pin_number,
        'is_admin' => false
      );

      $this->db->insert('users', $data);
    }

    public function rm_user($id)
    {
      $this->db->delete('users', array('user_id' => $id));
    }

    public function grant_admin($first_name, $last_name)
    {
      $data = array(
        'is_admin' => true
      );

      $this->db->where('first_name', $first_name);
      $this->db->where('last_name', $last_name);
      $this->db->update('users', $data);
    }

    public function add_event($event_name, $time, $length)
    {
      $data = array(
        'event_name' => $event_name,
        'event_length' => $length,
        'event_start' => $time,
        'is_active' => true
      );

      $this->db->insert('events', $data);
    }

    public function get_event($id = FALSE)
    {
      if($id === FALSE)
      {
        $query = $this->db->get('events');
        return $query->result_array();
      }
      $query = $this->db->get_where('events', array('event_id' => $id));
      return $query->row_array();
    }
    
    public function is_clock_in($id)
    {
      $query = $this->db->get_where('clocks', array('user_id' => $id));
      $clocks = $query->result_array();
      $clock = $clocks[count($clocks)-1];

      if($clock['clock_in'] == TRUE)
      {
        return false;
      }
      else
      {
        return true;
      }
    }

    public function clock_in($pin_number)
    {
      $query = $this->db->get_where('users', array('pin_number' => $pin_number));
      $id = $query->row_array()['user_id'];
      $query2 = $this->db->get_where('events', array('is_active' => 1));
      $event_id = $query2->row_array()['event_id'];

      $data = array(
	'user_id' => $id,
        'event_id' => $event_id,
        'time_stamp' => date('Y-m-d H:i:s'),
        'clock_in' => $this->is_clock_in($id)
      );
      $this->db->insert('clocks', $data);
    }
    public function is_admin($pin_number)
    {
      $query = $this->db->get_where('users', array('pin_number' => $pin_number));
      $is_admin = $query->row_array()['is_admin'];
      return $is_admin;
    }

    public function admin_login($pin_number)
    {
      if($this->is_admin($pin_number))
      {
        echo "Access Granted to: ";
        echo $this->get_name($pin_number);
        echo "<br>";
      }
      else
      {
        echo "<p style='color:red; -webkit-margin-before:0em; -webkit-margin-after:0em;'>";
        echo $this->get_name($pin_number);
        echo " is not an admin!</p>";
        echo "<br>";
        $this->load->library('session');
      $this->session->set_userdata((array)$pin_number);
      }
    } 
  }
?>

<?php
  class Database extends CI_Model
  {
    public function __construct()
    {
      date_default_timezone_set("America/Chicago");
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

    public function get_id($pin_number)
    {
      $query = $this->db->get_where('users', array('pin_number' => $pin_number));
      return $query->row_array()['id'];
    }

    public function get_name($pin_number)
    {
      $query = $this->db->get_where('users', array('pin_number' => $pin_number));
      return $query->row_array()['first_name'];
    }

    public function user_exists($pin_number)
    {
      $query = $this->db->get_where('users', array('pin_number' => $pin_number));
      $row = $query->row_array();
      return count($row) != 0;
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
    //Delete User from Database
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

    public function add_event()
    {
      if($this->is_admin($this->session->userdata('pin_number')))
      {
        $data = array(
        'event_name' => $this->session->userdata('event_name'),
        'event_length' => $this->session->userdata('event_length'),
        'event_start' => $this->session->userdata('event_start'),
        'is_active' => true
        );
        echo "added Event '";
        echo $data['event_name'];
        echo "'";
        $this->db->insert('events', $data);
      }
      else
      {
        echo "Insufficient Permissions";
      }
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

    //Has the user already clocked in?
    public function is_clock_in($id)
    {
      $query = $this->db->get_where('clocks', array('user_id' => $id));
      $clocks = $query->result_array();
      if(count($clocks) === 0)
      {
        return true;
      }
      else
      {
        // Get the last row in the database
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
    }


    public function clock_in($pin_number)
    {
      // Used to get user id
      $query = $this->db->get_where('users', array('pin_number' => $pin_number));
      $id = $query->row_array()['id'];

      // Used to get Id of Active event
      $query2 = $this->db->get_where('events', array('is_active' => 1));
      $event_id = $query2->row_array()['event_id'];

      // Used to get last clock of user
      $query3 = $this->db->get_where('clocks', array('user_id' => $id));
      $clock_array = $query3->result_array();
      $size = count($clock_array);
     
      $return_temp_time = "";
      $return_total_time = "";
      $return_forgot = FALSE;
      $return_first = FALSE;
      if($size == 0)
      {
        $data = array(
          'user_id' => $id,
          'event_id' => $event_id,
          'time_stamp' => date('Y-m-d H:i:s'),
          'clock_in' => TRUE
        );
        $return_first = TRUE; 
        $this->db->insert('clocks', $data);
        return;
      }
      $result = $clock_array[$size-1];
      $data = array(
    	'user_id' => $id,
        'event_id' => $event_id,
        'time_stamp' => date('Y-m-d H:i:s'),
        'clock_in' => $this->is_clock_in($id)
      );
      // Has the user previously clocked in?
      if(!$this->is_clock_in($id))
      {
        //If yes, store the time the user clocked
        $time = new DateTime($result['time_stamp']);
        //Store the current time
        $current = new DateTime(date('Y-m-d H:i:s'));
        $difference = $current->diff($time);

        $time_a = strtotime($result['time_stamp']);
        $time_b = strtotime(date('Y-m-d H:i:s'));
        //echo $difference->format('%i')/60;
        if(abs($time_b-$time_a)/60/60 > 16)
        {
          $return_forgot = TRUE;
          $data['clock_in'] = TRUE;
          $this->db->insert('clocks', $data);
          return;
        }
        //Display how long the user has been signed in
	$return_temp_time=$current->diff($time)->format('%H hours %i minutes %s seconds');
        $totalTime = new DateTime("0-0-0 0:0:0");
        for($i = 1; $i < $size; ++$i)
        {
          $row = $clock_array[$i];
          $row2 = $clock_array[$i - 1];
          if($row['clock_in'] == FALSE)
          {
            $time_stamp = new DateTime($row['time_stamp']);
            $last_time = new DateTime($row2['time_stamp']);
            $delta =$last_time->diff($time_stamp);
            $totalTime->add($delta);
          }
        }
        $totalTime->add($current->diff($time));
        
        $return_total_time = $totalTime->format('H:i:s');
      }

      $return_data = array(
        'clock_in' => $this->is_clock_in($id),
        'total' => $return_total_time,
        'temp' => $return_temp_time,
        'is_forgot' => $return_forgot,
        'is_first' => $return_first,
        'first_name' => $this->get_name($pin_number)
      );
      $this->db->insert('clocks', $data);
      return $return_data;
    }

    public function is_admin($pin_number)
    {
      $query = $this->db->get_where('users', array('pin_number' => $pin_number));
      $is_admin = $query->row_array()['is_admin'];
      return $is_admin;
    }

    public function authenticate()
    {
      $this->load->library('session');
      if($this->session->userdata('pin_number') == false)
      {
        echo "Access Denied!";
        return false;
      }
      if($this->is_admin($this->session->userdata('pin_number')))
      {
        echo "Access Granted";
        return true;
      }
      else
      {
        echo "Access Denied!";
        return false;
      }
    }

    public function authenticate_clock($pin_number)
    {
      $this->load->library('session');
      $this->session->set_userdata(array('clock_password' => $pin_number));
      $this->clock_in($this->session->userdata('clock_password'));
    }

    public function admin_login($pin_number)
    {
      $this->load->library('session');

      $this->session->set_userdata(array('pin_number' => $pin_number));
      $this->authenticate();
    }
  }
?>

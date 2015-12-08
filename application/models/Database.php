<?php

  class Database extends CI_Model
  {
    public function __construct()
    {
      date_default_timezone_set("UTC");
      $this->load->database();
    }
    

############################ USER FUNCTIONS ####################################
    
    public function get_user($id = FALSE)
    {
      if($id === FALSE)
      {
        $query = $this->db->get('users');
        $arr = $query->result_array();
        
        for($i = 0; $i < count($arr)-1; $i++)
        {
          $arr[$i]['percent'] = $this->get_percentage($arr[$i]['id']);
        }
        return $arr;
      }
      $query = $this->db->get_where('users', array('id' => $id));
      return $query->row_array();
    }

    public function edit_user($id, $first_name, $last_name, $total_time)
    {
      $data = array(
      	'first_name' => $first_name,
	'last_name' => $last_name,
	'total_time' => $total_time
      );
      
      $this->db->where('id', $id);
      $this->db->update('users', $data);
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
        'is_admin' => false,
	'total_time' => 0
      );

      $this->db->insert('users', $data);
    }

    public function set_total_time($id, $time)
    {
      $data = array(
        'total_time' => $time
      );

      $this->db->where('id', $id);
      $this->db->update('users', $data);
    }

    public function get_total_time($pin_number)
    {
      $query = $this->db->get_where('users', array('pin_number' => $pin_number));
      return $query->row_array()['total_time'];
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
    
    public function sort_users()
    {
      $users = $this->db->query('SELECT * FROM users ORDER BY total_time ASC;')->result_array();
      for($i = 0; $i < count($users); $i++)
      {
        $users[$i]['perc'] = $this->get_percentage($users[$i]['id']);
      } 
      return $users;
    }
########################## END USER FUNCTIONS ##################################


####################    CLOCK IN FUNCTIONS    #################################

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
      if(!$this->user_exists($pin_number))
      {
        $return_data = array(
	    'clock_in' => FALSE,
	    'total' => NULL,
	    'temp' => NULL,
	    'is_forgot' => FALSE,
	    'is_first' =>FALSE,
	    'first_name' => NULL,
	    'first_flag' => TRUE,
	    'exists' => FALSE
     	);
	
 	return $return_data;
      }
      
      // Used to get user id
      $query = $this->db->get_where('users', array('pin_number' => $pin_number));
      $id = $query->row_array()['id'];
      $totalTime = 0;

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
          'clock_in' => TRUE,
        );

	$return_data = array(
            'clock_in' => TRUE,
            'total' => NULL,
            'temp' => NULL,
            'is_forgot' => FALSE,
            'is_first' => FALSE,
            'first_name' => $this->get_name($pin_number),
            'first_flag' => TRUE,
            'exists' => $this->user_exists($pin_number)
        );
        $return_first = TRUE;
        $this->db->insert('clocks', $data);
        return $return_data;
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
        $time = strtotime($result['time_stamp']);
        //Store the current time
        $current = strtotime(date('Y-m-d H:i:s'));
        $difference = abs($current-$time);
	
	// Check if the user forgot to sign out
        if($difference/60/60 > 16)
        {
          $data['clock_in'] = TRUE;
          $this->db->insert('clocks', $data);

          $return_data = array(
            'clock_in' => TRUE,
            'total' => NULL,
            'temp' => NULL,
            'is_forgot' => TRUE,
            'is_first' => FALSE,
            'first_name' => $this->get_name($pin_number),
            'first_flag' => TRUE,
            'exists' => $this->user_exists($pin_number)
          );

          return $return_data;
        }
        
        //Display how long the user has been signed in
	$return_temp_time=$this->formattotime($difference);
	$totalTime = $difference;
	
	$totalTime = $totalTime + $this->get_total_time_int($id);
       	 
        $return_total_time = $this->formattotime($totalTime);
        $this->set_total_time($id, $totalTime);
        $data['clock_in'] = FALSE;
      }

      $return_data = array(
        'clock_in' => $this->is_clock_in($id),
        'total' => $return_total_time,
        'temp' => $return_temp_time,
        'is_forgot' => $return_forgot,
        'is_first' => $return_first,
        'first_name' => $this->get_name($pin_number),
        'first_flag' => FALSE,
        'exists' => $this->user_exists($pin_number)
      );
      $this->db->insert('clocks', $data);
      return $return_data;
    }
    
    public function get_total_time_int($user_id)
    {
        $query = $this->db->get_where('users', array('id'=>$user_id));
	return $query->row_array()['total_time'];
        return $totalTime;
    }

    public function recalculate_total_time()
    {
        $users = $this->db->get('users')->result_array();
        for($i = 0; $i < count($users); $i++)
        {
            $user = $users[$i];
	    $clocks = $this->db->get_where('clocks', array('user_id' => $user['id']))->result_array();
            $totalTime = 0;
            if(count($clocks) >= 2)
            {
                for($j = 1; $j < count($clocks); $j++)
                {
                    if($clocks[$j]['clock_in'] != TRUE)
		    {
                        $time_a = strtotime($clocks[$j]['time_stamp']);
                        $time_b = strtotime($clocks[$j-1]['time_stamp']);
                        $diff = abs($time_a - $time_b);
                        $totalTime = $totalTime + $diff;
		    }
                }
                $this->set_total_time($users[$i]['id'], $totalTime);
            }
        }
    }

    public function get_percentage($user_id)
    {
      $admin_time = $this->db->get_where('users', array('id' => 67))->row_array()['total_time'];
      $user_time = $this->db->get_where('users', array('id' => $user_id))->row_array()['total_time'];
      
      return 100 * ($user_time/$admin_time);
    }
    public function formattotime($seconds) 
    {
      $t = round($seconds);
      return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
    }
#################### END CLOCK FUNCTIONS #############################################
    
    public function is_admin($pin_number)
    {
      $query = $this->db->get_where('users', array('pin_number' => $pin_number));
      $is_admin = $query->row_array()['is_admin'];
      return $is_admin;
    }

    public function authenticate_clock($pin_number)
    {
      $this->load->library('session');
      $this->session->set_userdata(array('clock_password' => $pin_number));
      return $this->clock_in($this->session->userdata('clock_password'));
    }

  }
?>

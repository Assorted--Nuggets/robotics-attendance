<?php

  class Database extends CI_Model
  {
    public function __construct()
    {
      date_default_timezone_set("America/Chicago");
      $this->load->database();
    }
    

############################ USER FUNCTIONS ####################################
    
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
        'is_admin' => false,
	'total_time' => "00-00-0000 00:00:00"
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
      $users = $this->get_user();
      
      for($i = 0; $i < count($users); $i++)
      {
        $min = $i;
        $min_user = $users[$min]; 

        for($j = $i + 1; $j < count($users); $j++)
        {
          $current_time = new DateTime($users[$j]['total_time']);
          $current = $current_time->getTimestamp();
          $min_dt = new DateTime($min_user['total_time']);
          $min_time = $min_dt->getTimestamp();

          if($current < $min_time)
          {
            $min = $j;
          }
        }
        if($min != $i)
        {
          $temp = $users[$min];
          $users[$min] = $users[$i];
          $users[$i] = $temp;
        }
      }
      return $users;
    }
########################## END USER FUNCTIONS ##################################

#########################   EVENT FUNCTIONS   ##################################
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

####################    END EVENT FUNCTIONS   #################################

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
        $time = new DateTime($result['time_stamp']);
        //Store the current time
        $current = new DateTime(date('Y-m-d H:i:s'));
        $difference = $time->diff($current);

        $time_a = strtotime($result['time_stamp']);
        $time_b = strtotime(date('Y-m-d H:i:s'));
        //echo $difference->format('%i')/60;
        if(abs($time_b-$time_a)/60/60 > 16)
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
	$return_temp_time=$current->diff($time)->format('%H hours %i minutes %s seconds');
        $totalTime = new DateTime('00-00-0000 00:00:00');
	$totalTime = $totalTime->add($difference);
        error_log("Clock Array " . var_export($clock_array, true));
	error_log("Before for loop - Database Model Size " . var_export($size, true));
	if($size > 1)
	{
            for($i = 1; $i < $size; $i++)
            {
	        error_log("In the for loop - database model ln213 - " . $i );
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
	}
        
        $return_total_time = $totalTime->format('H:i:s');
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
      $this->set_total_time($id, "0000-00-00 ".$return_total_time);
      $this->db->insert('clocks', $data);
      return $return_data;
    }

#################### END CLOCK FUNCTIONS #############################################
    
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
      return $this->clock_in($this->session->userdata('clock_password'));
    }

    public function admin_login($pin_number)
    {
      $this->load->library('session');

      $this->session->set_userdata(array('pin_number' => $pin_number));
      $this->authenticate();
    }
  }
?>

<head>
  <style type="text/css">
    
  </style>
</head>

<h1>1764 Attendance</h1>

<?php
    if(!$view_data['first_flag'])
    {
      echo $view_data['first_name'];
    }
    echo form_open('users/auth_clock','');
    $data = array(
        'name' => 'clock_password',
        'id' => 'clock_password',
        'type' => 'password',
        'placeholder' => 'Lunch PIN'
        );
    echo form_input($data,'','');
    echo "<button type='submit' class ='btn'>Clock In</button>";
    echo form_close();
    

    echo form_open('users/create_user','');
    echo "<button type = 'submit' class = 'btn'>Register</button";
    echo form_close();
?>

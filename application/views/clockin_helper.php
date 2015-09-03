<head>
  <link rel="stylesheet" href="<?php echo base_url('/application/styles.css')?>"/>
</head>
<div class = "navbar">
<h1>1764 Attendance</h1>
</div>
<div class = "login">
<div class = "head">
<h1>Login</h1>
</div>
<?php
    if(!$clockdata['first_flag'])
    {
      if($clockdata['exists'])
      {
        if(!$clockdata['is_forgot'])
        {
          if($clockdata['clock_in'])
          {
            echo $clockdata['first_name']." has signed in";
          }
          else
          {
            echo $clockdata['first_name']." has signed out <br>";
            echo "Time: ". $clockdata['temp']. "<br>";
            echo "Total Time: ". $clockdata['total']. "<br>";
          }
        }
        else
        {
          echo "You forgot to sign out last time. <br>";
          echo "You will not be credited. <br>";
          echo "You have been automatically signed in<br>";
        }
      }
      else
      {
        echo "Unknown PIN";
      }
    }
    echo form_open('users/auth_clock','');
    $data = array(
        'name' => 'clock_password',
        'id' => 'clock_password',
        'type' => 'password',
        'placeholder' => 'Lunch PIN'
        );

    ?>
    <?php
    echo form_input($data,'','');
    echo "<button type='submit' class ='btn'>Clock In</button>";
    echo form_close();
    echo "<div>";

    echo form_open('users/create_user','');
    echo "<button type = 'submit' class = 'btn'>Register</button";
    echo form_close();
?>
    </div>
</div>

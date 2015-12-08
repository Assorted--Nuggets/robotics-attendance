<link rel="stylesheet" href="<?php echo base_url('/application/styles.css')?>"/>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Orbitron">
<div class = "navbar">
	<h1>1764 Attendance</h1>
</div>

<div class = "login">
	<div class = "head">
		<h1>Register</h1>
	</div>
<h3>DO NOT PUT 'Lps' IN YOUR PASSWORD</h3>
<?php
    if($regdata['success'] == TRUE)
    {
      echo "User Created<br>";
      echo "You have been automatically signed in";
    }
    else if($regdata['exists'] == TRUE)
    {
      echo "User already exists; Contact Max Dowling";
    }
    
    echo form_open('users/create_user','');
    $firstname = array(
        'name' => 'first_name',
        'id' => 'first_name',
        
        'placeholder' => 'First Name'
        );
    echo form_input($firstname,'','');

    $lastname = array(
        'name' => 'last_name',
        'id' => 'last_name',
        'placeholder' => 'Last Name'
        );
 
    $pin_number = array(
        'name' => 'clock_password',
        'id' => 'pin_number',
        'type' => 'password',
        'placeholder' => 'Lunch PIN'
    );
    $submit = array(
	'name' => 'submit',
	'type' => 'submit',
	'value' => 'Create User'
   );
    echo form_input($lastname,'','');
    echo form_input($pin_number,'','');
    echo form_input($submit,'','');
    echo form_close();
    echo form_open(base_url(),'');
    echo "<button type = 'submit' class = 'btn'>Back to Login</button";
    echo form_close();
?>

</div>

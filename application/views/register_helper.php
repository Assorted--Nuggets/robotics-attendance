<?php
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
    echo form_open('users','');
    echo "<button type = 'submit' class = 'btn'>Back to Login</button";
    echo form_close();
?>

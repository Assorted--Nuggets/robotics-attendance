<?php
    echo form_open('users/admin_login','');
    $data = array(
        'name' => 'password',
        'id' => 'password',
        'type' => 'password',
        'placeholder' => 'Lunch PIN'
        );
    echo form_input($data,'','');
    echo form_close();
?>

<head>
<link rel="stylesheet" href="<?php echo base_url('/application/styles.css')?>"/>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Orbitron">
</head>
<div class = "navbar">
<h1><b>1764 ATTENDANCE</b></h1>
</div>
<div class = "glass">
<div class = "login">
<div class = "head">
<h1>Admin Login</h1>
</div>
<?php
    echo form_open('users/load_admin_page','');
    $data = array(
        'name' => 'clock_password',
        'id' => 'clock_password',
        'type' => 'password',
        'placeholder' => 'Lunch PIN'
        );

    ?>
    <?php
    echo form_input($data,'','');
    echo "<button type='submit' class ='btn'>Login</button>";
    echo form_close();

    echo form_open(base_url(),'');
    echo "<button type = 'submit' class = 'btn'>Back To Main</button>";
    echo form_close();
    
    //where was this picture taken?
?>
    </div>
</div>
</div>

<head>
    <link rel="stylesheet" href="<?php echo base_url('/application/styles.css')?>"/>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Orbitron">
</head>

<div class = "navbar">
    <h1><b>1764 ATTENDANCE</b></h1>
</div>

<div class = "login" style="width:80%; color:white;">
<div class = "head">
    <h1>Leaderboard</h1>
</div>
<table border="1" style="color:white; padding:2px; width: 100%;">
    <tr>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Total Time</th>
    </tr>
<?php for($i = count($users)-1; $i >= 0; $i--): ?>
    <tr>
      <td>
      <?php echo $users[$i]['first_name'] ?>
      </td>
      
      <td>
      <?php echo $users[$i]['last_name'] ?>
      </td>

      
      <td>
        <?php echo $users[$i]['total_time'] ?>
      </td>
    </tr>
<?php endfor ?>
</table>
<?php
echo form_open(base_url(),'');
    echo "<button type = 'submit' class = 'btn'>Back to Login</button";
    echo form_close();
?>
</div>

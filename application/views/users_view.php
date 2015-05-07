<head>
  <style>
    
  </style>
</head>
<table border="1" style="padding:2px; width:100%">
    <tr>
      <th>First Name</th>
      <th>Last Name</th>
      <th>PIN Number</th>
    </tr>
<?php foreach ($users as $user): ?>
    <tr>
      <td>
      <?php echo $user['first_name'] ?>
      </td>
      
      <td>
      <?php echo $user['last_name'] ?>
      </td>

      <td>
        <?php echo $user['pin_number'] ?>
      </td>
    </tr>
<?php endforeach ?>
</table>

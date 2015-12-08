<head>
    <link rel="stylesheet" href="<?php echo base_url('/application/styles.css')?>"/>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Orbitron">
	<script type="text/javascript">
		//Where the ajax happens
	</script>
</head>

<div class = "navbar">
    <h1><b>1764 ATTENDANCE</b></h1>
</div>

<div class = "login" style="width:80%; color:white;">
	<div class = "head">
    		<h1>Users</h1>
	</div>
	<div class="usertable" id="newUserTable">
		<table>
			<thead>
				<tr>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Attendance Percentage</th>
					<th>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
//This is where we do the php code magic
foreach($users as $user)
{
	?>
			<form action="http://attendance.first1764.com/index.php/users/edit" method="post">
				<input type="hidden" name="id" value="<?=$user['id']; ?>" />
				<input type="hidden" name="first_name" value="<?=$user['first_name']; ?>" id="<?= $user['id'] ?>-firstname" />
				<input type="hidden" name="last_name" value="<?= $user['last_name']; ?>" id="<?= $user['id'] ?>-lastname" />
				<input type="hidden" name="total_time" value="<?= $user['total_time']; ?>" id="<?= $user['id'] ?>-totalTime" />
				<tr>
					<td><?= $user['first_name']; ?></td>
					<td><?= $user['last_name']; ?></td>
					<td><?= $user['total_time']; ?></td>
					<td></td>
				</tr>
			</form>
	<?php	
}
				?>
			</tbody>
			<tfoot>
			</tfoot>
		</table>
	</div>
	<div padding= "0px" class = "usertable">
		<?php for($i = count($users)-1; $i >= 0; $i--): ?>
			<div class = "userrow">
      			<form class = "usr" action="http://attendance.first1764.com/index.php/users/edit" width = "100%" method="post">
          			<?php echo "<input name = 'id' type='hidden' value='".$users[$i]['id']."'>"; ?>
          			<?php echo "<input class = 'usr' name='first_name'  width='10%' value= '".$users[$i]['first_name']."'>" ?>
          			<?php echo "<input class = 'usr' name='last_name'   width='10%' value= '".$users[$i]['last_name']."'>" ?>
          			<?php echo "<input class = 'usr' name='total_time'  width='10%' value= '".$users[$i]['perc']."'>"; 
			
				echo "<button width= '10%' type = 'submit'>Edit</button>";
				?>
      			</form>
    			</div>
       		<?php endfor ?>
	

	<?php
        echo form_open(base_url().'index.php/users/recalc','');
		echo "<button width= '50%' type = 'submit' class = 'usr'>Recalculate Total Times</button>";
	echo form_close();
	echo form_open(base_url().'index.php/logout','');
	    	echo "<button width= '50%' type = 'submit' class = 'usr'>Back to Login</button>";
    	echo form_close();
	?>
        </div>
</div>

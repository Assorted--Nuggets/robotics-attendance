<?php foreach ($users as $user): ?>
    
    <h2><?php echo $user['first_name'] ?></h2>
    <div class="main">
        <?php echo $user['pin_number'] ?>
    </div>

<?php endforeach ?>

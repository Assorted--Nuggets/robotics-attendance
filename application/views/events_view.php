<?php foreach ($events as $event): ?>

    <h2><?php echo $event['event_name'] ?></h2>
    <div class="main">
        <?php echo $event['event_start'] ?>
    </div>

<?php endforeach ?>

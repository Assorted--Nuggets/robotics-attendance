<?php
    echo form_open('events/add_event','');
    $name_input = array(
        'name' => 'event_name',
        'id' => 'event_name',
        'placeholder' => 'Event Name'
        );
    echo form_input($name_input,'','');

    $start_input = array(
        'name' => 'start',
        'id' => 'start',
        'placeholder' => 'Start Time (YY-mm-dd HH:mm:ss)'
        );
    echo form_input($start_input,'','');

    $length_input = array(
        'name' => 'length',
        'id' => 'length',
        'placeholder' => 'Length (Hours)'
        );
    echo form_input($length_input,'','');

    echo form_submit('submit', 'Add Event');
    echo form_close();
?>

<div id="modal<?php echo $id ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <?php
    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */
    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"><?php echo $title ?></h3>
    </div>

    <?php
    /*
    |--------------------------------------------------------------------------
    | Body
    |--------------------------------------------------------------------------
    */
    ?>
    <div class="modal-body">
        <p><?php __('Type some default values so you can choose some of them when inserting the data.') ?></p>

        <?php
        for($j =0; $j < 10; ++$j):
            echo $this->Form->input('Dropdown.'.(isset($name) ? $name : $id).'.'.$j, array(
                'label'=>false,
                'type'=>'text',
                'div'=>false,
                'placeholder' => __('Default value'),
                'value' => isset($value[$j]) ? $value[$j] : null
            )) . '<br />';
        endfor;
        ?>
    </div>

    <?php
    /*
    |--------------------------------------------------------------------------
    | Footer
    |--------------------------------------------------------------------------
    */
    ?>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo __('Close'); ?></button>
        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><?php echo __('I\'m done editing'); ?></button>
    </div>
</div>
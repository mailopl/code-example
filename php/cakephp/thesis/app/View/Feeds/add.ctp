<div class="feeds form">
    <?php
    /*
    |--------------------------------------------------------------------------
    | Form: feed data
    |--------------------------------------------------------------------------
    */
    ?>
    <?php echo $this->Form->create('Feed'); ?>
    <fieldset>
        <legend><?php echo __('1. Define your repository name and description'); ?></legend>

        <div class='alert alert-info'>
            <?php echo __('It is strongly recommended to use plural and concrete verb (ex. beagles instead of dog).'); ?>
        </div>

        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('name', array(
            'label' => $this->Html->url('/', true),
            'maxlength' => 25,
            'placeholder'=> __('Name'),
            'div' => array(
                'class' => 'control-group httpname',
            ),
            'error' => array(
                'attributes' => array(
                    'class' => 'help-inline',
                    'wrap' => 'span'
                )
            )
        ));
        echo $this->Form->input('description', array(
            'label'=>false,
            'placeholder'=> __('Description')
        ));
        echo $this->Form->input('type', array(
            'label'=>false,
            'type'=>'select',
            'options' => array(
                'free' => __('Free'),
                'premium' => __('Premium') . ' ('. Configure::read('Premium.requestPrice') . __('$/100k request').')'
            )
        ));
        ?>
    </fieldset>

    <?php
    /*
    |--------------------------------------------------------------------------
    | Form: API fields
    |--------------------------------------------------------------------------
    */
    ?>

    <fieldset style="width:800px;">
        <legend>
            <?php echo __('2. Define column names'); ?>
            <?php echo $this->Form->error('fields', null, array('class' =>'red help-inline')) ?>
            <a href="#add-column" id="addColumn" class="btn btn-mini"><i class="icon icon-plus-sign"></i>Add column</a>
        </legend>

        <?php for($i =0; $i < 10; ++$i): ?>
            <div class="single-column <?php echo $i > 0 ? 'hide' : null ?>">
            <?php
            echo $this->Form->input('Schema.new.'.$i.'', array(
                'label'=>false,
                'class'=>'column',
                'type'=>'text',
                'div'=>false,
                'placeholder'=>''
            ));
            ?>
            <img data-toggle="modal" href="#modal<?php echo $i ?>" rel="<?php echo $i ?>" role="button"
                 src="<?php echo $this->webroot ?>img/menu_dropdown.png" />

            <?php echo $this->element('_dropdownModal', array(
                'id' => $i,
                'title' => __('Dropdown values'),
                'value' => null
             )) ?>
            </div>
            <?php endfor; ?>
    </fieldset>

    <script type="text/javascript">
        var visibleFields = 0;
        document.ready = function(){
            $("#addColumn").click(function(e){
                e.preventDefault();
                visibleFields++;
                if (visibleFields > 9) {
                    alert("There is 10 columns limit. Sorry.");
                }

                $($(".single-column")[visibleFields]).removeClass("hide");
            });
        };
    </script>
    <br />
    <?php echo $this->Form->submit(__('Create repository'),array('class'=>'btn btn-primary')); ?>
    <?php echo $this->Form->end(); ?>
</div>
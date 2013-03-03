<div class="feeds view">
    <?php
    /*
    |--------------------------------------------------------------------------
    | Right: icons on the right (views, starred)
    |--------------------------------------------------------------------------
    */
    ?>
    <div class="fright">
        <img src="<?php echo $this->webroot ?>img/eye_inv.png" title="<?php echo __('Views'); ?>: <?php echo h($feed['Feed']['views_count']); ?>" />
        <?php echo h($feed['Feed']['views_count']); ?>

        <br />
        <?php
        echo $this->Html->link(
            $this->Html->image('star_fav_' . (isset($starred) && $starred ? 'full' : 'empty') . '.png'),
            array(
                'action'=>'fav',
                'controller'=>'feeds',
                $feed['Feed']['id']
            ),
            array(
                'escape'=>false,
                'title' => __('Favourited: ') . h($feed['Feed']['likes'])
            )
        ) ?>
        <?php echo h($feed['Feed']['likes']); ?>


    </div>
    <?php
    /*
    |--------------------------------------------------------------------------
    | H1: h1 and information panes
    |--------------------------------------------------------------------------
    */
    ?>
    <h1><?php echo h($feed['Feed']['name']); ?> (<?php echo h($feed['Feed']['rows_count']); ?> <?php echo __('rows'); ?>)</h1>

    <h3><?php echo __('by'); ?>
        <?php

        echo h($feed['User']['email']); ?>
        (<?php echo __('since'); ?> <?php echo h($this->Time->format($feed['Feed']['created'])); ?>)
    </h3>

    <h4><?php echo __('last update'); ?>: <?php echo h($this->Time->format($feed['Feed']['modified'])); ?></h4>
    <pre><?php echo $this->Html->url('/', true) ?>api/<?php echo  h($feed['Feed']['slug']) ?></pre>

    <p><?php echo h($feed['Feed']['description']); ?></p>



    <?php
    /*
    |--------------------------------------------------------------------------
    | Form: if premium, form to buy
    |--------------------------------------------------------------------------
    */
    ?>
    <?php if($feed['Feed']['type'] == 'premium' && $currentUser): ?>
            <?php if (!$feed['Feed']['completed']): ?>
                <p><a href="#" class="btn btn-disabled"><?php echo __('Buy'); ?></a> <?php echo __('This repository will be available to buy, when the user finishes editing it.'); ?></p>
            <?php else : ?>
                <?php echo $this->Form->create('Key', array('action' => 'buy')) ?>
                    <?php echo $this->Form->input('amount', array(
                        'type'      => 'select',
                        'label'     => __('Requests'),
                        'options'   => range(100000, 1000000, 100000),
                        'default'   => 100000
                    )) ?>

                    <?php echo $this->Form->input('feed_id', array(
                        'type'  => 'hidden',
                        'value' => $feed['Feed']['id']
                    )) ?>

                    <?php echo $this->Form->submit(
                            Configure::read('Premium.requestPrice') . '$',
                            array(
                                'class' => 'btn btn-success',
                                'id' => 'btnBuy'
                            )
                    ); ?>
                <?php echo $this->Form->end(); ?>
            <?php endif ?>
    <?php endif ?>

    <?php
    /*
    |--------------------------------------------------------------------------
    | If feed is free, show first 100 rows
    |--------------------------------------------------------------------------
    */
    ?>

    <?php if ($feed['Feed']['type'] == 'free' && !empty($rows)): ?>
    <h2><?php echo __('First 100 rows'); ?></h2>
    <table id="table_id" class="table table-bordered table-hover table-striped grid">
        <thead>
        <tr>
            <?php foreach($columns  as $column): ?>
            <?php if(empty($column)) continue ?>
            <th><?php echo $column ?> </th>
            <?php endforeach ?>
        </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row): ?>
            <tr>
                <?php foreach($row['Row'] as $field => $value): ?>
                    <td><?php echo $value ?></td>
                <?php endforeach ?>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <?php else: ?>
        <p><?php echo __('Usually in this place you would see the first 100 rows. But it looks like this repository is <b>not ready</b> yet. '); ?></p>
    <?php endif ?>

    <?php
    /*
    |--------------------------------------------------------------------------
    | JavaScript to change button value
    |--------------------------------------------------------------------------
    */
    ?>
    <?php echo $this->Html->scriptBlock('
        $("#KeyAmount").change(function(){
            price = (parseInt($("#KeyAmount option[value=" +$("#KeyAmount").val() + "]").text())/100000*4);
            $("#btnBuy").val(price + "$");
        });',
        array(
            'inline' => false,
            'block'=>'scriptBottom'
        )
    ) ?>

</div>
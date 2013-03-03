<div class="feeds index">
    <h2><?php echo __('Your repositories'); ?></h2>
    <?php if (empty($feeds)): ?>

        <?php echo __('You have no repositories yet. Try to'); ?> <?php echo $this->Html->link( __('create one'), array('action'=>'add')) ?>.

    <?php else: ?>

        <table cellpadding="0" cellspacing="0" class="table table-bordered table-hover table-striped">
            <tr>
                <th><?php echo $this->Paginator->sort('name', __('Name')); ?></th>

                <th><?php echo $this->Paginator->sort('views_count',__('Views')); ?></th>
                <th><?php echo $this->Paginator->sort('created', __('Created')); ?></th>
                <th><?php echo $this->Paginator->sort('modified', __('Modified')); ?></th>
                <th><?php echo $this->Paginator->sort('rows_count',__('Entries')); ?></th>
                <th><?php echo $this->Paginator->sort('type', __('Type')); ?></th>
                <th><?php echo $this->Paginator->sort('completed',__('Completed')); ?></th>

                <th><?php echo __('Options'); ?></th>
            </tr>

            <?php foreach ($feeds as $feed): ?>
            <tr>

                <td><?php echo $this->Html->link(
                        $feed['Feed']['name'],
                        array(
                            'action'=>'view',
                            $feed['Feed']['id']
                        ),
                        array(
                            'class' => $feed['Feed']['type'] == 'free' ? '' : 'link-premium',
                            'title' => $this->Html->url('/', true) . 'api/'. h($feed['Feed']['slug'])  . ($feed['Feed']['type'] == 'free' ? '' : ' (premium feed)')
                        )
                    )
                    ?>
                </td>

                <td><?php echo h($feed['Feed']['views_count']); ?></td>
                <td><?php echo h($this->Time->format($feed['Feed']['created'])); ?></td>
                <td><?php echo h($this->Time->format($feed['Feed']['modified'])); ?></td>

                <td><?php echo h($feed['Feed']['rows_count']); ?></td>

                <td>
                    <?php
                        if ($feed['Feed']['type'] == 'free'):
                            $icon = 'eye-open';
                            $action = array('action' => 'toggle','type', $feed['Feed']['id']);
                            $title = __("Switch repository to premium");
                        else:
                            $icon = 'shopping-cart';
                            $action = array('action' => 'toggle','type', $feed['Feed']['id']);
                            $title = __("Switch repository to free");
                        endif;

                        echo $this->Html->link(sprintf('<i class="icon icon-%s"></i>', $icon), $action, array('escape'=>false, 'title'=> $title))
                    ?>
                </td>

                <td>
                    <?php
                    if ($feed['Feed']['completed']):
                        $icon = 'ok-sign';
                        $action = array('action' => 'toggle','completed', $feed['Feed']['id']);
                        $title = __("Mark repository as NOT completed");
                    else:
                        $icon = 'ok-circle';
                        $action = array('action' => 'toggle','completed', $feed['Feed']['id']);
                        $title = __("Mark repository as completed");
                    endif;

                    echo $this->Html->link(sprintf('<i class="icon icon-%s"></i>', $icon), $action, array('escape'=>false,'title'=>$title))
                    ?>
                </td>



                <td>
                    <?php echo $this->Form->postLink(
                        '<i class="icon-trash"></i>',
                        array(
                            'action' => 'delete',
                            $feed['Feed']['id']
                        ),
                        array(
                            'escape'=>false,
                            'title'=> __('Deletes the repository (if no premium users)')
                        ),
                        __('Are you sure you want to delete %s?', $feed['Feed']['name'])
                    ); ?>

                    <?php echo $this->Html->link(
                        '<i class="icon-pencil"></i>',
                        array(
                            'action'=>'edit',
                            $feed['Feed']['id']
                        ),
                        array(
                            'escape'=>false,
                            'title'=> __('Edit the repository')
                        )
                    ) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

    <?php echo $this->element('paginator'); ?>

    <?php endif ?>
</div>

<?php
/**
 * In this shell we simply recalculate the row count for each feed.
 */
class RecalculateShell extends Shell
{
    public $uses = array('Schema', 'Feed', 'Row');

    function main()
    {
        foreach ($this->Feed->find('all') as $feed) {
            $count = $this->Row->find(
                'count',
                array(
                    'conditions' => array(
                        'feed_id' => (int)$feed['Feed']['id']
                    )
                )
            );

            $this->out('Feed #' . $feed['Feed']['id'] . '=' . $count);

            $this->Feed->id = $feed['Feed']['id'];
            $this->Feed->saveField('rows_count', $count);
        }
    }
}

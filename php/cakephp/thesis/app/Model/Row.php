<?php
App::uses('AppModel', 'Model');

/**
 * This class represents an abstraction layer between API and database tables.
 *
 * @property Feed $Feed
 * @property Value $Value
 */
class Row extends AppModel
{

    public $useDbConfig = 'mongo';

    public $safeDelete = false;

    public $belongsTo = array(
        'Schema' => array(
            'className' => 'Schema',
            'foreignKey' => 'feed_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * This methods imports a CSV file to a MongoDB Row collection
     *
     * @param $filename
     * @param $feed_id
     * @return bool
     */
    public function import($filename, $feed_id)
    {
        $handle = fopen($filename, "r");
        $header = fgetcsv($handle); // headings

        $schema = $this->getSchema($feed_id);
        $i = 0;
        $return = array();
        while (($row = fgetcsv($handle)) !== false) {
            $i++;
            $data = array();
            $data['Row'] = $schema;

            foreach ($header as $k => $head) {
                if (isset($schema[$head])) {
                    $data['Row'][$head] = (isset($row[$k])) ? $row[$k] : '';
                }
            }

            $this->create();
            $data['Row']['feed_id'] = $feed_id;
            $this->set($data);
            $this->save($data);
        }
        fclose($handle);
        return true;
    }

    /**
     * Returns the schema so when importing, you can only import existing fields
     *
     * @param $feed_id
     * @return mixed
     */
    public function getSchema($feed_id)
    {
        $schema = $this->Schema->find(
            'first',
            array(
                'conditions' => array(
                    'id' => (int)$feed_id
                ),
                'fields' => array('_id' => 0)
            )
        );

        return $schema['Schema'];
    }
}
	

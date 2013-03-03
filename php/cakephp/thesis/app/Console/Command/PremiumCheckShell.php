<?php
/**
 * We recount here requests that user has left, because everytime
 * the user requests the API with certain key, request decrementation is only made
 * in memcached to improve performance, we need to recount that and update MySQL
 *
 * @author Marcin Wawrzyniak
 */
class PremiumCheckShell extends Shell
{
    public $uses = array('Key');

    protected $memcache;

    function startup()
    {
        $this->memcache = new Memcache();
        $this->memcache->addServer("127.0.0.1");
    }

    function main()
    {
        $totalFeeds = $this->Key->find('count');
        $step = 1000;

        $this->log("PremiumCheckShell starts...", LOG_DEBUG);
        $this->log("PremiumCheckShell feeds: " . $totalFeeds, LOG_DEBUG);
        $this->log("PremiumCheckShell step: " . $step, LOG_DEBUG);

        // let's split the data into portions to save memory
        for ($i = 0; $i < $totalFeeds; $i = $i + $step) {
            $keys = $this->Key->find(
                'all',
                array(
                    'fields' => array('id', 'key', 'requests'),
                    'limit' => $step,
                    'offset' => $i,
                    'contain' => array('Feed' => array('fields' => 'slug'))
                )
            );

            $this->log("PremiumCheckShell processing from " . ($i + 1) . " to " . $step, LOG_DEBUG);

            foreach ($keys as $key) {
                $requests = $this->memcache->get($key['Feed']['slug'] . '/' . $key['Key']['key']);

                // if such key wasn't found, there was some problem with memcached
                if ($requests === false) {
                    // just put the data into memcached so it
                    // wont happen next time
                    $cacheKey = $key['Feed']['slug'] . '/' . $key['Key']['key'];
                    $this->memcache->set($cacheKey, $key['Key']['requests']);
                }

                //if everythings fine, just update MySQL
                $this->Key->id = $key['Key']['id'];
                $this->Key->saveField('requests', $requests);
            }

            if (empty($keys)) {
                $this->log("PremiumCheckShell no more keys, last ID " . $key['Key']['id'], LOG_DEBUG);
                break;
            }

        }

    }
}

<?php
class Memcached
{
    public static $memcache = null;
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$memcache === null) {
            self::$memcache = new Memcache();
            self::$memcache->addServer("127.0.0.1");
        }

        if (self::$instance === null){
            self::$instance = new Memcached();
        }

        return self::$instance;
    }

    public function get($key)
    {
        return self::$memcache->get($key);

    }
    public function set($key, $value)
    {
        return self::$memcache->set($key, $value);

    }
    public function delete($key)
    {
        return self::$memcache->delete($key);
    }
    public function decrement($key)
    {
        return self::$memcache->decrement($key);
    }
    public function increment($key)
    {
        return self::$memcache->increment($key);
    }

}
?>
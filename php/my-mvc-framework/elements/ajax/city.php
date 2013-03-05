<?php

require "../../library/OddCore.php"; //autoload stuff
require "../../library/Odd/Config.php";
require "../../library/Odd/MySql.php";
$config = new Config('../../config/application.ini');

$db = new MySql($config->db_user,
                                      $config->db_password,
                                      $config->db_host,
                                      $config->db_database);
?>
<ul>
<?php
if (empty($_GET['string'])) die();
$cities = $db->proc("SELECT id, name FROM cities WHERE name LIKE '".$_GET['string']."%' LIMIT 20;");
foreach($cities as $city):
?>
<li onclick="document.getElementById('city').value=this.innerHTML;dojo.byId('dropdown').innerHTML='';" value="<?php echo $city['id']; ?>"><?php echo $city['name']; ?></li>
<?php endforeach; ?>
</ul>
<?php
require_once("./library/idiorm.php");

ORM::configure('mysql:host=localhost;dbname=scrap;');
ORM::configure('username', 'root');
ORM::configure('password', '');

$sort = 'url';
if (isset($_GET['sort'])) {
	if (in_array($_GET['sort'], array('script','pr'))) {
		$sort = $_GET['sort'];
	}
}
?>

<html>
<head>
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
</head>
<body>
<table id="sort" class="table table-striped">
	<thead>
		<tr>
			<th><a href="./catalogue.php">URL</a></th>
			<th><a href="./catalogue.php?sort=pr">PR</a></th>
			<th><a href="./catalogue.php?sort=script">Skrypt</a></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach(ORM::for_table('urls')->order_by_desc($sort)->find_many() as $model): ?>
			<?php echo sprintf('<tr><td>%s</td><td>%s</td><td>%s</td></tr>', $model->url, $model->pr, $model->script); ?>
		<?php endforeach ?>
	</tbody>
</table>
</body>

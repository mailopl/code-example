<head>
    <base href="http://localhost/odd/" />
    <script src="http://ajax.googleapis.com/ajax/libs/dojo/1.3.2/dojo/dojo.xd.js"></script>
    <link rel="stylesheet" href="./elements/main.css" type="text/css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="./elements/js/main.js"></script>
</head>

<div style="padding:5px; width:50%; float:right;bordeR:1px solid #d3d3d3; color:gray;font-family:tahoma;">
	<a style="color: #3399FF" onclick="document.getElementById('session').style.display='block';document.getElementById('get').style.display='none';document.getElementById('post').style.display='none';document.getElementById('debug').style.display='none';">SESSION</a>
	<a style="color: #3399FF" onclick="document.getElementById('get').style.display='block';document.getElementById('post').style.display='none';document.getElementById('session').style.display='none';document.getElementById('debug').style.display='none';">GET</a>
	<a style="color: #3399FF"onclick="document.getElementById('post').style.display='block';document.getElementById('get').style.display='none';document.getElementById('session').style.display='none';document.getElementById('debug').style.display='none';">POST</a>
	<a style="color: #3399FF" onclick="document.getElementById('debug').style.display='block';document.getElementById('get').style.display='none';document.getElementById('session').style.display='none';document.getElementById('post').style.display='none';">DEBUG</a> | Total queries time: <?php global $totalExecutionTime;echo $totalExecutionTime; ?> s
	<div id="session" style="display:none;"><?php echo '<pre>';print_r($_SESSION); echo '</pre>';?></div>
	<div id="get" style="display:none;"><?php echo '<pre>';print_r($_GET); echo '</pre>';?></div>
	<div id="post" style="display:none;"><?php echo '<pre>';print_r($_POST); echo '</pre>';?></div>
	<div id="debug" style="display:none;"><?php echo '<pre>';global $debug;print_r($debug); echo '</pre>';?></div>
</div>

<?php echo $this->pageContent; ?>
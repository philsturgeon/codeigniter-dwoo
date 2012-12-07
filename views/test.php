<html>
<head>
		<title>Dwoo Test</title>
</head>
<body>
<?php
error_reporting(E_ALL);

$attributes['selected'] = 'something';

$f = array_values((array) (empty($attributes['selected']) ? false : $attributes['selected']));
var_dump($f);
?>
</body>
</html>
<?php

function hsc($string)
{
    return htmlspecialchars($string, ENT_COMPAT, "UTF-8");
}

function csvToGC($path)
{
	if (($handle = fopen($path, 'r')) === false) {
		die('Error opening file');
	}
	//$s_count = 0;
	$series = array();
	$headers = fgetcsv($handle, 1024, ',');
	$complete = array();

	$series[] = $headers[0];
	
	while ($row = fgetcsv($handle, 1024, ',')) {
		$complete[] = array_combine($headers, $row);
		$series[] = $row[0];
	}
	
	$string = '';
	$temp = array();
	for($i = 0; $i < count($complete); $i++)
	{
		foreach ($complete[$i] as $key => $value) {
			$temp[$key][$i] = $value;
		}
	}
	
	$t = 1;
	foreach ($temp as $key => $value) {
		$string .= '[\'' . $key . '\'';
		for($i = 0; $i < count($temp[$key]); $i++)
		{
			if($t == 1) $string .= ', \'' . $temp[$key][$i] . '\'';
			else $string .= ', ' . $temp[$key][$i];
		}
		if($t != count($temp)) $string .= '], ';
		else $string .= ']';
		$t++;
	}
	fclose($handle);
	//return $string;
	//return $complete;
	return array($string, $series);
}

?>

<?php
/**
 * @param string $path
 * return string
 * 
 */

 function basePath($path = ''){
	return __DIR__ . '/' . $path;
 }

 /**
  * @param string $name
  * return void
  */
  function loadView($name, $data = []) {
	$filePath = basePath("App/views/{$name}.view.php");
	if(file_exists($filePath)) {
		extract($data);
		require $filePath;
	}
	else {
		?>
			<div>
				<p><?= $name; ?> file not found</p>
			</div>
		</div>
		<?php
	}
}

function loadPartials($name) {
	$filePath = basePath("App/views/partials/{$name}.php");
	if(file_exists($filePath)) {
		require $filePath;
	}
	else {
		?>
			<div>
				<p><?= $name; ?> file not found</p>
			</div>
		</div>
		<?php
	}
}


/**
 * @param mixed $value
 * return void
 * 
 */
function debug($value) {
	echo '<pre>';
		var_dump($value);
	echo '</pre>';
}
function debugDie($value) {
	echo '<pre>';
		die(var_dump($value));
	echo '</pre>';
}
function formatSalary($salary) {
    return "$" . number_format(floatval($salary));
}

function dataFilter($data) {
	return filter_var(trim($data), FILTER_SANITIZE_SPECIAL_CHARS);
}

function redirect($url) {
	header("Location:{$url}");
	return;
}


?>


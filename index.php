<?php
	function generateName()
	{
		$retVal = '';
		for ($x = 0; $x < 16; $x++) {
			$retVal .= chr(65 + rand(0, 23));
		}
		return $retVal;
	}

	switch ($_SERVER['REQUEST_METHOD']) {
		case 'POST':
			if (count($_FILES) > 0) {
				do {
					$name = generateName();
				} while (file_exists('../uploads/' . $name));
				move_uploaded_file($_FILES['data']['tmp_name'], '../uploads/' . $name);
				$fp = fopen('../uploads/' . $name . '.type', 'w');
				fwrite($fp, $_FILES['data']["type"]);
				fclose($fp);
				echo json_encode([
					'path' => 'https://www.ahrotahntee.ca/u/' . $name,
					'delete' => 'https://www.ahrotahntee.ca/u/' . $name . '?delete=' . md5(file_get_contents('../uploads/' . $name))
				]);
			}
			break;

		case 'GET':
			$path = preg_replace("/[^A-Za-z0-9]/", '', $_SERVER['PATH_INFO']);
			if (!file_exists('../uploads/' . $path)) {
				http_response_code(404);
				die();
			}
			if (array_key_exists('delete', $_GET)) {
				if ($_GET['delete'] === md5(file_get_contents('../uploads/' . $path))) {
					unlink('../uploads/' . $path);
					unlink('../uploads/' . $path . '.type');
					header('Content-Type: text/plain');
					die('File Deleted');
				}
			}
			header('Content-Type: ' . file_get_contents('../uploads/' . $path . '.type'));
			echo file_get_contents('../uploads/' . $path);
			die();
	}


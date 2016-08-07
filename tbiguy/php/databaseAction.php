<?php
	include "commonFunctions.php";

	if ($_POST['action'] == "textSave"){
		$itemsToSaveArray = $_POST['changedText'];
		
		$conn = getConnection();
		
		foreach ($itemsToSaveArray as $row) {
			file_put_contents('php://stderr', print_r(key($row), TRUE));
			$stmt = $conn->prepare('UPDATE tbi_guy.text_elements SET text=? WHERE element_id=?;');
			$stmt->bind_param('si', $conn->real_escape_string($row[key($row)]), key($row));
			$stmt->execute();
		}
		
		$conn->close();
		
		reCacheText();
	}
?>
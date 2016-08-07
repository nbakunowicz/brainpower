<?php
	//Constants *All constants will start with "C_" and be uppercase
	define("C_SPACE", " ");
	define ("ROOT_PATH", $_SERVER['DOCUMENT_ROOT']);

	//This function gets a DB Connection
	function getConnection() {
		$env =  parse_ini_file("env.ini");
		return mysqli_connect($env['dbHost'], $env['dbUser'], $env['dbPass'], $env['dbDB']);
	}
	
	//returns text for an element Id
	function getDisplayText($txtElementId){
		$returnText = "No Data Found";
		
		//load the cached text into an array if not already there
		if (empty($GLOBALS['cachedText'])){
			$cachedText =  file_exists(ROOT_PATH . "/php/textCache.ini") ? parse_ini_file(ROOT_PATH . "/php/textCache.ini") : reCacheText();
			$GLOBALS['cachedText'] = $cachedText;
		}
		
		if (!empty($GLOBALS['cachedText'][$txtElementId])){
			$returnText = giveSpan($GLOBALS['cachedText'][$txtElementId], $txtElementId);
		}
		
		return $returnText;
	}
	
	function giveSpan($text, $textId){
		return '<span id="text' . $textId . '" class="editable"> ' . $text . '</span>';
	}
	
	function reCacheText() {
		$conn = getConnection();
	
		// prepare & execute
		$sql = "Select * from tbi_guy.text_elements;";
		$result = mysqli_query($conn,$sql);

		//process result set
		if ($result->num_rows > 0) {
			$textKeyValue;
			$textFile = fopen(ROOT_PATH . "/php/textCache.ini", "w");
	
			//save all the items in the DB to a file
			while($row = $result->fetch_assoc()) {
				$textKeyValue = $row['element_id'] . ' = "' . $row['text']. '"' . "\r\n";
				fwrite($textFile, $textKeyValue);
			}
				
			fclose($textFile);
		}
		$conn->close();
		return parse_ini_file("textCache.ini");
	}

	function storeDisplayText(){
		
		
	}
?>
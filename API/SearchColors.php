<?php

	$inData = getRequestInfo();

	if (
    	!isset($inData['search'], $inData['userId'])
    	|| !is_string($inData['search'])
    	|| (!is_string($inData['userId']) && !is_int($inData['userId']))
	) {
    returnWithError('Invalid search request');
    exit;
	}

	$searchResults = [];
	//Any login credentials replaced with placeholders
	$conn = new mysqli("placeholder", "placeholder", "placeholder", "placeholder"); 
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$stmt = $conn->prepare("select Name from Colors where Name like ? and UserID=?");
		$colorName = "%" . $inData["search"] . "%";
		$stmt->bind_param("ss", $colorName, $inData["userId"]);
		$stmt->execute();
		
		$result = $stmt->get_result();
		
		while ($row = $result->fetch_assoc()) {
    		$searchResults[] = (string) $row['Name'];
		}

		if ($searchResults === []) {
    		returnWithError('No Records Found');
		} else {
    		returnWithSearchInfo($searchResults);
		}
		
		$stmt->close();
		$conn->close();
	}

	function getRequestInfo(): array
	{
    	$input = file_get_contents('php://input');

    	if ($input === false) {
        	return [];
    	}

    	$data = json_decode($input, true);

    	return is_array($data) ? $data : [];
	}

	function sendResultInfoAsJson(string $json): void
	{
    	header('Content-type: application/json');
    	echo $json;
	}

	function returnWithError(string $error): void
	{
    	sendResultInfoAsJson(json_encode(
        	['id' => 0, 'firstName' => '', 'lastName' => '', 'error' => $error],
        	JSON_THROW_ON_ERROR
    	));
	}

	function returnWithSearchInfo(array $searchResults): void
	{
    	sendResultInfoAsJson(json_encode(
        	['results' => $searchResults, 'error' => ''],
        	JSON_THROW_ON_ERROR
    	));
	}
	
?>

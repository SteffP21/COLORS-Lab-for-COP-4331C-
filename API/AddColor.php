<?php
	$inData = getRequestInfo();

	if (
    	!isset($inData['color'], $inData['userId'])
    	|| !is_string($inData['color'])
    	|| (!is_string($inData['userId']) && !is_int($inData['userId']))
	) {
    	returnWithError('Invalid color request');
    	exit;
	}

	$color = $inData['color'];
	$userId = (string) $inData['userId'];

	//Any login credentials replaced with placeholders
	$conn = new mysqli("placeholder", "placeholder", "placeholder", "placeholder"); 
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$stmt = $conn->prepare("INSERT into Colors (UserId,Name) VALUES(?,?)");
		$stmt->bind_param("ss", $userId, $color);
		$stmt->execute();
		$stmt->close();
		$conn->close();
		returnWithError("");
	}

	function getRequestInfo(): array
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson(string $json): void
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError(string $error): void
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
?>

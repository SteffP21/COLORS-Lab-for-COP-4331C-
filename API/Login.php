
<?php

	$inData = getRequestInfo();

	if (
    	!isset($inData['login'], $inData['password'])
    	|| !is_string($inData['login'])
    	|| !is_string($inData['password'])
	) {
    	returnWithError('Invalid login request');
    	exit;
	}
	
	//Any login credentials replaced with placeholders
	$conn = new mysqli("placeholder", "placeholder", "placeholder", "placeholder"); 	
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$stmt = $conn->prepare("SELECT ID,firstName,lastName FROM Users WHERE Login=? AND Password =?");
		$stmt->bind_param("ss", $inData["login"], $inData["password"]);
		$stmt->execute();
		$result = $stmt->get_result();

		if( $row = $result->fetch_assoc()  )
		{
			returnWithInfo((string) $row['firstName'], (string) $row['lastName'], (int) $row['ID']);
		}
		else
		{
			returnWithError("No Records Found");
		}

		$stmt->close();
		$conn->close();
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
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo(string $firstName, string $lastName, int $id): void
	{
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>

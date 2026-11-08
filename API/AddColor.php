<?php

$inData = getRequestInfo();

$color = (string) ($inData["color"] ?? "");
$userId = (string) ($inData["userId"] ?? "");

$dbHost = getenv("DB_HOST");
$dbHost = $dbHost === false ? "localhost" : $dbHost;

$dbUser = getenv("DB_USER");
$dbUser = $dbUser === false ? "null" : $dbUser;

$dbPassword = getenv("DB_PASSWORD");
$dbPassword = $dbPassword === false ? "null" : $dbPassword;

$dbName = getenv("DB_NAME");
$dbName = $dbName === false ? "COP4331" : $dbName;

$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
if ($conn->connect_error !== null) {
    returnWithError($conn->connect_error);
} else {
    $stmt = $conn->prepare("INSERT into Colors (UserId,Name) VALUES(?,?)");
    $stmt->bind_param("ss", $userId, $color);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    returnWithError("");
}

/**
 * @return array<array-key, mixed>
 */
function getRequestInfo(): array
{
    $requestBody = file_get_contents("php://input");
    if ($requestBody === false) {
        return [];
    }

    $requestData = json_decode($requestBody, true);

    return is_array($requestData) ? $requestData : [];
}

function sendResultInfoAsJson(string $obj): void
{
    header("Content-type: application/json");
    echo $obj;
}

function returnWithError(string $err): void
{
    $retValue = json_encode(["error" => $err]);
    if ($retValue !== false) {
        sendResultInfoAsJson($retValue);
    }
}

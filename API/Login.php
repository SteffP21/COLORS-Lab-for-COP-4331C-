<?php

$inData = getRequestInfo();

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
    $login = (string) ($inData["login"] ?? "");
    $password = (string) ($inData["password"] ?? "");
    $stmt = $conn->prepare("SELECT ID,firstName,lastName FROM Users WHERE Login=? AND Password =?");
    $stmt->bind_param("ss", $login, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    $row = $result->fetch_assoc();
    if ($row !== null && $row !== false) {
        returnWithInfo(
            (string) $row["firstName"],
            (string) $row["lastName"],
            (int) $row["ID"]
        );
    } else {
        returnWithError("No Records Found");
    }

    $stmt->close();
    $conn->close();
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
    $retValue = json_encode([
        "id" => 0,
        "firstName" => "",
        "lastName" => "",
        "error" => $err,
    ]);
    if ($retValue !== false) {
        sendResultInfoAsJson($retValue);
    }
}

function returnWithInfo(string $firstName, string $lastName, int $id): void
{
    $retValue = json_encode([
        "id" => $id,
        "firstName" => $firstName,
        "lastName" => $lastName,
        "error" => "",
    ]);
    if ($retValue !== false) {
        sendResultInfoAsJson($retValue);
    }
}

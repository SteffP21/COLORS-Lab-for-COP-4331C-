<?php

$inData = getRequestInfo();

$searchResults = [];
$conn = new mysqli(
    getenv("DB_HOST") ?: "localhost",
    getenv("DB_USER") ?: "",
    getenv("DB_PASSWORD") ?: "",
    getenv("DB_NAME") ?: "COP4331"
);
if ($conn->connect_error !== null) {
    returnWithError($conn->connect_error);
} else {
    $search = (string) ($inData["search"] ?? "");
    $userId = (string) ($inData["userId"] ?? "");
    $stmt = $conn->prepare("select Name from Colors where Name like ? and UserID=?");
    $colorName = "%" . $search . "%";
    $stmt->bind_param("ss", $colorName, $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    while (($row = $result->fetch_assoc()) !== null && $row !== false) {
        $searchResults[] = (string) $row["Name"];
    }

    if ($searchResults === []) {
        returnWithError("No Records Found");
    } else {
        returnWithInfo($searchResults);
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

/**
 * @param array<int, string> $searchResults
 */
function returnWithInfo(array $searchResults): void
{
    $retValue = json_encode([
        "results" => $searchResults,
        "error" => "",
    ]);
    if ($retValue !== false) {
        sendResultInfoAsJson($retValue);
    }
}

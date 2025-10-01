<?php
// Database connection
$host = "localhost";
$user = "root"; 
$pass = "";     
$db   = "daily_pulse";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

// Read JSON from frontend
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["employee_id"], $data["question"], $data["answer"])) {
    $employee_id = intval($data["employee_id"]);
    $question    = $conn->real_escape_string($data["question"]);
    $answer      = $conn->real_escape_string($data["answer"]);

    // Insert response
    $sql = "INSERT INTO pulse_responses (employee_id, question, answer) 
            VALUES ($employee_id, '$question', '$answer')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Response saved"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
}

$conn->close();
?>

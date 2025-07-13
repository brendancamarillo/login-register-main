<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require 'database.php';

$user_id = $_SESSION["user"]["id"];  // make sure you store 'id' in session
$today = date('Y-m-d');

// Check if already tapped today
$sql = "SELECT * FROM attendance WHERE user_id = ? AND date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $today);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION["attendance_message"] = "You already tapped attendance today!";
} else {
    // Insert attendance
    $insertSql = "INSERT INTO attendance (user_id, date, status) VALUES (?, ?, 'Present')";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("is", $user_id, $today);
    if ($insertStmt->execute()) {
        $_SESSION["attendance_message"] = "Attendance recorded successfully!";
    } else {
        $_SESSION["attendance_message"] = "Error saving attendance.";
    }
    $insertStmt->close();
}

$stmt->close();
$conn->close();

header("Location: index.php");
exit();

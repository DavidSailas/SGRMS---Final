<?php
include '../../../Database/db_connect.php';

if (!isset($_GET['c_id']) || empty($_GET['c_id'])) {
    die("Invalid Counselor ID.");
}

$c_id = intval($_GET['c_id']);
$sql = "SELECT * FROM counselors WHERE c_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $c_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Counselor not found.");
}

$counselor = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lname = trim($_POST['lname']);
    $fname = trim($_POST['fname']);
    $mname = trim($_POST['mname']);
    $email = trim($_POST['email']);
    $contact_num = trim($_POST['contact_num']);
    $c_level = $_POST['c_level'];

    // Validate Required Fields
    if (empty($lname) || empty($fname) || empty($email) || empty($contact_num) || empty($c_level)) {
        die("All fields are required.");
    }

    // Update counselor record
    $sql = "UPDATE counselors SET lname=?, fname=?, mname=?, email=?, contact_num=?, c_level=? WHERE c_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $lname, $fname, $mname, $email, $contact_num, $c_level, $c_id);

    if ($stmt->execute()) {
        echo "Counselor updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
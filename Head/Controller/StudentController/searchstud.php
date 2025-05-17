<?php
include '../../../Database/db_connect.php';

$sql = "SELECT s_id, id_num, prefix, lname, fname, mname, bod, educ_level, section, program FROM students";
$conditions = [];
$params = [];
$types = "";

// Process the search term
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $conditions[] = "(id_num LIKE ? OR lname LIKE ? OR fname LIKE ? OR mname LIKE ?)";
    $likeTerm = "%$searchTerm%";
    $params[] = $likeTerm;
    $params[] = $likeTerm;
    $params[] = $likeTerm;
    $params[] = $likeTerm;
    $types .= "ssss";
}

// Process the educational level filter
if (isset($_GET['filter_educ']) && !empty($_GET['filter_educ'])) {
    $conditions[] = "educ_level = ?";
    $params[] = $_GET['filter_educ'];
    $types .= "s";
}

// Build the final SQL query if conditions exist
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $result = $conn->query($sql);
}

$output = "";
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bod = $row['bod'];
        $birthdate = new DateTime($bod);
        $today = new DateTime();
        $age = $today->diff($birthdate)->y;
        
        $output .= "<tr>
            <td><span class='status-circle green'></span></td>
            <td>".$row['id_num']."</td>
            <td>".$row['prefix']." ".$row['lname'].", ".$row['fname']." ".$row['mname']."</td>
            <td>".$age."</td>
            <td>".$row['educ_level']."</td>
            <td>".(!empty($row['section']) ? $row['section'] : $row['program'])."</td>
            <td>
                <button class='btn btn-view' onclick='viewStudent(".$row['s_id'].")'>View</button>
                <button class='btn btn-edit' onclick='editStudent(".$row['s_id'].")'>Edit</button>
                <button class='btn btn-delete' onclick='openDeleteConfirmationModal(".$row['s_id'].")'>Delete</button>
            </td>
        </tr>";
    }
} else {
    $output .= "<tr><td colspan='7'>No students found</td></tr>";
}

echo $output;
?>

<?php
include '../../../Database/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $id_num = $_POST['id_num']; // Ensure this is fetched from the form
    $prefix = $_POST['prefix'];
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $bod = $_POST['bod'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $mobile_num = $_POST['mobile_num'];
    $email = $_POST['email'];
    $educ_level = $_POST['educ_level'];
    $year_level = $_POST['year_level'];
    $section = $_POST['section'];
    $program = $_POST['program'];
    $previous_school = $_POST['previous_school'];  
    $last_year_school = $_POST['last_year_school'];

    // Handle image upload
    $default_image = '/Public/stud.img/default.png';
    $image = $_FILES['image']['name'];
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/Public/stud.img/'; 
    $target_file = $target_dir . basename($image);

    // Check if an image was uploaded
    if (!empty($image)) {
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image);
        $image_path = '/Public/stud.img/' . $image; 
    } else {
        $image_path = $default_image; 
    }

    // Insert into database
    $sql = "INSERT INTO students (id_num, prefix, lname, fname, mname, bod, gender, 
            address, mobile_num, email, educ_level, year_level, section, program, 
            s_image, previous_school, last_year_school) 
            VALUES ('$id_num', '$prefix', '$lname', '$fname', '$mname', '$bod', '$gender',
            '$address', '$mobile_num', '$email', '$educ_level', '$year_level', '$section',
            '$program', '$target_file', '$previous_school', '$last_year_school')";

    if ($conn->query($sql) === TRUE) {
       
        header("Location: ../View/students.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

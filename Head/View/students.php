<?php
session_start();
include '../../Database/db_connect.php';

$activitySql = "SELECT activity, timestamp FROM activity_logs ORDER BY timestamp DESC LIMIT 10";
$activityResult = $conn->query($activitySql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>School Guidance Record Management System</title>
    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="stylesheet" href="../../CSS/bar.css">
    <link rel="stylesheet" href="../../CSS/table.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<!-- SIDEBAR -->
<section id="sidebar">
    <a href="#" class="brand">
        <span class="text">SGRMS</span>
    </a>
    <ul class="side-menu top">
        <li class="active">
            <a href="dashboard.php">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="#" id="profiling-link">
                <i class='bx bxs-user'></i>
                <span class="text">Profiling</span>
                <i class='bx bx-chevron-down' style="margin-left:auto;"></i> 
            </a>
        </li> 
        <ul class="submenu" id="profiling-submenu">
              <li >
                <a href="counsel.php">
                    <i class='bx bxs-user-voice'></i> 
                        <span class="text">Counselors</span>
                    </a>
            </li>
            <li>
                <a href="parents.php">
                    <i class='bx bxs-chalkboard'></i> 
                    <span class="text">Parents</span>
                </a>
            </li>
            <li>
                <a href="students.php">
                    <i class='bx bxs-graduation'></i> 
                    <span class="text">Students</span>
                </a>
            </li>
        </ul>
        <li>
            <a href="case.php">
                <i class='bx bxs-report'></i>
                <span class="text">Reports</span>
            </a>
        </li>
        <li>
            <a href="appoint.php">
                <i class='bx bxs-calendar'></i>
                <span class="text">Appointments</span>
            </a>
        </li>
    </ul>
    <ul class="side-menu">
        <li>
            <a href="settings.php">
                <i class='bx bxs-cog'></i>
                <span class="text">Settings</span>
            </a>
        </li>
        <li>
            <a href="../../Login/index.php" class="logout">
                <i class='bx bxs-log-out-circle'></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const profilingLink = document.getElementById('profiling-link');
        const profilingSubmenu = document.getElementById('profiling-submenu');

        profilingLink.addEventListener('click', function() {
            profilingSubmenu.classList.toggle('active');
        });
    });
</script>

<section id="content">
        <!-- NAVBAR -->
    <nav>
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">Welcome, Admin</a>
        <form action="#">
            <div class="form-input">
                <input type="search" placeholder="Search...">
                <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
            </div>
        </form>
        <input type="checkbox" id="switch-mode" hidden>
        <label for="switch-mode" class="switch-mode" aria-label="Switch Dark/Light Mode"></label>
        <a href="#" class="notification"><i class='bx bxs-bell'></i><span class="num">8</span></a>
        <a href="#" class="profile"><img src="../../Public/user.img/people.png" alt="Profile"></a>
    </nav>

    <main class="wrapper">
        <div class="card">
        <section class="student-list">
            <div class="search-flex">
                <h2>Student List</h2>                       
                <div class="search-bar">
                    <input type="text" id="search" name="search" class="search" placeholder="Search by ID or Name">
                    <select name="filter_educ" id="filter_educ">
                        <option value="">All Levels</option>
                        <option value="Elementary">Elementary</option>
                        <option value="High School">High School</option>
                        <option value="College">College</option>
                    </select>
                     <button class="btn btn-add" onclick="openAddModal()">Add Student</button>
                </div>
               
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Educational Level</th>
                            <th>Section/Program</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        <?php
                            $sql = "SELECT s_id, id_num, prefix, lname, fname, mname, bod, educ_level, 
                            section, program FROM students";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $bod = $row['bod'];
                                    $birthdate = new DateTime($bod);
                                    $today = new DateTime();
                                    $age = $today->diff($birthdate)->y;

                                    echo "<tr>                                           
                                        <td><span class='status-circle green'></span></td>
                                        <td>".$row['id_num']."</td>
                                        <td>".$row['prefix']." ".$row['lname'].", ".$row['fname']." ".$row['mname']."</td>
                                        <td>".$age."</td>
                                        <td>".$row['educ_level']."</td>
                                        <td>".(!empty($row['section']) ? $row['section'] : $row['program'])."</td>
                                        <td>
                                            <button class='btn btn-view' onclick='viewStudent(".$row['s_id'].")'>View</button>
                                            <button class='btn btn-edit' onclick='openEditModal(".$row['s_id'].")'>Edit</button>
                                            <button class='btn btn-delete' onclick='openDeleteConfirmationModal(".$row['s_id'].")'>Delete</button>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No students found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
        </div>
    </main>
</section>

<?php include 'Modal/studModal.php'; ?>

<!-- SCRIPTS -->
<script src="../../Script/head.js"></script>
<script src="../../Script/searchfilter.js"></script>
<script src="../../Script/Modal/studModal.js"></script>
</body>
</html>
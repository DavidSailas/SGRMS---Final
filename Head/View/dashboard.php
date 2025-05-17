<?php
session_start();
include '../../Database/db_connect.php';

$activitySql = "SELECT activity, timestamp FROM activity_logs ORDER BY timestamp DESC LIMIT 10";
$activityResult = $conn->query($activitySql);

// Get all case types
$caseTypes = [];
$res = $conn->query("SELECT DISTINCT case_type FROM case_records WHERE case_type IS NOT NULL");
while ($row = $res->fetch_assoc()) {
    $caseTypes[] = $row['case_type'];
}

// Prepare data: [case_type][month] = count
$caseTypeData = [];
foreach ($caseTypes as $type) {
    $caseTypeData[$type] = array_fill(1, 12, 0);
    $sql = "SELECT MONTH(filed_date) AS month, COUNT(*) AS total 
            FROM case_records 
            WHERE case_type = '$type' AND filed_date IS NOT NULL 
            GROUP BY MONTH(filed_date)";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $caseTypeData[$type][(int)$row['month']] = (int)$row['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Student Guidance Record Management System</title>
    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="stylesheet" href="../../CSS/bar.css">
    <link rel="stylesheet" href="../../CSS/dashboard.css">
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

<!-- CONTENT -->
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

    <div class="wrapper">


        <!-- HEAD -->
        <div class="head-title">
            <div class="left"><h1>Dashboard</h1></div>
        </div>


        <!-- STATS -->
        <section class="stats">
            <?php
                $entities = ['case_records' => 'Cases', 'students' => 'Students', 'teachers' => 'Teachers', 'counselors' => 'Counselors'];
                foreach ($entities as $table => $label) {
                    $result = $conn->query("SELECT COUNT(*) AS total FROM $table");
                    $count = $result ? $result->fetch_assoc()['total'] : "Error";
                    echo "<div class='stat-box'><h2>$label</h2><p>$count</p></div>";
                }
            ?>
        </section>


        <!-- CHART + ACTIVITIES -->
        <div class="box-page">


            <!-- CHART -->
            <section class="analytics">
                <h2>Case Report Analytics</h2>
                <canvas id="caseChart"></canvas>
                <?php
                    $caseData = array_fill(1, 12, 0);
                    $result = $conn->query("SELECT MONTH(filed_date) AS month, COUNT(*) AS total FROM case_records WHERE filed_date IS NOT NULL GROUP BY MONTH(filed_date)");
                    while ($row = $result->fetch_assoc()) {
                        $caseData[intval($row['month'])] = $row['total'];
                    }
                ?>
                <script>
                    const caseData = <?php echo json_encode(array_values($caseData)); ?>;
                </script>
                <script>
                    const caseTypeData = <?php echo json_encode($caseTypeData); ?>;
                </script>
                <script>
                    const datasets = Object.keys(caseTypeData).map((type, index) => ({
                        label: type,
                        data: caseTypeData[type],
                    }));
                </script>
            </section>


            <!-- ACTIVITIES -->
            <section class="activities">
                <div class="activities-box">
                    <h2>Upcoming Counselings</h2>
                    <ul>
                        <?php
                            if ($activityResult && $activityResult->num_rows > 0) {
                                while ($activityRow = $activityResult->fetch_assoc()) {
                                    echo "<li><strong>{$activityRow['timestamp']}</strong>: {$activityRow['activity']}</li>";
                                }
                            } else {
                                echo "<li>No upcoming counseling sessions.</li>";
                            }
                        ?>
                    </ul>
                </div>
            </section>


            <!-- APPOINTMENTS -->
            <section class="appointment">
                <div class="appointment-box">
                    <h2>Appointments</h2>
                    <div id='calendar'></div>
                </div>
            </section>
        </div>


        <!-- TABLE + TODO -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Recent Counselings</h3>
                    <i class='bx bx-search'></i>
                    <i class='bx bx-filter'></i>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Date Scheduled</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><img src="../../Public/user.img/people.png"><p>Juan Dela Cruz</p></td><td>05-13-2025</td><td><span class="status completed">Completed</span></td></tr>
                        <tr><td><img src="../../Public/user.img/people.png"><p>Maria Santos</p></td><td>05-14-2025</td><td><span class="status pending">Pending</span></td></tr>
                        <tr><td><img src="../../Public/user.img/people.png"><p>Carlos Reyes</p></td><td>05-15-2025</td><td><span class="status process">Ongoing</span></td></tr>
                    </tbody>
                </table>
            </div>


            <div class="todo">
                <div class="head">
                    <h3>Counselor's Tasks</h3>
                    <i class='bx bx-plus'></i>
                    <i class='bx bx-filter'></i>
                </div>
                <ul class="todo-list">
                    <li class="completed"><p>Review case reports</p><i class='bx bx-dots-vertical-rounded'></i></li>
                    <li class="not-completed"><p>Call parent of student X</p><i class='bx bx-dots-vertical-rounded'></i></li>
                    <li class="completed"><p>Submit monthly report</p><i class='bx bx-dots-vertical-rounded'></i></li>
                    <li class="not-completed"><p>Schedule new counseling session</p><i class='bx bx-dots-vertical-rounded'></i></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- SCRIPTS -->
<script src="../../Script/head.js"></script>
<script src="../../Script/linechart.js"></script>
</body>
</html>
``` 

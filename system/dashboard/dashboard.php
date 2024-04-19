<?php
require_once ("dashboardFunction.php");
?>

<?php require_once ("../components/header.php"); ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php require_once ("../components/sidebar.php"); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php require_once ("../components/topbar.php"); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <?php if ($role == "admin" || $role == "lecturer"):
                        require_once ("dashboardFunction.php"); ?>

                        <!-- Content Row -->
                        <div class="row">


                            <!-- Kelas paling banyak dihadiri -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card background-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Kelas Paling Banyak Dihadiri</div>
                                                <div class="h5 mb-0 font-weight-bold text-primary">
                                                    <?php $mostAttendedClass = getMostAttendedClass();
                                                    echo ($mostAttendedClass) ? $mostAttendedClass['Name'] : "-" ?>
                                                </div>
                                                <hr>
                                                <div>
                                                    <a href="dashboardMostAttendedClass.php"
                                                        class="text-xs font-weight-normal text-primary mb-1">View
                                                        Details</a>
                                                    <span class="fas fa-angle-right	sidebar-dashboard text-primary"
                                                        style="float: right;"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kelas paling jarang dihadiri -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card background-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Kelas Paling Jarang Dihadiri</div>
                                                <div class="h5 mb-0 font-weight-bold text-primary">
                                                    <?php $leastAttendedClass = getLeastAttendedClass();
                                                    echo ($leastAttendedClass) ? $leastAttendedClass['Name'] : "-"; ?>
                                                </div>
                                                <hr>
                                                <div>
                                                    <a href="dashboardLeastAttendedClass.php"
                                                        class="text-xs font-weight-normal text-primary mb-1">View
                                                        Details</a>
                                                    <span class="fas fa-angle-right	sidebar-dashboard text-primary"
                                                        style="float: right;"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mahasiswa paling rajin -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card background-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Mahasiswa Paling Rajin</div>
                                                <div class="h5 mb-0 font-weight-bold text-primary">
                                                    <?php $mostActiveStudent = getMostActiveStudent();
                                                    echo ($mostActiveStudent) ? $mostActiveStudent['Name'] : "-"; ?>
                                                </div>
                                                <hr>
                                                <div>
                                                    <a href="dashboardMostAttendedStudent.php"
                                                        class="text-xs font-weight-normal text-primary mb-1">View
                                                        Details</a>
                                                    <span class="fas fa-angle-right	sidebar-dashboard text-primary"
                                                        style="float: right;"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mahasiswa Potensi Cekal -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card background-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Mahasiswa Potensi Cekal</div>
                                                <div class="h5 mb-0 font-weight-bold text-primary">
                                                    <?php $suspectStudent = getSuspectStudent();
                                                    echo ($suspectStudent) ? $suspectStudent['Name'] : "-"; ?>
                                                </div>
                                                <hr>
                                                <div>
                                                    <a href="dashboardLeastAttendedStudent.php"
                                                        class="text-xs font-weight-normal text-primary mb-1">View
                                                        Details</a>
                                                    <span class="fas fa-angle-right	sidebar-dashboard text-primary"
                                                        style="float: right;"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Row -->
                        <div class="row">

                            <!-- Area Chart -->
                            <div class="col-xl-6 col-lg-7">
                                <div class="card shadow mb-4">
                                    <!-- Card Header - Dropdown -->
                                    <div
                                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary" style="color: black !important;">
                                            Grafik Absensi Mahasiswa Harian</h6>
                                    </div>
                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <div class="chart-area">
                                            <canvas id="dailyAttendanceChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-5">
                                <div class="card shadow mb-4">
                                    <!-- Card Header - Dropdown -->
                                    <div
                                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary" style="color: black !important;">
                                            Grafik Absensi Mahasiswa Bulanan</h6>
                                    </div>
                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <div class="chart-bar">
                                            <canvas id="monthlyAttendanceChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php endif; ?>

                    <!-- Begin Page Content -->
                    <?php if ($role == "student"):
                        require_once ("dataCourseStudentFunction.php"); ?>
                        <div class="row">
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card background-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Persentase Kehadiran
                                                </div>
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col-auto">
                                                    <?php $attendanceData = dataAttendanceStudent(); ?>
                                                        <?php if ($attendanceData): ?>
                                                            <div class="h5 mb-0 mr-3 font-weight-bold text-primary">
                                                                <?= $attendanceData['AttendancePercentage']; ?>%
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="h5 mb-0 mr-3 font-weight-bold text-primary">
                                                                0%
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col">
                                                        <div class="progress">
                                                            <?php if ($attendanceData): ?>
                                                                <div class="progress-bar bg-warning" role="progressbar"
                                                                    aria-valuenow="<?php echo $attendanceData['AttendancePercentage']; ?>"
                                                                    aria-valuemin="0" aria-valuemax="100"
                                                                    style="width: <?php echo $attendanceData['AttendancePercentage']; ?>%">
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card background-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Jumlah Ketidakhadiran
                                                </div>
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col-auto">
                                                        <div class="h5 mb-0 mr-3 font-weight-bold text-primary">
                                                        <?php $attendanceData = dataAttendanceStudent(); ?>
                                                            <?php if ($attendanceData): ?>
                                                                <?= $attendanceData['AbsenceCount']; ?>
                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Page Heading -->
                        <h1 class="h3 mb-4 text-gray-800">Mata Kuliah Hari Ini</h1>
                        <!-- Tabel Mata Kuliah -->
                        <table id="example1" class="display cell-border " style="width:100%">
                            <thead>
                                <tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode Mata Kuliah</th>
                                    <th>Mata Kuliah</th>
                                    <th>Ruang</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['courses'] as $course): ?>
                                    <tr>
                                        <td>
                                            <?= $course["Date"] ?>
                                        </td>
                                        <td>
                                            <?= $course["Code"] ?>
                                        </td>
                                        <td>
                                            <?= $course['Name'] ?>
                                        </td>
                                        <td>
                                            <?= $course['Room'] ?>
                                        </td>
                                        <td>
                                            <?= $course['StartTime'] ?>
                                        </td>
                                        <td>
                                            <?= $course["EndTime"] ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- /.container-fluid -->
                    <?php endif; ?>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->

    <?php require_once ("../components/js.php"); ?>

    <!-- js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        new DataTable('#example1', {
            columns: [{
                data: 'tanggal'
            },
            {
                data: 'kode_mk'
            },
            {
                data: 'mata_kuliah'
            },
            {
                data: 'ruang'
            },
            {
                data: 'jam_mulai'
            },
            {
                data: 'jam_selesai'
            }
            ]
        });
    </script>
    <script>
        // Load data for daily attendance chart
        <?php
        $dailyAttendanceData = getDailyAttendanceData();
        $dailyAttendanceLabels = json_encode($dailyAttendanceData['labels']);
        $dailyAttendanceData = json_encode($dailyAttendanceData['data']);
        ?>
        var ctx = document.getElementById('dailyAttendanceChart').getContext('2d');
        var dailyAttendanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo $dailyAttendanceLabels; ?>,
                datasets: [{
                    label: 'Daily Attendance',
                    data: <?php echo $dailyAttendanceData; ?>,
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1,
                    pointRadius: 3,
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    fill: true
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 10
                        }
                    }]
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10
                }
            }
        });

        // Load data for monthly attendance chart
        <?php
        $monthlyAttendanceData = getMonthlyAttendanceData();
        $monthlyAttendanceLabels = json_encode($monthlyAttendanceData['labels']);
        $monthlyAttendanceData = json_encode($monthlyAttendanceData['data']);
        ?>
        var ctx2 = document.getElementById('monthlyAttendanceChart').getContext('2d');
        var monthlyAttendanceChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: <?php echo $monthlyAttendanceLabels; ?>,
                datasets: [{
                    label: 'Monthly Attendance',
                    data: <?php echo $monthlyAttendanceData; ?>,
                    backgroundColor: 'rgba(78, 115, 223, 0.5)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'month'
                        },
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            min: 0
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }]
                },
                legend: {
                    display: false
                },
                tooltips: {
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10
                }
            }
        });
    </script>
</body>

</html>
<?php
require_once ("historyAttendanceFunction.php");
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

                <!-- Topbar -->
                <?php require_once ("../components/topbar.php"); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Histori Absensi</h1>
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <form action="historyAttendance.php" method="GET">
                                    <div class="form-group row">
                                        <label for="inputKodeMK" class="col-xl-4 col-form-label">Kode Mata
                                            Kuliah</label>
                                        <div class="col-xl-8">
                                            <input type="text" class="form-control" id="inputKodeMK" name="inputKodeMK"
                                                value="<?php echo isset($_GET['inputKodeMK']) ? $_GET['inputKodeMK'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputTanggal" class="col-xl-4 col-form-label">Tanggal</label>
                                        <div class="col-xl-8">
                                            <input type="date" class="form-control" id="inputTanggal"
                                                name="inputTanggal"
                                                value="<?php echo isset($_GET['inputTanggal']) ? $_GET['inputTanggal'] : ''; ?>">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success tambah_btn">Cari</button>
                                </form>
                            </div>
                            &nbsp;
                        </div>
                    </div>

                    &nbsp;
                    <!-- Tabel Histori Absensi -->
                    <table id="example" class="display cell-border " style="width:100%">
                        <thead>
                            <th>Id</th>
                            <th>Tanggal</th>
                            <th>Nama Mahasiswa</th>
                            <th>Kode Mata Kuliah</th>
                            <th>Mata Kuliah</th>
                            <th>Ruang</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Metode Absensi</th>
                            <th>Jam Absensi</th>
                            <th>Status</th>
                            <?php if ($role == "admin" || $role == "lecturer"): ?>
                                <th>Aksi</th>
                            <?php endif; ?>
                        </thead>
                        <tbody>
                            <?php foreach ($data['attendances'] as $attendance): ?>
                                <tr>
                                    <td>
                                        <?= $attendance['StudentId'] ?>
                                    </td>
                                    <td>
                                        <?= ($attendance["Date"] == null) ? "-" : date("Y-m-d", strtotime($attendance["Date"])) ?>
                                    </td>
                                    <td>
                                        <?= $attendance['StudentName'] ?>
                                    </td>
                                    <td>
                                        <?= $attendance['CourseCode'] ?>
                                    </td>
                                    <td>
                                        <?= $attendance['ClassName'] ?>
                                    </td>
                                    <td>
                                        <?= $attendance['Room'] ?>
                                    </td>
                                    <td>
                                        <?= $attendance['StartTime'] ?>
                                    </td>
                                    <td>
                                        <?= $attendance['EndTime'] ?>
                                    </td>
                                    <td>
                                        <?= $attendance['TimeinMethod'] ?>
                                    </td>
                                    <td>
                                        <?= ($attendance['TimeIn'] == null) ? "-" : $attendance['TimeIn'] ?>
                                    </td>
                                    <td>
                                        <?php if ($attendance["Status"] == "1"): ?>
                                            <span class="badge badge-primary">Hadir</span>
                                        <?php elseif ($attendance["Status"] == "2"): ?>
                                            <span class="badge badge-warning">Izin</span>
                                        <?php elseif ($attendance["Status"] == "3"): ?>
                                            <span class="badge badge-warning">Telat</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Tidak Hadir</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="display: flex; gap: 5px;">
                                        <?php if ($role == "admin" || $role == "lecturer"): ?>
                                            <a href="updateAttendance.php?StudentId=<?= $attendance["StudentId"] ?>&ScheduleId=<?= $attendance["ScheduleId"] ?>"
                                                class="btn btn-primary btn-sm">Edit</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
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

    <!-- Custom scripts for all pages-->
    <?php require_once ("../components/js.php"); ?>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#example').DataTable({
                columns: [{
                    data: 'id'
                },
                {
                    data: 'tanggal'
                },
                {
                    data: 'nama_mahasiswa'
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
                    data: 'ruang1'
                },
                {
                    data: 'jam_mulai'
                },
                {
                    data: 'jam_selesai'
                },
                {
                    data: 'status'
                },
                {
                    data: 'status1'
                },
                {
                    data: 'aksi'
                }
                ],
                columnDefs: [{
                    targets: [0], // Indeks kolom yang ingin disembunyikan
                    visible: false
                }]
            });
        });
    </script>

</body>

</html>
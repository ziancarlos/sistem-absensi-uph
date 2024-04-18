<?php
require_once ("dataCourseDetailFunction.php");
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
                    <?php if ($role == "admin" || $role == "lecturer"): ?>
                    <h1 class="h3 mb-4 text-gray-800">List Mahasiswa yang di Enroll</h1>
                    <?php endif; ?>

                    <?php if ($role == "student"): ?>
                    <h1 class="h3 mb-4 text-gray-800">Detail Mata Kuliah</h1>
                    <?php endif; ?>

                    <table id="example1" class="display cell-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id Dosens</th>
                                <th>Nama Dosen</th>
                                <th>Email Dosen</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($data["lecturers"] as $lecturer): ?>
                            <tr>
                                <td>
                                    <?= $lecturer["UserId"] ?>
                                </td>
                                <td>
                                    <?= $lecturer["Name"] ?>
                                </td>
                                <td>
                                    <?= $lecturer["Email"] ?>
                                </td>

                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <br><br>
                    <?php if ($role == "admin" || $role == "lecturer"): ?>
                    <table id="example" class="display cell-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Tahun Angkatan</th>
                                <th>Jumlah Absen</th>
                                <th>Jumlah Telat</th>
                                <th>Jumlah Hadir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data["students"] as $student): ?>
                            <tr>
                                <td>
                                    <?= $student["Name"] ?>
                                </td>
                                <td>
                                    <?= $student["StudentId"] ?>
                                </td>
                                <td>
                                    <?= $student["YearIn"] ?>
                                </td>
                                <td>
                                    <?= $student["numberAbsent"] ?>
                                </td>
                                <td>
                                    <?= $student["numberLate"] ?>
                                </td>
                                <td>
                                    <?= $student["numberPresent"] ?>
                                </td>
                                <td style="display: flex; gap: 5px;">
                                    <?php if ($student["EnrollmentStatus"] == 1): ?>
                                    <form action="../students/deactivateEnrollmentFunction.php" method="post"
                                        style="display: inline-block;">
                                        <button type="submit" name="deactivate" value="<?= $student["EnrollmentId"]; ?>"
                                            class="btn btn-danger btn-sm" style="width: 90px">Non Aktif</button>
                                    </form>
                                    <?php else: ?>
                                    <form action="../students/activateEnrollmentFunction.php" method="post"
                                        style="display: inline-block;">
                                        <button type="submit" name="activate" value="<?= $student["EnrollmentId"]; ?>"
                                            class="btn btn-success btn-sm" style="width: 90px">Aktifkan</button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
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

    <?php require_once ("../components/js.php"); ?>


    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
    new DataTable('#example', {
            columns: [{
                    data: 'nama_mahasiswa'
                },
                {
                    data: 'nim'
                },
                {
                    data: 'angakatan'
                },
                {
                    data: 'angakatan1'
                },
                {
                    data: 'angakatan2'
                },
                {
                    data: 'angakatan3'
                }
                <?php if ($role != "student"): ?>,

                {
                    data: 'aksi'
                }
                <?php endif; ?>
            ]
        },
        new DataTable('#example1', {
            columns: [{
                    data: 'kode_MK'
                },
                {
                    data: 'nama_dosen'
                },
                {
                    data: 'jam_mulai'
                },
            ]
        })
    );
    </script>

</body>

</html>
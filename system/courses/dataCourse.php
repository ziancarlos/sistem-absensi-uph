<?php
require_once("dataCourseFunction.php");
?>

<?php require_once("../components/header.php"); ?>


<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php require_once("../components/sidebar.php"); ?>

        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php require_once("../components/topbar.php"); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">List Mata Kuliah</h1>
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="form-group row">
                                        <label for="inputSemester" class=" col-xl-4 col-form-label">Semester</label>
                                        <div class="col-xl-8">
                                            <input type="text" class="form-control" id="inputSemester">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputAngkatan" class="col-xl-4 col-form-label">Tahun
                                            Angkatan</label>
                                        <div class="col-xl-8">
                                            <input type="text" class="form-control" id="inputAngkatan">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success tambah_btn">Cari</button>
                                </form>
                            </div>
                            &nbsp;
                        </div>

                    </div>

                    &nbsp;

                    <!-- Tabel Mata Kuliah -->
                    <table id="example" class="display cell-border " style="width:100%">
                        <thead>
                            <tr>
                                <th>ID MK</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Mata Kuliah</th>
                                <th>Kode MK</th>
                                <th>Ruang</th>
                                <?php if ($role == "student"): ?>
                                <th>Status MK Mhs</th>
                                <?php else: ?>
                                <th>Status MK</th>
                                <?php endif; ?>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['courses'] as $course): ?>
                            <tr>
                                <td>
                                    <?= $course["CourseId"] ?>
                                </td>
                                <td>
                                    <?= $course['StartDate'] ?>
                                </td>
                                <td>
                                    <?= $course['EndDate'] ?>
                                </td>
                                <td>
                                    <?= $course['Name'] ?>
                                </td>
                                <td>
                                    <?= $course['Code'] ?>
                                </td>
                                <td>
                                    <?= $course['Room'] ?>
                                </td>
                                <td>

                                    <?php if ($role == "student"): ?>
                                    <?php if ($course["EnrollmentStatus"] == 1): ?>
                                    <span class="badge badge-primary">Aktif</span>
                                    <?php else: ?>
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                    <?php endif; ?>
                                    <?php else: ?>
                                    <?php if ($course["CoursesStatus"] == 1): ?>
                                    <span class="badge badge-primary">Aktif</span>
                                    <?php else: ?>
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                    <?php endif; ?>
                                    <?php endif; ?>

                                </td>
                                <td style="display: flex; gap: 5px;">
                                    <a class="btn btn-success btn-sm" href="dosen_list_MK_detail.html"
                                        style="width: 90px">Detail</a>

                                    <?php if ($role == "admin"): ?>
                                    <a class="btn btn-primary btn-sm" href="dosen_list_MK_edit.html"
                                        style="width: 90px">Edit</a>
                                    <?php if ($course["CoursesStatus"] == 1): ?>
                                    <form action="deactivateCourseFunction.php" method="post"
                                        style="display: inline-block;">
                                        <button type="submit" name="deactivate" value="<?= $course["CourseId"]; ?>"
                                            class="btn btn-danger btn-sm" style="width: 90px">Non Aktif</button>
                                    </form>
                                    <?php else: ?>
                                    <form action="activateCourseFunction.php" method="post"
                                        style="display: inline-block;">
                                        <button type="submit" name="activate" value="<?= $course["CourseId"]; ?>"
                                            class="btn btn-success btn-sm" style="width: 90px">Aktif</button>
                                    </form>
                                    <?php endif; ?>

                                    <a class="btn btn-warning btn-sm" href="dosen_list_MK_enroll.html"
                                        style="width: 90px">Enroll</a>
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

    <?php require_once("../components/js.php"); ?>


    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
    new DataTable('#example', {
        columns: [{
                data: 'idMk'
            }, {
                data: 'tanggalMulai'
            },
            {
                data: 'tanggalSelesai'
            },
            {
                data: 'mata_kuliah'
            },
            {
                data: 'mata_kulia1h'
            },
            {
                data: 'ruang'
            }, {
                data: 'status'
            },
            {
                data: 'aksi'
            },
        ]
    });
    </script>

</body>

</html>
<?php
require_once ("updateCourseScheduleFunction.php");
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
                    <h1 class="h3 mb-4 text-gray-800">Jadwal Mata Kuliah</h1>
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" action="addCourseScheduleFunction.php">
                                    <div class="form-group row">
                                        <label for="inputTglKuliah" class="col-xl-4 col-form-label">Waktu Kuliah</label>
                                        <div class="col-xl-8">
                                            <input type="datetime-local" class="form-control" id="inputTglKuliah"
                                                name="schedule" />
                                        </div>
                                    </div>
                                    <button type="submit" name="tambah" value="<?= $_GET["CourseId"] ?>"
                                        class="btn btn-primary tambah_btn">Tambah</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    &nbsp;

                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <!-- Tabel Mata Kuliah -->
                                    <table id="example" class="display cell-border" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>ID Jadwal</th>
                                                <th>Waktu Kuliah</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data["schedules"] as $schedule): ?>
                                                <tr>
                                                    <td>
                                                        <?= $schedule["ScheduleId"] ?>
                                                    </td>
                                                    <td>
                                                        <?= $schedule["DateTime"] ?>
                                                    </td>
                                                    <td style="display: flex; gap: 5px">
                                                        <a class="btn btn-success btn-sm"
                                                            href="updateCourseScheduleEdit.php"
                                                            style="width: 90px; color: white;">Edit</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <br>
                                    <button type="submit" class="btn btn-primary tambah_btn">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>

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
                data: 'kode_MK'
            }, {
                data: 'mata_kuliah'
            }, {
                data: 'waktu_kuliah'
            }]
        });
    </script>

</body>

</html>
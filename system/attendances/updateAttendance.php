<?php
require_once("updateAttendanceFunction.php");
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
                    <h1 class="h3 mb-4 text-gray-800">Edit Absensi</h1>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <form>
                                        <div class="form-group row">
                                            <label for="inputTgl" class="col-sm-3 col-form-label">Tanggal</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" id="inputTgl">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputNamaMhs" class="col-sm-3 col-form-label">Nama Mahasiswa</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" id="inputNamaMhs">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputNamaMK" class="col-sm-3 col-form-label">Nama Mata Kuliah</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="inputNamaMK">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputKodeMK" class="col-sm-3 col-form-label">Kode Mata Kuliah</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="inputKodeMK">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputNamaDosen" class="col-sm-3 col-form-label">Nama Dosen</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="inputNamaDosen">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputRuang" class="col-sm-3 col-form-label">Ruang</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="inputRuang">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputJamMulai" class="col-sm-3 col-form-label">Jam Mulai</label>
                                            <div class="col-sm-9">
                                                <input type="time" class="form-control" id="inputJamMulai">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputJamSelesai" class="col-sm-3 col-form-label">Jam Selesai</label>
                                            <div class="col-sm-9">
                                                <input type="time" class="form-control" id="inputJamSelesai">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputStatus" class="col-sm-3 col-form-label">Status</label>
                                            <div class="col-sm-9">
                                            <select id="status-mhs" name="status_mhs" class="form-control">
                                                <option value="1">Hadir</option>
                                                <option value="0">Tidak Hadir</option>
                                            </select>
                                            </div>
                                        </div>
                                        <input type="button" onclick="location.href='dosen_edit_absensi.html'" value="Simpan" class="btn btn-primary tambah_btn" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            &nbsp;
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
</body>

</html>
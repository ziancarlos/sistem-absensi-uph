<?php
require_once("addCourseFunction.php");
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
                    <h1 class="h3 mb-4 text-gray-800">Tambah Mata Kuliah</h1>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <form>

                                        <div class="form-group row">
                                            <label for="inputNamaMK" class="col-sm-3 col-form-label">Nama Mata
                                                Kuliah</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="inputNamaMK">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputNamaDosen" class="col-sm-3 col-form-label">Kode
                                                Kelas</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="inputNamaDosen">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputRuang" class="col-sm-3 col-form-label">Ruang</label>
                                            <div class="col-sm-9">
                                                <select class="custom-select">
                                                    <option selected>Pilih Kelas</option>
                                                    <?php foreach ($data["classrooms"] as $classroom): ?>
                                                    <option value="<?= $classroom["ClassroomId"] ?>">
                                                        <?= $classroom["Room"] ?> / Kapasitas
                                                        <?= $classroom["Capacity"] ?> Orang
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary tambah_btn">Simpan</button>
                                    </form>
                                </div>
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

    <?php require_once("../components/js.php"); ?>




</body>

</html>
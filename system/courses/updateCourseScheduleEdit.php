<?php
require_once("updateCourseScheduleEditFunction.php");

// Memeriksa apakah ScheduleId disertakan dalam URL
if(isset($_GET['ScheduleId'])) {
    $scheduleId = $_GET['ScheduleId'];

    // Memanggil fungsi untuk mendapatkan detail jadwal mata kuliah berdasarkan ScheduleId
    $scheduleData = getCourseScheduleById($scheduleId);

    // Memeriksa apakah data jadwal ditemukan
    if(!$scheduleData) {
        echo "Data jadwal tidak ditemukan.";
        exit;
    }

    // Assign data jadwal ke variabel
    $kodeMataKuliah = $scheduleData['Code'];
    $tanggalKuliah = $scheduleData['DateTime'];
} else {
    // Menangani kasus di mana parameter ScheduleId tidak ditemukan dalam URL
    echo "Parameter ScheduleId tidak ditemukan dalam URL.";
    exit;
}
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
                    <h1 class="h3 mb-4 text-gray-800">Edit Jadwal Mata Kuliah</h1>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                <form method="post">
                                        <div class="form-group row">
                                            <label for="inputKodeMK" class="col-sm-3 col-form-label">Kode Mata Kuliah</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="inputKodeMK" name="kode" value="<?php echo $kodeMataKuliah; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputTglKuliah" class="col-sm-3 col-form-label">Tanggal Kuliah</label>
                                            <div class="col-sm-9">
                                                <input type="datetime-local" class="form-control" id="inputTglKuliah" name="tanggal_kuliah" value="<?php echo $tanggalKuliah; ?>">
                                            </div>
                                        </div>
                                        <button name="update" type="submit" class="btn btn-primary tambah_btn">Simpan</button>
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

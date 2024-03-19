<?php
require_once("updateAttendanceFunction.php");

// Periksa apakah parameter StudentId, tanggal, dan kode mata kuliah disertakan dalam URL
if(isset($_GET['StudentId']) && isset($_GET['tanggal']) && isset($_GET['kodeMataKuliah'])) {
    $studentId = $_GET['StudentId'];
    $tanggal = $_GET['tanggal'];
    $kodeMataKuliah = $_GET['kodeMataKuliah'];

    // Panggil fungsi untuk mendapatkan data absensi berdasarkan StudentId, tanggal, dan kode mata kuliah
    $attendanceData = getAttendanceByStudentIdAndDateAndCourseCode($studentId, $tanggal, $kodeMataKuliah);

    // Periksa apakah data absensi ditemukan
    if(!$attendanceData) {
        // Tangani kasus di mana data absensi tidak ditemukan
        echo "Data absensi tidak ditemukan.";
        exit;
    }

    // Mengatur nilai status berdasarkan nilai TimeIn
    $status = ($attendanceData["TimeIn"] !== null) ? 1 : 0;

    // Isi nilai awal pada input formulir dengan data absensi yang diperoleh
    $date = $attendanceData['Date'];
    $studentName = $attendanceData['Name'];
    $courseCode = $attendanceData['Code'];
    $courseName = $attendanceData['ClassName'];
} else {
    // Tangani kasus di mana parameter tidak lengkap dalam URL
    echo "Parameter tidak lengkap dalam URL.";
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
                    <h1 class="h3 mb-4 text-gray-800">Edit Absensi</h1>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" action="updateAttendanceFunction.php">
                                        <div class="form-group row">
                                            <label for="inputTgl" class="col-sm-3 col-form-label">Tanggal</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" id="inputTgl" name="date" value="<?php echo $date; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputNamaMhs" class="col-sm-3 col-form-label">Nama Mahasiswa</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="inputNamaMhs" name="studentName" value="<?php echo $studentName; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputKodeMK" class="col-sm-3 col-form-label">Kode Mata Kuliah</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="inputKodeMK" name="courseCode" value="<?php echo $courseCode; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputNamaMK" class="col-sm-3 col-form-label">Nama Mata Kuliah</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="inputNamaMK" name="courseName" value="<?php echo $courseName; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputStatus" class="col-sm-3 col-form-label">Status</label>
                                            <div class="col-sm-9">
                                                <select id="status-mhs" name="status" class="form-control">
                                                    <option value="1" <?php echo ($status == 1) ? 'selected' : ''; ?>>Hadir</option>
                                                    <option value="0" <?php echo ($status == 0) ? 'selected' : ''; ?>>Tidak Hadir</option>
                                                </select>

                                            </div>
                                        </div>
                                        <!-- Hidden input for AttendanceId -->
                                        <input type="hidden" name="attendanceId" value="<?php echo $studentId; ?>">
                                        <button name="updateAttendance" type="submit" class="btn btn-primary tambah_btn">Simpan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Custom scripts for all pages-->
    <?php require_once("../components/js.php"); ?>
</body>

</html>

<?php
session_start();
require_once("editClassroomFunction.php");

// Penanganan pesan error
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

// Memeriksa apakah classroomId dikirimkan melalui parameter URL
if (isset($_GET['ClassroomId'])) {
    $classroomId = $_GET['ClassroomId'];

    // Memanggil fungsi untuk mendapatkan informasi ruang kuliah berdasarkan classroomId
    $classroom = getClassroomById($classroomId);

    if ($classroom) {
        // Jika ruang kelas ditemukan, tampilkan informasinya
        $buildingId = $classroom['Room'];
        $roomNumber = $classroom['code'];
        $capacity = $classroom['Capacity'];
    } else {
        // Jika ruang kelas tidak ditemukan, tampilkan pesan error
        $_SESSION['error'] = "Ruang kelas tidak ditemukan";
        header("location: addClassroom.php");
        exit;
    }
} else {
    // Jika classroomId tidak dikirimkan, redirect kembali ke halaman sebelumnya
    $_SESSION['error'] = "Parameter classroomId tidak ditemukan";
    header("location: addClassroom.php");
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
                    <h1 class="h3 mb-4 text-gray-800">Edit Ruang Kuliah</h1>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" action="editClassroomFunction.php">
                                        <div class="form-group row">
                                            <label for="inputBuildingId" class="col-sm-2 col-form-label">Kode Gedung</label>
                                            <div class="col-sm-10">
                                                <!-- Tampilkan kode gedung yang sesuai dengan data ruang kelas -->
                                                <input type="text" class="form-control" id="inputBuildingId" name="buildingId" value="<?php echo $buildingId; ?> " readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputRoom" class="col-sm-2 col-form-label">Nomor Ruang</label>
                                            <div class="col-sm-10">
                                                <!-- Tampilkan nomor ruang yang sesuai dengan data ruang kelas -->
                                                <input type="text" class="form-control" id="inputRoom" name="roomNumber" value="<?php echo $roomNumber; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputCapacity" class="col-sm-2 col-form-label">Kapasitas</label>
                                            <div class="col-sm-10">
                                                <!-- Tampilkan kapasitas yang sesuai dengan data ruang kelas -->
                                                <input type="text" class="form-control" id="inputCapacity" name="capacity" value="<?php echo $capacity; ?>">
                                            </div>
                                        </div>
                                        <!-- Hidden input for ClassroomId -->
                                        <input type="hidden" name="ClassroomId" value="<?php echo $classroomId; ?>">
                                        <button name="ubah" type="submit" class="btn btn-primary tambah_btn">Simpan</button>
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

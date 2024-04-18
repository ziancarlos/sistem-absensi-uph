<?php
require_once ("updateAttendanceFunction.php");


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
                    <h1 class="h3 mb-4 text-gray-800">Edit Absensi</h1>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" action="updateAttendance.php">
                                        <div class="form-group row">
                                            <label for="inputTgl" class="col-sm-3 col-form-label">Tanggal Kelas</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" id="inputTgl" name="date"
                                                    value="<?= $data['Date']; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputJamMulai" class="col-sm-3 col-form-label">Jam Mulai
                                                Kelas</label>
                                            <div class="col-sm-9">
                                                <input type="time" class="form-control" id="inputJamMulai"
                                                    name="startTime" value="<?= $data['StartTime']; ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputJamSelesai" class="col-sm-3 col-form-label">Jam Selesai
                                                Kelas</label>
                                            <div class="col-sm-9">
                                                <input type="time" class="form-control" id="inputJamSelesai"
                                                    name="endTime" value="<?= $data['EndTime']; ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputNamaMhs" class="col-sm-3 col-form-label">Nama
                                                Mahasiswa</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="inputNamaMhs"
                                                    name="studentName" value="<?= $data['UserName']; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputKodeMK" class="col-sm-3 col-form-label">Kode Mata
                                                Kuliah</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="inputKodeMK"
                                                    name="courseCode" value="<?= $data['CourseCode']; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputNamaMK" class="col-sm-3 col-form-label">Nama Mata
                                                Kuliah</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="inputNamaMK"
                                                    name="courseName" value="<?= $data['CourseName']; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputStatus" class="col-sm-3 col-form-label">Status</label>
                                            <div class="col-sm-9">
                                                <select id="status-mhs" name="status" class="form-control">

                                                    <option value="3" <?= ($data["Status"] == 3) ? 'selected' : ''; ?>>
                                                        Telat</option>
                                                    <option value="2" <?= ($data["Status"] == 2) ? 'selected' : ''; ?>>
                                                        Izin</option>
                                                    <option value="1" <?= ($data["Status"] == 1) ? 'selected' : ''; ?>>
                                                        Hadir</option>
                                                    <option value="0" <?= ($data["Status"] == 0) ? 'selected' : ''; ?>>
                                                        Tidak Hadir</option>


                                                </select>
                                            </div>
                                        </div>
                                        <!-- Hidden input for StudentId -->
                                        <input type="hidden" name="studentId" value="<?= $_GET["StudentId"] ?>">
                                        <!-- Hidden input for ScheduleId -->
                                        <input type="hidden" name="scheduleId" value="<?= $_GET["ScheduleId"] ?>">
                                        <button name="updateAttendance" type="submit"
                                            class="btn btn-primary tambah_btn">Simpan</button>
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
    <?php require_once ("../components/js.php"); ?>
</body>

</html>
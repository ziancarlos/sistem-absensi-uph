<?php
require_once("updateStudentFunction.php");
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
                    <h1 class="h3 mb-4 text-gray-800">Edit Mahasiswa</h1>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post">
                                        <div class="form-group row">
                                            <label for="inputNIM" class="col-sm-2 col-form-label">NIM</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" id="inputNIM" name="nim"
                                                    value="<?php echo $data['student']['StudentId']; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Nama</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="inputName" name="name"
                                                    value="<?php echo $data['student']['Name']; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" id="inputEmail" name="email"
                                                    value="<?php echo $data['student']['Email']; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputYear" class="col-sm-2 col-form-label">Tahun Angkatan</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" id="inputYear" name="yearIn"
                                                    value="<?php echo $data['student']['YearIn']; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" id="inputPassword"
                                                    name="password" value="">
                                            </div>
                                        </div>

                                        <!-- Hidden input for StudentId -->
                                        <input type="hidden" name="studentId"
                                            value="<?php echo $data['student']['StudentId']; ?>">
                                        <button name="ubah" type="submit"
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
    <?php require_once("../components/js.php"); ?>
</body>

</html>
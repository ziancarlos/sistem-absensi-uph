<?php
    require_once("addClassroomFunction.php");
    // Memanggil fungsi getBuildings() untuk mendapatkan data bangunan
    $buildings = getBuildings();

    // Periksa apakah data bangunan berhasil diambil
    if (!$buildings) {
        // Tangani kesalahan jika gagal mengambil data bangunan
        // Misalnya, tampilkan pesan kesalahan atau arahkan pengguna ke halaman lain
        echo "Error fetching buildings data. Please try again later.";
        exit;
    }
?>

<?php 
    if (isset($_POST['add'])) {
        // Memastikan data yang diterima sesuai
        if (isset($_POST['kodeGedung'], $_POST['nomorRuang'], $_POST['kapasitas'])) {
            $buildingId = $_POST['kodeGedung'];
            $roomNumber = $_POST['nomorRuang'];
            $capacity = $_POST['kapasitas'];
    
            // Memanggil fungsi untuk menambahkan ruang kuliah ke database
            addClassroom($buildingId, $roomNumber, $capacity);
        } else {
            // Menangani kesalahan jika data tidak lengkap
            $_SESSION["error"] = "Semua field harus diisi";
            header("location: addClassroom.php");
            exit;
        }
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
                

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Ruang Kuliah</h1>
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body">                                    
                                        <form method="post">
                                            <div class="form-group row">
                                                <label for="inputKodeGedung" class="col-sm-3 col-form-label">Kode Gedung</label>
                                                <div class="col-sm-9">
                                                    <select class="custom-select" name="kodeGedung">
                                                        <option selected>Pilih Kode</option>
                                                        <?php foreach ($buildings as $building): ?>
                                                            <option value="<?= $building["BuildingId"] ?>">
                                                                <?= $building["Letter"] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputKodeRuangKuliah" class="col-sm-3 col-form-label">Nomor Ruang</label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" id="inputKodeRuangKuliah" name="nomorRuang">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputKapasitas" class="col-sm-3 col-form-label">Kapasitas</label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" id="inputKapasitas" name="kapasitas">
                                                </div>
                                            </div>
                                            <button name="add" type="submit" class="btn btn-primary tambah_btn">Simpan</button>
                                        </form>
                                    </div>
                                    &nbsp;                            
                                </div>
                            </div>
                        </div>
                    </div>
                    &nbsp;
                    <!-- Tabel Simpan Jadwal Mata Kuliah -->
                    <table id="example" class="display cell-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Ruang</th>
                                <th>Kapasitas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $class): ?>
                                <tr>
                                    <td><?= $class["ClassroomId"] ?></td>
                                    <td><?= $class["Room"] ?></td>
                                    <td><?= $class["Capacity"] ?></td>
                                    <td>
                                        <!-- Tambahkan tombol "Edit" dengan mengirimkan ID ruang kuliah ke fungsi JavaScript -->
                                        <button class="btn btn-success btn-sm" style="width: 90px" onclick="editClassroom(<?= $class["ClassroomId"]; ?>)">Edit</button>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    &nbsp;                
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
                data: 'id'
            },{
                data: 'ruang'
            }, {
                data: 'kapasitas'
            },
            {
                data: 'aksi'
            },
            ]
        });
    </script>

    <script>
        // Fungsi untuk menangani klik tombol "Edit" di tabel
        function editClassroom(id) {
            // Kirim ID ruang kuliah ke editClassroom.php
            window.location.href = "editClassroom.php?ClassroomId=" + id;
        }
    </script>



</body>

</html>
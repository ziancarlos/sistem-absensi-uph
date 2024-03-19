<?php
require_once("enrollCourseFunction.php");
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
                <h1 class="h3 mb-4 text-gray-800">Enroll Mata Kuliah</h1>

                <!-- Tabel Mata Kuliah -->
                <table id="example" class="display cell-border" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Kode Mata Kuliah</th>
                            <th>Mata Kuliah</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Jumlah Mahasiswa Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>SYS1</td>
                            <td>Struktur Data</td>
                            <td>2024-02-02</td>
                            <td>2024-04-30</td>
                            <td>23</td>
                            <td style="display: flex; gap: 5px">
                                <a class="btn btn-warning btn-sm" href="enrollCourseStudent.php" style="width: 90px; color: white;">Enroll</a>
                            </td>
                        </tr>
                        <tr>
                            <td>SYS1</td>
                            <td>Struktur Data</td>
                            <td>2024-02-02</td>
                            <td>2024-04-30</td>
                            <td>23</td>
                            <td style="display: flex; gap: 5px">
                                <a class="btn btn-warning btn-sm" href="enrollCourseStudent.php" style="width: 90px; color: white;">Enroll</a>
                            </td>
                        </tr>
                        <tr>
                            <td>SYS2</td>
                            <td>Sistem Basis Data</td>
                            <td>2024-02-02</td>
                            <td>2024-04-30</td>
                            <td>22</td>
                            <td style="display: flex; gap: 5px">
                                <a class="btn btn-warning btn-sm" href="enrollCourseStudent.php" style="width: 90px; color: white;">Enroll</a>
                            </td>
                        </tr>
                        <tr>
                            <td>SYS3</td>
                            <td>Teknologi Web</td>
                            <td>2024-02-02</td>
                            <td>2024-04-30</td>
                            <td>9</td>
                            <td style="display: flex; gap: 5px">
                                <a class="btn btn-warning btn-sm" href="enrollCourseStudent.php" style="width: 90px; color: white;">Enroll</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br>

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
                data: 'kode_MK'
            }, {
                data: 'mata_kuliah'
            }, {
                data: 'tanggal_mulai'
            }, {
                data: 'tanggal_selesai'
            }, {
                data: 'jumlah_mhs'
            },
            {
                data: 'aksi'
            }
            ]
        });
    </script>

</body>

</html>
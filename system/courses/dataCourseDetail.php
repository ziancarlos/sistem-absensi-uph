<?php
require_once("dataCourseDetailFunction.php");
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
                    <h1 class="h3 mb-4 text-gray-800">List Mahasiswa yang di Enroll</h1>
                    <table id="example1" class="display cell-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>Kode Mata Kuliah</th>
                                <th>Nama Dosen</th>
                                <th>Jam Mulai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>SYS1</td>
                                <td>Arnold Aribowo</td>
                                <td>08.15</td>
                            </tr>
                            <tr>
                                <td>SYS1</td>
                                <td>Kusno Prasetya</td>
                                <td>08.15</td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br>
                    <table id="example" class="display cell-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>Kode Mata Kuliah</th>
                                <th>Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Tahun Angkatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>SYS1</td>
                                <td>Kelvin</td>
                                <td>01081210011</td>
                                <td>2021</td>
                                <td style="display: flex; gap: 5px;">                                
                                    <a class="btn btn-danger btn-sm" href="enrollCourseDeactivate.php" style="width: 90px">Non Aktif</a>
                                </td>
                            </tr>
                            <tr>
                                <td>SYS1</td>
                                <td>Nathania Michaela</td>
                                <td>01081210007</td>
                                <td>2021</td>
                                <td style="display: flex; gap: 5px;">
                                    <a class="btn btn-danger btn-sm" href="enrollCourseDeactivate" style="width: 90px">Non Aktif</a>
                                </td>
                            </tr>
                            <tr>
                                <td>SYS1</td>
                                <td>Yoana Sonia</td>
                                <td>01081210001</td>
                                <td>2021</td>
                                <td style="display: flex; gap: 5px;">
                                    <a class="btn btn-danger btn-sm" href="enrollCourseDeactivate" style="width: 90px">Non Aktif</a>
                                </td>
                            </tr>
                            <tr>
                                <td>SYS1</td>
                                <td>Zian Carlos</td>
                                <td>01081210013</td>
                                <td>2021</td>
                                <td style="display: flex; gap: 5px;">
                                    <a class="btn btn-danger btn-sm" href="" style="width: 90px">Non Aktif</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
            columns: [
                { data: 'kode_MK' },
                { data: 'nama_mahasiswa' },
                { data: 'nim' },
                { data: 'angakatan' },
                { data: 'aksi' }
            ]
        },
        new DataTable('#example1', {
            columns: [
                { data: 'kode_MK' },
                { data: 'nama_dosen' },
                { data: 'jam_mulai' },
            ]
        }
        )
        );
    </script>

</body>

</html>
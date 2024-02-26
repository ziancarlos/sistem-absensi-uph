<?php
require_once("dataStudentFunction.php");
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
                    <h1 class="h3 mb-4 text-gray-800">List Mahasiswa</h1>
                    <table id="example" class="display cell-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Semester</th>
                                <th>Tahun Angkatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Kelvin</td>
                                <td>01081210011</td>
                                <td>8</td>
                                <td>2021</td>
                                <td style="display: flex; gap: 5px;">
                                    <a class="btn btn-success btn-sm" href="" style="width: 90px">Detail</a>
                                    <a class="btn btn-primary btn-sm" href="dosen_list_mahasiswa_edit.html"
                                        style="width: 90px">Edit</a>
                                    <a class="btn btn-danger btn-sm" href="" style="width: 90px">Non Aktif</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Nathania Michaela</td>
                                <td>01081210007</td>
                                <td>8</td>
                                <td>2021</td>
                                <td style="display: flex; gap: 5px;">
                                    <a class="btn btn-success btn-sm" href="" style="width: 90px">Detail</a>
                                    <a class="btn btn-primary btn-sm" href="" style="width: 90px">Edit</a>
                                    <a class="btn btn-danger btn-sm" href="" style="width: 90px">Non Aktif</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Yoana Sonia</td>
                                <td>01081210001</td>
                                <td>8</td>
                                <td>2021</td>
                                <td style="display: flex; gap: 5px;">
                                    <a class="btn btn-success btn-sm" href="" style="width: 90px">Detail</a>
                                    <a class="btn btn-primary btn-sm" href="" style="width: 90px">Edit</a>
                                    <a class="btn btn-danger btn-sm" href="" style="width: 90px">Non Aktif</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Zian Carlos</td>
                                <td>01081210013</td>
                                <td>8</td>
                                <td>2021</td>
                                <td style="display: flex; gap: 5px;">
                                    <a class="btn btn-success btn-sm" href="" style="width: 90px">Detail</a>
                                    <a class="btn btn-primary btn-sm" href="" style="width: 90px">Edit</a>
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


    <!-- Bootstrap core JavaScript-->
    <script src="../../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../assets/js/sb-admin-2.min.js"></script>



    <!-- Custom scripts for all pages-->
    <script src="../../assets/js/sb-admin-2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        new DataTable('#example', {
            columns: [{
                data: 'nama_mahasiswa'
            },
            {
                data: 'nim'
            },
            {
                data: 'semester'
            },
            {
                data: 'angakatan'
            },
            {
                data: 'aksi'
            }
            ]
        });
    </script>

</body>

</html>
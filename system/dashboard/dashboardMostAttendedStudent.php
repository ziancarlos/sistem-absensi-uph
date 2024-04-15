<?php
require_once ("dataStudentFunction.php");
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
                    <h1 class="h3 mb-4 text-gray-800">Mahasiswa Paling Rajin</h1>

                    &nbsp;
                    <!-- Tabel Histori Absensi -->
                    <table id="example" class="display cell-border " style="width:100%">
                        <thead>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Jumlah Kehadiran</th>
                            <th>Jumlah Ketidakhadiran</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            <?php foreach ($data['attendances'] as $attendance): ?>
                            <tr>
                                <td>
                                    <?= $attendance['StudentId'] ?>
                                </td>
                                <td>
                                    <?= $attendance['Name'] ?>
                                </td>
                                <td>
                                    <?= $attendance['AttendanceCount'] ?>
                                </td>
                                <td>
                                    <?= $attendance['AbsenceCount'] ?>
                                </td>
                                <td style="display: flex; gap: 5px;">
                                    <a href="mostAttendedDetail.php?StudentId=<?= $attendance["StudentId"] ?>"
                                        class="btn btn-success btn-sm">Detail</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
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

    <!-- Custom scripts for all pages-->
    <?php require_once ("../components/js.php"); ?>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        var table = $('#example').DataTable({
            columns: [{
                    data: 'kode_mk'
                },
                {
                    data: 'mata_kuliah'
                },
                {
                    data: 'jumlah_kehadiran'
                },
                {
                    data: 'jumlah_ketidakhadiran'
                },
                {
                    data: 'aksi'
                }
            ],
            order: [
                [2, 'desc'] 
            ]
        });
    });
    </script>

</body>

</html>
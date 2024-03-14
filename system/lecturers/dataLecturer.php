<?php
require_once("dataLecturerFunction.php");
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
                    <h1 class="h3 mb-4 text-gray-800">List Dosen</h1>
                    <table id="example" class="display cell-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Dosen</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data["users"] as $lecturer): ?>
                            <tr>
                                <td>
                                    <?= $lecturer["UserId"] ?>
                                </td>
                                <td>
                                    <?= $lecturer["Name"] ?>
                                </td>
                                <td>
                                    <?= $lecturer["Email"] ?>
                                </td>
                                <td>
                                    <?php if ($lecturer["Status"] == 1): ?>
                                    <span class="badge badge-primary">Aktif</span>
                                    <?php else: ?>
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                    <?php endif; ?>


                                </td>
                                <td style="display: flex; gap: 5px;">
                                    <form action="dataLecturerDetail.php" method="post" style="display: inline-block;">
                                        <button type="submit" name="detailView" value="<?= $lecturer["UserId"]; ?>"
                                            class="btn btn-info btn-sm" style="width: 90px">Detail</button>
                                    </form>
                                    <form action="updateLecturer.php" method="post" style="display: inline-block;">
                                        <button type="submit" name="ubahView" value="<?= $lecturer["UserId"]; ?>"
                                            class="btn btn-primary btn-sm" style="width: 90px">Edit</button>
                                    </form>

                                    <?php if ($lecturer["Status"] == 1): ?>
                                    <form action="deactivateLecturerFunction.php" method="post"
                                        style="display: inline-block;">
                                        <button type="submit" name="deactivate" value="<?= $lecturer["UserId"]; ?>"
                                            class="btn btn-danger btn-sm" style="width: 90px">Non Aktif</button>
                                    </form>
                                    <?php else: ?>
                                    <form action="activateLecturerFunction.php" method="post"
                                        style="display: inline-block;">
                                        <button type="submit" name="activate" value="<?= $lecturer["UserId"]; ?>"
                                            class="btn btn-success btn-sm" style="width: 90px">Aktifkan</button>
                                    </form>
                                    <?php endif; ?>
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






    <?php require_once("../components/js.php"); ?>


    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
    new DataTable('#example', {
        columns: [ {
                data: 'nama_dosen'
            },
            {
                data: 'nip'
            },
            {
                data: 'email'
            },
            {
                data: 'status'
            },
            {
                data: 'aksi'
            }
        ]
    });
    </script>

</body>

</html>
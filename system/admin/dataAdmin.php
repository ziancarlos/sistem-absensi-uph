<?php
require_once ("dataAdminFunction.php");
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
                    <h1 class="h3 mb-4 text-gray-800">List Admin</h1>
                    <table id="example" class="display cell-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Admin</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data["users"] as $admin): ?>
                                <tr>
                                    <td>
                                        <?= $admin["UserId"] ?>
                                    </td>
                                    <td>
                                        <?= $admin["Name"] ?>
                                    </td>
                                    <td>
                                        <?= $admin["Email"] ?>
                                    </td>
                                    <td>
                                        <?php if ($admin["Status"] == 1): ?>
                                            <span class="badge badge-primary">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        <?php endif; ?>


                                    </td>
                                    <td style="display: flex; gap: 5px;">
                                        <form action="updateAdmin.php" method="post" style="display: inline-block;">
                                            <button type="submit" name="ubahView" value="<?= $admin["UserId"]; ?>"
                                                class="btn btn-primary btn-sm" style="width: 90px">Edit</button>
                                        </form>

                                        <?php if ($admin["Status"] == 1): ?>
                                            <form action="deactivateAdminFunction.php" method="post"
                                                style="display: inline-block;">
                                                <button type="submit" name="deactivate" value="<?= $admin["UserId"]; ?>"
                                                    class="btn btn-danger btn-sm" style="width: 90px">Non Aktif</button>
                                            </form>
                                        <?php else: ?>
                                            <form action="activateAdminFunction.php" method="post"
                                                style="display: inline-block;">
                                                <button type="submit" name="activate" value="<?= $admin["UserId"]; ?>"
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






    <?php require_once ("../components/js.php"); ?>


    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        new DataTable('#example', {
            columns: [{
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
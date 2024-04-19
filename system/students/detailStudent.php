<?php
require_once("detailStudentFunction.php");
?>

<?php require_once("../components/header.php"); ?>
<head>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
</head>
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
                    <h1 class="h3 mb-4 text-gray-800">Mata Kuliah yang Diambil Mahasiswa</h1>
                    <table id="example" class="display cell-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID Pendaftaran</th>
                                <th>Mata Kuliah</th>
                                <th>Kode MK</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Ruang</th>
                                <th>Status MK Mhs</th>
                                <th>Status MK</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data["courses"] as $course): ?>
                            <tr>
                                <td>
                                    <?= $course["EnrollmentId"] ?>
                                </td>
                                <td>
                                    <?= $course["Name"] ?>
                                </td>
                                <td>
                                    <?= $course["Code"] ?>
                                </td>
                                <td>
                                    <?= $course["StartTime"] ?>
                                </td>
                                <td>
                                    <?= $course["EndTime"] ?>
                                </td>

                                <td>
                                    <?= $course["Room"] ?>
                                </td>
                                <td>
                                    <?php if ($course["EnrollmentStatus"] == 1): ?>
                                    <span class="badge badge-primary">Aktif</span>
                                    <?php else: ?>
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                    <?php endif; ?>

                                </td>
                                <td>
                                    <?php if ($course["CoursesStatus"] == 1): ?>
                                    <span class="badge badge-primary">Aktif</span>
                                    <?php else: ?>
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                    <?php endif; ?>

                                </td>
                                <td style="display: flex; gap: 5px;">
                                    <?php if ($course["EnrollmentStatus"] == 1): ?>
                                    <form action="deactivateEnrollmentFunction.php" method="post"
                                        style="display: inline-block;">
                                        <button type="submit" name="deactivate" value="<?= $course["EnrollmentId"]; ?>"
                                            class="btn btn-danger btn-sm" style="width: 90px">Non Aktif</button>
                                    </form>
                                    <?php else: ?>
                                    <form action="activateEnrollmentFunction.php" method="post"
                                        style="display: inline-block;">
                                        <button type="submit" name="activate" value="<?= $course["EnrollmentId"]; ?>"
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

        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Custom scripts for all pages-->
    <?php require_once("../components/js.php"); ?>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script>
    new DataTable('#example', {
        responsive : true,
        columns: [{
                data: 'idpendaftaran'
            }, {
                data: 'mataKuliah'
            },
            {
                data: 'tangalMulai'
            },
            {
                data: 'tangalSelesai'
            }, {
                data: 'tangalSelesai'
            },
            {
                data: 'ruangan'
            },
            {
                data: 'ruangazn'
            },
            {
                data: 'statuss'
            },
            {
                data: 'aksi'
            }
        ]
    });
    </script>
</body>

</html>

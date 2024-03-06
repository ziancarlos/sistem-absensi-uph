<?php
require_once("detailStudentFunction.php");
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
                    <h1 class="h3 mb-4 text-gray-800">Mata Kuliah yang Diambil Mahasiswa</h1>
                    <table id="example" class="display cell-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID Pendaftaran</th>
                                <th>Mata Kuliah</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Ruang</th>
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
                                    <?= $course["StartDate"] ?>
                                </td>
                                <td>
                                    <?= $course["EndDate"] ?>
                                </td>
                                <td>
                                    <?= $course["Room"] ?>
                                </td>
                                <td style="display: flex; gap: 5px;">
                                    <a class="btn btn-info btn-sm" href="" style="width: 90px">Detil</a>
                                    <a class="btn btn-danger btn-sm" href="" style="width: 90px">Non Aktif</a>
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
    <script>
    new DataTable('#example', {
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
            },
            {
                data: 'ruangan'
            },
            {
                data: 'aksi'
            }
        ]
    });
    </script>
</body>

</html>
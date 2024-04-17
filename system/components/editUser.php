<?php
require_once("../../helper/dbHelper.php");
require_once("../../helper/authHelper.php");
session_start();

$permittedRole = ["student", "lecturer", "admin"];
$pageName = "Sistem Absensi UPH - Edit Profil";
$data = [];

if (!authorization($permittedRole, $_SESSION["UserId"])) {
    header('location: ../auth/login.php');
    exit;
}

// Function to get user info by UserId
function getUserById($userId) {
    $connection = getConnection();

    try {
        $sql = "SELECT Name, Email, Password FROM Users WHERE UserId = :userId";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':userId', $userId);
        $statement->execute();
        $userInfo = $statement->fetch(PDO::FETCH_ASSOC);
        return $userInfo;
    } catch (PDOException $e) {
        // Handle error
        return null;
    }
}

// Get user info by UserId
$user = getUserById($_SESSION["UserId"]);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once("editUserFunction.php");
    if (isset($_POST["edit"])) {
        $name = htmlspecialchars($_POST["name"]);
        $email = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);

        // Panggil function untuk mengupdate informasi pengguna ke dalam database
        $result = updateUser($_SESSION["UserId"], $name, $email, $password);

        if ($result) {
            $_SESSION['success'] = "Informasi pengguna berhasil diperbarui";
            header('location: ../dashboard/dashboard.php');
            exit;
        } else {
            $_SESSION['error'] = "Gagal memperbarui informasi pengguna";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("../components/header.php"); ?>
</head>
<body id="page-top">
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
                    <h1 class="h3 mb-4 text-gray-800">Edit Profil</h1>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" action="editUserFunction.php">
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Nama</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="inputName" name="name" value="<?= $user['Name'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" id="inputEmail" name="email" value="<?= $user['Email'] ?>">
                                            </div>
                                        </div>                                    
                                        <div class="form-group row">
                                            <label for="inputPass" class="col-sm-2 col-form-label">Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" id="inputPass" name="password" value="">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputKonfirmasiPass" class="col-sm-2 col-form-label">Konfirmasi Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" id="inputKonfirmasiPass" name="confirm_password" value="">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button type="submit" class="btn btn-primary" name="edit">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
</body>
</html>

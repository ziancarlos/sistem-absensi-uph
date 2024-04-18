<?php
if (!isset($_SESSION["UserId"])) {
    header("location: ../auth/login.php");
    exit();
}

// Setelah login berhasil, ambil informasi pengguna dari database
$userId = $_SESSION["UserId"];
$userInfo = getUserInfo($userId);

// Fungsi untuk mengambil informasi pengguna dari database
function getUserInfo($userId)
{
    $connection = getConnection();
    $statement = null;

    try {
        $sql = "SELECT Name FROM Users WHERE UserId = :userId";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':userId', $userId);
        $statement->execute();
        $userInfo = $statement->fetch(PDO::FETCH_ASSOC);
        return $userInfo;
    } catch (PDOException $e) {
        return null;
    }
}

// Setelah mendapatkan informasi pengguna, tampilkan nama pengguna di top bar
$userName = $userInfo['Name'];
?>

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $userName ?></span>
                <img class="img-profile rounded-circle" src="../../assets/img/undraw_profile.svg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="../components/editUser.php">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="../auth/logout.php">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<!-- End of Topbar -->
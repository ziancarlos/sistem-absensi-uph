<?php $role = getUserRole($_SESSION["UserId"]); ?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-text mx-3">ABSENSI</div>
    </a>
    <img src="../../assets/img/UPH-White.png" alt="" class="logo-image">

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        HOME
    </div>

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="../dashboard/dashboard.php">
            <span class="material-symbols-outlined sidebar-dashboard">dashboard</span>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        MAHASISWA
    </div>

    <!-- Nav Item - Mahasiswa Collapse Menu -->
    <?php if ($role != "student"): ?>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMhs" aria-expanded="true"
            aria-controls="collapseMhs">
            <i class="fas fa-fw fa-user-friends	"></i>
            <span>Mahasiswa</span>
        </a>
        <div id="collapseMhs" class="collapse" aria-labelledby="headingMhs" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded">
                <a class="collapse-item" href="../students/dataStudent.php">List Mahasiswa</a>

                <a class="collapse-item" href="../students/addStudent.php">Tambah Mahasiswa</a>

            </div>
        </div>
    </li>
    <?php endif; ?>

    <!-- Nav Item - MK Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMK" aria-expanded="true"
            aria-controls="collapseMK">
            <i class="fas fa-fw fa-user-friends	"></i>
            <span>Mata Kuliah</span>
        </a>
        <div id="collapseMK" class="collapse" aria-labelledby="headingMK" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded">
                <a class="collapse-item" href="../courses/dataCourse.php">List Mata Kuliah</a>
                <?php if ($role != "student"): ?>
                <a class="collapse-item" href="../courses/addCourse.php">Tambah Mata Kuliah</a>
                <a class="collapse-item" href="../classroom/addClassroom.php">Ruang Kelas</a>
                <a class="collapse-item" href="../courses/courseSchedule.php">Jadwal Mata Kuliah</a>
                <?php endif; ?>
            </div>
        </div>
    </li>

    <!-- Nav Item - Absensi Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAbsensi"
            aria-expanded="true" aria-controls="collapseAbsensi">
            <i class="fas fa-fw fas fa-user-friends	"></i>
            <span>Absensi</span>
        </a>
        <div id="collapseAbsensi" class="collapse" aria-labelledby="headingAbsensi" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded">
                <a class="collapse-item" href="../attendances/historyAttendance.php">Histori Absensi</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <?php if ($role == "admin"): ?>
    <!-- Heading -->
    <div class="sidebar-heading">
        DOSEN
    </div>

    <!-- Nav Item - Dosen Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDosen" aria-expanded="true"
            aria-controls="collapseDosen">
            <i class="fas fa-fw fas fa-user-alt	"></i>
            <span>Dosen</span>
        </a>
        <div id="collapseDosen" class="collapse" aria-labelledby="headingDosen" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded">
                <a class="collapse-item" href="../lecturers/dataLecturer.php">List Dosen</a>
                <a class="collapse-item" href="../lecturers/addLecturer.php">Tambah Dosen</a>
            </div>
        </div>
    </li>

    <?php endif; ?>


    <!-- Divider -->
    <hr class="sidebar-divider">
    <?php if ($role == "admin"): ?>
    <!-- Heading -->
    <div class="sidebar-heading">
        Admin
    </div>

    <!-- Nav Item - Admin Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdmin" aria-expanded="true"
            aria-controls="collapseAdmin">
            <i class="fas fa-fw fas fa-user-alt	"></i>
            <span>Admin</span>
        </a>
        <div id="collapseAdmin" class="collapse" aria-labelledby="headingAdmin" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded">
                <a class="collapse-item" href="../admin/dataAdmin.php">List Admin</a>
                <a class="collapse-item" href="../admin/addAdmin.php">Tambah Admin</a>
            </div>
        </div>
    </li>

    <?php endif; ?>
</ul>
<!-- Bootstrap core JavaScript-->
<script src="../../assets/vendor/jquery/jquery.min.js"></script>
<script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../../assets/js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="../../assets/vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="../../assets/js/demo/chart-area-demo.js"></script>
<script src="../../assets/js/demo/chart-bar-demo.js"></script>



<!-- If there is error session -->
<?php if (isset($_SESSION['error'])): ?>
    <script>
        Swal.fire({
            title: "Error!",
            text: "<?= ($_SESSION['error'] == "default") ? "Gagal memuat database, Silahkan hubungin admin!" : $_SESSION['error'] ?>",
            icon: "error",
            confirmButtonText: "Ok!"
        })
    </script>
    <?php unset($_SESSION['error']) ?>
<?php endif; ?>

<!-- If there is succes session -->
<?php if (isset($_SESSION['success'])): ?>
    <script>
        Swal.fire({
            title: "success!",
            text: "<?= $_SESSION['success'] ?>",
            icon: "success",
            confirmButtonText: "Ok!"
        })
    </script>
    <?php unset($_SESSION['success']) ?>
<?php endif; ?>
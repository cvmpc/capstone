<?php
session_start();
if (!isset($_SESSION['email'])) { 
    // If not logged in, redirect to the login page 
    header("Location: ../login.php"); 
    exit; 
}
?>
<!DOCTYPE html>
<html lang="en">
<?php require '../includes/head.php' ?>

<body>
    <!-- ======= Header ======= -->
    <?php require '../includes/header.php' ?>
    <!-- ======= Sidebar ======= -->
    <?php require '../includes/navbar.php' ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Blank Page</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Pages</li>
                    <li class="breadcrumb-item active">Blank</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Example Card</h5>
                            <p>This is an examle page with no contrnt. You can use it as a starter for your custom pages.</p>
                        </div>
                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Example Card</h5>
                            <p>This is an examle page with no contrnt. You can use it as a starter for your custom pages.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->


    <!-- ======= Footer ======= -->
    <?php require '../includes/footer.php' ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/chart.js/chart.umd.js"></script>
    <script src="../assets/vendor/echarts/echarts.min.js"></script>
    <script src="../assets/vendor/quill/quill.js"></script>
    <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="../assets/js/main.js"></script>

</body>

</html>
<?php

require '../includes/function.php';
if (!isset($_SESSION['id'])) {
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
            <h1>Add New
                Staff</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Staff</li>
                    <li class="breadcrumb-item active">Add Staff</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section" style="display: flex; justify-content: center;">
            <div class="row" style="width: 100%;">
                <div class="col-xl-8" style="margin: 0 auto; width: 100%; max-width: 800px;">

                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pesonal-information">Add new Member</button>
                                </li>

                            </ul>
                            <div class="tab-content pt-2">

                                <div class="tab-pane fade show active profile-overview" id="pesonal-information">



                                    <form action="../check.php" method="POST" class="row g-3 needs-validation" novalidate>
                                        <h5 class="card-title">Personal Information</h5>

                                        <div class="col-12">
                                            <label for="hire-date" class="form-label">Hire Date</label>
                                            <input type="date" name="hire-date" class="form-control" id="hire-date">

                                        </div>

                                        <div class="col-md-4">
                                            <label for="firstname" class="form-label">First Name</label>
                                            <input type="text" name="firstname" class="form-control" id="firstname" required>
                                            <div class="invalid-feedback">Please, enter your first name!</div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="middlename" class="form-label">Middle Name</label>
                                            <input type="text" name="middlename" class="form-control" id="middlename" required>
                                            <div class="invalid-feedback">Please, enter your middle name!</div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="lastname" class="form-label">Last Name</label>
                                            <input type="text" name="lastname" class="form-control" id="lastname" required>
                                            <div class="invalid-feedback">Please, enter your last name!</div>
                                        </div>

                                        <div class="col-12">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" id="email" required>
                                            <div class="invalid-feedback">Please enter a valid Email adddress!</div>
                                        </div>

                                        <h5 class="card-title">Address Information</h5>
                                        <div class="col-md-12">
                                            <label for="province" class="form-label">Province</label>
                                            <input type="text" name="province" class="form-control" id="province">

                                        </div>

                                        <div class="col-md-12">
                                            <label for="city" class="form-label">City/Municipality</label>
                                            <input type="text" name="city" class="form-control" id="city">

                                        </div>

                                        <div class="col-md-12">
                                            <label for="barangay" class="form-label">Barangay</label>
                                            <input type="text" name="barangay" class="form-control" id="barangay">

                                        </div>

                                        <div class="col-12">
                                            <label for="sitio" class="form-label">Sitio</label>
                                            <input type="text" name="sitio" class="form-control" id="sitio">

                                        </div>

                                        <h5 class="card-title">Account Setup</h5>
                                        <div class="col-12">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" id="password" required>
                                            <div class="invalid-feedback">Please enter your password!</div>
                                        </div>

                                        <div class="col-12">
                                            <label for="repeatPassword" class="form-label">Repeat Password</label>
                                            <input type="password" name="repeatPassword" class="form-control" id="repeatPassword" required>
                                            <div class="invalid-feedback">Please enter your password!</div>
                                        </div>

                                        <div class="col-12" style="display: flex; justify-content: center; gap: 10px;">
                                            <button onclick="window.location.href='staff.php';" type="button" class="btn btn-outline-primary" style="flex: 1;">Cancel</button>
                                            <button class="btn btn-primary" type="submit" name="addStaff" style="flex: 1;">Submit</button>
                                        </div>

                                    </form>
                                </div>


                            </div><!-- End Bordered Tabs -->

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
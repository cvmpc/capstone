<?php
session_start();
require '../includes/db_con.php';
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
            <h1>Loan Plans</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Loan Plan</li>
                    <li class="breadcrumb-item active">Loan Plan List</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">

                            <h5 class="card-title" style="display: inline-block; margin-right: 10px;">Loan Plan List</h5>
                            <a href="add-loan-plan.php"><button type="button" class="btn btn-primary" name="addPlan" style="display: inline-block; vertical-align: middle;"><i class="bi bi-plus-lg me-1"></i> Add Loan Plan</button></a>

                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>
                                            LOAN PLAN ID
                                        </th>
                                        <th>LOAN TYPE</th>
                                        <th>INTEREST RATE</th>

                                        <th>PENALTY RATE</th>
                                        <th>MONTHLY TERM</th>
                                        <th>STATUS</th>
                                        <th>ACTION</th>

                                    </tr>
                                </thead>
                                <?php
                                $sql = "SELECT CONCAT('LP', LPAD(loan_plan_id, 3, '0')) AS loanPlanID, 
                                            loan_plan_id, type_name, interest_rate, penalty_rate, monthly_term, status 
                                        FROM loan_plan lp 
                                        left join loan_types lt 
                                        on lp.loan_type_id = lt.loan_type_id;";

                                $users = $pdo->query($sql);
                                ?>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?= $user['loanPlanID'] ?></td>
                                            <td><?= $user['type_name'] ?></td>
                                            <td><?= $user['interest_rate'] ?></td>
                                            <td><?= $user['penalty_rate'] ?></td>
                                            <td><?= $user['monthly_term'] ?></td>
                                            <td><?= $user['status'] ?></td>
                                            <td>
                                                <div class="pt-2">
                                                    <!-- <a href="member-profile.php?member_id=<?= $user['member_id']; ?>" class="btn btn-primary btn-sm" title="View member's Profille"><i class="bi bi-pencil-square"></i></a>
                                                    <a href="../check.php?archive=true&member_id=<?php echo $user['member_id']; ?>" class="btn btn-danger btn-sm" title="Deactivate member"><i class="bi bi-person-x"></i></a>
                                                </div> -->
                                                    <a href="edit-loan-plan.php?loan_plan_id=<?= $user['loan_plan_id']; ?>" class="btn btn-primary btn-sm" title="Edit Loan Plan"><i class="bi bi-pencil-square"></i></a>
                                                    <?php
                                                    if ($user['status'] == 'Deactivated'): ?>
                                                        <a href="../check.php?activate=true&loan_plan_id=<?= $user['loan_plan_id']; ?>" class="btn btn-success btn-sm" title="Activate Loan Plan"><i class="bi bi-check2-square"></i></a>
                                                    <?php else: ?>
                                                        <a href="../check.php?deactivate=true&loan_plan_id=<?= $user['loan_plan_id']; ?>" class="btn btn-danger btn-sm" title="Deactivate Loan Plan"><i class="bi bi-x-square"></i></a>
                                                    <?php endif; ?>



                                            </td>

                                        </tr>
                                    <?php endforeach;
                                    ?>

                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

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
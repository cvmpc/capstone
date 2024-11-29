<?php
session_start();
require '../includes/db_con.php';
if (!isset($_SESSION['id'])) {
    // If not logged in, redirect to the login page 
    header("Location: ../login.php");
    exit;
}

// Get the logged-in user's member ID
$member_id = $_SESSION['id'];
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
            <h1>Loan Application Status</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Loans</li>
                    <li class="breadcrumb-item active">Loan Application Status</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <br>
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>APPLICATION ID</th>
                                        <th>LOAN TYPE</th>
                                        <th>AMOUNT REQUESTED</th>
                                        <th>APPLICATION DATE</th>
                                        <th>STATUS</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <?php
                                $sql = "SELECT CONCAT('LA', LPAD(la.application_id, 4, '0')) AS applicationID, 
                                            la.application_id, 
                                            lt.type_name AS loan_type, 
                                            la.amount_requested, 
                                            la.application_date, 
                                            la.status
                                        FROM 
                                            loan_application la
                                        LEFT JOIN 
                                            loan l ON la.application_id = l.application_id
                                        LEFT JOIN 
                                            loan_plan lp ON l.loan_plan_id = lp.loan_plan_id
                                        LEFT JOIN 
                                            loan_types lt ON lp.loan_type_id = lt.loan_type_id
                                        WHERE 
                                            la.member_id = :member_id and la.status != 'Active'";

                                $stmt = $pdo->prepare($sql);
                                $stmt->execute(['member_id' => $member_id]);
                                $loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                <tbody>
                                    <?php foreach ($loans as $loan): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($loan['applicationID']) ?></td>
                                            <td><?= htmlspecialchars($loan['loan_type']) ?></td>
                                            <td><?= number_format($loan['amount_requested'], 2) ?></td>
                                            <td><?= htmlspecialchars($loan['application_date']) ?></td>
                                            <td><?= htmlspecialchars($loan['status']) ?></td>
                                            <td>
                                                <div class="pt-2">
                                                    <a href="loan-details.php?application_id=<?= htmlspecialchars($loan['application_id']); ?>" class="btn btn-primary btn-sm" title="View Details"><i class="bi bi-eye"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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
<?php
require '../includes/function.php';

if (!isset($_SESSION['id'])) {
    // If not logged in, redirect to the login page
    header("Location: ../login.php");
    exit;
}

// Get the loan_plan_id from the URL
$loan_plan_id = isset($_GET['loan_plan_id']) ? intval($_GET['loan_plan_id']) : 0;

// Fetch loan plan details based on provided ID
$loanPlan = $pdo->prepare("SELECT * FROM loan_plan WHERE loan_plan_id = :loan_plan_id");
$loanPlan->execute(['loan_plan_id' => $loan_plan_id]);
$loanPlanData = $loanPlan->fetch(PDO::FETCH_ASSOC);

// Fetch loan types from the loan_types table
$loanTypes = $pdo->query("SELECT loan_type_id, type_name FROM loan_types")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<?php require '../includes/head.php'; ?>

<body>
    <!-- ======= Header ======= -->
    <?php require '../includes/header.php'; ?>
    <!-- ======= Sidebar ======= -->
    <?php require '../includes/navbar.php'; ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Edit Loan Plan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Loan Plan</li>
                    <li class="breadcrumb-item active">Edit Loan Plan</li>
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
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#edit-loan-plan">Edit Loan Plan</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview" id="edit-loan-plan">
                                    <form action="../check.php" method="POST" class="row g-3 needs-validation" novalidate>
                                        <h5 class="card-title">Loan Plan Details</h5>
                                        <input type="hidden" name="loan_plan_id" value="<?= htmlspecialchars($loanPlanData['loan_plan_id']) ?>">

                                        <div class="row mb-12">
                                            <label class="col-sm-2 col-form-label">Loan Type</label>
                                            <div class="col-sm-10">
                                                <select class="form-select" name="loan-type" required>
                                                    <option value="">Please Select Loan Type</option>
                                                    <?php foreach ($loanTypes as $loanType): ?>
                                                        <option value="<?= htmlspecialchars($loanType['loan_type_id']) ?>" <?= $loanType['loan_type_id'] == $loanPlanData['loan_type_id'] ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($loanType['type_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback">Please, choose loan type</div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="interest-rate" class="form-label">Interest Rate (%)</label>
                                            <div class="input-group">
                                                <input type="number" name="interest-rate" class="form-control" id="interest-rate" placeholder="Enter rate" step="0.01" value="<?= htmlspecialchars($loanPlanData['interest_rate']) ?>" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback">Please, enter Interest Rate in percentage!</div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="penalty-rate" class="form-label">Penalty Rate (%)</label>
                                            <div class="input-group">
                                                <input type="number" name="penalty-rate" class="form-control" id="penalty-rate" placeholder="Enter rate" step="0.01" value="<?= htmlspecialchars($loanPlanData['penalty_rate']) ?>" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback">Please, enter Penalty Rate in percentage!</div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="monthly-term" class="form-label">Monthly Term</label>
                                            <input type="number" name="monthly-term" class="form-control" id="monthly-term" value="<?= htmlspecialchars($loanPlanData['monthly_term']) ?>" required>
                                            <div class="invalid-feedback">Please enter Monthly Term!</div>
                                        </div>

                                        <div class="col-12" style="display: flex; justify-content: center; gap: 10px;">
                                            <button onclick="window.location.href='loan-plan.php';" type="button" class="btn btn-outline-primary" style="flex: 1;">Cancel</button>
                                            <button class="btn btn-primary" type="submit" name="editLoanPlan" style="flex: 1;">Submit</button>
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
    <?php require '../includes/footer.php'; ?>

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
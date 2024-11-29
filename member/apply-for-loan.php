<?php
require '../includes/function.php';
session_start();
if (!isset($_SESSION['id'])) {
    // If not logged in, redirect to the login page 
    header("Location: ../login.php");
    exit;
}

try {
    // Fetch loan types
    $loanTypeStmt = $pdo->prepare("SELECT loan_type_id, type_name FROM loan_types");
    $loanTypeStmt->execute();
    $loanTypes = $loanTypeStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch loan plans
    $loanPlanStmt = $pdo->prepare("SELECT loan_plan_id, interest_rate, penalty_rate, monthly_term, loan_type_id FROM loan_plan");
    $loanPlanStmt->execute();
    $loanPlans = $loanPlanStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
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
            <h1>Apply for Loan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Loan</li>
                    <li class="breadcrumb-item active">Apply for Loan</li>
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
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#personal-information">Apply for Loan</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview" id="personal-information">

                                    <form action="../check.php" method="POST" class="row g-3 needs-validation" novalidate>
                                        <h5 class="card-title">Loan Type Selection</h5>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Loan Type</label>
                                            <div class="col-sm-10">
                                                <select class="form-select" name="loan-type" id="loan-type" required>
                                                    <option value="">Please Select Loan Type</option>
                                                    <?php foreach ($loanTypes as $loanType): ?>
                                                        <option value="<?= htmlspecialchars($loanType['loan_type_id']) ?>">
                                                            <?= htmlspecialchars($loanType['type_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback">Please, choose a loan type</div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="purpose" class="col-sm-2 col-form-label">Purpose</label>
                                            <div class="col-sm-10">
                                                <textarea name="purpose" class="form-control" id="purpose" style="height: 100px" required></textarea>
                                            </div>
                                            <div class="invalid-feedback">Please, enter your purpose!</div>
                                        </div>

                                        <h5 class="card-title">Loan Details</h5>

                                        <div class="row mb-3">
                                            <label for="amount-requested" class="col-sm-2 col-form-label">Amount Requested</label>
                                            <div class="col-sm-10">
                                                <input type="number" name="amount-requested" class="form-control" id="amount-requested" required>
                                            </div>
                                            <div class="invalid-feedback">Please, enter the amount requested</div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Loan Plan</label>
                                            <div class="col-sm-10">
                                                <select class="form-select" name="loan-plan" id="loan-plan" required>
                                                    <option value="">Please Select Loan Plan</option>
                                                    <!-- Options will be populated by JavaScript -->
                                                </select>
                                                <div class="invalid-feedback">Please, choose a loan plan</div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary" name="applyNow">Apply Now</button>
                                        </div>
                                    </form>

                                </div>

                            </div>

                        </div><!-- End Bordered Tabs -->

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

    <!-- JavaScript to Filter Loan Plans Based on Loan Type -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loanTypeSelect = document.getElementById('loan-type');
            const loanPlanSelect = document.getElementById('loan-plan');
            const loanPlans = <?= json_encode($loanPlans) ?>;

            loanTypeSelect.addEventListener('change', function() {
                const selectedLoanType = this.value;
                loanPlanSelect.innerHTML = '<option value="">Please Select Loan Plan</option>';

                const filteredLoanPlans = loanPlans.filter(plan => plan.loan_type_id == selectedLoanType);
                filteredLoanPlans.forEach(plan => {
                    const option = document.createElement('option');
                    option.value = plan.loan_plan_id;
                    option.textContent = `${plan.monthly_term} months [${plan.interest_rate}%, ${plan.penalty_rate}%]`;
                    loanPlanSelect.appendChild(option);
                });
            });
        });
    </script>

</body>

</html>
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
            <h1>My Loans</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Loans</li>
                    <li class="breadcrumb-item active">My Loans</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <br>
                            <p>Note: The loan plan format is displayed as <strong>monthly term [interest rate%, penalty rate%]</strong>.</p>
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>LOAN ID</th>
                                        <th>LOAN TYPE</th>
                                        <th>MONTHLY PAYMENT</th>
                                        <th>LOAN PLAN</th>
                                        <th>STATUS</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <?php
                                $sql = "SELECT CONCAT('L', LPAD(loan_id, 4, '0')) AS loanID,
                                    l.loan_id,
                                    lt.type_name AS loan_type,
                                    lt.description AS loan_description,
                                    l.principal_amount,
                                    (l.principal_amount * lp.interest_rate / 100) / lp.monthly_term AS monthly_payment,
                                    CONCAT(lp.monthly_term, ' [', lp.interest_rate, '%, ', lp.penalty_rate, '%]') AS loan_plan,
                                    l.status,
                                    l.start_date,
                                    l.end_date,
                                    l.remaining_balance,
                                    l.release_date,
                                    la.amount_requested,
                                    la.purpose,
                                    la.application_date
                                FROM 
                                    loan l
                                LEFT JOIN 
                                    loan_application la ON l.application_id = la.application_id
                                LEFT JOIN 
                                    loan_plan lp ON l.loan_plan_id = lp.loan_plan_id
                                LEFT JOIN 
                                    loan_types lt ON lp.loan_type_id = lt.loan_type_id
                                LEFT JOIN 
                                    user_credentials uc ON la.member_id = uc.uc_id
                                WHERE 
                                    la.member_id = :member_id and l.status != 'On Progress'";

                                $stmt = $pdo->prepare($sql);
                                $stmt->execute(['member_id' => $member_id]);
                                $loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                <tbody>
                                    <?php foreach ($loans as $loan): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($loan['loanID']) ?></td>
                                            <td><?= htmlspecialchars($loan['loan_type']) ?></td>
                                            <td><?= number_format($loan['monthly_payment'], 2) ?></td>
                                            <td><?= htmlspecialchars($loan['loan_plan']) ?></td>
                                            <td><?= htmlspecialchars($loan['status']) ?></td>
                                            <td>
                                                <div class="pt-2">
                                                    <button class="btn btn-primary btn-sm view-details" data-bs-toggle="modal" data-bs-target="#loanDetailsModal"
                                                        data-loan-id="<?= htmlspecialchars($loan['loan_id']) ?>"
                                                        data-loan-type="<?= htmlspecialchars($loan['loan_type']) ?>"
                                                        data-loan-description="<?= htmlspecialchars($loan['loan_description']) ?>"
                                                        data-principal-amount="<?= htmlspecialchars($loan['principal_amount']) ?>"
                                                        data-monthly-payment="<?= number_format($loan['monthly_payment'], 2) ?>"
                                                        data-loan-plan="<?= htmlspecialchars($loan['loan_plan']) ?>"
                                                        data-status="<?= htmlspecialchars($loan['status']) ?>"
                                                        data-start-date="<?= htmlspecialchars($loan['start_date']) ?>"
                                                        data-end-date="<?= htmlspecialchars($loan['end_date']) ?>"
                                                        data-remaining-balance="<?= htmlspecialchars($loan['remaining_balance']) ?>"
                                                        data-release-date="<?= htmlspecialchars($loan['release_date']) ?>"
                                                        data-amount-requested="<?= htmlspecialchars($loan['amount_requested']) ?>"
                                                        data-purpose="<?= htmlspecialchars($loan['purpose']) ?>"
                                                        data-application-date="<?= htmlspecialchars($loan['application_date']) ?>"
                                                        title="View Details"><i class="bi bi-eye"></i></button>
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

    <!-- Modal -->
    <div class="modal fade" id="loanDetailsModal" tabindex="-1" aria-labelledby="loanDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loanDetailsModalLabel">Loan Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Loan ID:</strong> <span id="loan-id"></span></p>
                    <p><strong>Loan Type:</strong> <span id="loan-type"></span></p>
                    <p><strong>Loan Description:</strong> <span id="loan-description"></span></p>
                    <p><strong>Principal Amount:</strong> <span id="principal-amount"></span></p>
                    <p><strong>Monthly Payment:</strong> <span id="monthly-payment"></span></p>
                    <p><strong>Loan Plan:</strong> <span id="loan-plan"></span></p>
                    <p><strong>Status:</strong> <span id="status"></span></p>
                    <p><strong>Start Date:</strong> <span id="start-date"></span></p>
                    <p><strong>End Date:</strong> <span id="end-date"></span></p>
                    <p><strong>Remaining Balance:</strong> <span id="remaining-balance"></span></p>
                    <p><strong>Release Date:</strong> <span id="release-date"></span></p>
                    <p><strong>Amount Requested:</strong> <span id="amount-requested"></span></p>
                    <p><strong>Purpose:</strong> <span id="purpose"></span></p>
                    <p><strong>Application Date:</strong> <span id="application-date"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle modal data -->
    <script>
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('loan-id').textContent = this.getAttribute('data-loan-id');
                document.getElementById('loan-type').textContent = this.getAttribute('data-loan-type');
                document.getElementById('loan-description').textContent = this.getAttribute('data-loan-description');
                document.getElementById('principal-amount').textContent = this.getAttribute('data-principal-amount');
                document.getElementById('monthly-payment').textContent = this.getAttribute('data-monthly-payment');
                document.getElementById('loan-plan').textContent = this.getAttribute('data-loan-plan');
                document.getElementById('status').textContent = this.getAttribute('data-status');
                document.getElementById('start-date').textContent = this.getAttribute('data-start-date');
                document.getElementById('end-date').textContent = this.getAttribute('data-end-date');
                document.getElementById('remaining-balance').textContent = this.getAttribute('data-remaining-balance');
                document.getElementById('release-date').textContent = this.getAttribute('data-release-date');
                document.getElementById('amount-requested').textContent = this.getAttribute('data-amount-requested');
                document.getElementById('purpose').textContent = this.getAttribute('data-purpose');
                document.getElementById('application-date').textContent = this.getAttribute('data-application-date');
            });
        });
    </script>

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
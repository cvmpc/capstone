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
            <h1>Members</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Member</li>
                    <li class="breadcrumb-item active">Member List</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="display: inline-block; margin-right: 10px;">Member List</h5>
                            <a href="add-member.php"><button type="button" class="btn btn-primary" name="addMember" style="display: inline-block; vertical-align: middle;"><i class="bi bi-plus-lg me-1"></i> Add Member</button></a>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>
                                            MEMBER ID
                                        </th>
                                        <th>FULL NAME</th>

                                        <th data-type="date" data-format="YYYY/DD/MM">REGISTRATION DATE</th>

                                        <th>STATUS</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <?php
                                $sql = "SELECT 
                                            CONCAT('M', LPAD(member_id, 4, '0')) AS memberID, 
                                            member_id, 
                                            CONCAT(first_name, ' ', last_name) AS fullname, 
                                            registration_date, 
                                            status, 
                                            line_of_business 
                                        FROM 
                                            member 
                                         WHERE 
                                           status != 'Archived' OR status IS NULL
                                            ;";
                                $users = $pdo->query($sql);
                                ?>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?= $user['memberID'] ?></td>
                                            <td><?= $user['fullname'] ?></td>

                                            <td><?= !empty($user['registration_date']) ? $user['registration_date'] : 'NOT REGISTERED' ?></td>

                                            <td><?= $user['status'] ?></td>
                                            <td>
                                                <div class="pt-2">
                                                    <a href="member-profile.php?member_id=<?= $user['member_id']; ?>" class="btn btn-primary btn-sm" title="View member's Profile"><i class="bi bi-person"></i></a>
                                                    <a href="#" class="btn btn-info btn-sm" title="Send Notification"><i class="bi bi-cursor"></i></a>
                                                    <?php
                                                    if ($user['status'] == 'Deactivated'): ?>
                                                        <a href="../check.php?activate=true&member_id=<?= $user['member_id']; ?>" class="btn btn-success btn-sm" title="Activate member"><i class="bi bi-person-check"></i></a>
                                                    <?php else: ?>
                                                        <a href="../check.php?deactivate=true&member_id=<?= $user['member_id']; ?>" class="btn btn-warning btn-sm" title="Deactivate member"><i class="bi bi-person-x"></i></a>
                                                    <?php endif; ?>
                                                    <a href="../check.php?deleteMember=true&member_id=<?= $user['member_id']; ?>" class="btn btn-danger btn-sm" title="Delete member"><i class="bi bi-trash"></i></a>
                                                </div>
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
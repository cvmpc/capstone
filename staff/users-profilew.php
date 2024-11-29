<?php
session_start();
require '../includes/db_con.php';
if (!isset($_SESSION['email'])) {
  // If not logged in, redirect to the login page 
  header("Location: ../login.php");
  exit;
} else {
  $userId = $_SESSION['email'];

  try {


    // Fetch user details with a JOIN for admin data


    $stmt = $pdo->prepare("
            SELECT 
                uc.email, uc.role, phone_number, staff_id, last_login, about_me, sitio, barangay, city, first_name, middle_name, last_name,
                CONCAT(sitio, ', ', barangay, ', ', city) AS address, province,
                CONCAT(first_name, ' ', middle_name, ' ', last_name) AS fullname, facebook_link, twitter_link, instagram_link, linkedin_link
            FROM user_credentials uc
            RIGHT JOIN staff a ON uc.uc_id = a.uc_id
            left join user_socials us ON uc.us_id = us.us_id
            LEFT JOIN address ad on ad.address_id = uc.address_id
            WHERE uc.email = :email;
        ");


    $stmt->execute(['email' => $userId]);
    $user = $stmt->fetch();

    if ($user) {
      $fullname = htmlspecialchars($user['fullname']);
      $fname = htmlspecialchars($user['first_name']);
      $mname = htmlspecialchars($user['middle_name']);
      $lname = htmlspecialchars($user['last_name']);
      $initial = !empty($mname) ? substr($mname, 0, 1) . '.' : '';
      $formattedName = htmlspecialchars($fname . ' ' . $initial . ' ' . $lname);
      $role = htmlspecialchars($user['role']);
      $email = htmlspecialchars($user['email']);
      $phone_number = htmlspecialchars($user['phone_number']);
      $staff_id = htmlspecialchars($user['staff_id']);
      $about_me = htmlspecialchars($user['about_me']);
      $address = htmlspecialchars($user['address']);
      $sitio = htmlspecialchars($user['sitio']);
      $barangay = htmlspecialchars($user['barangay']);
      $city = htmlspecialchars($user['city']);
      $province = htmlspecialchars($user['province']);
      $fb_link = htmlspecialchars($user['facebook_link']);
      $tw_link = htmlspecialchars($user['twitter_link']);
      $ig_link = htmlspecialchars($user['instagram_link']);
      $li_link = htmlspecialchars($user['linkedin_link']);
    } else {
      $fullname = "Guest";
      $role = "Unknown";
      $email = "Unknown";
      $phone_number = "Unknown";
      $staff_id = "Unknown";
      $about_me = "Unknown";
      $address = "Unknown";
      $province = "Unknown";
      $fb_link = "Unknown";
      $tw_link = "Unknown";
      $ig_link = "Unknown";
      $li_link = "Unknown";
      $sitio = "Unknown";
      $barangay = "Unknown";
      $city = "Unknown";
    }
  } catch (PDOException $e) {
    echo $e;
  }
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
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="img/profile.jpg" alt="Profile" class="rounded-circle">
              <h2><?php echo $formattedName ?></h2>
              <h3><?php echo $role ?></h3>
              <div class="social-links mt-2">
                <a href="<?php echo $tw_link ?>" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="<?php echo $fb_link ?>" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="<?php echo $ig_link ?>" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="<?php echo $li_link ?>" class="linkedin"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <h5 class="card-title">About</h5>
                  <p class="small fst-italic"><?php echo $about_me ?></p>

                  <h5 class="card-title">Profile Details</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Staff ID</div>
                    <div class="col-lg-9 col-md-8"><?php echo "A" . str_pad($staff_id, 4, "0", STR_PAD_LEFT); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                    <div class="col-lg-9 col-md-8"><?php echo $fullname ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Company</div>
                    <div class="col-lg-9 col-md-8">Calapan Vendors Multi-Purpose Cooperative (CVMPC)</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Role</div>
                    <div class="col-lg-9 col-md-8"><?php echo $role ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Province</div>
                    <div class="col-lg-9 col-md-8"><?php echo $province ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Address</div>
                    <div class="col-lg-9 col-md-8"><?php echo $address ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Phone</div>
                    <div class="col-lg-9 col-md-8"><?php echo $phone_number ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8"><?php echo $email ?></div>
                  </div>

                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form action="../check.php" method="POST">
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                      <div class="col-md-8 col-lg-9">
                        <img src="img/profile.jpg" alt="Profile" class="rounded-circle">
                        <div class="pt-2">
                          <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                          <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="firstname" class="col-md-4 col-lg-3 col-form-label">Firstname</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="firstname" type="text" class="form-control" id="firstname" value="<?= $fname; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="middlename" class="col-md-4 col-lg-3 col-form-label">Middlename</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="middlename" type="text" class="form-control" id="middlename" value="<?= $mname; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="lastname" class="col-md-4 col-lg-3 col-form-label">Lastname</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="lastname" type="text" class="form-control" id="lastname" value="<?= $lname; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                      <div class="col-md-8 col-lg-9">
                        <textarea name="about" class="form-control" id="about" style="height: 100px"><?= $about_me; ?></textarea>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="company" class="col-md-4 col-lg-3 col-form-label">Company</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="company" type="text" class="form-control" id="company" value="Calapan Vendors Multi-Purpose Cooperative (CVMPC)">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="role" class="col-md-4 col-lg-3 col-form-label">Role</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="role" type="text" class="form-control" id="role" value="<?= $role; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="province" class="col-md-4 col-lg-3 col-form-label">Province</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="province" type="text" class="form-control" id="province" value="<?= $province; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="sitio" class="col-md-4 col-lg-3 col-form-label">Sitio</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="sitio" type="text" class="form-control" id="sitio" value="<?= $sitio; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="barangay" class="col-md-4 col-lg-3 col-form-label">Barangay</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="barangay" type="text" class="form-control" id="barangay" value="<?= $barangay; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="city" class="col-md-4 col-lg-3 col-form-label">City/Municipality
                      </label>
                      <div class="col-md-8 col-lg-9">
                        <input name="city" type="text" class="form-control" id="city" value="<?= $city; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="phone" type="text" class="form-control" id="phone" value="<?= $phone_number; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="email" value="<?= $email; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Twitter Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="twitter" type="text" class="form-control" id="Twitter" value="<?= $tw_link; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="facebook" type="text" class="form-control" id="Facebook" value="<?= $fb_link; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="instagram" type="text" class="form-control" id="Instagram" value="<?= $ig_link; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="linkedin" type="text" class="form-control" id="Linkedin" value="<?= $ig_link; ?>">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary" name="saveChanges">Save Changes</button>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-settings">

                  <!-- Settings Form -->
                  <form>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="changesMade" checked>
                          <label class="form-check-label" for="changesMade">
                            Changes made to your account
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="newProducts" checked>
                          <label class="form-check-label" for="newProducts">
                            Information on new products and services
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="proOffers">
                          <label class="form-check-label" for="proOffers">
                            Marketing and promo offers
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="securityNotify" checked disabled>
                          <label class="form-check-label" for="securityNotify">
                            Security alerts
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form><!-- End settings Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form action="../check.php" method="POST">

                    <div class="row mb-3">
                      <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Email Address</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="currentPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newpassword" type="password" class="form-control" id="newPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="repeatPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="repeatpassword" type="password" class="form-control" id="repeatPassword">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary" name="resetPassword">Change Password</button>
                    </div>
                  </form><!-- End Change Password Form -->

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
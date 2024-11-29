<?php
session_start();
require 'includes/db_con.php';

if (isset($_POST['loginAccount'])) {

    // Sanitize inputs
    $email = $_POST['email'];
    $password = $_POST['password'];



    $stmt = $pdo->prepare("SELECT * FROM user_credentials WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if (password_verify($password, $user['password']) && $user) {
        // Store user info in the session
        $_SESSION['id'] = $user['uc_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['loggedin'] = true;

        // Redirect based on user role
        switch ($user['role']) {
            case 'Admin':
                header('Location: admin/index.php');
                break;
            case 'Member':
                header('Location: member/index.php');
                break;
            case 'Staff':
                header('Location: staff/index.php');
                break;
            default:
                header('Location: index.php?error=Unknown role. Please contact support.');
        }
        exit;
    } else {
        echo 'Invalid username or password!';
    }
}

if (isset($_GET['logout'])) {
    // Unset all session variables
    $_SESSION = array(); // Destroy the session 
    session_destroy(); // Redirect to login page or homepage 
    header("Location: index.php");
    exit;
}

if (isset($_POST['createAccount'])) {
    // Capture and sanitize form data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $repeat_password = $_POST['repeatPassword'];

    // Check if email is valid
    if (!$email) {
        echo "Invalid email format!";
        exit;
    }

    // Check if the email already exists
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM user_credentials WHERE email = :email");
    $stmtCheck->execute([':email' => $email]);
    if ($stmtCheck->fetchColumn() > 0) {
        echo "Email already exists!";
        exit;
    }

    // Check if passwords match
    if ($password === $repeat_password) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Begin transaction
            $pdo->beginTransaction();

            // Insert into user_credentials table
            $sql1 = "INSERT INTO user_credentials (email, password, role) VALUES (:email, :password, 'Member')";
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->execute([
                ':email' => $email,
                ':password' => $hashed_password,
            ]);

            // Get the last inserted uc_id
            $uc_id = $pdo->lastInsertId();

            // Insert into member table with uc_id
            $sql2 = "INSERT INTO member (first_name, last_name, uc_id) VALUES (:fname, :lname, :uc_id)";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute([
                ':fname' => $fname,
                ':lname' => $lname,
                ':uc_id' => $uc_id
            ]);

            // Commit transaction
            $pdo->commit();

            echo '<script>alert("Account Created"); window.location.href = "index.php";</script>';
        } catch (PDOException $e) {
            // Roll back transaction in case of an error
            $pdo->rollBack();
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo "Passwords do not match!";
        header('Location: try.php');
    }
}


if (isset($_POST['resetPassword'])) {
    $email = $_POST['email'];
    $newPassword = $_POST['newpassword'];
    $repeatPassword = $_POST['repeatpassword'];

    // Check if passwords match
    if ($newPassword === $repeatPassword) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);




        // Prepare SQL to update the password
        $sql = "UPDATE user_credentials SET password = :hashedPassword WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':hashedPassword' => $hashedPassword,
            ':email' => $email
        ]);

        echo '<script>alert("Password has been reset successfully."); window.location.href = "index.php";</script>';
    } else {
        echo "Passwords do not match!";
    }
}

// if (isset($_POST['saveChanges'])) {

//     $userId = $_SESSION['id'];

//     // Retrieve current user details
//     $stmt = $pdo->prepare("
//         SELECT *
//         FROM user_credentials
//         WHERE uc_id = :uc_id;
//     ");
//     $stmt->execute(['uc_id' => $userId]);
//     $user = $stmt->fetch();

//     if (!$user) {
//         echo '<script>alert("User not found.");</script>';
//         exit;
//     }

//     // Collect form data
//     $email = $_POST['email'];
//     $fname = $_POST['firstname'];
//     $mname = $_POST['middlename'];
//     $lname = $_POST['lastname'];
//     $phone_number = $_POST['phone'];
//     $about_me = $_POST['about'];

//     $sitio = $_POST['sitio'];
//     $barangay = $_POST['barangay'];
//     $city = $_POST['city'];
//     $province = $_POST['province'];

//     $fb_link = $_POST['facebook'];
//     $tw_link = $_POST['twitter'];
//     $ig_link = $_POST['instagram'];
//     $li_link = $_POST['linkedin'];

//     $pp = null; // Placeholder for profile picture filename

//     // Handle profile picture upload
//     if (isset($_FILES['pp']['name']) && !empty($_FILES['pp']['name'])) {
//         $img_name = $_FILES['pp']['name'];
//         $tmp_name = $_FILES['pp']['tmp_name'];
//         $error = $_FILES['pp']['error'];

//         if ($error === 0) {
//             $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
//             $img_ex_to_lc = strtolower($img_ex);

//             $allowed_exs = array('jpg', 'jpeg', 'png');
//             if (in_array($img_ex_to_lc, $allowed_exs)) {
//                 $new_img_name = uniqid($userId, true) . '.' . $img_ex_to_lc;
//                 $img_upload_path = 'profile/' . $new_img_name;
//                 move_uploaded_file($tmp_name, $img_upload_path);
//                 $pp = $new_img_name; // Set the profile picture filename
//             } else {
//                 $em = "You can't upload files of this type";
//                 header("Location: ../profile.php?error=$em");
//                 exit;
//             }
//         } else {
//             $em = "Unknown error occurred!";
//             header("Location: ../profile.php?error=$em");
//             exit;
//         }
//     }

//     // Retrieve user credential ID
//     $uc_id = $user['uc_id'];

//     try {
//         // Start transaction
//         $pdo->beginTransaction();

//         // Update `admin` table
//         $sqlAdmin = "UPDATE admin 
//                      SET first_name = :fname, middle_name = :mname, last_name = :lname, about_me = :about, phone_number = :phone 
//                      WHERE uc_id = :uc_id";
//         $stmtAdmin = $pdo->prepare($sqlAdmin);
//         $stmtAdmin->execute([
//             ':fname' => $fname,
//             ':mname' => $mname,
//             ':lname' => $lname,
//             ':about' => $about_me,
//             ':uc_id' => $uc_id,
//             ':phone' => $phone_number
//         ]);

//         // Update `user_credentials` table
//         $sqlCredentials = "UPDATE user_credentials 
//                            SET email = :email" . ($pp ? ", pp = :pp" : "") . "
//                            WHERE uc_id = :uc_id";
//         $stmtCredentials = $pdo->prepare($sqlCredentials);
//         $params = [
//             ':email' => $email,
//             ':uc_id' => $uc_id
//         ];
//         if ($pp) {
//             $params[':pp'] = $pp;
//         }
//         $stmtCredentials->execute($params);

//         // Check if address_id is NULL and create a new address if necessary
//         $sqlCheckAddress = "SELECT address_id FROM user_credentials WHERE uc_id = :uc_id";
//         $stmtCheckAddress = $pdo->prepare($sqlCheckAddress);
//         $stmtCheckAddress->execute([':uc_id' => $uc_id]);
//         $address_id = $stmtCheckAddress->fetchColumn();

//         if (!$address_id) {
//             // Insert new address
//             $sqlInsertAddress = "INSERT INTO address (sitio, barangay, city, province) VALUES (:sitio, :barangay, :city, :province)";
//             $stmtInsertAddress = $pdo->prepare($sqlInsertAddress);
//             $stmtInsertAddress->execute([
//                 ':sitio' => $sitio,
//                 ':barangay' => $barangay,
//                 ':city' => $city,
//                 ':province' => $province
//             ]);
//             $address_id = $pdo->lastInsertId();

//             // Update user_credentials with new address_id
//             $sqlUpdateAddressId = "UPDATE user_credentials SET address_id = :address_id WHERE uc_id = :uc_id";
//             $stmtUpdateAddressId = $pdo->prepare($sqlUpdateAddressId);
//             $stmtUpdateAddressId->execute([
//                 ':address_id' => $address_id,
//                 ':uc_id' => $uc_id
//             ]);
//         } else {
//             // Update existing address
//             $sqlAddress = "UPDATE address 
//                            SET sitio = :sitio, barangay = :barangay, city = :city, province = :province 
//                            WHERE address_id = :address_id";
//             $stmtAddress = $pdo->prepare($sqlAddress);
//             $stmtAddress->execute([
//                 ':sitio' => $sitio,
//                 ':barangay' => $barangay,
//                 ':city' => $city,
//                 ':province' => $province,
//                 ':address_id' => $address_id
//             ]);
//         }

//         // Check if us_id is NULL and create a new user_socials if necessary
//         $sqlCheckSocials = "SELECT us_id FROM user_credentials WHERE uc_id = :uc_id";
//         $stmtCheckSocials = $pdo->prepare($sqlCheckSocials);
//         $stmtCheckSocials->execute([':uc_id' => $uc_id]);
//         $us_id = $stmtCheckSocials->fetchColumn();

//         if (!$us_id) {
//             // Insert new user_socials
//             $sqlInsertSocials = "INSERT INTO user_socials (facebook_link, twitter_link, instagram_link, linkedin_link) VALUES (:facebook, :twitter, :instagram, :linkedin)";
//             $stmtInsertSocials = $pdo->prepare($sqlInsertSocials);
//             $stmtInsertSocials->execute([
//                 ':facebook' => $fb_link,
//                 ':twitter' => $tw_link,
//                 ':instagram' => $ig_link,
//                 ':linkedin' => $li_link
//             ]);
//             $us_id = $pdo->lastInsertId();

//             // Update user_credentials with new us_id
//             $sqlUpdateSocialsId = "UPDATE user_credentials SET us_id = :us_id WHERE uc_id = :uc_id";
//             $stmtUpdateSocialsId = $pdo->prepare($sqlUpdateSocialsId);
//             $stmtUpdateSocialsId->execute([
//                 ':us_id' => $us_id,
//                 ':uc_id' => $uc_id
//             ]);
//         } else {
//             // Update existing user_socials
//             $sqlSocials = "UPDATE user_socials 
//                            SET facebook_link = :facebook, twitter_link = :twitter, instagram_link = :instagram, linkedin_link = :linkedin 
//                            WHERE us_id = :us_id";
//             $stmtSocials = $pdo->prepare($sqlSocials);
//             $stmtSocials->execute([
//                 ':facebook' => $fb_link,
//                 ':twitter' => $tw_link,
//                 ':instagram' => $ig_link,
//                 ':linkedin' => $li_link,
//                 ':us_id' => $us_id
//             ]);
//         }

//         // Commit transaction
//         $pdo->commit();

//         echo '<script>alert("Information Updated Successfully"); window.location.href = "admin/users-profile.php";</script>';
//     } catch (PDOException $e) {
//         // Rollback transaction on error
//         $pdo->rollBack();
//         echo '<script>alert("Error: ' . $e->getMessage() . '");</script>';
//     }
// }



if (isset($_POST['saveChanges'])) {

    $userId = $_SESSION['id'];

    // Retrieve current user details
    $stmt = $pdo->prepare("
        SELECT *
        FROM user_credentials
        WHERE uc_id = :uc_id;
    ");
    $stmt->execute(['uc_id' => $userId]);
    $user = $stmt->fetch();

    if (!$user) {
        echo '<script>alert("User not found.");</script>';
        exit;
    }

    // Collect form data
    $email = $_POST['email'];
    $fname = $_POST['firstname'];
    $mname = $_POST['middlename'];
    $lname = $_POST['lastname'];
    $phone_number = $_POST['phone'];
    $about_me = $_POST['about'];

    $sitio = $_POST['sitio'];
    $barangay = $_POST['barangay'];
    $city = $_POST['city'];
    $province = $_POST['province'];

    $fb_link = $_POST['facebook'];
    $tw_link = $_POST['twitter'];
    $ig_link = $_POST['instagram'];
    $li_link = $_POST['linkedin'];

    // Retrieve user credential ID
    $uc_id = $user['uc_id'];

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Update `admin` table
        $sqlAdmin = "UPDATE admin 
                     SET first_name = :fname, middle_name = :mname, last_name = :lname, about_me = :about, phone_number = :phone 
                     WHERE uc_id = :uc_id";
        $stmtAdmin = $pdo->prepare($sqlAdmin);
        $stmtAdmin->execute([
            ':fname' => $fname,
            ':mname' => $mname,
            ':lname' => $lname,
            ':about' => $about_me,
            ':uc_id' => $uc_id,
            ':phone' => $phone_number
        ]);

        // Update `user_credentials` table
        $sqlCredentials = "UPDATE user_credentials 
                           SET email = :email
                           WHERE uc_id = :uc_id";
        $stmtCredentials = $pdo->prepare($sqlCredentials);
        $stmtCredentials->execute([
            ':email' => $email,
            ':uc_id' => $uc_id
        ]);

        // Check if address_id is NULL and create a new address if necessary
        $sqlCheckAddress = "SELECT address_id FROM user_credentials WHERE uc_id = :uc_id";
        $stmtCheckAddress = $pdo->prepare($sqlCheckAddress);
        $stmtCheckAddress->execute([':uc_id' => $uc_id]);
        $address_id = $stmtCheckAddress->fetchColumn();

        if (!$address_id) {
            // Insert new address
            $sqlInsertAddress = "INSERT INTO address (sitio, barangay, city, province) VALUES (:sitio, :barangay, :city, :province)";
            $stmtInsertAddress = $pdo->prepare($sqlInsertAddress);
            $stmtInsertAddress->execute([
                ':sitio' => $sitio,
                ':barangay' => $barangay,
                ':city' => $city,
                ':province' => $province
            ]);
            $address_id = $pdo->lastInsertId();

            // Update user_credentials with new address_id
            $sqlUpdateAddressId = "UPDATE user_credentials SET address_id = :address_id WHERE uc_id = :uc_id";
            $stmtUpdateAddressId = $pdo->prepare($sqlUpdateAddressId);
            $stmtUpdateAddressId->execute([
                ':address_id' => $address_id,
                ':uc_id' => $uc_id
            ]);
        } else {
            // Update existing address
            $sqlAddress = "UPDATE address 
                           SET sitio = :sitio, barangay = :barangay, city = :city, province = :province 
                           WHERE address_id = :address_id";
            $stmtAddress = $pdo->prepare($sqlAddress);
            $stmtAddress->execute([
                ':sitio' => $sitio,
                ':barangay' => $barangay,
                ':city' => $city,
                ':province' => $province,
                ':address_id' => $address_id
            ]);
        }

        // Check if us_id is NULL and create a new user_socials if necessary
        $sqlCheckSocials = "SELECT us_id FROM user_credentials WHERE uc_id = :uc_id";
        $stmtCheckSocials = $pdo->prepare($sqlCheckSocials);
        $stmtCheckSocials->execute([':uc_id' => $uc_id]);
        $us_id = $stmtCheckSocials->fetchColumn();

        if (!$us_id) {
            // Insert new user_socials
            $sqlInsertSocials = "INSERT INTO user_socials (facebook_link, twitter_link, instagram_link, linkedin_link) VALUES (:facebook, :twitter, :instagram, :linkedin)";
            $stmtInsertSocials = $pdo->prepare($sqlInsertSocials);
            $stmtInsertSocials->execute([
                ':facebook' => $fb_link,
                ':twitter' => $tw_link,
                ':instagram' => $ig_link,
                ':linkedin' => $li_link
            ]);
            $us_id = $pdo->lastInsertId();

            // Update user_credentials with new us_id
            $sqlUpdateSocialsId = "UPDATE user_credentials SET us_id = :us_id WHERE uc_id = :uc_id";
            $stmtUpdateSocialsId = $pdo->prepare($sqlUpdateSocialsId);
            $stmtUpdateSocialsId->execute([
                ':us_id' => $us_id,
                ':uc_id' => $uc_id
            ]);
        } else {
            // Update existing user_socials
            $sqlSocials = "UPDATE user_socials 
                           SET facebook_link = :facebook, twitter_link = :twitter, instagram_link = :instagram, linkedin_link = :linkedin 
                           WHERE us_id = :us_id";
            $stmtSocials = $pdo->prepare($sqlSocials);
            $stmtSocials->execute([
                ':facebook' => $fb_link,
                ':twitter' => $tw_link,
                ':instagram' => $ig_link,
                ':linkedin' => $li_link,
                ':us_id' => $us_id
            ]);
        }

        // Commit transaction
        $pdo->commit();

        echo '<script>alert("Information Updated Successfully"); window.location.href = "admin/users-profile.php";</script>';
    } catch (PDOException $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        echo '<script>alert("Error: ' . $e->getMessage() . '");</script>';
    }
}

if (isset($_POST['saveMember'])) {
    $member_id = $_POST['member_id'];

    // Collect form data
    $email = $_POST['email'];
    $fname = $_POST['firstname'];
    $mname = $_POST['middlename'];
    $lname = $_POST['lastname'];
    $phone_number = $_POST['phone'];
    $about_me = $_POST['about'];
    $date_of_birth = $_POST['date_of_birth'];
    $line_of_business = $_POST['line_of_business'];
    $sitio = $_POST['sitio'];
    $barangay = $_POST['barangay'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $fb_link = $_POST['facebook'];
    $tw_link = $_POST['twitter'];
    $ig_link = $_POST['instagram'];
    $li_link = $_POST['linkedin'];

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Update `member` table
        $sqlMember = "UPDATE member 
                      SET first_name = :fname, middle_name = :mname, last_name = :lname, about_me = :about, phone_number = :phone, line_of_business = :line_of_business, date_of_birth = :date_of_birth
                      WHERE member_id = :member_id";
        $stmtMember = $pdo->prepare($sqlMember);
        $stmtMember->execute([
            ':fname' => $fname,
            ':date_of_birth' => $date_of_birth,
            ':mname' => $mname,
            ':lname' => $lname,
            ':about' => $about_me,
            ':phone' => $phone_number,
            ':line_of_business' => $line_of_business,
            ':member_id' => $member_id
        ]);

        // Update `user_credentials` table
        $sqlCredentials = "UPDATE user_credentials 
                           SET email = :email 
                           WHERE uc_id = (SELECT uc_id FROM member WHERE member_id = :member_id)";
        $stmtCredentials = $pdo->prepare($sqlCredentials);
        $stmtCredentials->execute([
            ':email' => $email,
            ':member_id' => $member_id
        ]);

        // Check if address_id is NULL and create a new address if necessary
        $sqlCheckAddress = "SELECT address_id FROM user_credentials WHERE uc_id = (SELECT uc_id FROM member WHERE member_id = :member_id)";
        $stmtCheckAddress = $pdo->prepare($sqlCheckAddress);
        $stmtCheckAddress->execute([':member_id' => $member_id]);
        $address_id = $stmtCheckAddress->fetchColumn();

        if (!$address_id) {
            // Insert new address
            $sqlInsertAddress = "INSERT INTO address (sitio, barangay, city, province) VALUES (:sitio, :barangay, :city, :province)";
            $stmtInsertAddress = $pdo->prepare($sqlInsertAddress);
            $stmtInsertAddress->execute([
                ':sitio' => $sitio,
                ':barangay' => $barangay,
                ':city' => $city,
                ':province' => $province
            ]);
            $address_id = $pdo->lastInsertId();

            // Update user_credentials with new address_id
            $sqlUpdateAddressId = "UPDATE user_credentials SET address_id = :address_id WHERE uc_id = (SELECT uc_id FROM member WHERE member_id = :member_id)";
            $stmtUpdateAddressId = $pdo->prepare($sqlUpdateAddressId);
            $stmtUpdateAddressId->execute([
                ':address_id' => $address_id,
                ':member_id' => $member_id
            ]);
        } else {
            // Update existing address
            $sqlAddress = "UPDATE address 
                           SET sitio = :sitio, barangay = :barangay, city = :city, province = :province 
                           WHERE address_id = :address_id";
            $stmtAddress = $pdo->prepare($sqlAddress);
            $stmtAddress->execute([
                ':sitio' => $sitio,
                ':barangay' => $barangay,
                ':city' => $city,
                ':province' => $province,
                ':address_id' => $address_id
            ]);
        }

        // Check if us_id is NULL and create a new user_socials if necessary
        $sqlCheckSocials = "SELECT us_id FROM user_credentials WHERE uc_id = (SELECT uc_id FROM member WHERE member_id = :member_id)";
        $stmtCheckSocials = $pdo->prepare($sqlCheckSocials);
        $stmtCheckSocials->execute([':member_id' => $member_id]);
        $us_id = $stmtCheckSocials->fetchColumn();

        if (!$us_id) {
            // Insert new user_socials
            $sqlInsertSocials = "INSERT INTO user_socials (facebook_link, twitter_link, instagram_link, linkedin_link) VALUES (:facebook, :twitter, :instagram, :linkedin)";
            $stmtInsertSocials = $pdo->prepare($sqlInsertSocials);
            $stmtInsertSocials->execute([
                ':facebook' => $fb_link,
                ':twitter' => $tw_link,
                ':instagram' => $ig_link,
                ':linkedin' => $li_link
            ]);
            $us_id = $pdo->lastInsertId();

            // Update user_credentials with new us_id
            $sqlUpdateSocialsId = "UPDATE user_credentials SET us_id = :us_id WHERE uc_id = (SELECT uc_id FROM member WHERE member_id = :member_id)";
            $stmtUpdateSocialsId = $pdo->prepare($sqlUpdateSocialsId);
            $stmtUpdateSocialsId->execute([
                ':us_id' => $us_id,
                ':member_id' => $member_id
            ]);
        } else {
            // Update existing user_socials
            $sqlSocials = "UPDATE user_socials 
                           SET facebook_link = :facebook, twitter_link = :twitter, instagram_link = :instagram, linkedin_link = :linkedin 
                           WHERE us_id = :us_id";
            $stmtSocials = $pdo->prepare($sqlSocials);
            $stmtSocials->execute([
                ':facebook' => $fb_link,
                ':twitter' => $tw_link,
                ':instagram' => $ig_link,
                ':linkedin' => $li_link,
                ':us_id' => $us_id
            ]);
        }

        // Commit transaction
        $pdo->commit();

        echo '<script>alert("Information Updated Successfully"); window.location.href = "admin/member-profile.php?member_id=' . $member_id . '";</script>';
    } catch (PDOException $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        echo '<script>alert("Error: ' . $e->getMessage() . '");</script>';
    }
}

if (isset($_POST['saveStaff'])) {

    $staff_id = $_POST['staff_id'];

    // Collect form data
    $email = $_POST['email'];
    $fname = $_POST['firstname'];
    $mname = $_POST['middlename'];
    $lname = $_POST['lastname'];
    $phone_number = $_POST['phone'];
    $about_me = $_POST['about'];
    $date_of_birth = $_POST['date_of_birth'];


    $sitio = $_POST['sitio'];
    $barangay = $_POST['barangay'];
    $city = $_POST['city'];
    $province = $_POST['province'];

    $fb_link = $_POST['facebook'];
    $tw_link = $_POST['twitter'];
    $ig_link = $_POST['instagram'];
    $li_link = $_POST['linkedin'];

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Update `staff` table
        $sqlStaff = "UPDATE staff 
                      SET first_name = :fname, middle_name = :mname, last_name = :lname, about_me = :about, phone_number = :phone, date_of_birth = :date_of_birth
                      WHERE staff_id = :staff_id";
        $stmtStaff = $pdo->prepare($sqlStaff);
        $stmtStaff->execute([
            ':fname' => $fname,
            ':mname' => $mname,
            ':lname' => $lname,
            ':about' => $about_me,
            ':phone' => $phone_number,
            ':date_of_birth' => $date_of_birth,
            ':staff_id' => $staff_id
        ]);

        // Update `user_credentials` table
        $sqlCredentials = "UPDATE user_credentials 
                           SET email = :email 
                           WHERE uc_id = (SELECT uc_id FROM staff WHERE staff_id = :staff_id)";
        $stmtCredentials = $pdo->prepare($sqlCredentials);
        $stmtCredentials->execute([
            ':email' => $email,
            ':staff_id' => $staff_id
        ]);

        // Check if address_id is NULL and create a new address if necessary
        $sqlCheckAddress = "SELECT address_id FROM user_credentials WHERE uc_id = (SELECT uc_id FROM staff WHERE staff_id = :staff_id)";
        $stmtCheckAddress = $pdo->prepare($sqlCheckAddress);
        $stmtCheckAddress->execute([':staff_id' => $staff_id]);
        $address_id = $stmtCheckAddress->fetchColumn();

        if (!$address_id) {
            // Insert new address
            $sqlInsertAddress = "INSERT INTO address (sitio, barangay, city, province) VALUES (:sitio, :barangay, :city, :province)";
            $stmtInsertAddress = $pdo->prepare($sqlInsertAddress);
            $stmtInsertAddress->execute([
                ':sitio' => $sitio,
                ':barangay' => $barangay,
                ':city' => $city,
                ':province' => $province
            ]);
            $address_id = $pdo->lastInsertId();

            // Update user_credentials with new address_id
            $sqlUpdateAddressId = "UPDATE user_credentials SET address_id = :address_id WHERE uc_id = (SELECT uc_id FROM staff WHERE staff_id = :staff_id)";
            $stmtUpdateAddressId = $pdo->prepare($sqlUpdateAddressId);
            $stmtUpdateAddressId->execute([
                ':address_id' => $address_id,
                ':staff_id' => $staff_id
            ]);
        } else {
            // Update existing address
            $sqlAddress = "UPDATE address 
                           SET sitio = :sitio, barangay = :barangay, city = :city, province = :province 
                           WHERE address_id = :address_id";
            $stmtAddress = $pdo->prepare($sqlAddress);
            $stmtAddress->execute([
                ':sitio' => $sitio,
                ':barangay' => $barangay,
                ':city' => $city,
                ':province' => $province,
                ':address_id' => $address_id
            ]);
        }

        // Check if us_id is NULL and create a new user_socials if necessary
        $sqlCheckSocials = "SELECT us_id FROM user_credentials WHERE uc_id = (SELECT uc_id FROM staff WHERE staff_id = :staff_id)";
        $stmtCheckSocials = $pdo->prepare($sqlCheckSocials);
        $stmtCheckSocials->execute([':staff_id' => $staff_id]);
        $us_id = $stmtCheckSocials->fetchColumn();

        if (!$us_id) {
            // Insert new user_socials
            $sqlInsertSocials = "INSERT INTO user_socials (facebook_link, twitter_link, instagram_link, linkedin_link) VALUES (:facebook, :twitter, :instagram, :linkedin)";
            $stmtInsertSocials = $pdo->prepare($sqlInsertSocials);
            $stmtInsertSocials->execute([
                ':facebook' => $fb_link,
                ':twitter' => $tw_link,
                ':instagram' => $ig_link,
                ':linkedin' => $li_link
            ]);
            $us_id = $pdo->lastInsertId();

            // Update user_credentials with new us_id
            $sqlUpdateSocialsId = "UPDATE user_credentials SET us_id = :us_id WHERE uc_id = (SELECT uc_id FROM staff WHERE staff_id = :staff_id)";
            $stmtUpdateSocialsId = $pdo->prepare($sqlUpdateSocialsId);
            $stmtUpdateSocialsId->execute([
                ':us_id' => $us_id,
                ':staff_id' => $staff_id
            ]);
        } else {
            // Update existing user_socials
            $sqlSocials = "UPDATE user_socials 
                           SET facebook_link = :facebook, twitter_link = :twitter, instagram_link = :instagram, linkedin_link = :linkedin 
                           WHERE us_id = :us_id";
            $stmtSocials = $pdo->prepare($sqlSocials);
            $stmtSocials->execute([
                ':facebook' => $fb_link,
                ':twitter' => $tw_link,
                ':instagram' => $ig_link,
                ':linkedin' => $li_link,
                ':us_id' => $us_id
            ]);
        }

        // Commit transaction
        $pdo->commit();

        echo '<script>alert("Information Updated Successfully"); window.location.href = "admin/staff-profile.php?staff_id=' . $staff_id . '";</script>';
    } catch (PDOException $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        echo '<script>alert("Error: ' . $e->getMessage() . '");</script>';
    }
}

if (isset($_GET['deactivate']) && $_GET['deactivate'] == 'true') {
    if (isset($_GET['member_id'])) {
        // Deactivate member
        $member_id = htmlspecialchars($_GET['member_id']);

        $sql = "UPDATE member SET status = 'Deactivated' WHERE member_id = :member_id";
        $stmt = $pdo->prepare($sql);
        $data = ['member_id' => $member_id];

        try {
            $stmt->execute($data);
            header('Location: admin/member.php'); // Redirect to member list page
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } elseif (isset($_GET['staff_id'])) {
        // Deactivate staff
        $staff_id = htmlspecialchars($_GET['staff_id']);

        $sql = "UPDATE staff SET status = 'Deactivated' WHERE staff_id = :staff_id";
        $stmt = $pdo->prepare($sql);
        $data = ['staff_id' => $staff_id];

        try {
            $stmt->execute($data);
            header('Location: admin/staff.php'); // Redirect to staff list page
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } elseif (isset($_GET['loan_plan_id'])) {
        // Deactivate loan plan
        $loan_plan_id = htmlspecialchars($_GET['loan_plan_id']);

        $sql = "UPDATE loan_plan SET status = 'Deactivated' WHERE loan_plan_id = :loan_plan_id";
        $stmt = $pdo->prepare($sql);
        $data = ['loan_plan_id' => $loan_plan_id];

        try {
            $stmt->execute($data);
            header('Location: admin/loan-plan.php'); // Redirect to loan plan list page
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
} elseif (isset($_GET['activate']) && $_GET['activate'] == 'true') {
    if (isset($_GET['member_id'])) {
        // Activate member
        $member_id = htmlspecialchars($_GET['member_id']);

        $sql = "UPDATE member SET status = 'Active' WHERE member_id = :member_id";
        $stmt = $pdo->prepare($sql);
        $data = ['member_id' => $member_id];

        try {
            $stmt->execute($data);
            header('Location: admin/member.php'); // Redirect to member list page
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } elseif (isset($_GET['staff_id'])) {
        // Activate staff
        $staff_id = htmlspecialchars($_GET['staff_id']);

        $sql = "UPDATE staff SET status = 'Active' WHERE staff_id = :staff_id";
        $stmt = $pdo->prepare($sql);
        $data = ['staff_id' => $staff_id];

        try {
            $stmt->execute($data);
            header('Location: admin/staff.php'); // Redirect to staff list page
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } elseif (isset($_GET['loan_plan_id'])) {
        // Activate loan plan
        $loan_plan_id = htmlspecialchars($_GET['loan_plan_id']);

        $sql = "UPDATE loan_plan SET status = 'Active' WHERE loan_plan_id = :loan_plan_id";
        $stmt = $pdo->prepare($sql);
        $data = ['loan_plan_id' => $loan_plan_id];

        try {
            $stmt->execute($data);
            header('Location: admin/loan-plan.php'); // Redirect to loan plan list page
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}

if (isset($_POST['addMember'])) {
    // Capture and sanitize form data
    $fname = htmlspecialchars($_POST['firstname']);
    $mname = htmlspecialchars($_POST['middlename']);
    $lname = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);
    $province = htmlspecialchars($_POST['province']);
    $city = htmlspecialchars($_POST['city']);
    $barangay = htmlspecialchars($_POST['barangay']);
    $sitio = htmlspecialchars($_POST['sitio']);
    $password = htmlspecialchars($_POST['password']);
    $repeatPassword = htmlspecialchars($_POST['repeatPassword']);

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit;
    }

    // Check if the email already exists
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM user_credentials WHERE email = :email");
    $stmtCheck->execute([':email' => $email]);
    if ($stmtCheck->fetchColumn() > 0) {
        echo "Email already exists!";
        exit;
    }

    // Check if passwords match
    if ($password === $repeatPassword) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Begin transaction
            $pdo->beginTransaction();

            // Insert into address table
            $sql1 = "INSERT INTO address (province, city, barangay, sitio) VALUES (:province, :city, :barangay, :sitio)";
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->execute([
                ':province' => $province,
                ':city' => $city,
                ':barangay' => $barangay,
                ':sitio' => $sitio
            ]);

            // Get the last inserted address_id
            $address_id = $pdo->lastInsertId();

            // Insert into user_credentials table with address_id
            $sql2 = "INSERT INTO user_credentials (email, password, role, address_id) VALUES (:email, :password, 'Member', :address_id)";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute([
                ':email' => $email,
                ':password' => $hashed_password,
                ':address_id' => $address_id
            ]);

            // Get the last inserted uc_id
            $uc_id = $pdo->lastInsertId();

            // Insert into member table with uc_id and registration_date
            // $sql3 = "INSERT INTO member (first_name, middle_name, last_name, uc_id, registration_date) VALUES (:fname, :mname, :lname, :uc_id, NOW())";
            $sql3 = "INSERT INTO member (first_name, middle_name, last_name, uc_id) VALUES (:fname, :mname, :lname, :uc_id)";
            $stmt3 = $pdo->prepare($sql3);
            $stmt3->execute([
                ':fname' => $fname,
                ':mname' => $mname,
                ':lname' => $lname,
                ':uc_id' => $uc_id
            ]);

            // Commit transaction
            $pdo->commit();

            echo '<script>
    alert("Account Created");
    window.location.href = "admin/member.php";
</script>';
        } catch (PDOException $e) {
            // Roll back transaction in case of an error
            $pdo->rollBack();
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo "Passwords do not match!";
        header('Location: try.php');
    }
}

if (isset($_POST['addStaff'])) {
    // Capture and sanitize form data
    $fname = htmlspecialchars($_POST['firstname']);
    $mname = htmlspecialchars($_POST['middlename']);
    $lname = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);
    $hire_date = htmlspecialchars($_POST['hire_date']);
    $formatted_date = date('Y-m-d', strtotime($hire_date));
    $province = htmlspecialchars($_POST['province']);
    $city = htmlspecialchars($_POST['city']);
    $barangay = htmlspecialchars($_POST['barangay']);
    $sitio = htmlspecialchars($_POST['sitio']);
    $password = htmlspecialchars($_POST['password']);
    $repeatPassword = htmlspecialchars($_POST['repeatPassword']);

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit;
    }

    // Check if the email already exists
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM user_credentials WHERE email = :email");
    $stmtCheck->execute([':email' => $email]);
    if ($stmtCheck->fetchColumn() > 0) {
        echo "Email already exists!";
        exit;
    }

    // Check if passwords match
    if ($password === $repeatPassword) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Begin transaction
            $pdo->beginTransaction();

            // Insert into address table
            $sql1 = "INSERT INTO address (province, city, barangay, sitio) VALUES (:province, :city, :barangay, :sitio)";
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->execute([
                ':province' => $province,
                ':city' => $city,
                ':barangay' => $barangay,
                ':sitio' => $sitio
            ]);

            // Get the last inserted address_id
            $address_id = $pdo->lastInsertId();

            // Insert into user_credentials table with address_id
            $sql2 = "INSERT INTO user_credentials (email, password, role, address_id) VALUES (:email, :password, 'Staff', :address_id)";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute([
                ':email' => $email,
                ':password' => $hashed_password,
                ':address_id' => $address_id
            ]);

            // Get the last inserted uc_id
            $uc_id = $pdo->lastInsertId();

            // Insert into member table with uc_id and registration_date
            $sql3 = "INSERT INTO staff (first_name, middle_name, last_name, uc_id, hire_date) VALUES (:fname, :mname, :lname, :uc_id, :hire_date)";
            $stmt3 = $pdo->prepare($sql3);
            $stmt3->execute([
                ':fname' => $fname,
                ':mname' => $mname,
                ':lname' => $lname,
                ':hire_date' => $formatted_date,
                ':uc_id' => $uc_id
            ]);

            // Commit transaction
            $pdo->commit();

            echo '<script>
    alert("Account Created");
    window.location.href = "admin/staff.php";
</script>';
        } catch (PDOException $e) {
            // Roll back transaction in case of an error
            $pdo->rollBack();
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo "Passwords do not match!";
        header('Location: try.php');
    }
}

if (isset($_POST['addLoanPlan'])) {
    // Capture and sanitize form data
    $loan_type = htmlspecialchars($_POST['loan-type']);
    $interest_rate = htmlspecialchars($_POST['interest-rate']);
    $penalty_rate = htmlspecialchars($_POST['penalty-rate']);
    $monthly_term = htmlspecialchars($_POST['monthly-term']);

    // Convert to float for calculations if needed
    $interest_rate = floatval($interest_rate);
    $penalty_rate = floatval($penalty_rate);

    // Validate form data if needed
    if (empty($loan_type) || empty($interest_rate) || empty($penalty_rate) || empty($monthly_term)) {
        echo "Please fill in all fields.";
        exit;
    }

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Insert into loan_plan table
        $sql = "INSERT INTO loan_plan (loan_type_id, interest_rate, penalty_rate, monthly_term) VALUES (:loan_type, :interest_rate, :penalty_rate, :monthly_term)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':loan_type' => $loan_type,
            ':interest_rate' => $interest_rate,
            ':penalty_rate' => $penalty_rate,
            ':monthly_term' => $monthly_term
        ]);

        // Commit transaction
        $pdo->commit();

        echo '<script>
    alert("Loan Plan Added");
    window.location.href = "admin/loan-plan.php";
</script>';
    } catch (PDOException $e) {
        // Roll back transaction in case of an error
        $pdo->rollBack();
        echo 'Error: ' . $e->getMessage();
    }
}

if (isset($_POST['editLoanPlan'])) {
    // Capture and sanitize form data
    $loan_plan_id = intval($_POST['loan_plan_id']);
    $loan_type = htmlspecialchars($_POST['loan-type']);
    $interest_rate = htmlspecialchars($_POST['interest-rate']);
    $penalty_rate = htmlspecialchars($_POST['penalty-rate']);
    $monthly_term = htmlspecialchars($_POST['monthly-term']);

    // Validate form data if needed
    if (empty($loan_type) || empty($interest_rate) || empty($penalty_rate) || empty($monthly_term)) {
        echo "Please fill in all fields.";
        exit;
    }

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Update loan_plan table
        $sql = "UPDATE loan_plan SET loan_type_id = :loan_type, interest_rate = :interest_rate, penalty_rate = :penalty_rate, monthly_term = :monthly_term WHERE loan_plan_id = :loan_plan_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':loan_type' => $loan_type,
            ':interest_rate' => $interest_rate,
            ':penalty_rate' => $penalty_rate,
            ':monthly_term' => $monthly_term,
            ':loan_plan_id' => $loan_plan_id
        ]);

        // Commit transaction
        $pdo->commit();

        echo '<script>
    alert("Loan Plan Updated Successfully");
    window.location.href = "admin/loan-plan.php?loan_plan_id=' . $loan_plan_id . '";
</script>';
    } catch (PDOException $e) {
        // Roll back transaction in case of an error
        $pdo->rollBack();
        echo 'Error: ' . $e->getMessage();
    }
}

if (isset($_GET['deleteMember']) && $_GET['deleteMember'] === 'true') {
    $member_id = htmlspecialchars($_GET['member_id']);

    try {
        $pdo->beginTransaction();

        // Update member status to 'archived'
        $sql = "UPDATE member SET status = 'archived' WHERE member_id = :member_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':member_id' => $member_id]);

        $pdo->commit();

        echo '<script>
    alert("Member status updated to archived successfully.");
    window.location.href = "admin/member.php";
</script>';
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo 'Error: ' . $e->getMessage();
    }
}

if (isset($_GET['deleteStaff']) && $_GET['deleteStaff'] === 'true') {
    $staff_id = htmlspecialchars($_GET['staff_id']);

    try {
        $pdo->beginTransaction();

        // Update staff status to 'archived'
        $sql = "UPDATE staff SET status = 'archived' WHERE staff_id = :staff_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':staff_id' => $staff_id]);

        $pdo->commit();

        echo '<script>
    alert("Staff status updated to archived successfully.");
    window.location.href = "admin/staff.php";
</script>';
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo 'Error: ' . $e->getMessage();
    }
}


if (isset($_POST['applyNow'])) {
    $loan_type = htmlspecialchars($_POST['loan-type']);
    $purpose = htmlspecialchars($_POST['purpose']);
    $amount_requested = htmlspecialchars($_POST['amount-requested']);
    $loan_plan = htmlspecialchars($_POST['loan-plan']);
    $member_id = $_SESSION['id']; // Assuming the user is logged in and their id is stored in the session

    try {
        $pdo->beginTransaction();

        // Insert into loan_application table
        $applicationStmt = $pdo->prepare("INSERT INTO loan_application (member_id, amount_requested, purpose, application_date, status) VALUES (:member_id, :amount_requested, :purpose, NOW(), 'Pending')");
        $applicationStmt->execute([
            ':member_id' => $member_id,
            ':amount_requested' => $amount_requested,
            ':purpose' => $purpose
        ]);
        $application_id = $pdo->lastInsertId();

        // Insert into loan table
        $loanStmt = $pdo->prepare("INSERT INTO loan (application_id, loan_plan_id, status, principal_amount) VALUES (:application_id, :loan_plan, 'On Progress', :amount_requested)");
        $loanStmt->execute([
            ':application_id' => $application_id,
            ':loan_plan' => $loan_plan,
            ':amount_requested' => $amount_requested
        ]);

        $pdo->commit();
        echo '<script>alert("Loan application submitted successfully."); window.location.href = "member/loan-status.php";</script>';
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo 'Error: ' . $e->getMessage();
    }
}

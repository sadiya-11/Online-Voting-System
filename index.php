<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<?php
session_start();
require_once("admin/incc/config.php");

// Fetch all elections from the database
$fetchingElections = mysqli_query($db, "SELECT * FROM elections") or die(mysqli_error($db));
while ($data = mysqli_fetch_assoc($fetchingElections)) {
    $starting_date = $data['starting_date'];
    $ending_date = $data['ending_date'];
    $curr_date = date("Y-m-d");
    $election_id = $data['id'];
    $status = $data['status'];

    //Active= Expire=Ending Date
//Inactive = Active = Starting Date
    if ($status == "Active") {
        $date1 = date_create($curr_date);
        $date2 = date_create($ending_date);
        $diff = date_diff($date1, $date2);

 // If the current date is past the ending date, mark the election as "Expired"
        if ((int) $diff->format("%R%a") < 0) {
          //update
          mysqli_query($db, "UPDATE elections SET status = 'Expired' WHERE id = '".$election_id."'  ") OR die(mysqli_error($db));
        }
    } else if ($status == "Inactive") {

        $date1 = date_create($curr_date);
        $date2 = date_create($starting_date);
        $diff = date_diff($date1, $date2);

// If the current date is the starting date or later, mark the election as "Active"
        if ((int) $diff->format("%R%a") <= 0) {
           //update
          mysqli_query($db, "UPDATE elections SET status = 'Active' WHERE id = '".$election_id."'  ") OR die(mysqli_error($db));
        }
    }

}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login - Online Voting System</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"
        integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
</head>

<body>
    <div class="container h-100">
        <div class="d-flex justify-content-center h-100">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <img src="assets/img/logo.gif" class="brand_logo" alt="Logo">
                    </div>
                </div>

                <?php
                if (isset($_GET['sign-up'])) {
                    ?>
			
                      <!-- Sign-Up Form -->
			
                    <div class="d-flex justify-content-center form_container">
                        <form method="POST"  onsubmit="return validateForm()">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="su_username" class="form-control input_user" placeholder="Username"
                                    required />
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                </div>
                                <input type="number" name="su_con_no" id="contact_number" class="form-control input_pass"
                                placeholder="Contact Number" required />
                            </div>
                            <div class="input-group mb-2 password-container">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" id="su_pass" name="su_pass" class="form-control input_pass" placeholder="Password" required />
                                <span id="togglePassword" onclick="togglePasswordVisibility('su_pass', 'togglePassword')" style="cursor: pointer;">🙈</i></span>
                            </div>
                            <div class="input-group mb-2  password-container">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" id="su_con_pass" name="su_con_pass" class="form-control input_pass" placeholder="Confirm Password" required />
                                <span id="toggleConPassword" onclick="togglePasswordVisibility('su_con_pass', 'toggleConPassword')" style="cursor: pointer;">🙈</i></span>
                            </div>


                            <div class="d-flex justify-content-center mt-3 login_container">
                                <button type="submit" name="sign_up_button" class="btn login_btn">Sign Up</button>
                            </div>
                        </form>
                    </div>

                    <div class="my-3">
                        <div class="d-flex justify-content-center links">
                            Account already created? <a href="index.php" class="ml-2 text-black">Sign In</a>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    
                     <!-- Login Form -->
		    
                    <div class="d-flex justify-content-center form_container">
                        <form method="POST"  onsubmit="return validateForm()">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="number" name="contact_number" id="contact_number" class="form-control input_user"
                                placeholder="Contact Number" required />
                            </div>
                            <div class="input-group mb-2 password-container">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" id="password" name="password" class="form-control input_pass" placeholder="Password" required />
                                <span id="toggleLoginPassword" onclick="togglePasswordVisibility('password', 'toggleLoginPassword')" style="cursor: pointer;">🙈</i></span>
                            </div>
                            <div class="d-flex justify-content-center mt-3 login_container">
                                <button type="submit" name="Login_button" class="btn login_btn">Login</button>
                            </div>
                        </form>
                    </div>

                    <div class="mt-4">
                        <div class="d-flex justify-content-center links">
                            Don't have an account? <a href="?sign-up=1" class="ml-2 text-black">Sign Up</a>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <?php
              
                if (isset($_GET['registered'])) {
                    ?>
                    <span class="bg-white text-success text-center my-3">Your account has been created sucessfully!!</span>

                    <?php
                } else if (isset($_GET['invalid'])) {
                    ?>
                        <span class="bg-white text-danger text-center my-3">Password mismatched,please try again!</span>

                    <?php
                } else if (isset($_GET['not_registered'])) {
                    ?>
                            <span class="bg-white text-warning text-center my-3">Sorry, you are not registered</span>

                    <?php
                } else if (isset($_GET['invalid_access'])) {
                    ?>
                                <span class="bg-white text-danger text-center my-3">Invalid uername or password</span>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <script>
        function validateForm() {
            const contactNumber = document.getElementById('contact_number').value;
            
            // Check if the contact number is exactly 10 digits
            if (contactNumber.length !== 10 || !/^\d+$/.test(contactNumber)) {
                alert('Contact number must be exactly 10 digits!');
                return false; 
            }
            return true; 
        }

        function togglePasswordVisibility(inputId, toggleButtonId) {
            const passwordField = document.getElementById(inputId);
            const toggleButton = document.getElementById(toggleButtonId);
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleButton.textContent = '👁️'; // Change icon to indicate password is visible
            } else {
                passwordField.type = 'password';
                toggleButton.textContent = '🙈'; // Change icon back to indicate password is hidden
            }
        }
    </script>
</body>
</html>

<?php
require_once("admin/incc/config.php");

if (isset($_POST['sign_up_button'])) {
    $su_username = mysqli_real_escape_string($db, $_POST['su_username']);
    $su_con_no = mysqli_real_escape_string($db, $_POST['su_con_no']);
    $su_pass = mysqli_real_escape_string($db, sha1($_POST['su_pass']));
    $su_con_pass = mysqli_real_escape_string($db, sha1($_POST['su_con_pass']));
    $user_role = "Voter";

    if ($su_pass == $su_con_pass) {
        mysqli_query($db, "INSERT INTO users(username,contact_number,password,user_role) VALUES ('" . $su_username . "', '" . $su_con_no . "', '" . $su_pass . "', '" . $user_role . "'
)") or die(mysqli_error($db));
        ?>
        <script> location.assign("index.php?sign-up=1&registered=1");</script>
	    
        <?php
    } else {
        ?>
        <script> location.assign("index.php?sign-up=1&invalid=1");</script>
        <?php
    }

} else if (isset($_POST['Login_button'])) {
    $contact_number = mysqli_real_escape_string($db, $_POST['contact_number']);
    $password = mysqli_real_escape_string($db, sha1($_POST['password']));

    //fetch query
    $fetchingData = mysqli_query($db, "SELECT * FROM users WHERE contact_number ='" . $contact_number . "'") or die(mysqli_error($db));


    if (mysqli_num_rows($fetchingData) > 0) {
        $data = mysqli_fetch_assoc($fetchingData);

        if ($contact_number == $data['contact_number'] and $password == $data['password']) {
            session_start();
            $_SESSION['user_role'] = $data['user_role'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['user_id'] = $data['id'];

            if ($data['user_role'] == "Admin") {
                $_SESSION['key'] = "AdminKey";
                ?>
                    <script> location.assign("admin/index.php?homepage=1");</script>
                <?php

            } else {
                $_SESSION['key'] = "VotersKey";
                ?>
                    <script> location.assign("voters/index.php");</script>
                <?php
            }

        } else {
            ?>
                <script> location.assign("index.php?invalid_access=1");</script>
            <?php
        }
    } else {
        ?>
            <script> location.assign("index.php?not_registered=1");</script>
        <?php
    }
}
?>

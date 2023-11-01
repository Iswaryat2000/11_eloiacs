<?php
    session_start();
    include "connection.php"; 
    $error_message = "";

    // Check if the user is already logged in and has a session
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        header("Location: src/dashboard.php");
        exit();
    }

    // Check if the "remember_user" cookie exists and is not empty
    if (isset($_COOKIE['remember_user']) && !empty($_COOKIE['remember_user'])) {
        // Decode the JSON data from the cookie
        $cookieData = json_decode(base64_decode($_COOKIE['remember_user']), true);
        

        // Assuming you have the user's email in the cookie
        $email = $cookieData['email'];

        // Use the email to query your database and get user details
        $query = "SELECT `name`, `status`, `place`, `position` FROM usertable WHERE `email` = ?";
        $stmt = mysqli_prepare($conn, $query);

        if (!$stmt) {
            die("Error in preparing the query: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Now you have the user details from the cookie and the database
            $_SESSION['username'] = $row['name'];
            $_SESSION['email'] = $email;
            $_SESSION['status'] = $row['status'];
            $_SESSION['place'] = $row['place'];
            $_SESSION['position'] = $row['position'];
            $_SESSION['loggedin'] = true;

            // Redirect to the appropriate page based on user status
            if ($_SESSION['status'] === 'verified') {
                header("Location: src/dashboard.php");
                exit();
            } elseif ($_SESSION['status'] === 'Notverified') {
                header("Location: otp_verify.php"); // Redirect to error page
                exit();
            } else {
                echo '<script>alert("Unknown user status. Please contact support.");</script>';
            }
        }
    }

    // Handle the login form submission here
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT id, `name`, `email`, `password`, `status`, `place`, `position` FROM usertable WHERE `email` = ?";
        $stmt = mysqli_prepare($conn, $query);

        if (!$stmt) {
            die("Error in preparing the query: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $storedPasswordHash = $row['password'];

            // Verify the hashed password
            if (password_verify($password, $storedPasswordHash)) {
                $_SESSION['username'] = $row['name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['status'] = $row['status'];
                $_SESSION['place'] = $row['place'];
                $_SESSION['position'] = $row['position'];
                $_SESSION['loggedin'] = true;

                // Check if "Remember Me" checkbox is checked and set the persistent login cookie
                if (isset($_POST['remember_user'])) {
                    setcookie('remember_user', base64_encode(json_encode(array(
                        'username' => $row['name'],
                        'email' => $row['email'],
                        'status' => $row['status'],
                        'place' => $row['place'],
                        'position' => $row['position'],
                    ))), time() + 30 * 24 * 60 * 60, '/');
                }

                if ($_SESSION['status'] === 'verified') {
                    header("Location: src/dashboard.php");
                    exit();
                } elseif ($_SESSION['status'] === 'Notverified') {
                    header("Location:otp_verify.php"); // Redirect to error page
                    exit();
                } else {
                    echo '<script>alert("Unknown user status. Please contact support.");</script>';
                }
            } else {
              $error_message = 'Invalid username or password';
            }
        } else {
           $error_message = 'Invalid username or password';
        }
    }
    ?>

    
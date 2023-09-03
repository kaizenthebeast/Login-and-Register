<?php
include ('smtp/PHPMailerAutoload.php');

session_start();
include ('dbsconnection.php');

function sendemail_verify($fullName, $userEmail, $verify_token)
{
  

   
    // Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer();
    $mail->isSMTP();                                            // Send using SMTP
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication

    $mail->Host       = 'smtp.hostinger.com';                       // Set the SMTP server to send through 
    $mail->Username   = 'academicresearch@caacademic.website';                    // SMTP username
    $mail->Password   = 'Godl!kes12';                            // SMTP password

    $mail->SMTPSecure = 'ssl';                                   // Enable implicit TLS encryption
    $mail->Port       = 465;                                     // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    // Recipients
    $mail->setFrom('academicresearch@caacademic.website', 'Academic Research Portal');
    $mail->addAddress($userEmail);                               // Add a recipient

    // Content
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);                                         // Set email format to HTML
    $mail->Subject = 'Email Verification from Academic Research Portal';

    $email_template = "
        <h2>You have Registered with Academic Research Portal</h2>
        <p>Please Click the link given to verify your account</p>
        <br/><br/>
        <a href='https://caacademic.website//verification.php?token=$verify_token'>Click me to verify</a>
    ";

    $mail->Body    = $email_template;

    $mail->send();

    // Return the success message
    return 'Message has been sent to your email account';
}

$errorEmpty = false;
$errorEmail = false;

if (isset($_POST['fullName']) || isset($_POST['userEmail']) || isset($_POST['password']) || isset($_POST['confirmPassword'])) {
    $fullName = $_POST['fullName'];
    $userEmail = $_POST['userEmail'];
    $password = $_POST['password'];
    $re_pass = $_POST['confirmPassword'];
    $verify_token = md5(rand());

    // Check if email already exists in the database
    $check_mail_query = "SELECT userEmail FROM registered_users WHERE userEmail='$userEmail' LIMIT 1";
    $check_mail_query_run = mysqli_query($conn, $check_mail_query);

    $response = array(
        'status' => 0,
        'message' => 'Form Submission Failed'
    );

    if (!empty($fullName) && !empty($userEmail) && !empty($password) && !empty($re_pass)) {
        $response = array(); // Initialize the response array
        
        // Error handler
        if (!preg_match('/^[a-zA-Z ]+$/', $fullName)) {
            $response['message'] = 'Please enter a valid full name';
        } elseif (strlen($fullName) < 6) {
            $response['message'] = 'Enter your complete full name';
        } elseif (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Please enter a valid email';
        } elseif (strlen($password) < 5) {
            $response['message'] = 'Password must be at least 5 characters long';
        } elseif ($password !== $re_pass) {
            $response['message'] = 'Passwords do not match';
        } elseif (mysqli_num_rows($check_mail_query_run) > 0) {
            $response['message'] = 'Email is already taken';
        } else {
            // Hash the password
            $password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO registered_users (fullName, userEmail, password, verify_token) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $fullName, $userEmail, $password, $verify_token);
            $query_run = mysqli_stmt_execute($stmt);

            if ($query_run) {
                $response['status'] = 1;
                $response['message'] = 'Successfully Registered, Please Verify your account.';
                $response['email_message'] = sendemail_verify($fullName, $userEmail, $verify_token);
            } else {
                $response['status'] = 0;
                $response['message'] = 'Registration failed';
            }
        }
    } else {
        $response['message'] = 'All input fields are required';
        $errorEmpty = true;
    }

    echo json_encode($response);
}

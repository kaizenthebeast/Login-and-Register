<?php
include("dbsconnection.php");

session_start();

$response = array(
    'status'  => 0,
    'message' => 'Form Submission Failed'
);

if (isset($_POST['userEmail'], $_POST['password'])) {
    $userEmail = $_POST['userEmail'];
    $password = $_POST['password'];

    $query = "SELECT * FROM registered_users WHERE userEmail = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $userEmail);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userId = $row['ID']; // Fetch the user ID from the query result
        $fullName = $row['fullName'];

        // Password matched
        if (password_verify($password, $row['password'])) {
            if ($row['verify_status'] == "1") {
                // Check userType
                if ($row['userType'] == 'admin') {
                    $_SESSION['admin'] = TRUE;
                    $_SESSION['ID'] = $userId;
                    $_SESSION['fullName'] = $fullName;
                    $_SESSION['auth_user'] = array(
                        'fullName' => $row['fullName'],
                        'userEmail' => $row['userEmail']
                    );

                    $response['status'] = 1;
                    $response['message'] = 'Admin login successful';
                    $response['redirect'] = 'adminHome.php'; // Redirect to admin page
                } else if ($row['userType'] == 'user') {
                    $_SESSION['authenticated'] = TRUE;
                    $_SESSION['ID'] = $userId;
                    $_SESSION['fullName'] = $fullName;
                    $_SESSION['auth_user'] = array(
                        'fullName' => $row['fullName'],
                        'userEmail' => $row['userEmail']
                    );

                    $response['status'] = 1;
                    $response['message'] = 'User login successful';
                    $response['redirect'] = 'test.php'; // Redirect to user page
                } else {
                    // User type is not allowed
                    $response['message'] = 'Unathorized Access';
                }
            } else {
                // Email is not verified
                $response['message'] = 'Email is not yet verified';
            }
        } else {
            // Password did not match
            $response['message'] = 'Wrong Email or Password';
        }
    } else {
        // No user found
        $response['message'] = 'Email is not registered';
    }
}

echo json_encode($response);
?>

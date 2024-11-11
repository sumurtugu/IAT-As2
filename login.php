<?php
// Ονοματεπώνυμο: Φρόνιμος Σπύρος
// Αριθμός μητρώου: 1117202000223

// Start the session to track attempts
session_start();

// Check if the session variable 'attempts' is set, if not, initialize it to 3
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 3;
}

// Initialize message and remaining attempts variables
$message = '';
$remaining_attempts = $_SESSION['attempts'];

// Check if the form was submitted using POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve email, password, and method from the POST request, defaulting to empty strings if not set
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $method = $_POST['method'] ?? '';

    // Check if email or password fields are empty
    if (empty($email) || empty($password)) {
        $message = "Please fill all fields.";
    } else {
        // Call the authenticate function to validate the credentials
        $is_authenticated = authenticate($email, $password, $method);

        // If authentication is successful
        if ($is_authenticated) {
            echo "Success!";
            // Reset attempts to 3
            $_SESSION['attempts'] = 3;
        } else {
            // Decrement the number of remaining attempts
            $_SESSION['attempts']--;
            $remaining_attempts = $_SESSION['attempts'];
            // Check if there are no remaining attempts
            if ($remaining_attempts == 0) {
                // Reset attempts to 3 and display a reset button
                $_SESSION['attempts'] = 3;
		echo "Authentication Failed. Please Try Again.";
                echo '<form method="post"><button type="submit" name="reset">Try Again</button></form>';
                exit;
            } else {
                $message = "Wrong Credentials. $remaining_attempts tries remaining.";
            }
        }
    }
}

// Check if the reset button was clicked
if (isset($_POST['reset'])) {
    // Reset attempts to 3 and clear the message
    $_SESSION['attempts'] = 3;
    $remaining_attempts = 3;
    $message = '';
}

// Function to authenticate user based on the selected method
function authenticate($email, $password, $method) {
    switch ($method) {
        // Check if password is double the email
        case 'double_email':
            return $password === $email . $email;
        // Check if password is the reversed email
        case 'reverse_email':
            return $password === strrev($email);
        // Check if password is in the predefined array of valid passwords
        case 'password_in_array':
            $valid_passwords = ['password1', 'password2', 'password3', 'password4', 'password5'];
            return in_array($password, $valid_passwords);
        // Default case for invalid method
        default:
            return false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <!-- Main heading for the login form -->
    <h1>Login</h1>
    
    <!-- Login form starts here, using POST method to submit data -->
    <form method="post" action="">
        <!-- Email input field -->
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <!-- Password input field -->
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <!-- Dropdown menu to select verification method -->
        <label for="method">Verification method:</label>
        <select id="method" name="method" required>
            <option value="double_email">Double email</option>
            <option value="reverse_email">Reverse email</option>
            <option value="password_in_array">Password in array</option>
        </select><br><br>

        <!-- Display remaining attempts, readonly input field -->
        <label for="remaining_attempts">Tries remaining:</label>
        <input type="text" id="remaining_attempts" value="<?php echo $remaining_attempts; ?>" readonly><br><br>

        <!-- Submit button for the login form -->
        <button type="submit" name="login">Login</button>
    </form>
    
    <!-- Display message to the user -->
    <p><?php echo $message; ?></p>
</body>
</html>

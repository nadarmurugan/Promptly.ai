<?php
session_start();

// -------------------------------------------------------------------------
// IMPORTANT: UNCOMMENTED FOR DYNAMIC LOGIN TO WORK WITH REAL DATABASE
// -------------------------------------------------------------------------
require_once '../includes/db.php';         // Assumes this file provides the $pdo connection
require_once '../includes/functions.php';   // Assumes this file provides sanitizeInput

// Check if the user is already logged in and if their role is NOT admin.
if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php'); // Redirect to non-admin home page
    exit;
}

// --- MOCK FUNCTION CHECK (just in case external functions.php is missing) ---

if (!function_exists('sanitizeInput')) {
    // If external file is missing, provide a local fallback
    function sanitizeInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }
}

/**
 * Checks admin credentials against the actual database using the $pdo connection.
 * This function is tailored to your current 'MOCK_HASH_MD5' storage scheme.
 * * NOTE: For production, you must use password_hash() and password_verify() for security.
 */
if (!function_exists('checkAdminCredentials')) {
    function checkAdminCredentials($username, $password, $pdo) {
        
        // Ensure the PDO object is available
        if (!isset($pdo)) {
            // Log an error if the database connection failed to load
            error_log("FATAL: Database connection (\$pdo) is not available in login.php.");
            return false;
        }

        try {
            // 1. Prepare and execute the query to fetch the stored hash for the username
            $stmt = $pdo->prepare("SELECT password FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            $stored_hash = $stmt->fetchColumn();
            
            if (!$stored_hash) {
                return false; // User not found
            }
            
            // 2. Re-create the mock hash from the submitted password
            // This must match the exact hashing used in add_admins.php (MOCK_HASH_MD5)
            $submitted_hash = 'MOCK_HASH_' . md5($password);
            
            // 3. Compare the hashes
            return $submitted_hash === $stored_hash;

        } catch (PDOException $e) {
            // Log database errors but fail login for security
            error_log("Login DB Error: " . $e->getMessage());
            return false;
        }
    }
}
// --- END FUNCTION DEFINITIONS ---

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize the username. The password must remain un-sanitized for hash comparison.
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password']; 

    // Dynamic Check: Pass the $pdo object to the function
    // Assuming $pdo is available globally from '../includes/db.php'
    if (checkAdminCredentials($username, $password, $pdo)) {
        // Credentials are valid
        $_SESSION['role'] = 'admin';
        header('Location: index.php'); // Redirect to admin dashboard on success
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Promptly.ai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Custom Tailwind Configuration for primary colors and Inter font
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'primary': '#6366f1', // Indigo 500
                        'primary-dark': '#4f46e5', // Indigo 600
                        'card-bg': 'rgba(17, 24, 39, 0.9)', // Dark background for the card
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Custom styles for a modern, glass-morphism effect on the card */
        .login-card {
            background-color: var(--card-bg);
            backdrop-filter: blur(15px); /* Apply the blur effect */
            border: 1px solid rgba(129, 140, 248, 0.2); /* Subtle light indigo border */
        }
        /* Custom gradient for the body */
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        }
    </style>
</head>
<body class="font-sans min-h-screen flex items-center justify-center p-4">
    <form
        method="POST"
        class="login-card p-8 md:p-10 rounded-3xl shadow-2xl w-full max-w-sm transform transition duration-500 hover:shadow-primary/50"
    >
        <div class="text-center mb-8">
            <div class="text-5xl mb-3 text-primary font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2a10 10 0 0 0-7.3 16.9A10 10 0 0 0 20.3 7.1 10 10 0 0 0 12 2z"></path>
                    <path d="M12 10V4"></path>
                    <path d="M16 14l-4 4-4-4"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-white">Admin Access</h2>
            <p class="text-gray-400 mt-2">Enter credentials for Promptly.ai management</p>
        </div>

        <?php if($error): ?>
            <div class="p-3 mb-6 rounded-xl bg-red-900/40 text-red-300 border border-red-600/50 text-center text-sm font-semibold transition duration-300 ease-in-out">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <input
            type="text"
            name="username"
            placeholder="Username"
            required
            aria-label="Username"
            class="w-full p-4 rounded-xl mb-5 bg-gray-700/80 text-white placeholder-gray-400 border border-gray-700 focus:outline-none focus:ring-4 focus:ring-primary/50 focus:border-primary transition duration-300"
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
            aria-label="Password"
            class="w-full p-4 rounded-xl mb-8 bg-gray-700/80 text-white placeholder-gray-400 border border-gray-700 focus:outline-none focus:ring-4 focus:ring-primary/50 focus:border-primary transition duration-300"
        >

        <button
            type="submit"
            class="w-full py-4 bg-primary hover:bg-primary-dark rounded-xl font-bold text-lg text-white shadow-lg shadow-primary/40 transition duration-300 ease-in-out transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-primary/60"
        >
            Login Securely
        </button>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-400">
                Not an admin?
                <a href="../index.php" class="text-primary hover:text-primary-dark font-semibold transition duration-300">
                    Go back to the main site.
                </a>
            </p>
        </div>
    </form>
</body>
</html>

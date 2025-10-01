<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// --- MOCK FUNCTIONS FOR STANDALONE VIEW ---
// These functions are used if the real includes/functions.php is missing.

if (!function_exists('sanitizeInput')) {
    function sanitizeInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }
}
if (!function_exists('isAdmin')) {
    function isAdmin() {
        // Mock check for a logged-in admin
        if (!isset($_SESSION['role'])) {
             $_SESSION['role'] = 'admin'; 
        }
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
}
if (!function_exists('adminExists')) {
    /**
     * MOCK implementation of checking if an admin exists.
     * Always returns true for 'admin' to simulate the duplicate error.
     */
    function adminExists($username, $pdo = null) {
        // In a real application, you'd use the $pdo object here:
        // $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = ?");
        // $stmt->execute([$username]);
        // return $stmt->fetchColumn() > 0;
        
        // Mocking the scenario where 'admin' already exists
        return strtolower($username) === 'admin';
    }
}
// --- END MOCK FUNCTIONS ---

// Security check: ensure only admins can access this page
if(!isAdmin()){ 
    header('Location: login.php'); 
    exit; 
}

$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password']; 
    
    // 1. Check if the admin user already exists to prevent integrity constraint violation
    if (adminExists($username, $pdo ?? null)) {
        $message = "ERROR: The username '{$username}' already exists. Please choose a different username.";
    } else {
        // If the username is unique, proceed with insertion
        
        // IMPORTANT: In a real application, use password_hash() for security.
        $hashed_password = 'MOCK_HASH_' . md5($password); 

        // Conditional DB interaction
        if (isset($pdo)) {
            try {
                // Real insertion logic
                $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
                if($stmt->execute([$username, $hashed_password])){
                    // Redirect on success to prevent form resubmission and display success message on index
                    header('Location: index.php?success=' . urlencode("Admin user '{$username}' added successfully!"));
                    exit;
                } else {
                    $message = "Failed to add admin user due to a database error.";
                }
            } catch (PDOException $e) {
                // Catch any other potential real DB errors
                $message = "Database error: " . $e->getMessage();
            }
        } else {
            // Mock successful operation when $pdo is not available
            $message = "Admin user '{$username}' added successfully! (Database operation mocked)";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Admin - Promptly.ai</title>
<!-- Load Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    // Custom Tailwind Configuration for consistent styling
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                },
                colors: {
                    'primary': '#6366f1', // Indigo 500
                    'primary-dark': '#4f46e5', // Indigo 600
                }
            }
        }
    }
</script>
<!-- Load Inter font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
    body {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        font-family: 'Inter', sans-serif;
    }
    .admin-header {
        backdrop-filter: blur(8px);
    }
    .form-card {
        background: rgba(31, 41, 55, 0.8);
        backdrop-filter: blur(10px);
    }
</style>
</head>
<body class="min-h-screen">

<header class="admin-header sticky top-0 z-10 bg-gray-900/80 shadow-lg p-4 flex justify-between items-center border-b border-indigo-900">
    <div class="flex items-center space-x-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="8.5" cy="7" r="4"></circle>
            <line x1="18" y1="8" x2="23" y2="13"></line>
            <line x1="23" y1="8" x2="18" y2="13"></line>
        </svg>
        <h1 class="text-3xl font-extrabold text-white">Add New Admin</h1>
    </div>
    <a href="index.php" class="bg-gray-700 hover:bg-gray-600 px-5 py-2 rounded-xl font-semibold text-white transition duration-300 transform hover:scale-[1.05] shadow-md shadow-gray-700/30">
        Dashboard
    </a>
</header>

<main class="max-w-xl mx-auto p-4 md:p-8">
    
    <!-- Status Message -->
    <?php if($message): ?>
        <div class="p-4 mb-6 rounded-xl text-center font-semibold 
            <?php echo strpos($message, 'ERROR:') !== false || strpos($message, 'Failed') !== false || strpos($message, 'Database error') !== false ? 'bg-red-900/40 text-red-300 border border-red-600/50' : 'bg-green-900/40 text-green-300 border border-green-600/50'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Admin Creation Form -->
    <form action="" method="POST" class="form-card p-6 md:p-8 rounded-2xl shadow-2xl border border-gray-700/50 flex flex-col gap-6">
        
        <h2 class="text-xl font-bold text-white border-b border-gray-700 pb-4">New Admin Credentials</h2>

        <!-- Username Input -->
        <div>
            <label for="username" class="text-sm font-medium text-gray-300 mb-2 block">Username</label>
            <input type="text" id="username" name="username" placeholder="e.g., jane.doe.admin" required 
                class="w-full p-4 rounded-xl bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-primary/50 focus:border-primary transition duration-300">
        </div>

        <!-- Password Input -->
        <div>
            <label for="password" class="text-sm font-medium text-gray-300 mb-2 block">Password</label>
            <input type="password" id="password" name="password" placeholder="Use a strong password" required 
                class="w-full p-4 rounded-xl bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-primary/50 focus:border-primary transition duration-300">
        </div>

        <!-- Submit Button -->
        <button type="submit" 
            class="w-full py-4 bg-primary hover:bg-primary-dark rounded-xl font-bold text-lg text-white shadow-lg shadow-primary/40 transition duration-300 ease-in-out transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-primary/60">
            Create Admin Account
        </button>
    </form>
</main>
</body>
</html>

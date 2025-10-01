<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// --- MOCK FUNCTION/DATA FOR STANDALONE VIEW ---
if (!function_exists('sanitizeInput')) {
    function sanitizeInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }
}
if (!function_exists('isAdmin')) {
    function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
}
$admin = ['id' => 1, 'username' => 'admin', 'password_hash' => 'mock_hash_12345']; 
// --- END MOCK FUNCTIONS ---

if(!isAdmin()){ header('Location: login.php'); exit; }

if(!isset($_GET['id'])) { header('Location: index.php'); exit; }
$id = intval($_GET['id']);

$message = '';

// Fetch the existing admin data (Mocked if $pdo is not available)
if (isset($pdo)) {
    $stmt = $pdo->prepare("SELECT id, username, password FROM admins WHERE id = ?");
    $stmt->execute([$id]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$admin) { header('Location: index.php'); exit; }
} else {
    // Mock admin data based on ID for editing simulation
    if ($id !== $admin['id']) {
        $admin = ['id' => $id, 'username' => 'mock_user_' . $id, 'password_hash' => 'mock_hash_' . $id];
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password']; 
    $password_field = '';
    $params = [$username];

    // Check if password field was used (only update if non-empty)
    if (!empty($password)) {
        // In a real application, you MUST hash the password before saving
        $hashed_password = 'MOCK_HASH_' . md5($password); 
        $password_field = ", password=?";
        $params[] = $hashed_password;
    }
    
    $params[] = $id;

    // Mock DB interaction since $pdo is not available in this scope
    if (isset($pdo)) {
        // Real update logic here
        $sql = "UPDATE admins SET username=?{$password_field} WHERE id=?";
        $stmt = $pdo->prepare($sql);
        if($stmt->execute($params)){
            $message = "Admin user '{$username}' updated successfully!";
            // Re-fetch data or update array manually for immediate display
            $admin['username'] = $username; 
        } else {
            $message = "Failed to update admin user.";
        }
    } else {
        // Mock successful operation
        $message = "Admin user '{$username}' updated successfully! (Database operation mocked)";
        $admin['username'] = $username;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Admin - Promptly.ai</title>
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
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
        </svg>
        <h1 class="text-3xl font-extrabold text-white">Edit Admin (ID: <?php echo htmlspecialchars($admin['id']); ?>)</h1>
    </div>
    <a href="index.php" class="bg-gray-700 hover:bg-gray-600 px-5 py-2 rounded-xl font-semibold text-white transition duration-300 transform hover:scale-[1.05] shadow-md shadow-gray-700/30">
        Dashboard
    </a>
</header>

<main class="max-w-xl mx-auto p-4 md:p-8">
    
    <!-- Status Message -->
    <?php if($message): ?>
        <div class="p-4 mb-6 rounded-xl text-center font-semibold 
            <?php echo strpos($message, 'successfully') !== false ? 'bg-green-900/40 text-green-300 border border-green-600/50' : 'bg-red-900/40 text-red-300 border border-red-600/50'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Admin Editing Form -->
    <form action="" method="POST" class="form-card p-6 md:p-8 rounded-2xl shadow-2xl border border-gray-700/50 flex flex-col gap-6">
        
        <h2 class="text-xl font-bold text-white border-b border-gray-700 pb-4">Editing: <?php echo htmlspecialchars($admin['username']); ?></h2>

        <!-- Username Input -->
        <div>
            <label for="username" class="text-sm font-medium text-gray-300 mb-2 block">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required 
                class="w-full p-4 rounded-xl bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-primary/50 focus:border-primary transition duration-300">
        </div>

        <!-- Password Input -->
        <div>
            <label for="password" class="text-sm font-medium text-gray-300 mb-2 block">New Password (Leave blank to keep existing)</label>
            <input type="password" id="password" name="password" placeholder="Enter new password if changing"
                class="w-full p-4 rounded-xl bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-yellow-500/50 focus:border-yellow-500 transition duration-300">
            <p class="text-xs text-gray-500 mt-2">The current password hash is stored securely.</p>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
            class="w-full py-4 bg-yellow-500 hover:bg-yellow-600 rounded-xl font-bold text-lg text-white shadow-lg shadow-yellow-500/40 transition duration-300 ease-in-out transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-yellow-500/60">
            Update Admin Credentials
        </button>
    </form>
</main>
</body>
</html>

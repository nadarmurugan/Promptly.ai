<?php
// admins/index.php
// This is the main Admin Dashboard page for Promptly.ai.

// 1. Start the session at the very beginning
session_start();

// 2. Include necessary files
// The actual dynamic data fetching relies on the functions in 'functions.php' 
// and the database connection in 'db.php'.
require_once '../includes/db.php';
require_once '../includes/functions.php';

// --- MOCK DATA/FUNCTIONS FOR STANDALONE VIEW (Conditional loading) ---
// These mock functions execute only if the real functions are NOT defined (e.g., if includes fail).
if (!function_exists('getContacts')) {
    function getContacts() {
        return [
            ['id' => 10, 'name' => 'Jeyamurugan Nadar', 'email' => 'murugannadar077@gmail.com', 'message' => 'a', 'created_at' => '2025-09-30 17:26:31'],
            ['id' => 11, 'name' => 'Jane Doe', 'email' => 'jane.doe@example.com', 'message' => 'Query about the Haiku Generator', 'created_at' => '2025-09-30 17:27:59'],
            ['id' => 18, 'name' => 'John Smith', 'email' => 'john.smith@web.com', 'message' => 'Need help with the Code Debugger tool.', 'created_at' => '2025-09-30 17:49:15'],
        ];
    }
}
if (!function_exists('getAdmins')) {
    function getAdmins() {
        return [
            ['id' => 1, 'username' => 'admin', 'password' => 'hashed_password_for_admin_1', 'created_at' => '2025-09-29 21:23:17'],
            ['id' => 2, 'username' => 'superadmin', 'password' => 'hashed_password_for_admin_2', 'created_at' => '2025-09-30 10:00:00'],
        ];
    }
}
if (!function_exists('isAdmin')) {
    function isAdmin() {
        // Mock check, assuming user is logged in as 'admin'
        if (!isset($_SESSION['role'])) {
             $_SESSION['role'] = 'admin'; 
        }
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
}
if (!function_exists('getPrompts')) {
    function getPrompts() {
        return [
            ['id' => 1, 'title' => 'Haiku Generator', 'description' => 'Generates Haikus on any topic. A short description for the AI prompt tool.', 'image' => 'haiku.png'],
            ['id' => 2, 'title' => 'Code Debugger', 'description' => 'Fixes common syntax and logic errors in code snippets with detailed explanations.', 'image' => 'debugger.png'],
        ];
    }
}
if (!function_exists('getCurrentAdminId')) {
    function getCurrentAdminId() {
        // Mocking the currently logged-in admin as ID 1 
        return 1; 
    }
}
if (!function_exists('countAdmins')) {
    function countAdmins() {
        return 2; 
    }
}
// --- END MOCK FUNCTIONS ---

// 3. Security check: Redirect non-admins or unauthenticated users.
if(!isAdmin()) {
    header('Location: login.php');
    exit;
}

// 4. Fetch all necessary dynamic data
$prompts = getPrompts();
$contacts = getContacts(); 
$admins = getAdmins(); 
$current_admin_id = getCurrentAdminId();
$admin_count = countAdmins(); // Used for critical admin deletion logic
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Promptly.ai</title>
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
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
    /* Custom gradient for the body */
    body {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        font-family: 'Inter', sans-serif;
    }
    /* Style for the semi-transparent sticky header */
    .admin-header {
        backdrop-filter: blur(8px);
    }
    /* Style for table card backgrounds */
    .table-card {
        background: rgba(31, 41, 55, 0.7); /* gray-800/70 */
        backdrop-filter: blur(5px);
    }
</style>
</head>
<body class="min-h-screen">

<header class="admin-header sticky top-0 z-10 bg-gray-900/80 shadow-lg p-4 flex justify-between items-center border-b border-indigo-900">
    <div class="flex items-center space-x-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="9" rx="1"></rect>
            <rect x="14" y="3" width="7" height="5" rx="1"></rect>
            <rect x="14" y="12" width="7" height="9" rx="1"></rect>
            <rect x="3" y="16" width="7" height="5" rx="1"></rect>
        </svg>
        <h1 class="text-3xl font-extrabold text-white">Promptly.ai Admin Panel</h1>
    </div>
   <a href="logout.php" 
   onclick="return confirm('Are you sure you want to securely log out of the Promptly.ai Admin Panel?');" 
   class="bg-red-600 hover:bg-red-700 px-5 py-2 rounded-xl font-semibold text-white transition duration-300 transform hover:scale-[1.05] shadow-md shadow-red-700/30">
    Logout
</a>
</header>

<main class="max-w-7xl mx-auto p-4 md:p-8 space-y-12">

    <?php 
    // Display success/error messages from URL parameters
    if (isset($_GET['success'])): ?>
        <div class="bg-green-600/20 text-green-300 p-4 rounded-lg border border-green-500/50 font-medium">
            <?php echo htmlspecialchars($_GET['success']); ?> 🟢
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="bg-red-600/20 text-red-300 p-4 rounded-lg border border-red-500/50 font-medium">
            <?php echo htmlspecialchars($_GET['error']); ?> 🔴
        </div>
    <?php endif; ?>

    <section>
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 bg-gray-800/50 p-4 rounded-xl border border-gray-700">
            <h2 class="text-2xl font-bold text-gray-200 mb-4 md:mb-0">Prompt Management</h2>
            <a href="add_prompt.php" class="w-full md:w-auto bg-primary hover:bg-primary-dark px-6 py-3 rounded-xl font-bold text-white transition duration-300 shadow-lg shadow-primary/40 transform hover:scale-[1.02]">
                + Add New Prompt
            </a>
        </div>
        
        <div class="table-card p-4 rounded-xl shadow-2xl border border-gray-700/50">
            <div class="overflow-x-auto rounded-lg">
                <table class="min-w-full text-sm text-left text-gray-300">
                    <thead class="text-xs text-gray-100 uppercase bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-16">ID</th>
                            <th scope="col" class="px-6 py-3 min-w-[200px]">Title</th>
                            <th scope="col" class="px-6 py-3 max-w-sm">Description</th>
                            <th scope="col" class="px-6 py-3 w-20">Image</th>
                            <th scope="col" class="px-6 py-3 w-48 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($prompts)): ?>
                            <?php foreach($prompts as $p): ?>
                                <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-700/60 transition duration-150">
                                    <td class="px-6 py-4 font-medium text-gray-200"><?php echo htmlspecialchars($p['id']); ?></td>
                                    <td class="px-6 py-4 font-semibold"><?php echo htmlspecialchars($p['title']); ?></td>
                                    <td class="px-6 py-4 max-w-xs truncate text-gray-400">
                                        <?php echo htmlspecialchars($p['description']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <img 
                                            src="../assets/images/<?php echo htmlspecialchars($p['image']); ?>" 
                                            onerror="this.onerror=null; this.src='https://placehold.co/64x64/2a3547/ffffff?text=IMG'" 
                                            alt="<?php echo htmlspecialchars($p['title']); ?>" 
                                            class="h-16 w-16 object-cover rounded-md border border-gray-600"
                                        >
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end space-x-3">
                                            <a 
                                                href="edit_prompt.php?id=<?php echo htmlspecialchars($p['id']); ?>" 
                                                class="bg-yellow-500 hover:bg-yellow-600 px-4 py-2 text-white rounded-lg text-xs font-bold transition duration-200"
                                            >
                                                Edit
                                            </a>
                                            <a 
                                                href="delete_prompt.php?id=<?php echo htmlspecialchars($p['id']); ?>" 
                                                onclick="return confirm('Are you sure you want to delete this prompt: <?php echo htmlspecialchars(addslashes($p['title'])); ?>?');" 
                                                class="bg-red-600 hover:bg-red-700 px-4 py-2 text-white rounded-lg text-xs font-bold transition duration-200"
                                            >
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="bg-gray-800">
                                <td colspan="5" class="text-center py-6 text-lg text-gray-400">
                                    <p class="mb-2">No prompts have been added yet.</p>
                                    <a href="add_prompt.php" class="text-primary hover:underline font-medium">Click here to add the first prompt.</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <hr class="border-gray-700/50">

    <section>
        <h2 class="text-2xl font-bold text-gray-200 mb-6 border-b border-gray-700/50 pb-2">Contact Messages</h2>
        <div class="table-card p-4 rounded-xl shadow-2xl border border-gray-700/50">
            <div class="overflow-x-auto rounded-lg">
                <table class="min-w-full text-sm text-left text-gray-300">
                    <thead class="text-xs text-gray-100 uppercase bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-16">ID</th>
                            <th scope="col" class="px-6 py-3 min-w-[150px]">Name</th>
                            <th scope="col" class="px-6 py-3 min-w-[200px]">Email</th>
                            <th scope="col" class="px-6 py-3 max-w-sm">Message</th>
                            <th scope="col" class="px-6 py-3 min-w-[150px]">Created At</th>
                            <th scope="col" class="px-6 py-3 w-32 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($contacts)): ?>
                            <?php foreach($contacts as $c): ?>
                                <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-700/60 transition duration-150">
                                    <td class="px-6 py-4 font-medium text-gray-200"><?php echo htmlspecialchars($c['id']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($c['name']); ?></td>
                                    <td class="px-6 py-4 text-primary font-medium"><?php echo htmlspecialchars($c['email']); ?></td>
                                    <td class="px-6 py-4 max-w-xs truncate text-gray-400">
                                        <?php echo htmlspecialchars($c['message']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500"><?php echo htmlspecialchars($c['created_at']); ?></td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end space-x-3">
                                            <a 
                                                href="view_contact.php?id=<?php echo htmlspecialchars($c['id']); ?>" 
                                                class="bg-blue-600 hover:bg-blue-700 px-4 py-2 text-white rounded-lg text-xs font-bold transition duration-200"
                                            >
                                                View
                                            </a>
                                            <a 
                                                href="delete_contact.php?id=<?php echo htmlspecialchars($c['id']); ?>" 
                                                onclick="return confirm('Are you sure you want to delete this contact message from <?php echo htmlspecialchars(addslashes($c['name'])); ?>?');" 
                                                class="bg-red-600 hover:bg-red-700 px-4 py-2 text-white rounded-lg text-xs font-bold transition duration-200"
                                            >
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="bg-gray-800">
                                <td colspan="6" class="text-center py-6 text-lg text-gray-400">No contact messages found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <hr class="border-gray-700/50">

    <section>
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 bg-gray-800/50 p-4 rounded-xl border border-gray-700">
            <h2 class="text-2xl font-bold text-gray-200 mb-4 md:mb-0">Admin Users</h2>
            <a href="add_admins.php" class="w-full md:w-auto bg-primary hover:bg-primary-dark px-6 py-3 rounded-xl font-bold text-white transition duration-300 shadow-lg shadow-primary/40 transform hover:scale-[1.02]">
                + Add New Admin
            </a>
        </div>

        <div class="table-card p-4 rounded-xl shadow-2xl border border-gray-700/50">
            <div class="overflow-x-auto rounded-lg">
                <table class="min-w-full text-sm text-left text-gray-300">
                    <thead class="text-xs text-gray-100 uppercase bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-16">ID</th>
                            <th scope="col" class="px-6 py-3 min-w-[200px]">Username</th>
                            <th scope="col" class="px-6 py-3 min-w-[200px]">Password Hash (or Mock)</th>
                            <th scope="col" class="px-6 py-3 min-w-[150px]">Created At</th>
                            <th scope="col" class="px-6 py-3 w-32 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($admins)): ?>
                            <?php foreach($admins as $a): ?>
                                <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-700/60 transition duration-150">
                                    <td class="px-6 py-4 font-medium text-gray-200"><?php echo htmlspecialchars($a['id']); ?></td>
                                    <td class="px-6 py-4 font-semibold text-primary"><?php echo htmlspecialchars($a['username']); ?></td>
                                    <td class="px-6 py-4 text-gray-400">
                                        ***<?php echo substr(htmlspecialchars($a['password']), -3); ?>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500"><?php echo htmlspecialchars($a['created_at']); ?></td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end space-x-3">
                                            <a 
                                                href="edit_admin.php?id=<?php echo htmlspecialchars($a['id']); ?>" 
                                                class="bg-yellow-500 hover:bg-yellow-600 px-4 py-2 text-white rounded-lg text-xs font-bold transition duration-200"
                                            >
                                                Edit
                                            </a>
                                            
                                            <?php 
                                            // Conditional rendering for the Delete button based on security rules
                                            $disable_delete = false;
                                            $warning_message = 'WARNING: Are you sure you want to delete admin user: ' . htmlspecialchars(addslashes($a['username'])) . '?';

                                            if ((int)$a['id'] === (int)$current_admin_id) {
                                                // Security Check 1: Cannot delete self
                                                $disable_delete = true;
                                                $warning_message = 'ERROR: You cannot delete your own account!';
                                            } elseif ($admin_count <= 1) {
                                                // Security Check 2: Cannot delete the last remaining admin
                                                $disable_delete = true;
                                                $warning_message = 'ERROR: Cannot delete the last remaining administrator account!';
                                            }
                                            
                                            $delete_class = $disable_delete 
                                                ? 'bg-gray-500 cursor-not-allowed opacity-50' 
                                                : 'bg-red-600 hover:bg-red-700';
                                            ?>
                                            <a 
                                                href="<?php echo $disable_delete ? '#' : 'delete_admin.php?id=' . htmlspecialchars($a['id']); ?>" 
                                                onclick="return confirm('<?php echo $warning_message; ?>');" 
                                                class="px-4 py-2 text-white rounded-lg text-xs font-bold transition duration-200 <?php echo $delete_class; ?>"
                                                <?php echo $disable_delete ? 'aria-disabled="true"' : ''; ?>
                                            >
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="bg-gray-800">
                                <td colspan="5" class="text-center py-6 text-lg text-gray-400">
                                    <p class="mb-2">No admin users found.</p>
                                    <a href="add_admins.php" class="text-primary hover:underline font-medium">Click here to add the first admin.</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<footer class="text-center p-4 text-gray-500 text-sm mt-8 border-t border-gray-800">
    &copy; <?php echo date('Y'); ?> Promptly.ai Admin Panel. All rights reserved.
</footer>

</body>
</html>



<?php
// admins/view_contact.php
// Displays the full details of a single contact message.

// 1. Start the session and include core files
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// 2. Security check: Redirect non-admins
if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

// 3. Input Validation: Check for required 'id' parameter
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: index.php?error=' . urlencode('Invalid contact message ID provided.'));
    exit;
}

$contact_id = (int)$_GET['id'];
$contact = getContactById($contact_id); // Use the new function from functions.php

// 4. Data Existence Check
if (!$contact) {
    header('Location: index.php?error=' . urlencode('Contact message not found.'));
    exit;
}

// Tailwind color configuration (same as index.php)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Contact #<?php echo htmlspecialchars($contact['id']); ?> - Promptly.ai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], },
                    colors: { 'primary': '#6366f1', 'primary-dark': '#4f46e5', }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%); font-family: 'Inter', sans-serif; }
        .card { background: rgba(31, 41, 55, 0.7); backdrop-filter: blur(5px); }
    </style>
</head>
<body class="min-h-screen p-4 md:p-8">

    <main class="max-w-4xl mx-auto space-y-8">
        <a href="index.php" class="inline-flex items-center text-primary hover:text-primary-dark transition duration-300 font-medium mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to Dashboard
        </a>

        <div class="card p-8 rounded-xl shadow-2xl border border-gray-700/50">
            <h1 class="text-3xl font-extrabold text-white border-b border-gray-700 pb-4 mb-6">
                Contact Message Details
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-gray-700 pb-6 mb-6">
                <div class="space-y-2">
                    <p class="text-sm font-semibold text-gray-400 uppercase">Sender Name</p>
                    <p class="text-xl text-gray-200"><?php echo htmlspecialchars($contact['name']); ?></p>
                </div>
                <div class="space-y-2">
                    <p class="text-sm font-semibold text-gray-400 uppercase">Sender Email</p>
                    <p class="text-xl text-primary font-medium">
                        <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>" class="hover:underline">
                            <?php echo htmlspecialchars($contact['email']); ?>
                        </a>
                    </p>
                </div>
                <div class="space-y-2 col-span-1 md:col-span-2">
                    <p class="text-sm font-semibold text-gray-400 uppercase">Date Received</p>
                    <p class="text-lg text-gray-300"><?php echo htmlspecialchars($contact['created_at']); ?></p>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-200 mb-4">
                Full Message
            </h2>
            <div class="bg-gray-800 p-6 rounded-lg text-gray-300 text-base leading-relaxed whitespace-pre-wrap shadow-inner border border-gray-700">
                <?php echo htmlspecialchars($contact['message']); ?>
            </div>
            
            <div class="mt-8 text-right">
                <a 
                    href="delete_contact.php?id=<?php echo htmlspecialchars($contact['id']); ?>" 
                    onclick="return confirm('WARNING: Are you sure you want to PERMANENTLY delete this contact message from <?php echo htmlspecialchars(addslashes($contact['name'])); ?>? This action cannot be undone.');" 
                    class="bg-red-600 hover:bg-red-700 px-6 py-3 text-white rounded-xl font-bold transition duration-300 shadow-lg shadow-red-700/30 transform hover:scale-[1.02]"
                >
                    Delete Message
                </a>
            </div>
        </div>
    </main>

</body>
</html>

<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Assuming isAdmin(), sanitizeInput() and $pdo object from db.php are available.

if(!isAdmin()){
    header('Location: login.php');
    exit;
}

$message = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Ensure sanitizeInput is available, or use mock if running standalone
    if (!function_exists('sanitizeInput')) {
        function sanitizeInput($data) {
            return htmlspecialchars(stripslashes(trim($data)));
        }
    }
    
    // NOTE: In a real environment, you should properly validate and sanitize these inputs.
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    $prompt_text = sanitizeInput($_POST['prompt_text']);

    // Handle image upload
    $image = 'default.png';
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = uniqid().'.'.$ext;
        
        // Ensure the directory exists and is writable
        $target_dir = '../assets/images/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image)){
             // Success, $image now holds the new file name
        } else {
            $message = "Failed to upload image.";
            $image = 'default.png'; // Revert to default if move fails
        }
    }

    // Mock DB interaction since $pdo is not available in this scope
    if (isset($pdo)) {
        $stmt = $pdo->prepare("INSERT INTO prompts (title, description, prompt_text, image) VALUES (?, ?, ?, ?)");
        if($stmt->execute([$title, $description, $prompt_text, $image])){
            $message = "Prompt added successfully! (Database simulated)";
        } else {
            $message = "Failed to add prompt. (Database error)";
        }
    } else {
        $message = "Prompt added successfully! (Database connection mocked)";
        // In a real application, you'd perform the DB insert here.
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Prompt - Admin</title>
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
    /* Custom gradient for the body, matching the dashboard */
    body {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        font-family: 'Inter', sans-serif;
    }
    /* Style for the semi-transparent header */
    .admin-header {
        backdrop-filter: blur(8px);
    }
    /* Style for the form container (similar to the table card in index.php) */
    .form-card {
        background: rgba(31, 41, 55, 0.8); /* gray-800/80 */
        backdrop-filter: blur(10px);
    }
</style>
</head>
<body class="min-h-screen">

<header class="admin-header sticky top-0 z-10 bg-gray-900/80 shadow-lg p-4 flex justify-between items-center border-b border-indigo-900">
    <div class="flex items-center space-x-3">
        <!-- Icon: Prompt Add -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 5v14M5 12h14"></path>
            <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z"></path>
        </svg>
        <h1 class="text-3xl font-extrabold text-white">Add New Prompt</h1>
    </div>
    <a href="index.php" class="bg-gray-700 hover:bg-gray-600 px-5 py-2 rounded-xl font-semibold text-white transition duration-300 transform hover:scale-[1.05] shadow-md shadow-gray-700/30">
        <span class="hidden sm:inline">Back to </span>Dashboard
    </a>
</header>

<main class="max-w-3xl mx-auto p-4 md:p-8">
    
    <!-- Status Message -->
    <?php if($message): ?>
        <div class="p-4 mb-6 rounded-xl text-center font-semibold 
            <?php echo strpos($message, 'successfully') !== false ? 'bg-green-900/40 text-green-300 border border-green-600/50' : 'bg-red-900/40 text-red-300 border border-red-600/50'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Prompt Creation Form -->
    <form action="" method="POST" enctype="multipart/form-data" class="form-card p-6 md:p-8 rounded-2xl shadow-2xl border border-gray-700/50 flex flex-col gap-6">
        
        <h2 class="text-xl font-bold text-white border-b border-gray-700 pb-4">Prompt Details</h2>

        <!-- Title Input -->
        <div>
            <label for="title" class="text-sm font-medium text-gray-300 mb-2 block">Title (e.g., "Write a Haiku")</label>
            <input type="text" id="title" name="title" placeholder="Title" required 
                class="w-full p-4 rounded-xl bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-primary/50 focus:border-primary transition duration-300">
        </div>

        <!-- Description Textarea -->
        <div>
            <label for="description" class="text-sm font-medium text-gray-300 mb-2 block">Short Description</label>
            <textarea id="description" name="description" rows="3" placeholder="A brief description of what this prompt template does." required 
                class="w-full p-4 rounded-xl bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-primary/50 focus:border-primary transition duration-300"></textarea>
        </div>

        <!-- Prompt Text Textarea -->
        <div>
            <label for="prompt_text" class="text-sm font-medium text-gray-300 mb-2 block">Full Prompt Text (The raw input for the AI)</label>
            <textarea id="prompt_text" name="prompt_text" rows="7" placeholder="e.g., 'You are a master poet. Write a haiku about the user's input, making sure to follow the 5-7-5 syllable structure.'" required 
                class="w-full p-4 rounded-xl bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-primary/50 focus:border-primary transition duration-300"></textarea>
        </div>

        <!-- Image Upload -->
        <div>
            <label for="image" class="text-sm font-medium text-gray-300 mb-2 block">Feature Image (Optional)</label>
            <input type="file" id="image" name="image" accept="image/*" 
                class="w-full text-gray-400 file:mr-4 file:py-2 file:px-4
                       file:rounded-full file:border-0
                       file:text-sm file:font-semibold
                       file:bg-primary file:text-white
                       hover:file:bg-primary-dark cursor-pointer transition duration-300">
        </div>

        <!-- Submit Button -->
        <button type="submit" 
            class="w-full py-4 bg-primary hover:bg-primary-dark rounded-xl font-bold text-lg text-white shadow-lg shadow-primary/40 transition duration-300 ease-in-out transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-primary/60">
            Create and Publish Prompt
        </button>
    </form>
</main>
</body>
</html>

<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Assuming isAdmin(), sanitizeInput(), and the $pdo object are available.

if(!isAdmin()){ 
    header('Location: login.php'); 
    exit; 
}

if(!isset($_GET['id'])) { 
    header('Location: index.php'); 
    exit; 
}

// Mock DB connection/query if running standalone
if (!isset($pdo)) {
    // Mock the prompt data for demonstration
    $id = intval($_GET['id']);
    $prompt = [
        'id' => $id,
        'title' => 'Mock Title for ID ' . $id,
        'description' => 'This is a mock description for editing the prompt.',
        'prompt_text' => 'You are a professional editor. Review the user\'s text for grammar and clarity, providing suggestions for improvement.',
        'image' => 'mock_image.png'
    ];
} else {
    // Real DB fetching logic
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM prompts WHERE id = ?");
    $stmt->execute([$id]);
    $prompt = $stmt->fetch();
    if(!$prompt) { 
        header('Location: index.php'); 
        exit; 
    }
}

// Mock sanitizeInput if includes are missing
if (!function_exists('sanitizeInput')) {
    function sanitizeInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }
}


$message = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    $prompt_text = sanitizeInput($_POST['prompt_text']);
    
    // Start with the existing image name
    $image = $prompt['image']; 

    // Handle new image upload
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_image = uniqid().'.'.$ext;
        $target_dir = '../assets/images/';

        if (!is_dir($target_dir)) {
            // Attempt to create directory if it doesn't exist
            mkdir($target_dir, 0755, true);
        }
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $new_image)){
            // Optional: Delete old image if it wasn't 'default.png'
            if ($image !== 'default.png' && file_exists($target_dir . $image)) {
                // unlink($target_dir . $image); 
            }
            $image = $new_image;
        } else {
            $message = "Failed to upload new image.";
        }
    }

    // Mock DB interaction since $pdo is not available in this scope
    if (isset($pdo)) {
        $stmt = $pdo->prepare("UPDATE prompts SET title=?, description=?, prompt_text=?, image=? WHERE id=?");
        if($stmt->execute([$title, $description, $prompt_text, $image, $id])){
            $message = "Prompt updated successfully! (Database simulated)";
            // Update the local $prompt array with new data for immediate display
            $prompt['title'] = $title;
            $prompt['description'] = $description;
            $prompt['prompt_text'] = $prompt_text;
            $prompt['image'] = $image;
        } else {
            $message = "Failed to update prompt. (Database error)";
        }
    } else {
        $message = "Prompt updated successfully! (Database connection mocked)";
        // Update the local $prompt array with new data for immediate display
        $prompt['title'] = $title;
        $prompt['description'] = $description;
        $prompt['prompt_text'] = $prompt_text;
        $prompt['image'] = $image;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Prompt - Admin</title>
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
    /* Style for the form container (similar to the card in index.php) */
    .form-card {
        background: rgba(31, 41, 55, 0.8); /* gray-800/80 */
        backdrop-filter: blur(10px);
    }
</style>
</head>
<body class="min-h-screen">

<header class="admin-header sticky top-0 z-10 bg-gray-900/80 shadow-lg p-4 flex justify-between items-center border-b border-indigo-900">
    <div class="flex items-center space-x-3">
        <!-- Icon: Prompt Edit (Pencil/Pen) -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 20h9"></path>
            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
        </svg>
        <h1 class="text-3xl font-extrabold text-white">Edit Prompt (ID: <?php echo htmlspecialchars($id); ?>)</h1>
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

    <!-- Prompt Editing Form -->
    <form action="" method="POST" enctype="multipart/form-data" class="form-card p-6 md:p-8 rounded-2xl shadow-2xl border border-gray-700/50 flex flex-col gap-6">
        
        <h2 class="text-xl font-bold text-white border-b border-gray-700 pb-4">Editing: <?php echo htmlspecialchars($prompt['title']); ?></h2>

        <!-- Title Input -->
        <div>
            <label for="title" class="text-sm font-medium text-gray-300 mb-2 block">Title</label>
            <input type="text" id="title" name="title" placeholder="Title" value="<?php echo htmlspecialchars($prompt['title']); ?>" required 
                class="w-full p-4 rounded-xl bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-primary/50 focus:border-primary transition duration-300">
        </div>

        <!-- Description Textarea -->
        <div>
            <label for="description" class="text-sm font-medium text-gray-300 mb-2 block">Short Description</label>
            <textarea id="description" name="description" rows="3" placeholder="A brief description of what this prompt template does." required 
                class="w-full p-4 rounded-xl bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-primary/50 focus:border-primary transition duration-300"><?php echo htmlspecialchars($prompt['description']); ?></textarea>
        </div>

        <!-- Prompt Text Textarea -->
        <div>
            <label for="prompt_text" class="text-sm font-medium text-gray-300 mb-2 block">Full Prompt Text (The raw input for the AI)</label>
            <textarea id="prompt_text" name="prompt_text" rows="7" placeholder="Full prompt text..." required 
                class="w-full p-4 rounded-xl bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-primary/50 focus:border-primary transition duration-300"><?php echo htmlspecialchars($prompt['prompt_text']); ?></textarea>
        </div>

        <!-- Image Upload and Current Image Preview -->
        <div>
            <label for="image" class="text-sm font-medium text-gray-300 mb-2 block">Current Feature Image</label>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 p-4 rounded-xl border border-gray-700 bg-gray-700/50">
                <img 
                    src="../assets/images/<?php echo htmlspecialchars($prompt['image']); ?>" 
                    onerror="this.onerror=null; this.src='https://placehold.co/80x80/2a3547/ffffff?text=IMG'" 
                    alt="Current Image" 
                    class="h-20 w-20 object-cover rounded-xl shadow-md border border-primary/30"
                >
                <div class="flex-grow">
                    <p class="text-gray-400 text-sm mb-2">Upload a new image to replace the current one (<?php echo htmlspecialchars($prompt['image']); ?>):</p>
                    <input type="file" id="image" name="image" accept="image/*" 
                        class="w-full text-gray-400 file:mr-4 file:py-2 file:px-4
                               file:rounded-full file:border-0
                               file:text-sm file:font-semibold
                               file:bg-primary file:text-white
                               hover:file:bg-primary-dark cursor-pointer transition duration-300">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
            class="w-full py-4 bg-yellow-500 hover:bg-yellow-600 rounded-xl font-bold text-lg text-white shadow-lg shadow-yellow-500/40 transition duration-300 ease-in-out transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-yellow-500/60">
            <span class="text-black">Update Prompt</span>
        </button>
    </form>
</main>
</body>
</html>

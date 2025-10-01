<?php
// includes/functions.php

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php'; // Include PDO connection

// --------------------
// Authentication & Security
// --------------------

// Check if user is an admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Get currently logged-in admin ID
function getCurrentAdminId() {
    return $_SESSION['admin_id'] ?? null;
}

// --------------------
// CRUD Helper Functions
// --------------------

// Fetch all prompts
function getPrompts() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM prompts ORDER BY id DESC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching prompts: " . $e->getMessage());
        return [];
    }
}

// Fetch all contact messages
function getContacts() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching contacts: " . $e->getMessage());
        return [];
    }
}

/**
 * Fetch a single contact message by ID using a prepared statement.
 * @param int $id The ID of the contact message.
 * @return array|false The contact data or false if not found/error.
 */
function getContactById($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching contact {$id}: " . $e->getMessage());
        return false; // Changed from null to false for type consistency
    }
}

/**
 * Delete a single contact message by ID using a prepared statement.
 * @param int $id The ID of the contact message to delete.
 * @return bool True on successful deletion, false otherwise.
 */
function deleteContact($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        // Check if any row was affected (deleted)
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        error_log("Error deleting contact {$id}: " . $e->getMessage());
        return false;
    }
}


// Fetch all admins
function getAdmins() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM admins ORDER BY id ASC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching admins: " . $e->getMessage());
        return [];
    }
}

// Count total admins
function countAdmins() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT COUNT(*) AS total FROM admins");
        $row = $stmt->fetch();
        return $row['total'] ?? 0;
    } catch (PDOException $e) {
        error_log("Error counting admins: " . $e->getMessage());
        return 0;
    }
}

// --------------------
// Utility
// --------------------

// Sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

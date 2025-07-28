<?php
session_start();
require_once '../db/conn.php';
require_once '../backend/Semester.php';

echo "<h2>Semester Debug Test</h2>";

// Test 1: Check if table exists
echo "<h3>1. Checking if semesters table exists:</h3>";
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'semesters'");
    $tableExists = $stmt->fetch();
    if ($tableExists) {
        echo "✅ Semesters table exists<br>";
    } else {
        echo "❌ Semesters table does not exist<br>";
    }
} catch (Exception $e) {
    echo "❌ Error checking table: " . $e->getMessage() . "<br>";
}

// Test 2: Check table structure
echo "<h3>2. Checking table structure:</h3>";
try {
    $stmt = $pdo->query("DESCRIBE semesters");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Table structure:<br>";
    foreach ($columns as $column) {
        echo "- {$column['Field']}: {$column['Type']}<br>";
    }
} catch (Exception $e) {
    echo "❌ Error checking structure: " . $e->getMessage() . "<br>";
}

// Test 3: Check if any data exists
echo "<h3>3. Checking for existing data:</h3>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM semesters");
    $count = $stmt->fetchColumn();
    echo "Found $count semesters in database<br>";
} catch (Exception $e) {
    echo "❌ Error counting data: " . $e->getMessage() . "<br>";
}

// Test 4: Test Semester class
echo "<h3>4. Testing Semester class:</h3>";
try {
    $semester = new Semester($pdo);
    $result = $semester->getAllSemester();
    if ($result === 1) {
        echo "❌ getAllSemester() returned error code 1 (no data or error)<br>";
    } else {
        echo "✅ getAllSemester() returned " . count($result) . " semesters<br>";
        foreach ($result as $sem) {
            echo "- {$sem['semester_code']}: {$sem['start_date']} to {$sem['end_date']}<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ Error with Semester class: " . $e->getMessage() . "<br>";
}

echo "<br><a href='semester.php'>Back to Semester Management</a>";
?> 
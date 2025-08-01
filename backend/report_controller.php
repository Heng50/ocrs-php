<?php
session_start();
require_once '../db/conn.php';
require_once 'model/reportModel.php';

if (isset($_GET['action']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $reportModel = new Report($pdo);
    
    $filters = $_POST;
    
    switch ($_GET['action']) {
        case 'courseEnrollment':
            $data = $reportModel->getOverallEnrollment();
            if($data == 1) {
                echo json_encode(['error' => 'No data found']);
                exit;
            }
            echo json_encode(['success' => true, 'data' => $data[0], 'semester' => $data[1], 'programme' => $data[2]]);
            break;
            
        case 'studentPerformance':
            $data = $reportModel->getStudentPerformance();
            if($data == 1) {
                echo json_encode(['error' => 'No data found']);
                exit;
            }
            echo json_encode(['success' => true, 'data' => $data[0], 'cgpa' => $data[1]]);
            break;
            
        case 'overallAcademicProgress':
            $data = $reportModel->getOverallAcademicProgress();
            if($data == 1) {
                echo json_encode(['error' => 'No data found']);
                exit;
            }
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        default:
            echo json_encode(['error' => 'Invalid report type']);
    }
    exit;
} 
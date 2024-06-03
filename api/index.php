<?php 
require_once 'schedule.php';
require_once 'theses.php';
require_once '../config.php';
$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
$schedule = new Schedule($conn);
$theses = new Theses($conn);
$method = $_SERVER['REQUEST_METHOD'];

$parts = explode('/', $_SERVER['REQUEST_URI']);
$endpoint = isset($parts[3]) ? $parts[3] : null;
$id = isset($parts[4]) ? $parts[4] : null;

$typ_prace = isset($parts[5]) ? $parts[5] : null;

header('Content-Type: application/json');

switch($method) {
    case 'GET':
        if ($endpoint == 'schedule') {
            if (!empty($id)) {
                $scheduleData = $schedule->getSchedule($id);
                if ($scheduleData === null) {
                    http_response_code(404); 
                    echo json_encode(['message' => 'Schedule entity not found']);
                } else {
                    echo json_encode(['status' => 'success', 'data' => $scheduleData]);
                }
            } else {
                $allScheduleData = $schedule->getAllSchedule();
                echo $allScheduleData;
            }
        } else if ($endpoint == 'theses') {
            if (!empty($id) && !empty($typ_prace)) {
                $thesesData = $theses->getTheses($id, $typ_prace);
                echo $thesesData;
            } else {
                echo json_encode(['message' => 'Invalid ID']);
            }
        } else {
            http_response_code(404); 
            echo json_encode(['message' => 'Invalid endpoint']);
        }
        break;     
    
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            
            $success = $schedule->addSchedule($data);
            if ($success) {
                http_response_code(201); 
                echo json_encode(['message' => 'Schedule added successfully', 'data' => $data]);
            } else {
                http_response_code(400); 
                echo json_encode(['message' => 'Failed to add schedule', 'data' => $data]);
            }
            break;
        
        
        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!empty($id)) {
                $success = $schedule->editSchedule($id, $data);
                if ($success) {
                    echo json_encode(['message' => 'Schedule updated successfully', 'data' => $data]);
                } else {
                    http_response_code(400); 
                    echo json_encode(['message' => 'Bad request - Invalid input provided', 'data' => $data]);
                }
            } else {
                http_response_code(400); 
                echo json_encode(['message' => 'Invalid ID']);
            }
            break;
        
        
        case 'DELETE':
            if ($endpoint == 'schedule') {
                if (!empty($id)) {
                    $success = $schedule->deleteSchedule($id);
                    if ($success) {
                        http_response_code(200); 
                        echo json_encode(['message' => 'Schedule deleted successfully']);
                    } else {
                        http_response_code(404); 
                        echo json_encode(['message' => 'Schedule not found']);
                    }
                } else {
                    http_response_code(400); 
                    echo json_encode(['message' => 'Invalid ID']);
                }
            } else {
                http_response_code(404); 
                echo json_encode(['message' => 'Invalid endpoint']);
            }
            break;
        
}
?>



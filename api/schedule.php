<?php
class Schedule
{
    
    private $conn;
    private $table = 'schedule';

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getSchedule($id)
    {
        $query = "SELECT * FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 0) {
            return null; 
        }
    
        return $result->fetch_assoc();
    }

    public function getAllSchedule()
    {
        $query = "SELECT * FROM $this->table";
        $result = $this->conn->query($query);
        $scheduleData = [];
        while ($row = $result->fetch_assoc()) {
            $scheduleData[] = $row;
        }
        return json_encode(['status' => 'success', 'data' => $scheduleData]);
    }

    public function deleteSchedule($id)
    {
            $query = "DELETE FROM $this->table WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            
            if ($stmt->affected_rows === 0) {
                return false; 
            }
            
            return true;
    }

        public function editSchedule($id, $data)
    {
        $nazov = $data['nazov'];
        $typ = $data['typ'];
        $miestnost = $data['miestnost'];
        $ucitel = $data['ucitel'];

        $existingSchedule = $this->getSchedule($id);
        if (!$existingSchedule) {
            return false; 
        }

        $sql = "UPDATE $this->table SET nazov = '$nazov', typ = '$typ', miestnost = '$miestnost', ucitel = '$ucitel' WHERE id = $id";
        $result = $this->conn->query($sql);
        return true; 
    }



    public function addSchedule($data)
    {
        
        $nazov = $data['nazov'];
        $den = $data['den'];
        $miestnost = $data['miestnost'];
        $typ = $data['typ'];
        $cas_od = $data['cas_od'];
        $cas_do = $data['cas_do'];
        $ucitel = $data['ucitel'];

        $sql = "INSERT INTO $this->table (nazov, den, miestnost, typ, cas_od, cas_do, ucitel) VALUES ('$nazov', '$den', '$miestnost', '$typ', '$cas_od', '$cas_do', '$ucitel')";
        $result = $this->conn->query($sql);
        return true; 
    }

}
?>
<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/Patient.php';
require_once __DIR__ . '/Doctor.php';

class Appointment {
    private $db;
    private $patient;
    private $doctor;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->patient = new Patient();
        $this->doctor = new Doctor();
    }
    
    // Create a new appointment
    public function create($data) {
        try {
            $query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason, status, notes) 
                      VALUES (:patient_id, :doctor_id, :appointment_date, :appointment_time, :reason, :status, :notes)";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':patient_id', $data['patient_id'], PDO::PARAM_INT);
            $stmt->bindParam(':doctor_id', $data['doctor_id'], PDO::PARAM_INT);
            $stmt->bindParam(':appointment_date', $data['appointment_date']);
            $stmt->bindParam(':appointment_time', $data['appointment_time']);
            $stmt->bindParam(':reason', $data['reason']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':notes', $data['notes']);
            
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            die("Error creating appointment: " . $e->getMessage());
        }
    }
    
    // Get all appointments with patient and doctor names
    public function getAll() {
        try {
            $query = "SELECT a.*, 
                      CONCAT(p.first_name, ' ', p.last_name) AS patient_name,
                      CONCAT('Dr. ', d.first_name, ' ', d.last_name) AS doctor_name
                      FROM appointments a
                      JOIN patients p ON a.patient_id = p.id
                      JOIN doctors d ON a.doctor_id = d.id
                      ORDER BY a.appointment_date DESC, a.appointment_time DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Error fetching appointments: " . $e->getMessage());
        }
    }
    
    // Get appointment by ID with patient and doctor names
    public function getById($id) {
        try {
            $query = "SELECT a.*, 
                      CONCAT(p.first_name, ' ', p.last_name) AS patient_name,
                      CONCAT('Dr. ', d.first_name, ' ', d.last_name) AS doctor_name
                      FROM appointments a
                      JOIN patients p ON a.patient_id = p.id
                      JOIN doctors d ON a.doctor_id = d.id
                      WHERE a.id = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            die("Error fetching appointment: " . $e->getMessage());
        }
    }
    
    // Update appointment
    public function update($id, $data) {
        try {
            $query = "UPDATE appointments 
                      SET patient_id = :patient_id, 
                          doctor_id = :doctor_id, 
                          appointment_date = :appointment_date, 
                          appointment_time = :appointment_time, 
                          reason = :reason, 
                          status = :status, 
                          notes = :notes 
                      WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':patient_id', $data['patient_id'], PDO::PARAM_INT);
            $stmt->bindParam(':doctor_id', $data['doctor_id'], PDO::PARAM_INT);
            $stmt->bindParam(':appointment_date', $data['appointment_date']);
            $stmt->bindParam(':appointment_time', $data['appointment_time']);
            $stmt->bindParam(':reason', $data['reason']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':notes', $data['notes']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error updating appointment: " . $e->getMessage());
        }
    }
    
    // Delete appointment
    public function delete($id) {
        try {
            $query = "DELETE FROM appointments WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error deleting appointment: " . $e->getMessage());
        }
    }
    
    // Search appointments by patient name, doctor name, or reason
    public function search($term) {
        try {
            $term = "%$term%";
            $query = "SELECT a.*, 
                      CONCAT(p.first_name, ' ', p.last_name) AS patient_name,
                      CONCAT('Dr. ', d.first_name, ' ', d.last_name) AS doctor_name
                      FROM appointments a
                      JOIN patients p ON a.patient_id = p.id
                      JOIN doctors d ON a.doctor_id = d.id
                      WHERE p.first_name LIKE :term 
                      OR p.last_name LIKE :term 
                      OR d.first_name LIKE :term 
                      OR d.last_name LIKE :term 
                      OR a.reason LIKE :term
                      ORDER BY a.appointment_date DESC, a.appointment_time DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':term', $term);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Error searching appointments: " . $e->getMessage());
        }
    }
    
    // Get appointments by patient ID
    public function getByPatientId($patientId) {
        try {
            $query = "SELECT a.*, 
                      CONCAT('Dr. ', d.first_name, ' ', d.last_name) AS doctor_name
                      FROM appointments a
                      JOIN doctors d ON a.doctor_id = d.id
                      WHERE a.patient_id = :patient_id
                      ORDER BY a.appointment_date DESC, a.appointment_time DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':patient_id', $patientId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Error fetching patient appointments: " . $e->getMessage());
        }
    }
    
    // Get appointments by doctor ID
    public function getByDoctorId($doctorId) {
        try {
            $query = "SELECT a.*, 
                      CONCAT(p.first_name, ' ', p.last_name) AS patient_name
                      FROM appointments a
                      JOIN patients p ON a.patient_id = p.id
                      WHERE a.doctor_id = :doctor_id
                      ORDER BY a.appointment_date DESC, a.appointment_time DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':doctor_id', $doctorId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Error fetching doctor appointments: " . $e->getMessage());
        }
    }
}
?>
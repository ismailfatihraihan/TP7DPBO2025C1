<?php
require_once __DIR__ . '/../config/config.php';

class Patient {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Create a new patient
    public function create($data) {
        try {
            $query = "INSERT INTO patients (first_name, last_name, date_of_birth, gender, address, phone, email, medical_history) 
                      VALUES (:first_name, :last_name, :date_of_birth, :gender, :address, :phone, :email, :medical_history)";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':date_of_birth', $data['date_of_birth']);
            $stmt->bindParam(':gender', $data['gender']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':medical_history', $data['medical_history']);
            
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            die("Error creating patient: " . $e->getMessage());
        }
    }
    
    // Get all patients
    public function getAll() {
        try {
            $query = "SELECT * FROM patients ORDER BY last_name, first_name";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Error fetching patients: " . $e->getMessage());
        }
    }
    
    // Get patient by ID
    public function getById($id) {
        try {
            $query = "SELECT * FROM patients WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            die("Error fetching patient: " . $e->getMessage());
        }
    }
    
    // Update patient
    public function update($id, $data) {
        try {
            $query = "UPDATE patients 
                      SET first_name = :first_name, 
                          last_name = :last_name, 
                          date_of_birth = :date_of_birth, 
                          gender = :gender, 
                          address = :address, 
                          phone = :phone, 
                          email = :email, 
                          medical_history = :medical_history 
                      WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':date_of_birth', $data['date_of_birth']);
            $stmt->bindParam(':gender', $data['gender']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':medical_history', $data['medical_history']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error updating patient: " . $e->getMessage());
        }
    }
    
    // Delete patient
    public function delete($id) {
        try {
            // First check if patient has appointments
            $query = "SELECT COUNT(*) FROM appointments WHERE patient_id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($stmt->fetchColumn() > 0) {
                return false; // Cannot delete patient with appointments
            }
            
            $query = "DELETE FROM patients WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error deleting patient: " . $e->getMessage());
        }
    }
    
    // Search patients by name
    // public function search($term) {
    //     try {
    //         $term = "%$term%";
    //         $query = "SELECT * FROM patients 
    //                   WHERE first_name LIKE :term 
    //                   OR last_name LIKE :term 
    //                   ORDER BY last_name, first_name";
            
    //         $stmt = $this->db->prepare($query);
    //         $stmt->bindParam(':term', $term);
    //         $stmt->execute();
    //         return $stmt->fetchAll();
    //     } catch (PDOException $e) {
    //         die("Error searching patients: " . $e->getMessage());
    //     }
    // }
    public function search($term) {
        try {
            $term = "%$term%";
            $query = "SELECT * FROM patients 
                    WHERE first_name LIKE ? 
                    OR last_name LIKE ? 
                    ORDER BY last_name, first_name";
            
            $stmt = $this->db->prepare($query);
            // Kirim 3 parameter sekaligus dalam array
            $stmt->execute([$term, $term, $term]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Error searching doctors: " . $e->getMessage());
        }
    }


    // Get patient full name
    public function getFullName($id) {
        $patient = $this->getById($id);
        if ($patient) {
            return $patient['first_name'] . ' ' . $patient['last_name'];
        }
        return '';
    }
}
?>
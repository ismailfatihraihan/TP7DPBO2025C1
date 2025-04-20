<?php
require_once __DIR__ . '/../config/config.php';

class Doctor {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Create a new doctor
    public function create($data) {
        try {
            $query = "INSERT INTO doctors (first_name, last_name, specialization, qualification, phone, email, address) 
                      VALUES (:first_name, :last_name, :specialization, :qualification, :phone, :email, :address)";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':specialization', $data['specialization']);
            $stmt->bindParam(':qualification', $data['qualification']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':address', $data['address']);
            
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            die("Error creating doctor: " . $e->getMessage());
        }
    }
    
    // Get all doctors
    public function getAll() {
        try {
            $query = "SELECT * FROM doctors ORDER BY last_name, first_name";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Error fetching doctors: " . $e->getMessage());
        }
    }
    
    // Get doctor by ID
    public function getById($id) {
        try {
            $query = "SELECT * FROM doctors WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            die("Error fetching doctor: " . $e->getMessage());
        }
    }
    
    // Update doctor
    public function update($id, $data) {
        try {
            $query = "UPDATE doctors 
                      SET first_name = :first_name, 
                          last_name = :last_name, 
                          specialization = :specialization, 
                          qualification = :qualification, 
                          phone = :phone, 
                          email = :email, 
                          address = :address 
                      WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':specialization', $data['specialization']);
            $stmt->bindParam(':qualification', $data['qualification']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':address', $data['address']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error updating doctor: " . $e->getMessage());
        }
    }
    
    // Delete doctor
    public function delete($id) {
        try {
            // First check if doctor has appointments
            $query = "SELECT COUNT(*) FROM appointments WHERE doctor_id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($stmt->fetchColumn() > 0) {
                return false; // Cannot delete doctor with appointments
            }
            
            $query = "DELETE FROM doctors WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error deleting doctor: " . $e->getMessage());
        }
    }
    
    // Search doctors by name or specialization
    // public function search($term) {
    //     try {
    //         $term = "%$term%";
    //         $query = "SELECT * FROM doctors 
    //                   WHERE first_name LIKE :term 
    //                   OR last_name LIKE :term 
    //                   OR specialization LIKE :term 
    //                   ORDER BY last_name, first_name";
            
    //         $stmt = $this->db->prepare($query);
    //         $stmt->bindParam(':term', $term);
    //         $stmt->execute();
    //         return $stmt->fetchAll();
    //     } catch (PDOException $e) {
    //         die("Error searching doctors: " . $e->getMessage());
    //     }
    // }
    public function search($term) {
        try {
            $term = "%$term%";
            $query = "SELECT * FROM doctors 
                    WHERE first_name LIKE ? 
                    OR last_name LIKE ? 
                    OR specialization LIKE ? 
                    ORDER BY last_name, first_name";
            
            $stmt = $this->db->prepare($query);
            // Kirim 3 parameter sekaligus dalam array
            $stmt->execute([$term, $term, $term]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Error searching doctors: " . $e->getMessage());
        }
    }
    
    // Get doctor full name
    public function getFullName($id) {
        $doctor = $this->getById($id);
        if ($doctor) {
            return "Dr. " . $doctor['first_name'] . ' ' . $doctor['last_name'];
        }
        return '';
    }
}
?>
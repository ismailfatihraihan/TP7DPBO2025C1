<?php
require_once '../../config/config.php';
require_once '../../class/Patient.php';

$patient = new Patient();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $errors = [];
    
    if (empty($_POST['first_name'])) {
        $errors[] = "First name is required";
    }
    
    if (empty($_POST['last_name'])) {
        $errors[] = "Last name is required";
    }
    
    if (empty($_POST['date_of_birth'])) {
        $errors[] = "Date of birth is required";
    }
    
    if (empty($_POST['gender'])) {
        $errors[] = "Gender is required";
    }
    
    if (empty($_POST['phone'])) {
        $errors[] = "Phone number is required";
    }
    
    // If no errors, proceed with adding the patient
    if (empty($errors)) {
        $data = [
            'first_name' => sanitize($_POST['first_name']),
            'last_name' => sanitize($_POST['last_name']),
            'date_of_birth' => sanitize($_POST['date_of_birth']),
            'gender' => sanitize($_POST['gender']),
            'address' => sanitize($_POST['address']),
            'phone' => sanitize($_POST['phone']),
            'email' => sanitize($_POST['email']),
            'medical_history' => sanitize($_POST['medical_history'])
        ];
        
        $patientId = $patient->create($data);
        
        if ($patientId) {
            setFlashMessage('success', 'Patient added successfully');
            redirect(baseUrl() . '/view/patients/view.php?id=' . $patientId);
        } else {
            $errors[] = "Failed to add patient";
        }
    }
}

include_once '../../includes/header.php';
?>

<h2>Add New Patient</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="" method="POST">
    <div class="form-group">
        <label for="first_name">First Name *</label>
        <input type="text" id="first_name" name="first_name" required value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : ''; ?>">
    </div>
    
    <div class="form-group">
        <label for="last_name">Last Name *</label>
        <input type="text" id="last_name" name="last_name" required value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : ''; ?>">
    </div>
    
    <div class="form-group">
        <label for="date_of_birth">Date of Birth *</label>
        <input type="date" id="date_of_birth" name="date_of_birth" required value="<?php echo isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : ''; ?>">
    </div>
    
    <div class="form-group">
        <label for="gender">Gender *</label>
        <select id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
            <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="address">Address</label>
        <textarea id="address" name="address"><?php echo isset($_POST['address']) ? $_POST['address'] : ''; ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="phone">Phone *</label>
        <input type="tel" id="phone" name="phone" required value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>">
    </div>
    
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
    </div>
    
    <div class="form-group">
        <label for="medical_history">Medical History</label>
        <textarea id="medical_history" name="medical_history"><?php echo isset($_POST['medical_history']) ? $_POST['medical_history'] : ''; ?></textarea>
    </div>
    
    <div class="form-group">
        <button type="submit" class="btn btn-success">Add Patient</button>
        <a href="list.php" class="btn">Cancel</a>
    </div>
</form>

<?php include_once '../../includes/footer.php'; ?>
<?php
require_once '../../config/config.php';
require_once '../../class/Doctor.php';

$doctor = new Doctor();

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    setFlashMessage('danger', 'Doctor ID is required');
    redirect(baseUrl() . '/view/doctors/list.php');
}

$id = (int)$_GET['id'];
$doctorData = $doctor->getById($id);

// Check if doctor exists
if (!$doctorData) {
    setFlashMessage('danger', 'Doctor not found');
    redirect(baseUrl() . '/view/doctors/list.php');
}

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
    
    if (empty($_POST['specialization'])) {
        $errors[] = "Specialization is required";
    }
    
    if (empty($_POST['qualification'])) {
        $errors[] = "Qualification is required";
    }
    
    if (empty($_POST['phone'])) {
        $errors[] = "Phone number is required";
    }
    
    if (empty($_POST['email'])) {
        $errors[] = "Email is required";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // If no errors, proceed with updating the doctor
    if (empty($errors)) {
        $data = [
            'first_name' => sanitize($_POST['first_name']),
            'last_name' => sanitize($_POST['last_name']),
            'specialization' => sanitize($_POST['specialization']),
            'qualification' => sanitize($_POST['qualification']),
            'phone' => sanitize($_POST['phone']),
            'email' => sanitize($_POST['email']),
            'address' => sanitize($_POST['address'])
        ];
        
        $result = $doctor->update($id, $data);
        
        if ($result) {
            setFlashMessage('success', 'Doctor updated successfully');
            redirect(baseUrl() . '/view/doctors/view.php?id=' . $id);
        } else {
            $errors[] = "Failed to update doctor";
        }
    }
}

include_once '../../includes/header.php';
?>

<h2>Edit Doctor</h2>

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
        <input type="text" id="first_name" name="first_name" required value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : $doctorData['first_name']; ?>">
    </div>
    
    <div class="form-group">
        <label for="last_name">Last Name *</label>
        <input type="text" id="last_name" name="last_name" required value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : $doctorData['last_name']; ?>">
    </div>
    
    <div class="form-group">
        <label for="specialization">Specialization *</label>
        <input type="text" id="specialization" name="specialization" required value="<?php echo isset($_POST['specialization']) ? $_POST['specialization'] : $doctorData['specialization']; ?>">
    </div>
    
    <div class="form-group">
        <label for="qualification">Qualification *</label>
        <input type="text" id="qualification" name="qualification" required value="<?php echo isset($_POST['qualification']) ? $_POST['qualification'] : $doctorData['qualification']; ?>">
    </div>
    
    <div class="form-group">
        <label for="phone">Phone *</label>
        <input type="tel" id="phone" name="phone" required value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : $doctorData['phone']; ?>">
    </div>
    
    <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? $_POST['email'] : $doctorData['email']; ?>">
    </div>
    
    <div class="form-group">
        <label for="address">Address</label>
        <textarea id="address" name="address"><?php echo isset($_POST['address']) ? $_POST['address'] : $doctorData['address']; ?></textarea>
    </div>
    
    <div class="form-group">
        <button type="submit" class="btn btn-success">Update Doctor</button>
        <a href="view.php?id=<?php echo $id; ?>" class="btn">Cancel</a>
    </div>
</form>

<?php include_once '../../includes/footer.php'; ?>
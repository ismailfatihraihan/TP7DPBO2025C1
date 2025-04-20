<?php
require_once '../../config/config.php';
require_once '../../class/Appointment.php';
require_once '../../class/Patient.php';
require_once '../../class/Doctor.php';

$appointment = new Appointment();
$patient = new Patient();
$doctor = new Doctor();

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    setFlashMessage('danger', 'Appointment ID is required');
    redirect(baseUrl() . '/view/appointments/list.php');
}

$id = (int)$_GET['id'];
$appointmentData = $appointment->getById($id);

// Check if appointment exists
if (!$appointmentData) {
    setFlashMessage('danger', 'Appointment not found');
    redirect(baseUrl() . '/view/appointments/list.php');
}

// Get all patients and doctors for dropdown
$patients = $patient->getAll();
$doctors = $doctor->getAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $errors = [];
    
    if (empty($_POST['patient_id'])) {
        $errors[] = "Patient is required";
    }
    
    if (empty($_POST['doctor_id'])) {
        $errors[] = "Doctor is required";
    }
    
    if (empty($_POST['appointment_date'])) {
        $errors[] = "Appointment date is required";
    }
    
    if (empty($_POST['appointment_time'])) {
        $errors[] = "Appointment time is required";
    }
    
    if (empty($_POST['reason'])) {
        $errors[] = "Reason is required";
    }
    
    // If no errors, proceed with updating the appointment
    if (empty($errors)) {
        $data = [
            'patient_id' => (int)sanitize($_POST['patient_id']),
            'doctor_id' => (int)sanitize($_POST['doctor_id']),
            'appointment_date' => sanitize($_POST['appointment_date']),
            'appointment_time' => sanitize($_POST['appointment_time']),
            'reason' => sanitize($_POST['reason']),
            'status' => sanitize($_POST['status']),
            'notes' => sanitize($_POST['notes'])
        ];
        
        $result = $appointment->update($id, $data);
        
        if ($result) {
            setFlashMessage('success', 'Appointment updated successfully');
            redirect(baseUrl() . '/view/appointments/view.php?id=' . $id);
        } else {
            $errors[] = "Failed to update appointment";
        }
    }
}

include_once '../../includes/header.php';
?>

<h2>Edit Appointment</h2>

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
        <label for="patient_id">Patient *</label>
        <select id="patient_id" name="patient_id" required>
            <option value="">Select Patient</option>
            <?php foreach ($patients as $p): ?>
                <option value="<?php echo $p['id']; ?>" <?php echo ($appointmentData['patient_id'] == $p['id'] || (isset($_POST['patient_id']) && $_POST['patient_id'] == $p['id'])) ? 'selected' : ''; ?>>
                    <?php echo $p['first_name'] . ' ' . $p['last_name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="doctor_id">Doctor *</label>
        <select id="doctor_id" name="doctor_id" required>
            <option value="">Select Doctor</option>
            <?php foreach ($doctors as $d): ?>
                <option value="<?php echo $d['id']; ?>" <?php echo ($appointmentData['doctor_id'] == $d['id'] || (isset($_POST['doctor_id']) && $_POST['doctor_id'] == $d['id'])) ? 'selected' : ''; ?>>
                    Dr. <?php echo $d['first_name'] . ' ' . $d['last_name'] . ' (' . $d['specialization'] . ')'; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="appointment_date">Appointment Date *</label>
        <input type="date" id="appointment_date" name="appointment_date" required value="<?php echo isset($_POST['appointment_date']) ? $_POST['appointment_date'] : $appointmentData['appointment_date']; ?>">
    </div>
    
    <div class="form-group">
        <label for="appointment_time">Appointment Time *</label>
        <input type="time" id="appointment_time" name="appointment_time" required value="<?php echo isset($_POST['appointment_time']) ? $_POST['appointment_time'] : $appointmentData['appointment_time']; ?>">
    </div>
    
    <div class="form-group">
        <label for="reason">Reason *</label>
        <input type="text" id="reason" name="reason" required value="<?php echo isset($_POST['reason']) ? $_POST['reason'] : $appointmentData['reason']; ?>">
    </div>
    
    <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="Scheduled" <?php echo ($appointmentData['status'] === 'Scheduled' || (isset($_POST['status']) && $_POST['status'] === 'Scheduled')) ? 'selected' : ''; ?>>Scheduled</option>
            <option value="Completed" <?php echo ($appointmentData['status'] === 'Completed' || (isset($_POST['status']) && $_POST['status'] === 'Completed')) ? 'selected' : ''; ?>>Completed</option>
            <option value="Cancelled" <?php echo ($appointmentData['status'] === 'Cancelled' || (isset($_POST['status']) && $_POST['status'] === 'Cancelled')) ? 'selected' : ''; ?>>Cancelled</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea id="notes" name="notes"><?php echo isset($_POST['notes']) ? $_POST['notes'] : $appointmentData['notes']; ?></textarea>
    </div>
    
    <div class="form-group">
        <button type="submit" class="btn btn-success">Update Appointment</button>
        <a href="view.php?id=<?php echo $id; ?>" class="btn">Cancel</a>
    </div>
</form>

<?php include_once '../../includes/footer.php'; ?>
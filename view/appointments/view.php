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

include_once '../../includes/header.php';
?>

<h2>Appointment Details</h2>

<div class="actions">
    <a href="list.php" class="btn"><i class="fas fa-arrow-left"></i> Back to List</a>
    <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
    <a href="delete.php?id=<?php echo $id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this appointment?');"><i class="fas fa-trash"></i> Delete</a>
</div>

<div class="detail-card">
    <h3>Appointment Information</h3>
    
    <div class="detail-row">
        <div class="detail-label">Patient:</div>
        <div class="detail-value">
            <a href="<?php echo baseUrl(); ?>/view/patients/view.php?id=<?php echo $appointmentData['patient_id']; ?>">
                <?php echo $appointmentData['patient_name']; ?>
            </a>
        </div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Doctor:</div>
        <div class="detail-value">
            <a href="<?php echo baseUrl(); ?>/view/doctors/view.php?id=<?php echo $appointmentData['doctor_id']; ?>">
                <?php echo $appointmentData['doctor_name']; ?>
            </a>
        </div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Date:</div>
        <div class="detail-value"><?php echo date('M d, Y', strtotime($appointmentData['appointment_date'])); ?></div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Time:</div>
        <div class="detail-value"><?php echo date('h:i A', strtotime($appointmentData['appointment_time'])); ?></div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Reason:</div>
        <div class="detail-value"><?php echo $appointmentData['reason']; ?></div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Status:</div>
        <div class="detail-value"><?php echo $appointmentData['status']; ?></div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Notes:</div>
        <div class="detail-value"><?php echo $appointmentData['notes'] ?: 'No notes'; ?></div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Created:</div>
        <div class="detail-value"><?php echo date('M d, Y h:i A', strtotime($appointmentData['created_at'])); ?></div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Last Updated:</div>
        <div class="detail-value"><?php echo date('M d, Y h:i A', strtotime($appointmentData['updated_at'])); ?></div>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
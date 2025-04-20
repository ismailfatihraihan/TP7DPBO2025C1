<?php
require_once '../../config/config.php';
require_once '../../class/Patient.php';
require_once '../../class/Appointment.php';

$patient = new Patient();
$appointment = new Appointment();

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    setFlashMessage('danger', 'Patient ID is required');
    redirect(baseUrl() . '/view/patients/list.php');
}

$id = (int)$_GET['id'];
$patientData = $patient->getById($id);

// Check if patient exists
if (!$patientData) {
    setFlashMessage('danger', 'Patient not found');
    redirect(baseUrl() . '/view/patients/list.php');
}

// Get patient's appointments
$patientAppointments = $appointment->getByPatientId($id);

include_once '../../includes/header.php';
?>

<h2>Patient Details</h2>

<div class="actions">
    <a href="list.php" class="btn"><i class="fas fa-arrow-left"></i> Back to List</a>
    <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
    <a href="delete.php?id=<?php echo $id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this patient?');"><i class="fas fa-trash"></i> Delete</a>
</div>

<div class="detail-card">
    <h3>Personal Information</h3>
    
    <div class="detail-row">
        <div class="detail-label">Full Name:</div>
        <div class="detail-value"><?php echo $patientData['first_name'] . ' ' . $patientData['last_name']; ?></div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Date of Birth:</div>
        <div class="detail-value"><?php echo date('M d, Y', strtotime($patientData['date_of_birth'])); ?></div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Gender:</div>
        <div class="detail-value"><?php echo $patientData['gender']; ?></div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Address:</div>
        <div class="detail-value"><?php echo $patientData['address'] ?: 'N/A'; ?></div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Phone:</div>
        <div class="detail-value"><?php echo $patientData['phone']; ?></div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Email:</div>
        <div class="detail-value"><?php echo $patientData['email'] ?: 'N/A'; ?></div>
    </div>
</div>

<div class="detail-card">
    <h3>Medical Information</h3>
    
    <div class="detail-row">
        <div class="detail-label">Medical History:</div>
        <div class="detail-value"><?php echo $patientData['medical_history'] ?: 'No medical history recorded'; ?></div>
    </div>
</div>

<div class="detail-card">
    <h3>Appointments</h3>
    
    <div class="actions">
        <a href="<?php echo baseUrl(); ?>/view/appointments/add.php?patient_id=<?php echo $id; ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Schedule New Appointment</a>
    </div>
    
    <?php if (count($patientAppointments) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Doctor</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($patientAppointments as $apt): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($apt['appointment_date'])); ?></td>
                        <td><?php echo date('h:i A', strtotime($apt['appointment_time'])); ?></td>
                        <td><?php echo $apt['doctor_name']; ?></td>
                        <td><?php echo $apt['reason']; ?></td>
                        <td><?php echo $apt['status']; ?></td>
                        <td>
                            <a href="<?php echo baseUrl(); ?>/view/appointments/view.php?id=<?php echo $apt['id']; ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                            <a href="<?php echo baseUrl(); ?>/view/appointments/edit.php?id=<?php echo $apt['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No appointments found for this patient.</p>
    <?php endif; ?>
</div>

<?php include_once '../../includes/footer.php'; ?>
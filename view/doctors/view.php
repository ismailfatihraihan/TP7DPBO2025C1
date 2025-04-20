<?php
require_once '../../config/config.php';
require_once '../../class/Doctor.php';
require_once '../../class/Appointment.php';

$doctor = new Doctor();
$appointment = new Appointment();

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

// Get doctor's appointments
$doctorAppointments = $appointment->getByDoctorId($id);

include_once '../../includes/header.php';
?>

<h2>Doctor Details</h2>

<div class="actions">
    <a href="list.php" class="btn"><i class="fas fa-arrow-left"></i> Back to List</a>
    <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
    <a href="delete.php?id=<?php echo $id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this doctor?');"><i class="fas fa-trash"></i> Delete</a>
</div>

<div class="detail-card">
    <h3>Professional Information</h3>
    
    <div class="detail-row">
        <div class="detail-label">Full Name:</div>
        <div class="detail-value">Dr. <?php echo $doctorData['first_name'] . ' ' . $doctorData['last_name']; ?></div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Specialization:</div>
        <div class="detail-value"><?php echo $doctorData['specialization']; ?></div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Qualification:</div>
        <div class="detail-value"><?php echo $doctorData['qualification']; ?></div>
    </div>
</div>

<div class="detail-card">
    <h3>Contact Information</h3>
    
    <div class="detail-row">
        <div class="detail-label">Phone:</div>
        <div class="detail-value"><?php echo $doctorData['phone']; ?></div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Email:</div>
        <div class="detail-value"><?php echo $doctorData['email']; ?></div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Address:</div>
        <div class="detail-value"><?php echo $doctorData['address'] ?: 'N/A'; ?></div>
    </div>
</div>

<div class="detail-card">
    <h3>Appointments</h3>
    
    <div class="actions">
        <a href="<?php echo baseUrl(); ?>/view/appointments/add.php?doctor_id=<?php echo $id; ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Schedule New Appointment</a>
    </div>
    
    <?php if (count($doctorAppointments) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($doctorAppointments as $apt): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($apt['appointment_date'])); ?></td>
                        <td><?php echo date('h:i A', strtotime($apt['appointment_time'])); ?></td>
                        <td><?php echo $apt['patient_name']; ?></td>
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
        <p>No appointments found for this doctor.</p>
    <?php endif; ?>
</div>

<?php include_once '../../includes/footer.php'; ?>
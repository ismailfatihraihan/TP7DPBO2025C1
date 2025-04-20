<?php
require_once 'config/config.php';
require_once 'class/Patient.php';
require_once 'class/Doctor.php';
require_once 'class/Appointment.php';

// Initialize classes
$patient = new Patient();
$doctor = new Doctor();
$appointment = new Appointment();

// Get counts
$patientCount = count($patient->getAll());
$doctorCount = count($doctor->getAll());
$appointmentCount = count($appointment->getAll());

// Get upcoming appointments (next 7 days)
$upcomingAppointments = $appointment->getAll();
$upcomingAppointments = array_filter($upcomingAppointments, function($apt) {
    $aptDate = strtotime($apt['appointment_date']);
    $today = strtotime(date('Y-m-d'));
    $oneWeekLater = strtotime('+7 days', $today);
    return $aptDate >= $today && $aptDate <= $oneWeekLater && $apt['status'] == 'Scheduled';
});

include_once 'includes/header.php';
?>

<div class="dashboard">
    <h2>Dashboard</h2>
    
    <div class="stats">
        <div class="stat-card">
            <i class="fas fa-user-injured"></i>
            <h3>Patients</h3>
            <p><?php echo $patientCount; ?></p>
            <a href="<?php echo baseUrl(); ?>/view/patients/list.php" class="btn btn-primary">View All</a>
        </div>
        
        <div class="stat-card">
            <i class="fas fa-user-md"></i>
            <h3>Doctors</h3>
            <p><?php echo $doctorCount; ?></p>
            <a href="<?php echo baseUrl(); ?>/view/doctors/list.php" class="btn btn-primary">View All</a>
        </div>
        
        <div class="stat-card">
            <i class="fas fa-calendar-check"></i>
            <h3>Appointments</h3>
            <p><?php echo $appointmentCount; ?></p>
            <a href="<?php echo baseUrl(); ?>/view/appointments/list.php" class="btn btn-primary">View All</a>
        </div>
    </div>
    
    <div class="upcoming-appointments">
        <h3>Upcoming Appointments (Next 7 Days)</h3>
        <?php if (count($upcomingAppointments) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Reason</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($upcomingAppointments as $apt): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($apt['appointment_date'])); ?></td>
                            <td><?php echo date('h:i A', strtotime($apt['appointment_time'])); ?></td>
                            <td><?php echo $apt['patient_name']; ?></td>
                            <td><?php echo $apt['doctor_name']; ?></td>
                            <td><?php echo $apt['reason']; ?></td>
                            <td>
                                <a href="<?php echo baseUrl(); ?>/view/appointments/view.php?id=<?php echo $apt['id']; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No upcoming appointments in the next 7 days.</p>
        <?php endif; ?>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
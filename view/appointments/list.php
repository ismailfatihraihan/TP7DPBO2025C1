<?php
require_once '../../config/config.php';
require_once '../../class/Appointment.php';

$appointment = new Appointment();

// Handle search
$searchTerm = '';
$appointments = [];

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = sanitize($_GET['search']);
    $appointments = $appointment->search($searchTerm);
} else {
    $appointments = $appointment->getAll();
}

include_once '../../includes/header.php';
?>

<h2>Appointments</h2>

<div class="actions">
    <a href="add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Appointment</a>
</div>

<form action="" method="GET" class="search-form">
    <input type="text" name="search" placeholder="Search by patient, doctor, or reason..." value="<?php echo $searchTerm; ?>">
    <button type="submit"><i class="fas fa-search"></i></button>
</form>

<?php if (count($appointments) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Time</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $apt): ?>
                <tr>
                    <td><?php echo $apt['id']; ?></td>
                    <td><?php echo date('M d, Y', strtotime($apt['appointment_date'])); ?></td>
                    <td><?php echo date('h:i A', strtotime($apt['appointment_time'])); ?></td>
                    <td><?php echo $apt['patient_name']; ?></td>
                    <td><?php echo $apt['doctor_name']; ?></td>
                    <td><?php echo $apt['reason']; ?></td>
                    <td><?php echo $apt['status']; ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $apt['id']; ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                        <a href="edit.php?id=<?php echo $apt['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="delete.php?id=<?php echo $apt['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this appointment?');"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No appointments found.</p>
<?php endif; ?>

<?php include_once '../../includes/footer.php'; ?>
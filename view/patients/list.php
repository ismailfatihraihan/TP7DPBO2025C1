<?php
require_once '../../config/config.php';
require_once '../../class/Patient.php';

$patient = new Patient();

// Handle search
$searchTerm = '';
$patients = [];

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = sanitize($_GET['search']);
    $patients = $patient->search($searchTerm);
} else {
    $patients = $patient->getAll();
}

include_once '../../includes/header.php';
?>

<h2>Patients</h2>

<div class="actions">
    <a href="add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Patient</a>
</div>

<form action="" method="GET" class="search-form">
    <input type="text" name="search" placeholder="Search by name..." value="<?php echo $searchTerm; ?>">
    <button type="submit"><i class="fas fa-search"></i></button>
</form>

<?php if (count($patients) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Date of Birth</th>
                <th>Gender</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($patients as $p): ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td><?php echo $p['first_name'] . ' ' . $p['last_name']; ?></td>
                    <td><?php echo date('M d, Y', strtotime($p['date_of_birth'])); ?></td>
                    <td><?php echo $p['gender']; ?></td>
                    <td><?php echo $p['phone']; ?></td>
                    <td><?php echo $p['email']; ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                        <a href="edit.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="delete.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this patient?');"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No patients found.</p>
<?php endif; ?>

<?php include_once '../../includes/footer.php'; ?>
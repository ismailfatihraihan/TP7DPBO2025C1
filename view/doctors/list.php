<?php
require_once '../../config/config.php';
require_once '../../class/Doctor.php';

$doctor = new Doctor();

// Handle search
$searchTerm = '';
$doctors = [];

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = sanitize($_GET['search']);
    $doctors = $doctor->search($searchTerm);
} else {
    $doctors = $doctor->getAll();
}

include_once '../../includes/header.php';
?>

<h2>Doctors</h2>

<div class="actions">
    <a href="add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Doctor</a>
</div>

<form action="" method="GET" class="search-form">
    <input type="text" name="search" placeholder="Search by name or specialization..." value="<?php echo $searchTerm; ?>">
    <button type="submit"><i class="fas fa-search"></i></button>
</form>

<?php if (count($doctors) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Specialization</th>
                <th>Qualification</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($doctors as $d): ?>
                <tr>
                    <td><?php echo $d['id']; ?></td>
                    <td>Dr. <?php echo $d['first_name'] . ' ' . $d['last_name']; ?></td>
                    <td><?php echo $d['specialization']; ?></td>
                    <td><?php echo $d['qualification']; ?></td>
                    <td><?php echo $d['phone']; ?></td>
                    <td><?php echo $d['email']; ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $d['id']; ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                        <a href="edit.php?id=<?php echo $d['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="delete.php?id=<?php echo $d['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this doctor?');"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No doctors found.</p>
<?php endif; ?>

<?php include_once '../../includes/footer.php'; ?>
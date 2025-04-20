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

// Delete doctor
$result = $doctor->delete($id);

if ($result) {
    setFlashMessage('success', 'Doctor deleted successfully');
} else {
    setFlashMessage('danger', 'Cannot delete doctor with existing appointments');
}

redirect(baseUrl() . '/view/doctors/list.php');
?>
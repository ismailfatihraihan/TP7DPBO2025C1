<?php
require_once '../../config/config.php';
require_once '../../class/Appointment.php';

$appointment = new Appointment();

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

// Delete appointment
$result = $appointment->delete($id);

if ($result) {
    setFlashMessage('success', 'Appointment deleted successfully');
} else {
    setFlashMessage('danger', 'Failed to delete appointment');
}

redirect(baseUrl() . '/view/appointments/list.php');
?>
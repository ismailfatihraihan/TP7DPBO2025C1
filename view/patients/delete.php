<?php
require_once '../../config/config.php';
require_once '../../class/Patient.php';

$patient = new Patient();

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

// Delete patient
$result = $patient->delete($id);

if ($result) {
    setFlashMessage('success', 'Patient deleted successfully');
} else {
    setFlashMessage('danger', 'Cannot delete patient with existing appointments');
}

redirect(baseUrl() . '/view/patients/list.php');
?>
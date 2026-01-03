<?php
require_once '../../models/Enrollment.php';
require_once '../../models/Member.php';
require_once '../../models/ClassModel.php';

// Enrollment is a junction table - redirect to view or delete instead of editing
header('Location: view.php');
exit();
?>
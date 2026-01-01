<?php
require_once 'models/Member.php';
require_once 'models/ClassModel.php';
require_once 'models/Trainer.php';
require_once 'models/Payment.php';
require_once 'models/Program.php';

$member = new Member();
$classModel = new ClassModel();
$trainer = new Trainer();
$payment = new Payment();
$program = new Program();

include 'views/header.php';
?>

// Member Stats
$content .= '<h2>Member Statistics</h2>';
$memberStats = $member->getMemberStats();
$content .= '<table class="table table-striped">
    <thead>
        <tr>
            <th>Member</th>
            <th>Programs Enrolled</th>
            <th>Classes Attended</th>
            <th>Total Payments</th>
            <th>Membership Status</th>
        </tr>
    </thead>
    <tbody>';
foreach ($memberStats as $stat) {
    $content .= '<tr>
        <td>' . $stat['first_name'] . ' ' . $stat['last_name'] . '</td>
        <td>' . $stat['programs_enrolled'] . '</td>
        <td>' . $stat['classes_attended'] . '</td>
        <td>$' . $stat['total_payments'] . '</td>
        <td>' . $stat['membership_status'] . '</td>
    </tr>';
}
$content .= '</tbody></table>';

// Scheduled Classes
$content .= '<h2>Scheduled Classes</h2>';
$classes = $classModel->getAll();
$content .= '<table class="table table-striped">
    <thead>
        <tr>
            <th>Program</th>
            <th>Trainer</th>
            <th>Category</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Capacity</th>
        </tr>
    </thead>
    <tbody>';
foreach ($classes as $c) {
    $content .= '<tr>
        <td>' . $c['program_name'] . '</td>
        <td>' . $c['first_name'] . ' ' . $c['last_name'] . '</td>
        <td>' . $c['category_name'] . '</td>
        <td>' . $c['scheduled_date'] . '</td>
        <td>' . $c['scheduled_time'] . '</td>
        <td>' . $c['status'] . '</td>
        <td>' . $c['capacity'] . '</td>
    </tr>';
}
$content .= '</tbody></table>';

// Trainer Performance
$content .= '<h2>Trainer Performance</h2>';
$performances = $trainer->getPerformanceReport();
$content .= '<table class="table table-striped">
    <thead>
        <tr>
            <th>Trainer</th>
            <th>Total Classes Taught</th>
            <th>Cancelled Classes</th>
        </tr>
    </thead>
    <tbody>';
foreach ($performances as $p) {
    $content .= '<tr>
        <td>' . $p['first_name'] . ' ' . $p['last_name'] . '</td>
        <td>' . $p['total_classes'] . '</td>
        <td>' . $p['cancelled_classes'] . '</td>
    </tr>';
}
$content .= '</tbody></table>';

// Membership Fees
$content .= '<h2>Membership Fees</h2>';
$content .= '<h3>Annual</h3>';
$annualFees = $payment->getMembershipFees('annual');
$content .= '<table class="table table-striped">
    <thead>
        <tr>
            <th>Year</th>
            <th>Total Fees</th>
        </tr>
    </thead>
    <tbody>';
foreach ($annualFees as $f) {
    $content .= '<tr>
        <td>' . $f['YEAR(payment_date)'] . '</td>
        <td>$' . $f['total_fees'] . '</td>
    </tr>';
}
$content .= '</tbody></table>';

$content .= '<h3>Quarterly</h3>';
$quarterlyFees = $payment->getMembershipFees('quarterly');
$content .= '<table class="table table-striped">
    <thead>
        <tr>
            <th>Year</th>
            <th>Quarter</th>
            <th>Total Fees</th>
        </tr>
    </thead>
    <tbody>';
foreach ($quarterlyFees as $f) {
    $content .= '<tr>
        <td>' . $f['YEAR(payment_date)'] . '</td>
        <td>' . $f['QUARTER(payment_date)'] . '</td>
        <td>$' . $f['total_fees'] . '</td>
    </tr>';
}
$content .= '</tbody></table>';

// Top 5 Programs
$content .= '<h2>Top 5 Popular Programs</h2>';
$topPrograms = $program->getTopPrograms();
$content .= '<table class="table table-striped">
    <thead>
        <tr>
            <th>Program</th>
            <th>Category</th>
            <th>Trainer</th>
            <th>Enrolled Members</th>
        </tr>
    </thead>
    <tbody>';
foreach ($topPrograms as $tp) {
    $content .= '<tr>
        <td>' . $tp['name'] . '</td>
        <td>' . $tp['category_name'] . '</td>
        <td>' . $tp['first_name'] . ' ' . $tp['last_name'] . '</td>
        <td>' . $tp['enrolled_members'] . '</td>
    </tr>';
}
$content .= '</tbody></table>';

<?php include 'views/footer.php'; ?>
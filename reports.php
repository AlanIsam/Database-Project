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
<h1>Reports</h1>

<!-- Member Statistics -->
<h2>Member Statistics</h2>
<?php $memberStats = $member->getMemberStats(); ?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Member</th>
            <th>Programs Enrolled</th>
            <th>Classes Enrolled</th>
            <th>Total Payments</th>
            <th>Membership Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($memberStats as $stat): ?>
        <tr>
            <td><?php echo $stat['member_name']; ?></td>
            <td><?php echo $stat['programs_enrolled']; ?></td>
            <td><?php echo $stat['classes_enrolled']; ?></td>
            <td>$<?php echo $stat['total_payments']; ?></td>
            <td><?php echo $stat['membership_status']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Scheduled Classes -->
<h2>Scheduled Classes</h2>
<?php $classes = $classModel->getAll(); ?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Program</th>
            <th>Trainer</th>
            <th>Category</th>
            <th>Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Room</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($classes as $c): ?>
        <tr>
            <td><?php echo $c['program_name']; ?></td>
            <td><?php echo $c['trainer_name']; ?></td>
            <td><?php echo $c['category_name']; ?></td>
            <td><?php echo $c['class_date']; ?></td>
            <td><?php echo $c['start_time']; ?></td>
            <td><?php echo $c['end_time']; ?></td>
            <td><?php echo $c['room_number']; ?></td>
            <td>
                <?php 
                $status = $c['dynamic_status'];
                $badgeClass = 'bg-secondary'; // Default gray

                if ($status == 'Active') {
                    $badgeClass = 'bg-success'; // Green
                } elseif ($status == 'Cancelled') {
                    $badgeClass = 'bg-danger'; // Red
                } elseif ($status == 'Completed') {
                    $badgeClass = 'bg-primary'; // Blue
                }
                ?>
                <span class="badge <?php echo $badgeClass; ?>">
                    <?php echo $status; ?>
                </span>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Trainer Performance -->
<h2>Trainer Performance</h2>
<?php $performances = $trainer->getPerformanceReport(); ?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Trainer</th>
            <th>Total Classes Taught</th>
            <th>Total Cancelled</th> </tr>
    </thead>
    <tbody>
        <?php foreach ($performances as $p): ?>
        <tr>
            <td><?php echo $p['employee_name']; ?></td>
            
            <td><?php echo $p['total_taught']; ?></td>
            
            <td>
                <?php 
                $cancelled = $p['total_cancelled'];
                if($cancelled > 0) {
                    echo '<span class="text-danger font-weight-bold">' . $cancelled . '</span>';
                } else {
                    echo $cancelled;
                }
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Membership Fees -->
<h2>Membership Fees</h2>
<h3>Annual</h3>
<?php $annualFees = $payment->getMembershipFees('annual'); ?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Year</th>
            <th>Total Fees</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($annualFees as $f): ?>
        <tr>
            <td><?php echo $f['period']; ?></td>
            <td>$<?php echo number_format($f['total_fees'], 2); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Quarterly</h3>
<?php $quarterlyFees = $payment->getMembershipFees('quarterly'); ?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Period</th>
            <th>Total Fees</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($quarterlyFees as $f): ?>
        <tr>
            <td><?php echo $f['period']; ?></td>
            <td>$<?php echo number_format($f['total_fees'], 2); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Top 5 Programs -->
<h2>Top 5 Popular Programs</h2>
<?php $topPrograms = $program->getTopPrograms(); ?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Program</th>
            <th>Category</th>
            <th>Trainer</th>
            <th>Enrolled Members</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($topPrograms as $tp): ?>
        <tr>
            <td><?php echo $tp['program_name']; ?></td>
            <td><?php echo $tp['category_name']; ?></td>
            <td><?php echo $tp['trainer_name']; ?></td>
            <td><?php echo $tp['enrolled_members']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'views/footer.php'; ?>

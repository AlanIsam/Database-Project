<?php include 'views/header.php'; ?>
        <h1>Welcome to Wellness Center Management System</h1>
        <p>Use the navigation above to manage the database.</p>
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Members</h5>
                        <p class="card-text">Manage member information.</p>
                        <a href="routes/members/view.php" class="btn btn-primary">View Members</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Programs</h5>
                        <p class="card-text">Manage wellness programs.</p>
                        <a href="routes/programs/view.php" class="btn btn-primary">View Programs</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Employees</h5>
                        <p class="card-text">Manage employee information.</p>
                        <a href="routes/employees/view.php" class="btn btn-primary">View Employees</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Reports</h5>
                        <p class="card-text">View various reports.</p>
                        <a href="/Database-Project/reports.php" class="btn btn-primary">View Reports</a>
                    </div>
                </div>
            </div>
        </div>
<?php include 'views/footer.php'; ?>
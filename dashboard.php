<?php session_start(); include('sidebar.php'); ?>
<div class="main-content">
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar with options -->
        
            </div>
        </div>

        <!-- Main content area -->
        <div class="col-md-9 main-content">
            <div class="header">
                <h2>Welcome to the Admin Dashboard</h2>
            </div>

            <div class="row">
                <!-- Welcome Message Card -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Welcome Back, Admin!</h5>
                        </div>
                        <div class="card-body">
                            <p>Here you can manage the system, generate invoices, and access the admin panel.</p>
                            <p>Click on the options in the sidebar to get started.</p>
                        </div>
                    </div>
                </div>

                <!-- Options Cards -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Choose an Option</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Generate Invoice:</strong> Create invoices for your clients with ease.</p>
                            <p><strong>Admin Panel:</strong> Access reports, user management, and more.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Action Buttons (Optional) -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Admin Actions</h5>
                        </div>
                        <div class="card-body">
                            <a href="download_report.php" class="btn btn-info">Download Reports</a>
                            <!-- Add more admin functionality buttons as required -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  </div> <!-- end main-content -->
</div> <!-- end layout-wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
include('sidebar.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

include 'db_connect.php';

// Handle delete invoice with remarks
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_invoice'])) {
    $invoice_id = intval($_POST['invoice_id']);
    $remarks = $_POST['remarks'];

    try {
        // Fetch invoice details before deleting
        $fetch_stmt = $pdo->prepare("SELECT * FROM invoices WHERE id = ?");
        $fetch_stmt->execute([$invoice_id]);
        $invoice = $fetch_stmt->fetch(PDO::FETCH_ASSOC);

        if ($invoice) {
            // Log full details into invoice_deletion_log
            $log_stmt = $pdo->prepare("INSERT INTO invoice_deletion_log (
                invoice_id, invoice_number, reference_no, client_name, issued_date,
                total_amount, discount, net_amount, remarks, deleted_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

            $log_stmt->execute([
                $invoice['id'],
                $invoice['invoice_number'],
                $invoice['reference_no'],
                $invoice['client_name'],
                $invoice['issued_date'],
                $invoice['total_amount'],
                $invoice['discount'],
                $invoice['net_amount'],
                $remarks
            ]);

            // Then delete the invoice
            $delete_stmt = $pdo->prepare("DELETE FROM invoices WHERE id = ?");
            $delete_stmt->execute([$invoice_id]);
        }

        header("Location: admin_panel.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Search logic
$search = isset($_GET['search']) ? $_GET['search'] : '';
if (!empty($search)) {
    $sql = "SELECT * FROM invoices WHERE client_name LIKE :search OR invoice_number LIKE :search";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
} else {
    $sql = "SELECT * FROM invoices";
    $stmt = $pdo->prepare($sql);
}
$stmt->execute();
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2>Invoice Summary</h2>

    <!-- Search Form -->
    <form action="admin_panel.php" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Client Name or Invoice Number" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Invoices Table -->
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Invoice Number</th>
            <th>Reference No</th>
            <th>Client Name</th>
            <th>Issued Date</th>
            <th>Total Amount</th>
            <th>Discount</th>
            <th>Net Amount</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($invoices)): ?>
            <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td><?php echo htmlspecialchars($invoice['invoice_number']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['reference_no']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['client_name']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['issued_date']); ?></td>
                    <td><?php echo number_format($invoice['total_amount'], 2); ?></td>
                    <td><?php echo number_format($invoice['discount'], 2); ?></td>
                    <td><?php echo number_format($invoice['net_amount'], 2); ?></td>
                    <td>
                        <a href="generate_invoice.php?id=<?php echo $invoice['id']; ?>" class="btn btn-info btn-sm" target="_blank">View</a>
                        <button class="btn btn-danger btn-sm" onclick="openDeleteModal(<?php echo $invoice['id']; ?>)">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No records found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="download_report.php?search=<?php echo urlencode($search); ?>" class="btn btn-success">Download Summary Report</a>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Delete Invoice</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="invoice_id" id="deleteInvoiceId">
        <div class="mb-3">
          <label for="remarks" class="form-label">Reason for deletion:</label>
          <textarea name="remarks" class="form-control" id="remarks" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="delete_invoice" class="btn btn-danger">Delete</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function openDeleteModal(invoiceId) {
    document.getElementById('deleteInvoiceId').value = invoiceId;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>

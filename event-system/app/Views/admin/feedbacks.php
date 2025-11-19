<?php 
$current_page = isset($current_page) ? $current_page : 'feedback';
?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Feedback / Testimonials</h1>
    </div>

    <!-- SUCCESS / ERROR -->
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success"><?= session('success') ?></div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger"><?= session('error') ?></div>
    <?php endif; ?>


    <!-- ========== PENDING FEEDBACK ========== -->
    <h4 class="mt-4">
        Pending Feedback 
        <span class="badge bg-warning text-dark"><?= count($pending_feedback) ?> Pending</span>
    </h4>

    <div class="table-responsive mt-3">
        <table id="pendingTable" class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Client</th>
                    <th>Rating</th>
                    <th>Comments</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th width="140">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pending_feedback as $fb): ?>
                <tr>
                    <td><?= esc($fb['client_name'] ?? 'Unknown') ?></td>
                    <td><?= $fb['rating'] ?> ★</td>
                    <td><?= esc(character_limiter($fb['comments'], 120)) ?></td>
                    <td><?= esc($fb['client_email']) ?></td>
                    <td><?= date('F j, Y', strtotime($fb['created_at'])) ?></td>
                    <td>
                        <button class="btn btn-success btn-sm" onclick="approveFeedback(<?= $fb['id'] ?>)">✔ Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="rejectFeedback(<?= $fb['id'] ?>)">✖ Reject</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>



    <!-- ========== APPROVED FEEDBACK ========== -->
    <h4 class="mt-5">
        Approved Feedback 
        <span class="badge bg-success"><?= count($approved_feedback) ?> Approved</span>
    </h4>

    <div class="table-responsive mt-3">
        <table id="approvedTable" class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Client</th>
                    <th>Rating</th>
                    <th>Comments</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th width="110">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($approved_feedback as $fb): ?>
                <tr>
                    <td><?= esc($fb['client_name']) ?></td>
                    <td><?= $fb['rating'] ?> ★</td>
                    <td><?= esc(character_limiter($fb['comments'], 120)) ?></td>
                    <td><?= esc($fb['client_email']) ?></td>
                    <td><?= date('F j, Y', strtotime($fb['created_at'])) ?></td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="rejectFeedback(<?= $fb['id'] ?>)">✖ Reject</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>



    <!-- ========== REJECTED FEEDBACK ========== -->
    <h4 class="mt-5">
        Rejected Feedback 
        <span class="badge bg-secondary"><?= count($rejected_feedback) ?> Rejected</span>
    </h4>

    <div class="table-responsive mt-3">
        <table id="rejectedTable" class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Client</th>
                    <th>Rating</th>
                    <th>Comments</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th width="140">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rejected_feedback as $fb): ?>
                <tr>
                    <td><?= esc($fb['client_name']) ?></td>
                    <td><?= $fb['rating'] ?> ★</td>
                    <td><?= esc(character_limiter($fb['comments'], 120)) ?></td>
                    <td><?= esc($fb['client_email']) ?></td>
                    <td><?= date('F j, Y', strtotime($fb['created_at'])) ?></td>
                    <td>
                        <button class="btn btn-success btn-sm" onclick="approveFeedback(<?= $fb['id'] ?>)">✔ Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteFeedback(<?= $fb['id'] ?>)">🗑 Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?= $this->endSection() ?>



<!-- ========================= -->
<!--  PAGE SCRIPTS SECTION     -->
<!-- ========================= -->
<?= $this->section('scripts') ?>

<script>
    // Initialize all DataTables
    $(document).ready(function() {
        $('#pendingTable').DataTable();
        $('#approvedTable').DataTable();
        $('#rejectedTable').DataTable();
    });

    // Approve
    function approveFeedback(id) {
        if (!confirm("Approve this feedback?")) return;
        submitForm("feedback/approve/" + id, { status: "approved" });
    }

    // Reject
    function rejectFeedback(id) {
        if (!confirm("Reject this feedback?")) return;
        submitForm("feedback/reject/" + id, { status: "rejected" });
    }

    // Delete
    function deleteFeedback(id) {
        if (!confirm("Delete this feedback permanently?")) return;
        submitForm("feedback/delete/" + id, { });
    }

    // Helper to create a hidden form
    function submitForm(url, fields) {
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "<?= site_url() ?>/" + url;

        // CSRF
        let csrf = document.createElement("input");
        csrf.type = "hidden";
        csrf.name = "<?= csrf_token() ?>";
        csrf.value = "<?= csrf_hash() ?>";
        form.appendChild(csrf);

        // Extra fields
        for (let k in fields) {
            let input = document.createElement("input");
            input.type = "hidden";
            input.name = k;
            input.value = fields[k];
            form.appendChild(input);
        }

        document.body.appendChild(form);
        form.submit();
    }
</script>

<?= $this->endSection() ?>

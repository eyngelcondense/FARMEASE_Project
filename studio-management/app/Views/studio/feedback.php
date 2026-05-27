<?php
$pager = null;
$current_page = 'feedback';

$feedbackCount = ! empty($feedbacks) && is_array($feedbacks) ? count($feedbacks) : 0;
$ratingTotal = 0;
$ratedCount = 0;
$pendingCount = 0;

if (! empty($feedbacks) && is_array($feedbacks)) {
    foreach ($feedbacks as $fb) {
        if (! empty($fb['rating'])) {
            $ratingTotal += (float) $fb['rating'];
            $ratedCount++;
        }

        if (strtolower((string) ($fb['status'] ?? '')) === 'pending') {
            $pendingCount++;
        }
    }
}

$averageRating = $ratedCount > 0 ? number_format($ratingTotal / $ratedCount, 1) : '0.0';
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="p-4 p-lg-5 rounded-4 mb-4 text-white" style="background: linear-gradient(135deg, #6f5239 0%, #b38a63 100%); box-shadow: 0 20px 40px rgba(111, 82, 57, 0.16);">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-white bg-opacity-10 mb-3">
                    <i class="fas fa-comments"></i>
                    <span class="small fw-semibold">Guest Feedback</span>
                </div>
                <h1 class="display-6 fw-bold mb-2">Reviews & Feedback</h1>
                <p class="mb-0 text-white-75">See what guests are saying and keep an eye on ratings, comments, and follow-ups.</p>
            </div>
            <div class="col-lg-4">
                <div class="row g-3">
                    <div class="col-4">
                        <div class="bg-white bg-opacity-10 rounded-4 p-3 text-center h-100">
                            <div class="small text-white-75">Entries</div>
                            <div class="fs-4 fw-bold"><?= $feedbackCount ?></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-white bg-opacity-10 rounded-4 p-3 text-center h-100">
                            <div class="small text-white-75">Avg Rating</div>
                            <div class="fs-4 fw-bold"><?= $averageRating ?></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-white bg-opacity-10 rounded-4 p-3 text-center h-100">
                            <div class="small text-white-75">Pending</div>
                            <div class="fs-4 fw-bold"><?= $pendingCount ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0">Latest feedback</h5>
                    <small class="text-muted">Sorted by the system as they come in</small>
                </div>
                <span class="badge bg-light text-dark rounded-pill px-3 py-2"><?= $feedbackCount ?> total</span>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if (! empty($feedbacks) && is_array($feedbacks)): ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($feedbacks as $fb): ?>
                        <div class="list-group-item p-4 border-0 border-bottom">
                            <div class="d-flex justify-content-between gap-3">
                                <div class="d-flex gap-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; flex: 0 0 52px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Feedback #<?= esc($fb['id'] ?? '') ?></h5>
                                        <div class="text-muted small mb-2">From <?= esc($fb['client_name'] ?? 'Guest') ?></div>
                                        <p class="mb-0 text-body"><?= esc($fb['message'] ?? $fb['comments'] ?? '') ?></p>
                                    </div>
                                </div>
                                <div class="text-end ms-auto">
                                    <?php if (! empty($fb['rating'])): ?>
                                        <div class="badge bg-primary rounded-pill px-3 py-2 mb-2">Rating: <?= esc($fb['rating']) ?>/5</div><br>
                                    <?php endif; ?>
                                    <?php if (! empty($fb['status'])): ?>
                                        <div class="badge bg-secondary text-capitalize rounded-pill px-3 py-2 mb-2"><?= esc($fb['status']) ?></div><br>
                                    <?php endif; ?>
                                    <small class="text-muted"><?= esc($fb['created_at'] ?? '') ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="mx-auto mb-3 rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 88px; height: 88px;">
                        <i class="fas fa-comment-dots fa-2x text-muted"></i>
                    </div>
                    <h5 class="fw-bold mb-2">No feedback available yet</h5>
                    <p class="text-muted mb-0">Guest reviews will appear here once bookings start receiving feedback.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

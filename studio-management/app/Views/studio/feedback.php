<?php
$pager = null;
?>
<?= $this->extend('studio/layout_sidebar') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Feedback</h4>
            <small class="text-muted">Guest feedback and reviews</small>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (! empty($feedbacks) && is_array($feedbacks)): ?>
                <div class="list-group">
                    <?php foreach ($feedbacks as $fb): ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?= esc($fb['subject'] ?? ($fb['title'] ?? 'Feedback')) ?></h5>
                                <small class="text-muted"><?= esc($fb['created_at'] ?? '') ?></small>
                            </div>
                            <p class="mb-1"><?= esc($fb['message'] ?? $fb['content'] ?? '') ?></p>
                            <small class="text-muted">From: <?= esc($fb['client_name'] ?? $fb['name'] ?? 'Guest') ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-4 text-muted">No feedback available yet.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

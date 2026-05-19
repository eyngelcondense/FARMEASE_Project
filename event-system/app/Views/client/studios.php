<?php
$title = 'Studio Gallery | San Isidro Labrador Resort and Leisure Farm';
include('header.php');
?>

<style>
  body {
    font-family: 'Poppins', sans-serif;
    background: #f8f6f3;
    color: #3b2a18;
  }

  .studios-shell {
    max-width: 1200px;
    margin: 40px auto 70px;
    padding: 0 16px;
  }

  .studios-hero {
    background: linear-gradient(135deg, #7c6a43 0%, #5a4a33 100%);
    color: #fff;
    border-radius: 20px;
    padding: 36px 24px;
    margin-bottom: 24px;
  }

  .studios-hero h1 {
    margin: 0;
    font-family: 'Times New Roman', Times, serif;
    font-size: 2.5rem;
  }

  .studios-hero p {
    margin: 10px 0 0;
    opacity: 0.95;
  }

  .quick-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 16px;
  }

  .quick-actions .btn {
    border-radius: 999px;
    font-weight: 600;
  }

  .studio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
    gap: 18px;
  }

  .studio-card {
    background: #fff;
    border: 1px solid #eadfce;
    border-radius: 14px;
    box-shadow: 0 6px 18px rgba(60, 42, 24, 0.1);
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .studio-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 26px rgba(60, 42, 24, 0.18);
  }

  .studio-image {
    height: 190px;
    width: 100%;
    object-fit: cover;
    display: block;
    background: #efe8de;
  }

  .studio-body {
    padding: 14px;
  }

  .studio-name {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 8px;
  }

  .meta {
    font-size: 0.93rem;
    color: #59442c;
    margin-bottom: 5px;
  }

  .empty-state {
    background: #fff;
    border: 1px dashed #ccb892;
    border-radius: 14px;
    padding: 40px 16px;
    text-align: center;
  }

  .reviews-section {
    margin-top: 34px;
  }

  .reviews-title {
    font-family: 'Times New Roman', Times, serif;
    font-size: 2rem;
    margin-bottom: 12px;
    color: #3b2a18;
  }

  .reviews-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 16px;
  }

  .review-card {
    background: #fff;
    border: 1px solid #eadfce;
    border-radius: 14px;
    box-shadow: 0 6px 16px rgba(60, 42, 24, 0.08);
    padding: 14px;
  }

  .review-head {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
  }

  .review-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #d5c3a6;
  }

  .review-name {
    font-weight: 700;
    color: #3b2a18;
    margin: 0;
    font-size: 0.95rem;
  }

  .review-stars {
    color: #f2b01e;
    font-size: 0.95rem;
    letter-spacing: 1px;
  }

  .review-text {
    margin: 0;
    color: #59442c;
    font-size: 0.93rem;
    line-height: 1.6;
  }

  .review-date {
    display: block;
    margin-top: 10px;
    font-size: 0.8rem;
    color: #8d775d;
  }

  .studio-actions {
    margin-top: 12px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }
</style>

<div class="studios-shell">
  <section class="studios-hero">
    <h1>Studio Gallery</h1>
    <p>Explore available studios for shoots and events before booking.</p>
    <div class="quick-actions">
      <a href="<?= site_url('bookings') ?>" class="btn btn-light">Book Now</a>
      <a href="<?= site_url('gallery') ?>" class="btn btn-outline-light">Venue Gallery</a>
    </div>
  </section>

  <?php if (empty($studios)): ?>
    <div class="empty-state">
      <h4 class="mb-2">No studios available right now</h4>
      <p class="mb-0 text-muted">Please check back later.</p>
    </div>
  <?php else: ?>
    <section class="studio-grid">
      <?php foreach ($studios as $studio): ?>
        <article class="studio-card">
          <?php if (!empty($studio['cover_image'])): ?>
            <img
              src="<?= base_url($studio['cover_image']) ?>"
              alt="<?= esc($studio['name']) ?>"
              class="studio-image"
            >
          <?php else: ?>
            <img
              src="<?= base_url('images/no-image.png') ?>"
              alt="No image available"
              class="studio-image"
            >
          <?php endif; ?>

          <div class="studio-body">
            <div class="studio-name"><?= esc($studio['name']) ?></div>
            <div class="meta"><strong>Location:</strong> <?= esc($studio['location']) ?></div>
            <div class="meta"><strong>Capacity:</strong> <?= (int) $studio['capacity'] ?> guests</div>
            <div class="meta"><strong>Rate:</strong> PHP <?= number_format((float) $studio['cost'], 2) ?> / hour</div>
            <div class="studio-actions">
              <a href="<?= site_url('studio-gallery?studio_id=' . (int) $studio['id']) ?>#studio-reviews" class="btn btn-sm btn-outline-secondary">View Reviews</a>
              <a href="<?= site_url('testimonials') ?>" class="btn btn-sm btn-outline-primary">Leave Review</a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>

  <section class="reviews-section" id="studio-reviews">
    <h2 class="reviews-title">
      Studio Reviews
      <?php if (!empty($selectedStudio)): ?>
        <span style="font-size:1rem; font-family:'Poppins', sans-serif; color:#7a6549;">for <?= esc($selectedStudio['name']) ?></span>
      <?php endif; ?>
    </h2>

    <?php if (empty($hasStudioFeedback)): ?>
      <div class="empty-state">
        <h5 class="mb-2">Studio reviews are not enabled yet</h5>
        <p class="mb-0 text-muted">Please apply the database update for feedback.studio_id first.</p>
      </div>
    <?php elseif (empty($selectedStudio)): ?>
      <div class="empty-state">
        <h5 class="mb-2">No studio selected</h5>
        <p class="mb-0 text-muted">Choose a studio above to view its reviews.</p>
      </div>

    <?php elseif (empty($studioReviews)): ?>
      <div class="empty-state">
        <h5 class="mb-2">No reviews yet</h5>
        <p class="mb-0 text-muted">Be the first to share your experience for this studio.</p>
      </div>
    <?php else: ?>
      <div class="reviews-grid">
        <?php foreach ($studioReviews as $review): ?>
          <article class="review-card">
            <div class="review-head">
              <?php if (!empty($review['profile_pic'])): ?>
                <img src="/uploads/profile_pics/<?= esc($review['profile_pic']) ?>" alt="<?= esc($review['fullname']) ?>" class="review-avatar">
              <?php else: ?>
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($review['fullname'] ?? 'Client') ?>&background=7c6a43&color=fff&size=80" alt="<?= esc($review['fullname'] ?? 'Client') ?>" class="review-avatar">
              <?php endif; ?>
              <div>
                <p class="review-name"><?= esc($review['fullname'] ?? 'Anonymous Client') ?></p>
                <div class="review-stars">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?= $i <= (int) ($review['rating'] ?? 0) ? '★' : '☆' ?>
                  <?php endfor; ?>
                </div>
              </div>
            </div>
            <p class="review-text"><?= esc($review['comments'] ?? '') ?></p>
            <span class="review-date"><?= !empty($review['created_at']) ? date('M d, Y', strtotime($review['created_at'])) : '' ?></span>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</div>

<?php include('footer.php'); ?>

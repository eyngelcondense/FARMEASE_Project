<?php
$title = "Testimonials | San Isidro Labrador Resort and Leisure Farm";
?>
<?= view('client/header', ['title' => $title, 'user' => $user, 'client' => $client]) ?>

<style>
    /* Your existing CSS styles remain the same */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f6f3;
        color: #3b2a18;
        overflow-x: hidden;
    }

    .testimonials-header {
        background-color: #f8f6f3;
        padding: 60px 0 40px;
        text-align: center;
    }

    .testimonials-header h1 {
        font-family: 'Times New Roman', Times, serif;
        font-size: 3rem;
        font-weight: 700;
        color: #3b2a18;
        margin-bottom: 20px;
    }

    .testimonials-header .divider {
        height: 50px;
        margin: 0 auto 20px;
    }

    .testimonials-header p {
        font-size: 1.1rem;
        color: #5a4a3a;
        max-width: 700px;
        margin: 0 auto;
    }

    .testimonials-banner {
        background-color: #7a6a58;
        color: white;
        padding: 30px 0;
        text-align: center;
        margin-bottom: 60px;
    }

    .testimonials-banner h3 {
        font-family: 'Times New Roman', Times, serif;
        font-size: 1.8rem;
        font-weight: 600;
        margin: 0;
    }

    .testimonials-section {
        background-color: #f8f6f3;
        padding: 0 0 80px;
    }

    .testimonial-card {
        background-color: white;
        border: 2px solid #3b2a18;
        border-radius: 20px;
        padding: 40px 30px 30px;
        text-align: center;
        height: 100%;
        position: relative;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .testimonial-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .testimonial-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid #3b2a18;
        object-fit: cover;
        position: absolute;
        top: -50px;
        left: 50%;
        transform: translateX(-50%);
        background-color: white;
    }

    .testimonial-content {
        margin-top: 60px;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .testimonial-text {
        font-size: 0.95rem;
        line-height: 1.7;
        color: #3b2a18;
        font-style: italic;
        margin-bottom: 20px;
        text-align: left;
    }

    .testimonial-author {
        margin-top: auto;
        padding-top: 20px;
        border-top: 1px solid #e8e3db;
    }

    .testimonial-name {
        font-weight: 700;
        color: #3b2a18;
        font-size: 1rem;
        margin-bottom: 5px;
    }

    .testimonial-event {
        font-size: 0.85rem;
        color: #7a6a58;
        font-style: italic;
    }

    .book-section {
        background-color: #f8f6f3;
        padding: 60px 0 80px;
        text-align: center;
    }

    .btn-book {
        background-color: #3b2a18;
        color: white;
        border: none;
        padding: 15px 50px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .btn-book:hover {
        background-color: #2a1f12;
        color: white;
        transform: scale(1.05);
    }

    .feedback-section {
        background-color: #f8f6f3;
        padding: 40px 0 80px;
    }

    .feedback-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.8rem;
        font-weight: 600;
        color: #3b2a18;
        text-align: left;
        margin-bottom: 20px;
    }

    .feedback-form {
        max-width: 100%;
    }

    .feedback-textarea {
        background-color: #e8e3db;
        border: 1px solid #b2a187;
        border-radius: 5px;
        padding: 15px;
        font-size: 0.95rem;
        color: #3b2a18;
        resize: vertical;
        margin-bottom: 20px;
    }

    .feedback-textarea:focus {
        background-color: #e8e3db;
        border-color: #7a6a58;
        box-shadow: none;
        outline: none;
    }

    .feedback-textarea::placeholder {
        color: #8b7d6b;
    }

    .rating-stars {
        margin-bottom: 20px;
    }

    .star-rating {
        display: flex;
        gap: 5px;
        justify-content: center;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }

    .star-rating label:hover,
    .star-rating input:checked ~ label {
        color: #ffc107;
    }

    .btn-submit {
        background-color: #3b2a18;
        color: white;
        border: none;
        padding: 12px 40px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background-color: #2a1f12;
        color: white;
    }

    @media (max-width: 992px) {
        .testimonials-header h1 {
            font-size: 2.5rem;
        }

        .testimonial-card {
            margin-bottom: 60px;
        }
    }

    @media (max-width: 768px) {
        .testimonials-header h1 {
            font-size: 2rem;
        }

        .testimonials-banner h3 {
            font-size: 1.3rem;
            padding: 0 20px;
        }
    }
</style>

<!-- Testimonials Header -->
<section class="testimonials-header">
    <h1>Client Testimonials</h1>
    <img src="/images/divider.png" alt="Divider" class="divider">
    <p>San Isidro Labrador Resort and Leisure Farm is gearing up to be the premiere location for your once-in-a-lifetime event</p>
</section>

<!-- Banner Section -->
<section class="testimonials-banner">
    <div class="container">
        <h3>Hear what our clients have to say about our venue.</h3>
    </div>
</section>

<!-- Testimonials Cards -->
<section class="testimonials-section">
    <div class="container">
        <div class="row g-5">
            <?php if (!empty($recentTestimonials)): ?>
                <?php foreach ($recentTestimonials as $testimonial): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="testimonial-card">
                            <!-- FIX: Use $testimonial instead of $client -->
                            <?php if (!empty($testimonial['profile_pic'])): ?>
                                <img src="/uploads/profile_pics/<?= esc($testimonial['profile_pic']) ?>" 
                                    alt="<?= esc($testimonial['fullname']) ?>" class="testimonial-avatar">
                            <?php else: ?>
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($testimonial['fullname']) ?>&background=3b2a18&color=fff&size=100" 
                                    alt="<?= esc($testimonial['fullname']) ?>" class="testimonial-avatar">
                            <?php endif; ?>
                            <div class="testimonial-content">
                                <div class="testimonial-text">
                                    "<?= esc($testimonial['comments']) ?>"
                                </div>
                                <div class="testimonial-author">
                                    <!-- FIX: Use $testimonial instead of $client -->
                                    <div class="testimonial-name"><?= esc($testimonial['fullname']) ?></div>
                                    <div class="testimonial-event">
                                        Rating: 
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span style="color: <?= $i <= $testimonial['rating'] ? '#ffc107' : '#ddd' ?>;">★</span>
                                        <?php endfor; ?>
                                        (<?= $testimonial['rating'] ?>/5)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <img src="/images/angel.jpg" alt="Angel Cortino" class="testimonial-avatar">
                        <div class="testimonial-content">
                            <div class="testimonial-text">
                                "Superbb. Bongga apakaangas kinilig yung mga bisita sa place. Recommendable sha ya, see you next event yah."
                            </div>
                            <div class="testimonial-author">
                                <div class="testimonial-name">Angel Cortino</div>
                                <div class="testimonial-event">Wedding</div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>
</section>

<section class="book-section">
    <a href="<?= site_url('contact') ?>" class="btn btn-book">
        Book Your Event Today
    </a>
</section>

<!-- Feedback Section -->
<section class="feedback-section">
    <div class="container">
        <h2 class="feedback-title">Share Your Experience</h2>
        
        <?php if (session()->has('message')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill"></i>
                <?= session('message') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle-fill"></i>
                <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle-fill"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('feedback/submit') ?>" method="post" id="feedbackForm">
            <?= csrf_field() ?>
            
            <div class="feedback-form">
                <div class="rating-stars">
                    <label class="form-label">Your Rating</label>
                    <div class="star-rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" required>
                            <label for="star<?= $i ?>">★</label>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- Feedback Text -->
                <textarea 
                    class="form-control feedback-textarea" 
                    placeholder="Share your experience with us... What did you like about our resort? How was the service? Any suggestions for improvement?" 
                    rows="6"
                    name="feedback"
                    required
                ><?= old('feedback') ?></textarea>
                
                <button type="submit" class="btn btn-submit">Submit Feedback</button>
            </div>
        </form>
    </div>
</section>

<?php include ('footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Star rating interaction
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star-rating input');
        const labels = document.querySelectorAll('.star-rating label');
        
        stars.forEach((star, index) => {
            star.addEventListener('change', function() {
                labels.forEach((label, labelIndex) => {
                    if (labelIndex >= (5 - index)) {
                        label.style.color = '#ffc107';
                    } else {
                        label.style.color = '#ddd';
                    }
                });
            });
        });

        // Form submission
        document.getElementById('feedbackForm').addEventListener('submit', function(e) {
            const rating = document.querySelector('input[name="rating"]:checked');
            const feedback = document.querySelector('[name="feedback"]').value;
            
            if (!rating) {
                e.preventDefault();
                alert('Please select a rating before submitting.');
                return;
            }
            
            if (feedback.trim().length < 10) {
                e.preventDefault();
                alert('Please provide more detailed feedback (at least 10 characters).');
                return;
            }
        });
    });
</script>
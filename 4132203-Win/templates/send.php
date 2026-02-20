<?php

$formData = $formData ?? ['name' => '', 'email' => '', 'message' => ''];
?>
<section class="contact-shell">
    <div class="contact-aside">
        <div class="eyebrow">Contact</div>
        <h2 class="section-title">Get in touch</h2>
        <p class="muted">ส่งข้อความติดต่อระบบ</p>
        <ul class="contact-list">
            <li>Email: admin@yoursite.com</li>
            <li>Website: yoursite.com</li>
        </ul>
    </div>
    <div class="contact-main">
        <h2 class="section-title">Send Message</h2>
        <form method="post" action="/">
            <div class="form-grid">
                <div class="form-field">
                    <label for="name">Full Name</label>
                    <input id="name" name="name" type="text" required value="<?php echo e($formData['name']); ?>">
                </div>
                <div class="form-field">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" type="email" required value="<?php echo e($formData['email']); ?>">
                </div>
            </div>
            <div class="form-field" style="margin-top: 14px;">
                <label for="message">Message</label>
                <textarea id="message" name="message" required><?php echo e($formData['message']); ?></textarea>
            </div>
            <button type="submit" class="btn" style="margin-top: 16px;">
                Send Message
            </button>
        </form>
    </div>
</section>

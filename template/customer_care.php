<?php include("header.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer-Care</title>
    <link rel="stylesheet" href="../bootstrap/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../static/customer_care.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <section class="contact-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="contact-form">
                        <h2 class="contact-heading text-center">Liên hệ với chúng tôi</h2>
                        <p class="text-center text-muted mb-5">
                            Chúng tôi rất mong nhận được phản hồi từ bạn! Vui lòng điền vào mẫu dưới đây.
                        </p>

                        <div class="row">
                            <!-- Contact Information -------------------------------------------------->
                            <div class="col-md-5">
                                <div class="contact-info mb-4">
                                    <h5><i class="bi bi-geo-alt-fill"></i>Địa chỉ cửa hàng</h5>
                                    <a href="https://maps.app.goo.gl/v5BE3hTUXr68sydPA" target="_blank"
                                        class="text-decoration-none">
                                        <p class="text-muted">
                                            Tầng 3 Tòa nhà BMM, KM2, Đường Phùng Hưng, Phường Phúc La, Quận Hà Đông, TP
                                            Hà
                                            Nội
                                        </p>
                                    </a>
                                </div>
                                <div class="contact-info mb-4">
                                    <h5><i class="bi bi-telephone-fill"></i>Số điện thoại liên hệ </h5>

                                    <a href="tel:02877772737" class="text-decoration-none">
                                        <p class="text-muted">028.7777.2737</p>
                                    </a>
                                </div>
                                <div class="contact-info mb-4">
                                    <h5><i class="bi bi-envelope-fill"></i>Email</h5>
                                    <a href="mailto:Cool@coolmate.me" target="_blank" class="text-decoration-none">
                                        <p class="text-muted">
                                            Cool@coolmate.me
                                        </p>
                                    </a>

                                </div>
                                <div class="contact-info">
                                    <h5><i class="bi bi-clock-fill"></i>Giờ làm việc</h5>
                                    <p class="text-muted">
                                        Thứ Hai - Thứ sáu: 9am - 5pm<br />Cuối tuần: Đóng cửa
                                    </p>
                                </div>


                            </div>

                            <!-- Contact Form -->
                            <div class="col-md-7">
                                <form method="post" id="contactForm" action="../handlers/contact_form_handler.php">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txtName" class="form-label required-field">Tên đầy
                                                    đủ:</label>
                                                <input type="text" name="txtName" id="txtName" class="form-control"
                                                    placeholder="Enter your name" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txtEmail" class="form-label required-field">Địa chỉ
                                                    email:</label>
                                                <input type="email" name="txtEmail" id="txtEmail" class="form-control"
                                                    placeholder="Enter your email" required />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txtPhone" class="form-label required-field">Số điện
                                                    thoại</label>
                                                <input type="tel" name="txtPhone" id="txtPhone" class="form-control"
                                                    placeholder="Enter your phone number" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txtSubject" class="form-label">Chủ đề:</label>
                                                <input type="text" name="txtSubject" id="txtSubject"
                                                    class="form-control" placeholder="Enter subject" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtMsg" class="form-label required-field">Tin nhắn của bạn:</label>
                                        <textarea name="txtMsg" id="txtMsg" class="form-control"
                                            placeholder="Type your message here..." required></textarea>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="newsletter"
                                            name="newsletter" />
                                        <label class="form-check-label" for="newsletter">
                                            Đăng ký nhận bản tin của chúng tôi
                                        </label>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btnContact" data-bs-toggle="modal"
                                            data-bs-target="#confirmationModal">
                                            <i class="bi bi-send-fill me-2"></i>Gửi tin nhắn
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="text-center mt-3 mb-2">
                                <h3 class="contact-heading mb-4">Nền tảng khác</h3>
                                <button class="social-btn">
                                    <a href="https://www.facebook.com/coolmate.me" target="_blank"><img
                                            src="../img/main/favicon-fb.png" alt="Facebook" width="24" /></a>
                                </button>
                                <button class="social-btn">
                                    <a href="https://www.youtube.com/channel/UCWw8wLlodKBtEvVt1tTAsMA"
                                        target="_blank"><img src="../img/main/favicon-ytb.ico" alt="Youtube"
                                            width="24" /></a>
                                </button>
                                <button class="social-btn">
                                    <a href="https://www.instagram.com/coolmate.me/" target="_blank"><img
                                            src="../img/main/favicon-is.png" alt="IS" width="24" /></a>
                                </button>
                                <button class="social-btn">
                                    <a href="https://www.tiktok.com/@cool.coolmate" target="_blank"><img
                                            src="../img/main/favicon-tt.ico" alt="Tiktok" width="24" /></a>
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header bg-bg-secondary ">
                    <h5 class="modal-title" id="confirmationModalLabel">
                        Đã gửi tin nhắn!
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Cảm ơn bạn đã liên hệ với chúng tôi! Tin nhắn của bạn đã được gửi thành công
                    </p>
                    <p>Chúng tôi sẽ phản hồi sớm nhất có thể.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../bootstrap/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

</html>

<?php include("footer.html"); ?>
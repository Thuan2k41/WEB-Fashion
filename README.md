# Smart Fashion - Website Thương Mại Điện Tử Thời Trang

## 1. Giới thiệu dự án

Smart Fashion là một website thương mại điện tử chuyên về thời trang hiện đại, cung cấp các sản phẩm thời trang nam và nữ chất lượng cao. Website được xây dựng với giao diện thân thiện, dễ sử dụng và tích hợp đầy đủ các tính năng cần thiết cho một cửa hàng trực tuyến.

### Tính năng chính:

- **Giao diện người dùng**: Thiết kế responsive, hiện đại
- **Quản lý sản phẩm**: Hiển thị sản phẩm theo danh mục (Nam, Nữ)
- **Giỏ hàng**: Thêm, xóa, cập nhật sản phẩm
- **Đăng nhập/Đăng ký**: Hệ thống xác thực người dùng
- **Đăng nhập mạng xã hội**: Tích hợp Facebook và Google Login
- **Thanh toán**: Tích hợp VNPay
- **Quản trị**: Panel admin quản lý sản phẩm và khách hàng
- **Chat support**: Hệ thống chat real-time
- **Email**: Gửi email xác nhận và thông báo

## 2. Hướng dẫn cài đặt

### Yêu cầu hệ thống:

- PHP 7.4 hoặc cao hơn
- MySQL 5.7 hoặc cao hơn
- Apache/Nginx web server
- Composer (PHP package manager)
- XAMPP/WAMP/LAMP (cho môi trường phát triển)

### Các bước cài đặt:

1. **Clone dự án**:

   ```bash
   git clone <repository-url>
   cd smart-fashion
   ```

2. **Cài đặt dependencies**:

   ```bash
   composer install
   ```

3. **Cấu hình cơ sở dữ liệu**:

   - Tạo database mới trong MySQL
   - Import file `web-fashion.sql` vào database
   - Cập nhật thông tin kết nối database trong `handlers/dbcon.php`

4. **Cấu hình môi trường**:
   - Cập nhật thông tin SMTP trong PHPMailer
   - Cấu hình API keys cho Facebook và Google Login
   - Cấu hình VNPay merchant info

## 3. Hướng dẫn sử dụng

### Cho người dùng cuối:

1. **Truy cập website**: Mở trình duyệt và vào địa chỉ website
2. **Đăng ký/Đăng nhập**: Tạo tài khoản hoặc đăng nhập bằng email/Facebook/Google
3. **Duyệt sản phẩm**: Xem các sản phẩm theo danh mục Nam/Nữ
4. **Thêm vào giỏ hàng**: Chọn sản phẩm và thêm vào giỏ
5. **Thanh toán**: Thực hiện thanh toán qua VNPay
6. **Theo dõi đơn hàng**: Xem lịch sử và trạng thái đơn hàng

### Cho admin:

1. **Đăng nhập admin**: Sử dụng tài khoản admin
2. **Quản lý sản phẩm**: Thêm, sửa, xóa sản phẩm
3. **Quản lý khách hàng**: Xem danh sách khách hàng
4. **Báo cáo**: Xem thống kê bán hàng

## 4. Thông tin kỹ thuật

### Công nghệ sử dụng:

- **Backend**: PHP
- **Frontend**: HTML, CSS, JavaScript
- **Database**: MySQL
- **CSS Framework**: Bootstrap 5.3.3
- **Package Manager**: Composer

### Cấu trúc thư mục:

```
smart-fashion/
├── bootstrap/          # Bootstrap CSS/JS framework
├── handlers/           # PHP handlers cho backend logic
├── img/               # Hình ảnh sản phẩm và assets
├── js/                # JavaScript files
├── libs/              # Thư viện PHP (PHPMailer)
├── static/            # CSS files
├── template/          # PHP template files
├── vendor/            # Composer dependencies
├── composer.json      # Composer configuration
└── web-fashion.sql    # Database schema
```

### Dependencies chính:

- **PHPMailer**: Gửi email
- **Facebook SDK**: Đăng nhập Facebook
- **Google API Client**: Đăng nhập Google
- **Ratchet**: WebSocket cho chat real-time

## 5. Hướng dẫn Git/GitHub

### Cài đặt Git:

1. Tải Git từ: https://git-scm.com/
2. Cài đặt và cấu hình thông tin cá nhân:

```bash
git config --global user.name "Tên của bạn"
git config --global user.email "email@example.com"
```

### Các lệnh Git cơ bản:

#### 1. Khởi tạo repository:

```bash
# Khởi tạo Git trong thư mục hiện tại
git init

# Clone một repository từ GitHub
git clone https://github.com/username/repository-name.git
```

#### 2. Quản lý files:

```bash
# Xem trạng thái các file
git status

# Thêm file vào staging area
git add filename.txt          # Thêm một file cụ thể
git add .                     # Thêm tất cả files
git add *.php                 # Thêm tất cả file PHP

# Xóa file khỏi staging area
git reset filename.txt        # Xóa file khỏi staging
git reset --hard             # Reset về commit cuối cùng (mất hết thay đổi)
```

#### 3. Commit (Lưu thay đổi):

```bash
# Commit với message
git commit -m "Mô tả thay đổi"

# Commit và thêm tất cả file đã modified
git commit -am "Mô tả thay đổi"

# Sửa commit cuối cùng
git commit --amend -m "Message mới"
```

#### 4. Xem lịch sử:

```bash
# Xem lịch sử commit
git log
git log --oneline            # Hiển thị ngắn gọn
git log --graph              # Hiển thị dạng đồ thị

# Xem thay đổi
git diff                     # So sánh working directory với staging
git diff --staged            # So sánh staging với commit cuối
git diff HEAD~1              # So sánh với commit trước đó
```

#### 5. Branch (Nhánh):

```bash
# Xem danh sách branch
git branch                   # Branch local
git branch -r                # Branch remote
git branch -a                # Tất cả branch

# Tạo branch mới
git branch feature-login     # Tạo branch mới
git checkout -b feature-login # Tạo và chuyển sang branch mới

# Chuyển branch
git checkout main            # Chuyển sang branch main
git checkout feature-login   # Chuyển sang branch feature-login

# Xóa branch
git branch -d feature-login  # Xóa branch đã merge
git branch -D feature-login  # Xóa branch chưa merge (force)
```

#### 6. Merge (Gộp nhánh):

```bash
# Merge branch vào branch hiện tại
git checkout main            # Chuyển sang branch main
git merge feature-login      # Merge feature-login vào main

# Merge với message
git merge feature-login -m "Merge feature login"
```

#### 7. Remote (Làm việc với GitHub):

```bash
# Thêm remote repository
git remote add origin https://github.com/username/smart-fashion.git

# Xem danh sách remote
git remote -v

# Push code lên GitHub
git push origin main         # Push branch main
git push origin feature-login # Push branch feature-login
git push -u origin main      # Push và set upstream

# Pull code từ GitHub
git pull origin main         # Pull từ branch main
git pull                     # Pull từ upstream branch

# Fetch (chỉ tải về, không merge)
git fetch origin
```

#### 8. Stash (Lưu tạm thời):

```bash
# Lưu thay đổi hiện tại
git stash
git stash save "Mô tả"

# Xem danh sách stash
git stash list

# Áp dụng stash
git stash apply              # Áp dụng stash cuối
git stash apply stash@{0}    # Áp dụng stash cụ thể

# Xóa stash
git stash drop               # Xóa stash cuối
git stash clear              # Xóa tất cả stash
```

### Quy trình làm việc với GitHub:

#### 1. Khởi tạo project mới:

```bash
# Bước 1: Tạo repository trên GitHub
# Bước 2: Clone về máy
git clone https://github.com/username/smart-fashion.git
cd smart-fashion

# Bước 3: Tạo file và commit
git add .
git commit -m "Initial commit"
git push origin main
```

#### 2. Làm việc với feature mới:

```bash
# Bước 1: Tạo branch mới
git checkout -b feature-payment

# Bước 2: Code và commit
git add .
git commit -m "Add payment feature"

# Bước 3: Push branch lên GitHub
git push origin feature-payment

# Bước 4: Tạo Pull Request trên GitHub
# Bước 5: Merge vào main
git checkout main
git pull origin main
git merge feature-payment
git push origin main

# Bước 6: Xóa branch không cần thiết
git branch -d feature-payment
git push origin --delete feature-payment
```

#### 3. Collaboration (Làm việc nhóm):

```bash
# Luôn pull code mới nhất trước khi làm việc
git pull origin main

# Tạo branch từ main mới nhất
git checkout -b your-feature

# Push branch để chia sẻ
git push origin your-feature

# Rebase để có lịch sử sạch
git rebase main
```

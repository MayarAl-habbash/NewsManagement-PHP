<?php
require_once 'config/database.php';
require_once 'config/session.php';
requireLogin();

$news_id = $_GET['id'] ?? null;
$error = '';
$success = '';

if (!$news_id || !is_numeric($news_id)) {
    header('Location: /view_all_news.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ? AND status = 'active'");
    $stmt->execute([$news_id]);
    $news_item = $stmt->fetch();
    
    if (!$news_item) {
        header('Location: /view_all_news.php');
        exit;
    }
    
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
    $categories = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category_id = $_POST['category_id'] ?? '';
    $details = trim($_POST['details'] ?? '');

    if (empty($title) || empty($category_id) || empty($details)) {
        $error = 'جميع الحقول مطلوبة';
    } else {
        $image_path = $news_item['image'];
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            $allowed_mime_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            
            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $finfo->file($_FILES['image']['tmp_name']);
            
            if (!in_array($file_extension, $allowed_extensions)) {
                $error = 'امتداد الملف غير مسموح. الامتدادات المسموحة: jpg, jpeg, png, gif';
            } elseif (!in_array($mime_type, $allowed_mime_types)) {
                $error = 'نوع الملف غير صحيح. يرجى رفع صورة حقيقية';
            } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                $error = 'حجم الملف كبير جداً. الحد الأقصى 5 ميجابايت';
            } else {
                $upload_dir = 'uploads/';
            
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
            
                $filename = uniqid() . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $filename;
            
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            
                    if (!empty($news_item['image']) && file_exists($news_item['image'])) {
                        unlink($news_item['image']);
                    }
            
                    $image_path = $upload_path;
                } else {
                    $error = 'فشل رفع الصورة';
                }
            }
            
        }
        
        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("UPDATE news SET title = ?, category_id = ?, details = ?, image = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->execute([$title, $category_id, $details, $image_path, $news_id]);
                $success = 'تم تعديل الخبر بنجاح!';
                
                $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
                $stmt->execute([$news_id]);
                $news_item = $stmt->fetch();
            } catch (PDOException $e) {
                $error = 'حدث خطأ: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل خبر - نظام إدارة الأخبار</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        .header {
            background: #667eea;
            color: white;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            font-size: 24px;
        }
        .back-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .back-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }
        .form-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 30px;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
        }
        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            font-family: inherit;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        textarea {
            min-height: 150px;
            resize: vertical;
        }
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #5568d3;
        }
        .error {
            background: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .success {
            background: #efe;
            color: #3c3;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .current-image {
            margin-top: 10px;
        }
        .current-image img {
            max-width: 200px;
            border-radius: 8px;
            border: 2px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>تعديل خبر</h1>
            <a href="/view_all_news.php" class="back-btn">العودة لقائمة الأخبار</a>
        </div>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>تعديل بيانات الخبر</h2>
            
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">عنوان الخبر</label>
                    <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($news_item['title']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="category_id">الفئة</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">اختر الفئة</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $news_item['category_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="details">تفاصيل الخبر</label>
                    <textarea id="details" name="details" required><?php echo htmlspecialchars($news_item['details']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="image">صورة الخبر (اختياري - اتركها فارغة للإبقاء على الصورة الحالية)</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    
                    <?php if ($news_item['image']): ?>
                        <div class="current-image">
                            <p>الصورة الحالية:</p>
                            <img src="/<?php echo htmlspecialchars($news_item['image']); ?>" alt="الصورة الحالية">
                        </div>
                    <?php endif; ?>
                </div>
                
                <button type="submit">حفظ التعديلات</button>
            </form>
        </div>
    </div>
</body>
</html>

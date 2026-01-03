<?php
require_once 'config/database.php';
require_once 'config/session.php';
requireLogin();

try {
    $stmt = $pdo->prepare("SELECT n.*, c.name as category_name, u.name as author_name 
                          FROM news n 
                          LEFT JOIN categories c ON n.category_id = c.id 
                          LEFT JOIN users u ON n.user_id = u.id 
                          WHERE n.status = 'active' 
                          ORDER BY n.created_at DESC");
    $stmt->execute();
    $news = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - نظام إدارة الأخبار</title>
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
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .nav-menu {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .nav-menu h2 {
            margin-bottom: 15px;
            color: #333;
        }
        .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .nav-links a {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .nav-links a:hover {
            background: #5568d3;
        }
        .news-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .news-section h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .news-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .news-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #eee;
        }
        .news-content {
            padding: 15px;
        }
        .news-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        .news-category {
            display: inline-block;
            padding: 4px 12px;
            background: #667eea;
            color: white;
            border-radius: 15px;
            font-size: 12px;
            margin-bottom: 10px;
        }
        .news-details {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .news-meta {
            font-size: 12px;
            color: #999;
            margin-top: 10px;
        }
        .no-news {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>نظام إدارة الأخبار</h1>
            <div class="user-info">
                <span>مرحباً، <?php echo htmlspecialchars(getUserName()); ?></span>
                <a href="/logout.php" class="logout-btn">تسجيل الخروج</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="nav-menu">
            <h2>القائمة الرئيسية</h2>
            <div class="nav-links">
                <a href="/add_category.php">إضافة فئة</a>
                <a href="/view_categories.php">عرض الفئات</a>
                <a href="/view_all_news.php">عرض جميع الأخبار</a>
                <a href="/add_news.php">إضافة خبر</a>
                <a href="/view_deleted_news.php">عرض الأخبار المحذوفة</a>
            </div>
        </div>

        <div class="news-section">
            <h2>آخر الأخبار</h2>
            <?php if (count($news) > 0): ?>
                <div class="news-grid">
                    <?php foreach ($news as $item): ?>
                        <div class="news-card">
                            <?php if ($item['image']): ?>
                                <img src="/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="news-image">
                            <?php else: ?>
                                <div class="news-image"></div>
                            <?php endif; ?>
                            <div class="news-content">
                                <div class="news-category"><?php echo htmlspecialchars($item['category_name'] ?? 'غير مصنف'); ?></div>
                                <div class="news-title"><?php echo htmlspecialchars($item['title']); ?></div>
                                <div class="news-details"><?php echo htmlspecialchars(substr($item['details'], 0, 150)) . (strlen($item['details']) > 150 ? '...' : ''); ?></div>
                                <div class="news-meta">
                                    بواسطة: <?php echo htmlspecialchars($item['author_name'] ?? 'غير معروف'); ?> | 
                                    <?php echo date('Y-m-d', strtotime($item['created_at'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-news">لا توجد أخبار حالياً</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

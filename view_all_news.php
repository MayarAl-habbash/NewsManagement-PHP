<?php
require_once 'config/database.php';
require_once 'config/session.php';
requireLogin();

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $news_id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("UPDATE news SET status = 'deleted' WHERE id = ?");
        $stmt->execute([$news_id]);
        header('Location: /view_all_news.php?success=deleted');
        exit;
    } catch (PDOException $e) {
        $error = 'حدث خطأ أثناء الحذف';
    }
}

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
    <title>عرض جميع الأخبار - نظام إدارة الأخبار</title>
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
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #667eea;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .action-btn {
            display: inline-block;
            padding: 6px 12px;
            margin: 0 5px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 14px;
        }
        .edit-btn {
            background: #4CAF50;
        }
        .edit-btn:hover {
            background: #45a049;
        }
        .delete-btn {
            background: #f44336;
        }
        .delete-btn:hover {
            background: #da190b;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        .success {
            background: #efe;
            color: #3c3;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .news-image-small {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
    <script>
        function confirmDelete(id) {
            if (confirm('هل أنت متأكد من حذف هذا الخبر؟')) {
                window.location.href = '/view_all_news.php?delete=' + id;
            }
        }
    </script>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>عرض جميع الأخبار</h1>
            <a href="/dashboard.php" class="back-btn">العودة للوحة التحكم</a>
        </div>
    </div>

    <div class="container">
        <div class="table-container">
            <h2>جميع الأخبار</h2>
            
            <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
                <div class="success">تم حذف الخبر بنجاح!</div>
            <?php endif; ?>
            
            <?php if (count($news) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>الصورة</th>
                            <th>العنوان</th>
                            <th>الفئة</th>
                            <th>الكاتب</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($news as $item): ?>
                            <tr>
                                <td>
                                    <?php if ($item['image']): ?>
                                        <img src="/<?php echo htmlspecialchars($item['image']); ?>" alt="صورة الخبر" class="news-image-small">
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($item['title']); ?></td>
                                <td><?php echo htmlspecialchars($item['category_name'] ?? 'غير مصنف'); ?></td>
                                <td><?php echo htmlspecialchars($item['author_name'] ?? 'غير معروف'); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($item['created_at'])); ?></td>
                                <td>
                                    <a href="/edit_news.php?id=<?php echo $item['id']; ?>" class="action-btn edit-btn">تعديل</a>
                                    <a href="javascript:void(0)" onclick="confirmDelete(<?php echo $item['id']; ?>)" class="action-btn delete-btn">حذف</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">لا توجد أخبار حالياً</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

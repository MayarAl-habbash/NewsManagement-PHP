# نظام إدارة الأخبار

## نظرة عامة
نظام إدارة أخبار متكامل تم تطويره باستخدام PHP و MySQL. يوفر النظام واجهة سهلة الاستخدام لإدارة الأخبار والفئات مع نظام تسجيل دخول آمن.

## الميزات الرئيسية
- نظام تسجيل حساب جديد وتسجيل دخول يدوي
- لوحة تحكم رئيسية (Dashboard) تعرض آخر الأخبار
- إدارة الفئات (إضافة وعرض)
- إدارة الأخبار (إضافة، عرض، تعديل، حذف)
- نظام رفع وإدارة صور الأخبار
- عرض الأخبار المحذوفة
- حذف ناعم للأخبار (Soft Delete)

## البنية التقنية
- **Backend**: PHP 8.2
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Session Management**: PHP Sessions

## هيكل قاعدة البيانات

### جدول Users
- id (INT PRIMARY KEY)
- name (VARCHAR)
- email (VARCHAR UNIQUE)
- password (VARCHAR - مشفر)
- created_at (TIMESTAMP)

### جدول Categories
- id (INT PRIMARY KEY)
- name (VARCHAR)
- created_at (TIMESTAMP)

### جدول News
- id (INT PRIMARY KEY)
- title (VARCHAR)
- category_id (INTEGER - Foreign Key)
- details (TEXT)
- image (VARCHAR)
- user_id (INTEGER - Foreign Key)
- status (VARCHAR - active/deleted)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)

## الصفحات الرئيسية
1. **index.php** - صفحة البداية (توجيه تلقائي)
2. **register.php** - صفحة إنشاء حساب جديد
3. **login.php** - صفحة تسجيل الدخول
4. **dashboard.php** - لوحة التحكم الرئيسية
5. **add_category.php** - إضافة فئة جديدة
6. **view_categories.php** - عرض جميع الفئات
7. **add_news.php** - إضافة خبر جديد
8. **view_all_news.php** - عرض جميع الأخبار مع إمكانية الحذف والتعديل
9. **edit_news.php** - تعديل خبر موجود
10. **view_deleted_news.php** - عرض الأخبار المحذوفة
11. **logout.php** - تسجيل الخروج

## ملفات الإعداد
- **config/database.php** - إعدادات الاتصال بقاعدة البيانات وإنشاء الجداول
- **config/session.php** - إدارة الجلسات والمصادقة

## مجلد الرفع
- **uploads/** - يحتوي على صور الأخبار المرفوعة

## الأمان
- كلمات المرور مشفرة باستخدام password_hash()
- حماية من SQL Injection باستخدام Prepared Statements
- التحقق من صلاحية المستخدم في كل صفحة محمية
- التحقق الآمن من الملفات المرفوعة:
  - التحقق من MIME type الحقيقي باستخدام finfo
  - التحقق من امتداد الملف server-side
  - حد أقصى لحجم الملف (5 ميجابايت)
  - السماح فقط بالصور (jpg, jpeg, png, gif)

## كيفية الاستخدام

1. **افتح التطبيق** من خلال الرابط أو من السيرفر المحلي (مثل `http://localhost/` في XAMPP).
2. **أنشئ حسابًا جديدًا** من صفحة التسجيل، بإدخال اسمك وبريدك الإلكتروني وكلمة المرور.
3. **سجّل الدخول** باستخدام البريد الإلكتروني وكلمة المرور التي استخدمتها أثناء التسجيل.
4. بعد تسجيل الدخول، **استخدم القائمة الرئيسية** للوصول إلى جميع وظائف النظام مثل:

   * إضافة الأخبار الجديدة
   * إدارة الفئات (الأقسام)
   * تعديل أو حذف الأخبار
5. عند الانتهاء، يمكنك **تسجيل الخروج** بأمان من خلال الزر المخصص لذلك في لوحة التحكم.

# palgoals-dashboard
palgoals-dashboard/
│
├── assets/                  # ملفات التصميم والسكربتات
│   ├── css/                 # ملفات CSS
│   │   ├── style.css        # التصميم العام
│   │   ├── admin.css        # تصميم لوحة التحكم
│   │   └── public.css       # تصميم الواجهة العامة
│   │
│   ├── js/                  # ملفات JavaScript
│   │   ├── pg-pages/
│   │   │   └── login.js     # سكربت تسجيل الدخول
│   │   ├── admin.js         # سكربت لوحة التحكم
│   │   └── public.js        # سكربت الواجهة العامة
│   │
│   └── images/              # الصور
│       ├── logo.png
│       ├── palgoalsnew.webp
│       └── authentication/
│           └── img-auth-sideimg.jpg
│
├── includes/                # الوظائف الأساسية
│   ├── admin/               # الوظائف الخاصة بلوحة التحكم
│   │   ├── categories.php   # وظائف إدارة التصنيفات
│   │   ├── menus.php        # وظائف إدارة القوائم
│   │   └── login-dashboard.php # وظائف تسجيل الدخول المخصصة
│   │
│   ├── functions/           # الوظائف المشتركة
│   │   ├── enqueue-scripts.php # تحميل ملفات CSS و JavaScript
│   │   ├── login-assets.php    # تحميل سكربتات صفحة تسجيل الدخول
│   │   ├── login-ajax.php      # معالجة طلبات تسجيل الدخول AJAX
│   │   ├── login-rewrite.php   # قواعد التوجيه لصفحة تسجيل الدخول
│   │   └── login-redirect.php  # إعادة التوجيه بعد تسجيل الدخول
│   │
│   ├── custom-post-types.php   # تسجيل نوع المنشورات المخصصة
│   ├── taxonomies.php          # تسجيل التصنيفات المخصصة
│   └── helpers.php             # وظائف مساعدة مشتركة
│
├── templates/               # القوالب
│   ├── menus/               # قوالب القوائم
│   │   ├── category-menus.php # عرض تصنيفات القوائم
│   │   └── pg-menus.php       # عرض القوائم
│   └── login.php            # قالب تسجيل الدخول
│
├── languages/               # ملفات الترجمة
│   └── palgoals-dashboard.pot
│
├── palgoals-dashboard.php   # الملف الأساسي للإضافة
├── uninstall.php            # إزالة الإضافة وتنظيف البيانات
└── readme.txt               # وصف الإضافة

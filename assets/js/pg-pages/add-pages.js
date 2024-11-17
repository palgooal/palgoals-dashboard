// دالة لتحويل النص إلى Slug يتعامل مع اللغة العربية
function convertToSlug(text) {
    var arabicToLatinMap = {
        'ا': 'a', 'أ': 'a', 'إ': 'i', 'آ': 'a',
        'ب': 'b', 'ت': 't', 'ث': 'th', 'ج': 'j',
        'ح': 'h', 'خ': 'kh', 'د': 'd', 'ذ': 'dh',
        'ر': 'r', 'ز': 'z', 'س': 's', 'ش': 'sh',
        'ص': 's', 'ض': 'd', 'ط': 't', 'ظ': 'z',
        'ع': 'a', 'غ': 'gh', 'ف': 'f', 'ق': 'q',
        'ك': 'k', 'ل': 'l', 'م': 'm', 'ن': 'n',
        'ه': 'h', 'و': 'w', 'ي': 'y', 'ى': 'a',
        'ء': '', 'ئ': 'y', 'ؤ': 'w', 'ة': 'h'
    };

    // تحويل النص العربي إلى نص لاتيني
    var slug = text.split('').map(function (char) {
        return arabicToLatinMap[char] || char;
    }).join('');

    // تحويل النص إلى slug قابل للاستخدام في الرابط
    return slug
        .toLowerCase()
        .replace(/[^\w\s-]/g, '')  // إزالة الأحرف الخاصة
        .trim()                     // إزالة الفراغات الزائدة
        .replace(/\s+/g, '-');       // استبدال الفراغات بشرطات
}

// مراقبة التغيير في حقل العنوان وتعبئة slug تلقائيًا
document.getElementById('page-title').addEventListener('input', function() {
    var title = this.value;
    var slug = convertToSlug(title);
    document.getElementById('page-slug').value = slug;
});

// فتح وإغلاق النافذة المنبثقة
document.getElementById('animateModal').addEventListener('click', function(event) {
    if (event.target === this) {
        document.getElementById('animateModal').classList.add('hidden');
    }
});

// إنشاء الصفحة عند الضغط على زر "إضافة"
document.getElementById('create-page').addEventListener('click', function() {
    var title = document.getElementById('page-title').value;
    var slug = document.getElementById('page-slug').value;

    if (title && slug) {
        // إرسال البيانات إلى خادم ووردبريس باستخدام AJAX
        var data = {
            'action': 'create_page',
            'title': title,
            'slug': slug,
            'security': palgoals_sadd_pages_object.nonce
        };

        jQuery.post(ajaxurl, data, function(response) {
            console.log(response);
            if (response.success && response.data.edit_link) {
                // Redirect to the new page edit link
                window.location.href = response.data.edit_link + '&new_page_id=' + response.data.new_page_id, '_blank' ;
            } else {
                alert(response.data.message || 'حدث خطأ أثناء إنشاء الصفحة');
            }
        });
    } else {
        // إظهار رسالة التنبيه فقط عند عدم إدخال البيانات
        alert('يرجى ملء الحقول المطلوبة');
    }
});

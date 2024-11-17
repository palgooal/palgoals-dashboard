jQuery(document).ready(function($) {
    // عند فتح زر تحميل الوسائط
    $('#open-media-button').click(function(e) {
        e.preventDefault();
        var mediaUploader = wp.media({
            title: 'Upload Media',
            button: {
                text: 'Select'
            },
            multiple: true
        });

        mediaUploader.on('select', function() {
            var attachments = mediaUploader.state().get('selection').toJSON();
            var formData = new FormData();
            formData.append('action', 'palgoals_handle_media_upload');
            formData.append('nonce', ajax_object.nonce);
            attachments.forEach(function(attachment) {
                formData.append('media_ids[]', attachment.id);
            });

            $.ajax({
                url: ajax_object.ajaxurl,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        $('#message-container').html('<div class="alert alert-success">' + data.success + '</div>');
                        location.reload();
                    } else if (data.error) {
                        $('#message-container').html('<div class="alert alert-danger">' + data.error + '</div>');
                    }
                },
                error: function(response) {
                    $('#message-container').html('<div class="alert alert-danger">Upload failed.</div>');
                    console.error('Upload failed:', response);
                }
            });
        }).open();
    });

    // حذف الوسائط
    $(document).on('click', '.delete-media-button', function(e) {
        e.preventDefault();
        var confirmDelete = confirm("Are you sure you want to delete this file?");
        if (!confirmDelete) {
            return;
        }
        
        var mediaId = $(this).data('id');
        var formData = new FormData();
        formData.append('action', 'palgoals_handle_media_delete');
        formData.append('nonce', ajax_object.nonce);
        formData.append('media_id', mediaId);

        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    $('#message-container').html('<div class="alert alert-success">' + data.success + '</div>');
                    location.reload();
                } else if (data.error) {
                    $('#message-container').html('<div class="alert alert-danger">' + data.error + '</div>');
                }
            },
            error: function(response) {
                $('#message-container').html('<div class="alert alert-danger">Delete failed.</div>');
                console.error('Delete failed:', response);
            }
        });
    });

    // البحث في الوسائط
    $('#media-search-input').on('keyup', function() {
        var searchTerm = $(this).val();
        var formData = new FormData();
        formData.append('action', 'palgoals_search_media');
        formData.append('nonce', ajax_object.nonce);
        formData.append('search_term', searchTerm);

        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    $('#media-library').html(data.html); // تحديث مكتبة الوسائط بالنتائج
                } else if (data.error) {
                    $('#message-container').html('<div class="alert alert-danger">' + data.error + '</div>');
                }
            },
            error: function(response) {
                $('#message-container').html('<div class="alert alert-danger">Search failed.</div>');
                console.error('Search failed:', response);
            }
        });
    });

    // زر تحميل المزيد
    $('#load-more-button').click(function (e) {
        e.preventDefault();
        var offset = parseInt($(this).data('offset'));
        var searchTerm = $('#media-search-input').val(); // الحصول على مصطلح البحث الحالي
        loadMedia(offset, searchTerm); // استدعاء الدالة مع offset الحالي
    });

    // دالة تحميل الوسائط
    function loadMedia(offset, searchTerm) {
        var formData = new FormData();
        formData.append('action', 'palgoals_search_media');
        formData.append('nonce', ajax_object.nonce);
        formData.append('search_term', searchTerm);
        formData.append('offset', offset);

        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                var data = JSON.parse(response);
                if (data.success) {
                    $('#media-library').append(data.html); // إضافة الصور الجديدة

                    // تحديث عدد العناصر المعروضة
                    var totalItems = data.total_items; // تأكد من أن البيانات تحتوي على العدد الإجمالي
                    var loadedItems = offset + Math.min(data.html.split('<img').length - 1, totalItems - offset);
                    $('#media-count').text(`عرض ${loadedItems} من ${totalItems} من عناصر الوسائط`);

                    // التحقق من وجود المزيد من الصور لتحميلها
                    if (loadedItems < totalItems) {
                        $('#load-more-button').data('offset', offset + 12).show(); // تحديث offset وإظهار الزر
                    } else {
                        $('#load-more-button').hide(); // إخفاء الزر إذا لم يكن هناك المزيد
                    }
                }
            },
            error: function (response) {
                console.error('Failed to load media:', response);
            }
        });
    }

    // جلب عدد الوسائط عند تحميل الصفحة
    function fetchInitialMediaCount() {
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'palgoals_get_media_count',
                nonce: ajax_object.nonce
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    var totalItems = data.total_items;
                    $('#media-count').text(`عرض 0 من ${totalItems} من عناصر الوسائط`);
                    $('#load-more-button').data('offset', 12); // تعيين القيمة الافتراضية للـ offset
                }
            },
            error: function(response) {
                console.error('Failed to fetch media count:', response);
            }
        });
    }

    // استدعاء جلب عدد الوسائط عند تحميل الصفحة
    fetchInitialMediaCount();
});

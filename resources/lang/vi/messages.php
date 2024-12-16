<?php

return [
    'error-internal-server' => 'Có gì đó đã sai! Vui lòng thử lại sau.',
    'error-not-found' => 'Không tìm thấy bản ghi.',
    'error-error-system' => 'Lỗi hệ thống',
    'created-success' => 'Tạo thành công',
    'delete-success' => 'Xóa thành công',
    'update-success' => 'Cập nhật thành công',
    'restore-success' => 'Khôi phục thành công',
    'error-required' => 'Trường này là bắt buộc',
    'error-value' => 'Các giá trị phải là một mảng',

    'review' => [
        'error-review-forbidden' =>  'Bạn chỉ có thể đánh giá một sản phẩm sau khi mua và chỉ một lần cho mỗi sản phẩm.',
    ],
    'product' => [
        'error-variant' =>  'Biến thể bị sai định dạng.',
        'error-create.variant' =>  'Không thể tạo biến thể',
        'error-failed-create.variant' =>  'Tạo biến thể không thành công',
        'error-not-attribute' =>  'Không thể tìm thấy giá trị thuộc tính',
    ],
    'post' => [
        'error-already-exists' =>  'Tiêu đề hoặc slug đã tồn tại. Vui lòng chọn giá trị khác.',
        'error-Could-not-found-post' => 'Không thể tìm thấy bài viết',
        'error-delete-post' => 'Đã xảy ra lỗi khi xóa bài viết.',
        'error-restoring-post' => 'Đã xảy ra lỗi khi khôi phục bài viết.',
    ],
    'image' => [
        'error-image' =>  'Không thể tạo hình ảnh. Có thể hình ảnh đang ở sai định dạng!',
        'error-can-not-image' =>  'Không thể tìm thấy hình ảnh nào',
        'error-no-image' =>  'Không có hình ảnh nào được tải lên',
    ],
    'group' => [
        'error-group' =>  'Nhóm với tên này đã tồn tại.',
    ],
    'attribute' => [
        'error-can-not-attribute' =>  'Không thể tìm thấy hình ảnh nào',
    ],
    'sale' => [
        'error-can-not-sale' =>  'Không thể tạo chương trình khuyến mãi',
        'error-invalid-type' =>  'Loại không hợp lệ.',
    ],
    'order' => [
        'error-order' =>  'Không có quyền',
        'error-provider-detail' =>  'Vui lòng cung cấp thêm chi tiết.',
        'error-cancelled-order' =>  'Đơn hàng đã được hủy thành công!',
        'error-payment' =>  'Trạng thái thanh toán đã được cập nhật thành công',
        'error-specific' =>  'Vui lòng cung cấp lý do cụ thể.',
        'error-confirmed' =>  'Quản trị viên đã xác nhận đơn hàng',
        'error-delivered' =>  'Đơn hàng đang được giao',
        'error-was-delivered' =>  'Đơn hàng đã được giao',
        'error-processing' =>  'Đơn hàng trả lại đang được xử lý',
        'error-returned-processing' =>  'Đơn hàng đã được trả lại và đang được xử lý',
        'error-returned' =>  'Đơn hàng đã được trả lại',
        'error-can-not-order' =>  'Không thể hủy đơn hàng',
    ],
    'voucher' => [
        'error-voucher' =>  'Mã giảm giá đã hết lượt sử dụng',
    ],
    'topic' => [
        'error-can-not' =>  'Không thể tìm thấy chủ đề',
        'error-restore' =>  'Không thể khôi phục chủ đề',
        'error-force-delete' =>  'Không thể xóa vĩnh viễn',
    ],
    'user' => [
        'error-user' =>  'Không có quyền',
        'error-email' =>  'Email này đã được sử dụng',
        'error-invalid' =>  'Mã xác minh không hợp lệ',
        'error-register' =>  'Đăng ký không thành công',
        'error-password' =>  'Bạn đã nhập sai mật khẩu quá nhiều lần, vui lòng thử lại sau 5 phút',
        'error-wrong-password' =>  'Sai mật khẩu',
        'error-current-password' =>  'Mật khẩu hiện tại không đúng',
        'error-can-not-email' =>  'Email không được tìm thấy trong hệ thống!',
        'error-wrong-verification' =>  'Mã xác minh sai',
        'error-could-not-create' =>  'Không thể tạo người dùng',
        'error-system' =>  'Lỗi hệ thống!',
        'error-check-password' =>  'Vui lòng kiểm tra lại mật khẩu của bạn!',
        'error-logout' =>  'Đăng xuất thành công',
        'error-password-success' =>  'Mật khẩu đã được thay đổi thành công',
        'error-email-not-found' =>  'Không tìm thấy email!',
        'error-code' =>  'Mã đã được gửi đến email của bạn!',
        'error-password-reset' =>  'Mật khẩu đã được đặt lại thành công!',
        'error-add-favorite' =>  'Đã thêm sản phẩm yêu thích thành công!',
        'error-profile' =>  'Thông tin hồ sơ đã được cập nhật thành công!',
        'error-upload' =>  'Không thể tìm thấy tệp nào để tải lên',
    ],
    'mail' => [
        'order-success' => [
            'invoice' => 'Hóa Đơn',
            'created' => 'Ngày tạo',
            'status' => 'Trạng Thái',
            'status_order' => [
                'cancelled' => 'Đã Hủy',
                'waiting_confirm' => 'Chờ Xác Nhận',
                'confirmed' => 'Đã Xác Nhận',
                'delivering' => 'Đang Giao',
                'delivered' => 'Đã Giao'
            ],
            'payment_method_title' => 'Phương Thức Thanh Toán ',
            'payment_method' => [
                'banking' => 'Chuyển Khoảng',
                'momo' => 'Cổng Thanh Toán Momo',
                'vnpay' => 'Cổng Thanh Toán VnPay',
                'cash_on_delivery' => 'Thanh Toán Khi Nhận Hàng',
            ],
            'item_text' => 'Sản Phẩm',
            'price_text' => 'Giá',
            'subtotal_text' => 'Tổng Phụ',
            'delivery_fee' => 'Phí Vận Chuyển',
            'total_text' => 'Tổng'
        ],
        'authentication-code' => [
            'title' => 'Mã Xác Thực Tài Khoản Fshoes Của Bạn',
            'span_code' => 'Đây là mã xác minh một lần mà bạn đã yêu cầu',
            'message_time' => 'Mã này sẽ có hiệu lực trong 5 phút',
            'message_ignore' => 'Nếu bạn đã nhận được mã này hoặc không cần nó nữa, hãy bỏ qua email này',
        ]
    ],
];

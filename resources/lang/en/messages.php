<?php

return [
    'error-internal-server' => 'Something went wrong!. Please try later.',
    'error-not-found' => 'The record could not be found.',
    'error-error-system' => 'Error System',
    'created-success' => 'Created successfully',
    'delete-success' => 'Delete successfully',
    'update-success' => 'Update successfully',
    'restore-success' => 'Restore successfully',
    'error-required' => 'The field is required',
    'error-value' => 'The values must be an array',
    'invalid-value' => 'Invalid argument',
    'auth' => [
        'banned' => 'This account is banned.Please try later.'
    ],
    'review' => [
        'error-review-forbidden' => 'You can only review a product after purchasing it and only once per product.',

    ],
    'product' => [
        'error-variant' => 'Malformed variant.',
        'error-create.variant' => 'Can not create variations',
        'error-failed-create.variant' => 'Failed to create variation',
        'error-not-attribute' => 'Could not find any attribute value',
    ],
    'post' => [
        'error-already-exists' => 'The title or slug already exists. Please choose a different value.',
        'error-Could-not-found-post' => 'Could not found post',
        'error-delete-post' => 'An error occurred while deleting the post.',
        'error-restoring-post' => 'An error occurred while restoring the post.',
    ],
    'image' => [
        'error-image' => 'Cannot create images.Maybe the image is in the wrong format!',
        'error-can-not-image' => 'Cannot find any images',
        'error-no-image' => 'No images uploaded',
    ],
    'group' => [
        'error-group' => 'Group with this name already exists.',


    ],
    'attribute' => [
        'error-can-not-attribute' => 'Cannot find any images',
    ],
    'sale' => [
        'error-can-not-sale' => 'Can not create sale',
        'error-invalid-type' => 'Invalid type.',
    ],
    'order' => [
        'error-order' => 'Unauthorized',
        'error-provider-detail' => 'Please provide more detail.',
        'error-cancelled-order' => 'Order Cancelled Successfully!',
        'error-payment' => 'Payment status updated successfully',
        'error-specific' => 'Please provide specific reason.',
        'error-confirmed' => 'Admin confirmed order',
        'error-delivered' => 'Order is being delivered',
        'error-was-delivered' => 'Order was delivered',
        'error-processing' => 'Return order processing',
        'error-returned-processing' => 'Order returned processing',
        'error-returned' => 'Order is returned',
        'error-can-not-order' => 'Cannot cancel order',
    ],
    'voucher' => [
        'error-voucher' => 'Voucher is out of uses',

    ],
    'topic' => [
        'error-can-not' => 'Cannot find topic',
        'error-restore' => 'Cannot restore topic',
        'error-force-delete' => 'Cannot force delete',
    ],
    'user' => [
        'error-user' => 'Unauthorized',
        'error-email' => 'The email have already been taken',
        'error-invalid' => 'Invalid verify code',
        'error-register' => 'Failed to register',
        'error-password' => 'You have entered the wrong password too many times, please enter again after 5 minutes',
        'error-wrong-password' => 'Wrong password',
        'error-current-password' => 'Wrong current password',
        'error-can-not-email' => 'Email not found in the system!',
        'error-wrong-verification' => 'Wrong verification code',
        'error-could-not-create' => 'Could not create user',
        'error-system' => 'Error system!',
        'error-check-password' => 'Please check your password again!',
        'error-logout' => 'Successfully logged out',
        'error-password-success' => 'Password successfully changed',
        'error-email-not-found' => 'Not found email!',
        'error-code' => 'Code sent to your email!',
        'error-password-reset' => 'Password successfully reset!',
        'error-add-favorite' => 'Add favorite product successfully!',
        'error-profile' => 'Profile updated successfully!',
        'error-upload' => 'Could not find any files to upload',

    ],
    'mail' => [
        'order-success' => [
            'invoice' => 'Invoice',
            'created' => 'Created',
            'status' => 'Status',
            'status_order' => [
                'cancelled' => 'Cancelled',
                'waiting_confirm' => 'Waiting Confirm',
                'confirmed' => 'Confirmed',
                'delivering' => 'Delivering',
                'delivered' => 'Delivered'
            ],
            'payment_method_title' => 'Payment Method',
            'payment_method' => [
                'banking' => 'Banking',
                'momo' => 'Payment Gateway Momo',
                'vnpay' => 'Payment Gateway VnPay',
                'cash_on_delivery' => 'Cash On Delivery',
            ],
            'item_text' => 'Items',
            'price_text' => 'Price',
            'subtotal_text' => 'Subtotal',
            'delivery_fee' => 'Delivery Fee',
            'total_text' => 'Total',
            'create_message_title' => 'Thank for your purchase!'
        ],
        'authentication-code' => [
            'title' => 'Your Fshoes Member profile code',
            'span_code' => 'Here\'s the one-time verification code you requested',
            'message_time' => 'This code will be valid for 5 minutes',
            'message_ignore' => 'If you\'ve already received this code or don\'t need it any more, ignore this email',
        ]
    ],

    'statiscs' => [
        'error-statiscs' => 'Error system!',
        'message_ignore' => 'If you\'ve already received this code or don\'t need it any more, ignore this email',
    ],
    'paid-order' => [
        'title' => 'Order Successfully Paid',
        'message_success' => 'Thank you for the successful payment of your order. We will deliver your order as soon as we confirm your order.',
        'link_text' => 'See now',

    ],

    'cart' => [
        'error-cart' =>  'Unauthorized',
        'error-cart-add' =>  'Cannot add new cart.',
        'error-quantity' =>  'Not enough quantity.',
        'error-delete-cart' =>  'Cannot delete cart',
        'product_word' => 'Product',
        'variations_word' => 'Variation',
        'out_of_stock' => ' out of stock. There are only have ',
        'units' => ' units'
    ],

    'update_review_request' => [
        'title.sometimes.required' => 'Product title is required if present.',
        'title.string' => 'Product title must be a type of string.',
        'title.max' => 'Product title is too long; 255 characters is maximum.',
        'text.sometimes.required' => 'Review text is required if present.',
        'text.string' => 'Review text must be a type of string.',
        'rating.sometimes.required' => 'Rating is required if present.',
        'rating.integer' => 'Rating must be an integer.',
        'rating.min' => 'Rating must be at least 1.',
        'rating.max' => 'Rating may not be greater than 5.',
    ],
    'create_review_request' => [
        'product_id.required' => 'The product ID is required.',
        'product_id.exists' => 'The selected product does not exist in the system.',
        'title.required' => 'The title is required.',
        'title.string' => 'The title must be a string.',
        'title.max' => 'The title may not exceed 255 characters.',
        'text.required' => 'The review text is required.',
        'text.string' => 'The review text must be a string.',
        'rating.required' => 'The rating is required.',
        'rating.integer' => 'The rating must be an integer.',
        'rating.min' => 'The rating must be at least 1.',
        'rating.max' => 'The rating may not be greater than 5.',
    ],
    'create_voucher_request' => [
        'code.required' => 'Voucher Code is required.',
        'discount.required' => 'Discount is required.',
        'date_start.required' => 'Date start is required.',
        'date_end.required' => 'Date end is required',
        'quantity.required' => 'Quantity is required',
        'status.required' => 'Status is required',
    ],
    'create_user_request' => [
        'name.required' => 'User name is required',
        'name.string' => 'Product name must be a type of string',
        'name.max' => 'Product name is too long,255 characters is maximum',
        'email.required' => 'Email is required',
        'email.string' => 'Email must be a type of string',
        'email.max' => 'Email is too long,255 characters is maximum',
        'email.unique' => 'Email already exists',
        'password.required' => 'Password is required',
        'password.string' => 'Password must be a type of string',
        'password.min' => 'Password must be at least 6 characters',
        'group.exists' => 'Group does not exist',
    ],
    'update_user_request' => [
        'name.required' => 'User name is required',
        'name.string' => 'Product name must be a type of string',
        'name.max' => 'Product name is too long,255 characters is maximum',
        'password.required' => 'Password is required',
        'password.string' => 'Password must be a type of string',
        'password.min' => 'Password must be at least 6 characters',
        'group.exists' => 'Group does not exist',
    ],
    'create_sale_request' => [
        'name.string' => 'The sale name must be a string.',
        'type.in' => 'The sale type must be fixed or percent.',
        'value.number' => 'The sale value must be a number.',
        'start_date.date' => 'The sale start date must be a date.',
        'start_date.before' => 'The sale start date must not be after the end date.',
    ],
    'update_sale_request' => [
        'name.string' => 'The sale name must be a string.',
        'type.in' => 'The sale type must be fixed or percent.',
        'value.number' => 'The sale value must be a number.',
        'start_date.date' => 'The sale start date must be a date.',
        'start_date.before' => 'The sale start date must not be after the end date.',
    ],
    'create_variation_request' => [
        'variations.*.price.required' => 'Product price is required',
        'variations.*.stock_qty.required' => 'Product stock quantity is required',
        'variations.*.stock_qty.numeric' => 'Product stock quantity  must be a type of number',
    ],
    'update_variation_request' => [
        'price.required' => 'Variation price is required',
        'stock_qty.required' => 'Variation stock quantity is required',
        'stock_qty.numeric' => 'Variation stock quantity  must be a type of number',
    ],
    'create_product_request' => [
        'name.required' => 'Product name is required',
        'name.string' => 'Product name must be a type of string',
        'name.max' => 'Product name is too long,255 characters is maximum',
        'price.required' => 'Product price is required',
        'stock_qty.required' => 'Product stock quantity is required',
        'stock_qty.numeric' => 'Product stock quantity  must be a type of number',
        'image_url.required' => 'Product image is required',
        'image_url.string' => 'Product image not found.Try again!',
    ],
    'update_product_request' => [
        'name.required' => 'Product name is required',
        'name.string' => 'Product name must be a type of string',
        'name.max' => 'Product name is too long,255 characters is maximum',
        'price.required' => 'Product price is required',
        'stock_qty.required' => 'Product stock quantity is required',
        'stock_qty.numeric' => 'Product stock quantity  must be a type of number',
        'image_url.required' => 'Product image is required',
        'image_url.string' => 'Product image not found.Try again!',
    ],
    'post_request' => [
        "title.required" => "The title is required.",
        "slug.required" => "The slug is required.",
        "content.required" => "Content is required.",
        "topic_id.required" => "The topic ID is required.",
        "author_id.required" => "The author ID is required.",
    ],
    'create_order_request' => [
        'receiver_email.required' => 'Receiver email is required',
        'receiver_email.email' => 'Invalid Email',
        'total_amount.required' => 'Total amount is required',
        'payment_method.required' => 'Payment method is required',
        'payment_status.required' => 'Payment status is required',
        'shipping_method.required' => 'Shipping method is required',
        'shipping_cost.required' => 'Shipping cost is required',
        'amount_collected.required' => 'Amount collected is required',
        "receiver_full_name.required" => "Receiver full name is required",
        "phone.required" => "Phone is required",
        "city.required" => "City is required",
        "country.required" => "Country is required",
        "address.required" => "Address is required",
        "status.required" => "Status is required"
    ],
    'create_category_request' => [
        'name.required' => 'The name field is required.',
        'name.string' => 'The name must be a string.',
    ],
    'update_category_request' => [
        'name.required' => 'The name field is required.',
        'name.string' => 'The name must be a string.',
    ],
    'add_cart_request' => [
        "user_id.require" => "User is is required",
        "quantity.require" => "Quantity is required"
    ],
    'password_request' => [
        'password.required' => 'Password is required',
        'newPassword.required' => 'New Password is required',
        'newPassword.min' => 'New Password must be at least 8 characters',
    ],
    'error_middleware' => [
        'error_custom' => 'Too many requests, please try again later.',
        'error_isAdmin' => 'Unauthorized',
    ],
];

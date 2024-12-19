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
    'forbidden' => 'Forbidden',
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
        'error-cart' => 'Unauthorized',
        'error-cart-add' => 'Cannot add new cart.',
        'error-quantity' => 'Not enough quantity.',
        'error-delete-cart' => 'Cannot delete cart',
        'product_word' => 'Product',
        'variations_word' => 'Variation',
        'out_of_stock' => ' out of stock. There are only have ',
        'units' => ' units'
    ],

    'update_review_request' => [
        'title' => [
            'sometimes.required' => 'Product title is required if present.',
            'string' => 'Product title must be a type of string.',
            'max' => 'Product title is too long; 255 characters is maximum.',
        ],
        'text' => [
            'sometimes.required' => 'Review text is required if present.',
            'string' => 'Review text must be a type of string.',
        ],
        'rating' => [
            'sometimes.required' => 'Rating is required if present.',
            'integer' => 'Rating must be an integer.',
            'min' => 'Rating must be at least 1.',
            'max' => 'Rating may not be greater than 5.',
        ],
    ],
    'create_review_request' => [
        'product_id' => [
            'required' => 'The product ID is required.',
            'exists' => 'The selected product does not exist in the system.',
        ],
        'title' => [
            'required' => 'The title is required.',
            'string' => 'The title must be a string.',
            'max' => 'The title may not exceed 255 characters.',
        ],
        'text' => [
            'required' => 'The review text is required.',
            'string' => 'The review text must be a string.',
        ],
        'rating' => [
            'required' => 'The rating is required.',
            'integer' => 'The rating must be an integer.',
            'min' => 'The rating must be at least 1.',
            'max' => 'The rating may not be greater than 5.',
        ],
    ],

    'create_voucher_request' => [
        'code' => [
            'required' => 'Voucher Code is required.',
        ],
        'discount' => [
            'required' => 'Discount is required.',
        ],
        'date_start' => [
            'required' => 'Start date is required.',
        ],
        'date_end' => [
            'required' => 'End date is required.',
        ],
        'quantity' => [
            'required' => 'Quantity is required.',
        ],
        'status' => [
            'required' => 'Status is required.',
        ],
    ],

    'create_user_request' => [
        'name' => [
            'required' => 'User name is required',
            'string' => 'Product name must be a type of string',
            'max' => 'Product name is too long,255 characters is maximum',
        ],
        'email' => [
            'required' => 'Email is required',
            'string' => 'Email must be a type of string',
            'max' => 'Email is too long,255 characters is maximum',
            'unique' => 'Email already exists',
        ],
        'password' => [
            'required' => 'Password is required',
            'string' => 'Password must be a type of string',
            'min' => 'Password must be at least 6 characters',
        ],
        'groups' => [
            'exists' => 'Group does not exist',
            'nullable' => 'Group is optional.',
            'integer' => 'Group must be an integer.',
        ],
        'profile' => [
            'nullable' => 'Profile is optional.',
            'array' => 'Profile must be an array.',
        ],
        'verify_code' => [
            'nullable' => 'Verification code is optional.',
            'string' => 'Verification code must be a string.',
        ],
    ],
    'update_user_request' => [
        'name' => [
            'required' => 'User name is required',
            'string' => 'Product name must be a type of string',
            'max' => 'Product name is too long,255 characters is maximum',
        ],
        'password' => [
            'required' => 'Password is required',
            'string' => 'Password must be a type of string',
            'min' => 'Password must be at least 6 characters',
        ],
        'group' => [
            'exists' => 'Group does not exist',
            'nullable' => 'Group is optional.',
            'integer' => 'Group must be an integer.',
        ],
        'profile' => [
            'nullable' => 'Profile is optional.',
            'array' => 'Profile must be an array.',
        ],
    ],
    'create_sale_request' => [
        'name' => [
            'string' => 'The sale name must be a string.',
        ],
        'type' => [
            'in' => 'The sale type must be fixed or percent.',
        ],
        'value' => [
            'number' => 'The sale value must be a number.',
        ],
        'start_date' => [
            'date' => 'The sale start date must be a date.',
            'before' => 'The sale start date must not be after the end date.',
        ],
        'is_active' => [
            'nullable' => 'The is_active field is optional.',
            'boolean' => 'The is_active field must be true or false.',
        ],
        'end_date' => [
            'required' => 'The end date is required.',
            'date_format' => 'The end date must be in the format.',
            'after' => 'The end date must be after the start date.',
        ],
        'products' => [
            'nullable' => 'The products field is optional.',
            'array' => 'The products field must be an array.',
        ],
        'variations' => [
            'nullable' => 'The variations field is optional.',
            'array' => 'The variations field must be an array.',
        ],
        'applyAll' => [
            'nullable' => 'The applyAll field is optional.',
            'boolean' => 'The applyAll field must be true or false.',
        ],
    ],
    'update_sale_request' => [
        'name' => [
            'string' => 'The sale name must be a string.',
        ],
        'type' => [
            'in' => 'The sale type must be fixed or percent.',
        ],
        'value' => [
            'number' => 'The sale value must be a number.',
        ],
        'start_date' => [
            'date' => 'The sale start date must be a date.',
            'before' => 'The sale start date must not be after the end date.',
            'date_format' => 'Invalid format date',
            'required' => 'The start date is required',
        ],

        'end_date' => [
            'after' => 'The end date must be after the start date.',
            'format' => 'The end date must be in the format.',
            'required' => 'The start date is required',
        ],
        'variations' => [
            'nullable' => 'The variations field is optional.',
            'array' => 'The variations field must be an array.',
        ],
    ],

    'create_variation_request' => [
        'variations' => [
            'array' => 'The variations field must be an array.',
            '.*.price' => [
                'required' => 'Product price is required',
            ],
            '.*.stock_qty' => [
                'required' => 'Product stock quantity is required',
                'numeric' => 'Product stock quantity must be a type of number',
            ],
            '.*.import_price' => [
                'nullable' => 'The import price for each variation is optional.',
            ],
            '.*.sku' => [
                'nullable' => 'The SKU for each variation is optional.',
                'string' => 'The SKU for each variation must be a string.',
            ],
            '.*.description' => [
                'nullable' => 'The description for each variation is optional.',
            ],
            '.*.short_description' => [
                'nullable' => 'The short description for each variation is optional.',
            ],
            '.*.status' => [
                'nullable' => 'The status for each variation is optional.',
            ],
            '.*.attributes' => [
                'array' => 'The attributes for each variation must be an array.',
            ],
            '.*.images' => [
                'nullable' => 'The images for each variation are optional.',
                'array' => 'The images for each variation must be an array.',
            ],
            '.*.values' => [
                'required' => 'The values for each variation are required.',
                'array' => 'The values for each variation must be an array.',
            ],
        ],
    ],
    'update_variation_request' => [
        'price' => [
            'required' => 'Variation price is required',
        ],
        'stock_qty' => [
            'required' => 'Variation stock quantity is required',
            'numeric' => 'Variation stock quantity must be a type of number',
        ],
        'sku' => [
            'nullable' => 'The SKU is optional.',
            'string' => 'The SKU must be a string.',
        ],
        'description' => [
            'nullable' => 'The description is optional.',
        ],
        'short_description' => [
            'nullable' => 'The short description is optional.',
        ],
        'status' => [
            'nullable' => 'The status is optional.',
        ],
        'attributes' => [
            'array' => 'The attributes must be an array.',
        ],
        'images' => [
            'nullable' => 'The images field is optional.',
            'array' => 'The images field must be an array.',
        ],
        'values' => [
            'required' => 'The values field is required.',
            'array' => 'The values field must be an array.',
        ],
        'variations' => [
            '.*.import_price' => [
                'nullable' => 'The import price for each variation is optional.',
            ],
        ],
    ],

    'create_product_request' => [
        'name' => [
            'required' => 'Product name is required',
            'string' => 'Product name must be a type of string',
            'max' => 'Product name is too long, 255 characters is maximum',
        ],
        'price' => [
            'required' => 'Product price is required',
        ],
        'stock_qty' => [
            'required' => 'Product stock quantity is required',
            'numeric' => 'Product stock quantity must be a type of number',
        ],
        'image_url' => [
            'required' => 'Product image is required',
            'string' => 'Product image not found. Try again!',
        ],
        'import_price' => [
            'nullable' => 'The import price is optional.',
        ],
        'description' => [
            'nullable' => 'The description is optional.',
        ],
        'short_description' => [
            'nullable' => 'The short description is optional.',
        ],
        'images' => [
            'nullable' => 'The images field is optional.',
            'array' => 'The images field must be an array.',
        ],
        'categories' => [
            'nullable' => 'The categories field is optional.',
            'array' => 'The categories field must be an array.',
        ],
    ],
    'update_product_request' => [
        'name' => [
            'required' => 'Product name is required.',
            'string' => 'Product name must be a string.',
            'max' => 'Product name is too long, maximum 255 characters.',
        ],
        'price' => [
            'required' => 'Product price is required.',
        ],
        'stock_qty' => [
            'required' => 'Product stock quantity is required.',
            'numeric' => 'Product stock quantity must be a number.',
        ],
        'image_url' => [
            'required' => 'Product image is required.',
            'string' => 'Product image not found. Please try again!',
        ],
        'import_price' => [
            'nullable' => 'Import price is optional.',
        ],
        'description' => [
            'nullable' => 'Description is optional.',
        ],
        'short_description' => [
            'nullable' => 'Short description is optional.',
        ],
        'images' => [
            'nullable' => 'Images field is optional.',
            'array' => 'Images field must be an array.',
        ],
        'categories' => [
            'nullable' => 'Categories field is optional.',
            'array' => 'Categories field must be an array.',
        ],
    ],

    'post_request' => [
        'title' => [
            'required' => 'The title is required.',
        ],
        'slug' => [
            'required' => 'The slug is required.',
        ],
        'content' => [
            'required' => 'Content is required.',
        ],
        'topic_id' => [
            'required' => 'The topic ID is required.',
        ],
        'author_id' => [
            'required' => 'The author ID is required.',
        ],
    ],

    'create_order_request' => [
        'receiver_email' => [
            'required' => 'Receiver email is required',
            'email' => 'Invalid Email',
        ],
        'total_amount' => [
            'required' => 'Total amount is required',
            'numeric' => 'The total amount must be a numeric value.',
        ],
        'payment_method' => [
            'required' => 'Payment method is required',
            'string' => 'The payment method must be a string.',
        ],
        'payment_status' => [
            'required' => 'Payment status is required',
            'string' => 'The payment status must be a string.',
        ],
        'shipping_method' => [
            'required' => 'Shipping method is required',
            'string' => 'The shipping method must be a string.',
        ],
        'shipping_cost' => [
            'required' => 'Shipping cost is required',
            'numeric' => 'The shipping cost must be a numeric value.',
        ],
        'amount_collected' => [
            'required' => 'Amount collected is required',
            'numeric' => 'The amount collected must be a numeric value.',
        ],
        'receiver_full_name' => [
            'required' => 'Receiver full name is required',
            'string' => 'The receiver full name must be a string.',
        ],
        'phone' => [
            'required' => 'Phone is required',
            'string' => 'The phone number must be a string.',
        ],
        'city' => [
            'required' => 'City is required',
            'string' => 'The city must be a string.',
        ],
        'country' => [
            'required' => 'Country is required',
            'string' => 'The country must be a string.',
        ],
        'address' => [
            'required' => 'Address is required',
            'string' => 'The address must be a string.',
        ],
        'status' => [
            'required' => 'Status is required',
        ],
    ],

    'create_category_request' => [
        'name' => [
            'required' => 'The name field is required.',
            'string' => 'The name must be a string.',
        ],
        'parents' => [
            'array' => 'The parents must be an array.',
            'nullable' => 'The parents can be left empty.',
        ],
    ],

    'update_category_request' => [
        'name' => [
            'required' => 'The name field is required.',
            'string' => 'The name must be a string.',
        ],
        'parents' => [
            'array' => 'The parents must be an array.',
            'nullable' => 'The parents can be left empty.',
        ],
        'image_url' => [
            'nullable' => 'The image URL can be left empty.',
            'string' => 'The image URL must be a string.',
        ],
    ],

    'add_cart_request' => [
        'user_id' => [
            'require' => 'User ID is required.',
        ],
        'quantity' => [
            'require' => 'Quantity is required.',
        ],
    ],

    'password_request' => [
        'password' => [
            'required' => 'Password is required.',
            'string' => 'The password must be a string.',
        ],
        'newPassword' => [
            'required' => 'New Password is required.',
            'min' => 'New Password must be at least 8 characters.',
            'string' => 'The new password must be a string.',
        ],
    ],

    'error_middleware' => [
        'error_custom' => 'Too many requests, please try again later.',
        'error_isAdmin' => 'Unauthorized',
    ],
];

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
    'review' => [
        'error-review-forbidden' =>  'You can only review a product after purchasing it and only once per product.',

    ],
    'product' => [
        'error-variant' =>  'Malformed variant.',
        'error-create.variant' =>  'Can not create variations',
        'error-failed-create.variant' =>  'Failed to create variation',
        'error-not-attribute' =>  'Could not find any attribute value',
    ],
    'post' => [
        'error-already-exists' =>  'The title or slug already exists. Please choose a different value.',
        'error-Could-not-found-post' => 'Could not found post',
        'error-delete-post' => 'An error occurred while deleting the post.',
        'error-restoring-post' => 'An error occurred while restoring the post.',
    ],
    'image' => [
        'error-image' =>  'Cannot create images.Maybe the image is in the wrong format!',
        'error-can-not-image' =>  'Cannot find any images',
        'error-no-image' =>  'No images uploaded',
    ],
    'group' => [
        'error-group' =>  'Group with this name already exists.',


    ],
    'attribute' => [
        'error-can-not-attribute' =>  'Cannot find any images',
    ],
    'sale' => [
        'error-can-not-sale' =>  'Can not create sale',
        'error-invalid-type' =>  'Invalid type.',
    ],
    'order' => [
        'error-order' =>  'Unauthorized',
        'error-provider-detail' =>  'Please provide more detail.',
        'error-cancelled-order' =>  'Order Cancelled Successfully!',
        'error-payment' =>  'Payment status updated successfully',
        'error-specific' =>  'Please provide specific reason.',
        'error-confirmed' =>  'Admin confirmed order',
        'error-delivered' =>  'Order is being delivered',
        'error-was-delivered' =>  'Order was delivered',
        'error-processing' =>  'Return order processing',
        'error-returned-processing' =>  'Order returned processing',
        'error-returned' =>  'Order is returned',
        'error-can-not-order' =>  'Cannot cancel order',
    ],
    'voucher' => [
        'error-voucher' =>  'Voucher is out of uses',

    ],
    'topic' => [
        'error-can-not' =>  'Cannot find topic',
        'error-restore' =>  'Cannot restore topic',
        'error-force-delete' =>  'Cannot force delete',
    ],
    'user' => [
        'error-user' =>  'Unauthorized',
        'error-email' =>  'The email have already been taken',
        'error-invalid' =>  'Invalid verify code',
        'error-register' =>  'Failed to register',
        'error-password' =>  'You have entered the wrong password too many times, please enter again after 5 minutes',
        'error-wrong-password' =>  'Wrong password',
        'error-current-password' =>  'Wrong current password',
        'error-can-not-email' =>  'Email not found in the system!',
        'error-wrong-verification' =>  'Wrong verification code',
        'error-could-not-create' =>  'Could not create user',
        'error-system' =>  'Error system!',
        'error-check-password' =>  'Please check your password again!',
        'error-logout' =>  'Successfully logged out',
        'error-password-success' =>  'Password successfully changed',
        'error-email-not-found' =>  'Not found email!',
        'error-code' =>  'Code sent to your email!',
        'error-password-reset' =>  'Password successfully reset!',
        'error-add-favorite' =>  'Add favorite product successfully!',
        'error-profile' =>  'Profile updated successfully!',
        'error-upload' =>  'Could not find any files to upload',

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

];

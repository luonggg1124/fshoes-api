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
];
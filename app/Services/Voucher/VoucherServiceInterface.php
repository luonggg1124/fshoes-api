<?php
namespace App\Services\Voucher;

interface VoucherServiceInterface {
    function getAll(array $params);
    function findById(int|string $id);
    function findByCode(int|string $code);
    function create(array $data, array $option = []);
    function update(int|string $id,array $data, array $option = []);
    function delete(int|string $id);
    function restore(int|string $id);
    function forceDelete(int|string $id);
    function myVoucher();
}

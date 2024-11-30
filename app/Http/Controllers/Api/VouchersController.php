<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateVoucherRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Voucher\VoucherService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VouchersController extends Controller
{
    public function __construct(protected VoucherService $voucherService)
    {}
    public function index(Request $request)
    {
        return response()->json(
            $this->voucherService->getAll($request->all()) ,200
         );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateVoucherRequest $request)
    {
        return $this->voucherService->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       return $this->voucherService->findById($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return $this->voucherService->update($id , $request->all());

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->voucherService->delete($id);
    }

    public function restore(string $id)
    {
        return $this->voucherService->restore($id);
    }

    public function forceDelete(string $id)
    {
        return $this->voucherService->forceDelete($id);
    }

    public function getVoucherByCode(string $code)
    {
       return $this->voucherService->findByCode($code);
    }
    public function myVoucher(){
        return $this->voucherService->myVoucher();
    }
}

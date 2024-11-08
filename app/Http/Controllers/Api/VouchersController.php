<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateVoucherRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Voucher\VoucherService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class VouchersController extends Controller
{
    public function __construct(protected VoucherService $voucherService)
    {}
    public function index(Request $request)
    {
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('voucher.view'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }
        return response()->json(
            $this->voucherService->getAll($request->all()) ,200
         );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateVoucherRequest $request)
    {
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('voucher.create'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }
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
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('voucher.update'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }
        return $this->voucherService->update($id , $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('voucher.delete'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }
        return $this->voucherService->delete($id);
    }

    public function restore(string $id)
    {
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('voucher.restore'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }
        return $this->voucherService->restore($id);
    }

    public function forceDelete(string $id)
    {
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('voucher.forceDelete'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }
        return $this->voucherService->forceDelete($id);
    }

    public function getVoucherByCode(string $code)
    {
       return $this->voucherService->findByCode($code);
    }
}

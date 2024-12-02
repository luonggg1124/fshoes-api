<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateVoucherRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Voucher\VoucherService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

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
        try{
            $order =$this->voucherService->findByCode($code);
            return response()->json($order,200);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ],404);
        }catch(UnprocessableEntityHttpException $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ],422);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ],status: 500);
        }catch(\Throwable $th){
            logger()->error($th->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error system!',
            ],500);
        }
       
    }
    public function myVoucher(){
        return $this->voucherService->myVoucher();
    }
}

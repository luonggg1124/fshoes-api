<?php

namespace App\Services\Voucher;

use Mockery\Exception;
use App\Http\Resources\VoucherResource;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Voucher\VouchersRepositoryInterface;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class VoucherService implements VoucherServiceInterface
{

    public function __construct(protected VouchersRepositoryInterface $vouchersRepository)
    {
    }


    function getAll(array $params)
    {
        $voucher = $this->vouchersRepository->query()->withTrashed()->get();
        return VoucherResource::collection(
            $voucher
        );
    }

    function findById(int|string $id)
    {
        $voucher = $this->vouchersRepository->query()->withTrashed()->find($id);
        if ($voucher) return response()->json(VoucherResource::make($voucher) , 200);
        else return response()->json(["message"=>"Voucher Not Found"] , 404);

    }

    function findByCode(int|string $code)
    {
        $voucher = $this->vouchersRepository->query()->where('code', $code)->first();
        if(!$voucher) throw new ModelNotFoundException('Invalid Voucher Code');
        if($voucher->date_start > Carbon::now()){
            throw new UnprocessableEntityHttpException('Invalid voucher');
        } else if($voucher->date_end < Carbon::now()){
            throw new UnprocessableEntityHttpException('Voucher has expired');
        }
        
        if($voucher->quantity === 0) throw new UnprocessableEntityHttpException('The number of voucher uses has expired'); 
        $used = $voucher->whereHas('users', function ($query) {
            $query->whereIn('user_id', [request()->user()->id]);
        })->get();
        if($used) throw new UnprocessableEntityHttpException('You used the voucher');
        return VoucherResource::make($voucher);
    }

    function create(array $data, array $option = [])
    {
        try {
            $voucher = $this->vouchersRepository->create($data);
            return response()->json(VoucherResource::make($voucher) , 201);

        } catch (QueryException $exception) {
            if ($exception->getCode() == 23000) {
                return response()->json(["message" => "Voucher code already exists"], 400);
            }
            return response()->json(["message" => "Can't create new voucher"], 500);
        }
        catch (Exception $exception) {
            return response()->json(["message" => "Can't create new voucher"], 500);
        }
    }

    function update(int|string $id, array $data, array $option = [])
    {
       try {
        $voucher = $this->vouchersRepository->update($id, $data);

        return response()->json(["message"=>"Update successfully","voucher"=>VoucherResource::make($voucher)], 200);
    } catch (ModelNotFoundException $exception) {
        return response()->json(["message" => "Voucher not found"], 404);
    } catch (QueryException $exception) {
        if ($exception->getCode() == 23000) {
            return response()->json([
                "message" => "Update failed",
                "error" => $exception->getMessage(),
            ], 400);
        }

        return response()->json([
            "message" => "Update failed",
            "error" => $exception->getMessage(),
        ], 400);
    } catch (Exception $exception) {
        return response()->json(["message" => "An unexpected error occurred"], 500);
    }
    }

    function delete(int|string $id)
    {
        $voucher = $this->vouchersRepository->query()->find($id);
        if ($voucher) {
            $voucher->delete();
            return response()->json(["message" => "Soft deleted successfully"] , 200);
        } else return response()->json(["message" => "Voucher Not Found"] , 404);

    }

    function restore(int|string $id)
    {
        $voucher = $this->vouchersRepository->query()->withTrashed()->find($id);
        if ($voucher) {
            $voucher->restore();
            return response()->json(["message" => "Restore successfully"] , 200);
        } else return response()->json(["message" => "Voucher Not Found"] , 404);

    }

    function forceDelete(int|string $id)
    {
        $voucher = $this->vouchersRepository->query()->find($id);
        if ($voucher) {
            $voucher->forceDelete();
            return response()->json(["message" => "Force deleted successfully"] , 200);
        } else return response()->json(["message" => "Voucher Not Found"] , 404);

    }

    public function myVoucher(){
        $user = request()->user();
        $vouchers = $this->vouchersRepository->query()
        ->where('date_start','<=', Carbon::now())
        ->where('date_end', '<=', Carbon::now())->whereDoesntHave('users',function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        return VoucherResource::collection($vouchers);
    }
}

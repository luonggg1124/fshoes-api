<?php

namespace App\Http\Controllers\Api\Statistics;

use App\Http\Controllers\Controller;
use App\Services\Statistics\StatisticsServiceInterface;
use Exception;

class StatisticsController extends Controller
{
    public function __construct(
        protected StatisticsServiceInterface $statisticsService
    )
    {}
    public function index(){
       
       try {
        $statistics = $this->statisticsService->overall();
        return response()->json([
            'status' => true,
            'data' => $statistics
        ]);
       } catch (Exception $e) {
            logger()->error($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => __('messages.error-internal-server'),
            ],500);
       }catch(\Throwable $th){
        logger()->error($th->getMessage());
            return response()->json([
                'status' => false,
                'message' => __('messages.statiscs.error-statiscs'),
            ],500);
       }
    }
    public function forDiagram(){
       
        try {
         $statistics = $this->statisticsService->ordersForDiagram();
         return response()->json([
             'status' => true,
             'data' => $statistics
         ]);
        } catch (Exception $e) {
             logger()->error($e->getMessage());
             return response()->json([
                 'status' => false,
                 'message' => __('messages.error-internal-server'),
             ],500);
        }catch(\Throwable $th){
         logger()->error($th->getMessage());
             return response()->json([
                 'status' => false,
                 'message' => __('messages.statiscs.error-statiscs'),
             ],500);
        }
     }
     public function bestSellingProduct(){
       
        try {
         $statistics = $this->statisticsService->productBestSelling();
         return response()->json([
             'status' => true,
             'data' => $statistics
         ]);
        } catch (Exception $e) {
             logger()->error($e->getMessage());
             return response()->json([
                 'status' => false,
                 'message' => __('messages.error-internal-server'),
             ],500);
        }catch(\Throwable $th){
         logger()->error($th->getMessage());
             return response()->json([
                 'status' => false,
                 'message' => __('messages.statiscs.error-statiscs'),
             ],500);
        }
     }

     public function revenueOfYear(){
        try {
            $statistics = $this->statisticsService->revenueOfYear();
            return response()->json([
                'status' => true,
                'data' => [...$statistics]
            ]);
           } catch (Exception $e) {
                logger()->error($e->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => __('messages.error-internal-server'),
                ],500);
           }catch(\Throwable $th){
            logger()->error($th->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => __('messages.statiscs.error-statiscs'),
                ],500);
           }
     }
     public function countWaitingConfirmOrders(){
        try {
            $statistics = $this->statisticsService->countWaitingConfirmOrders();
            return response()->json([
                'status' => true,
                'data' => $statistics
            ]);
           } catch (Exception $e) {
                logger()->error($e->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => __('messages.error-internal-server'),
                ],500);
           }catch(\Throwable $th){
            logger()->error($th->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => __('messages.statiscs.error-statiscs'),
                ],500);
           }
     }
}

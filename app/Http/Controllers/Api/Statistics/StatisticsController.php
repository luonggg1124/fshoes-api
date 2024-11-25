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
                'message' => 'Something went wrong!',
            ],500);
       }catch(\Throwable $th){
        logger()->error($th->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error system!',
            ],500);
       }
    }
    public function forDiagram(){
       
        try {
         $statistics = $this->statisticsService->forDiagram();
         return response()->json([
             'status' => true,
             'data' => $statistics
         ]);
        } catch (Exception $e) {
             logger()->error($e->getMessage());
             return response()->json([
                 'status' => false,
                 'message' => 'Something went wrong!',
             ],500);
        }catch(\Throwable $th){
         logger()->error($th->getMessage());
             return response()->json([
                 'status' => false,
                 'message' => 'Error system!',
             ],500);
        }
     }
}

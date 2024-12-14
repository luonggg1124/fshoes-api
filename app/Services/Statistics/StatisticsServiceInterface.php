<?php 
namespace App\Services\Statistics;


interface StatisticsServiceInterface{
    function overall();
    function ordersForDiagram();
    function productBestSelling();
    function revenueOfYear();
    function revenueOfMonths($month = 1,$year = null);
    function countWaitingConfirmOrders();
}
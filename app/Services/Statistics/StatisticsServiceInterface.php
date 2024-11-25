<?php 
namespace App\Services\Statistics;


interface StatisticsServiceInterface{
    function overall();
    function ordersForDiagram();
    function productBestSelling();
}
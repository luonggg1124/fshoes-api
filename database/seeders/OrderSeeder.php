<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all()->pluck('id');
        $user = User::query()->find(1);

        for($i = 1; $i <= 25; $i++){
           Order::query()->create([
                'user_id' => fake()->randomElement($users),
                'total_amount' => 1,
                'payment_method' => 'Banking',
                'payment_status' => 'paid',
                'shipping_method' => 'test',
                'shipping_cost' => 10000,
                'amount_collected' => 100000,
                'receiver_full_name' => $user->name,
               'receiver_email'=>"ok",
                'address' => 'VietNam',
                'phone' => '0123456789',
                'city' => 'Hanoi',
                'country' => 'VietNam',
                'status' => '1',
                'created_at' => Carbon::now()->subDays(random_int(5,25)),
                'updated_at' => Carbon::now()
            ]);
        }
        $orders = Order::all();
        foreach ($orders as $order){
            $products = Product::all()->pluck('id');
            $total = 0;
            foreach ($products as $p) {
                $pro = Product::query()->find(random_int(1,count($products)));
                $orderDetail = OrderDetails::query()->create([
                    'order_id' => $order->id,
                    'product_variation_id' => $pro->variations()->first()->id ?? null,
                    'product_id' => $pro->variations()->first()->product->id,
                    'price' => $pro->variations()->first()->price ?? $pro->price,
                    'quantity' => 1,
                    'total_amount' => $pro->variations()->first()->price,

                ]);
                $total += $orderDetail->total_amount;
            }
            $order->total_amount = $total;
            $order->amount_collected = $order->total_amount + $order->shipping_cost;
            $order->save();
        }
    }
}

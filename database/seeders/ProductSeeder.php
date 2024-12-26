<?php

namespace Database\Seeders;


use App\Models\Image;
use App\Models\Product;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // php artisan db:seed --class=ProductSeeder
        $data = [
            [
                'name' => 'Giày Gazelle',
                'price' => '900000',
                'categories' => [3, 11,14],
                'images' => [
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/4c2b333cf2e2415fa73ce68f7761bb1c_9366/Giay_Gazelle_JQ5977_01_00_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/d6a521e3fe0846978cac9205cecdf736_9366/Giay_Gazelle_JQ5977_02_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/b62dd27a0b8345e59e4772ae39321e25_9366/Giay_Gazelle_JQ5977_03_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/257a821127644f2ca5a385ef20d90718_9366/Giay_Gazelle_JQ5977_04_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/07705a238b884b048e57190fdce53ffb_9366/Giay_Gazelle_JQ5977_05_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/5b00602caf4641b7b8d1800f0fc86479_9366/Giay_Gazelle_JQ5977_06_standard.jpg',
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '700000',
                
            ],
            [
                'name' => 'Giày Gazelle Bold x Liberty London',
                'price' => '900000',
                'categories' => [3, 11,14],
                'images' => [
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/17f4ce78feaa4605873b8af733aa151c_9366/Giay_Gazelle_Bold_x_Liberty_London_DJen_JI2572_01_00_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/9fba5faf30f04b3abf43e6edced43009_9366/Giay_Gazelle_Bold_x_Liberty_London_DJen_JI2572_02_standard_hover.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/7035072aa79f4cdf98021fc0f499b033_9366/Giay_Gazelle_Bold_x_Liberty_London_DJen_JI2572_03_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/bd4838de80774422ad88e454efb45731_9366/Giay_Gazelle_Bold_x_Liberty_London_DJen_JI2572_04_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/b0010979f461485199b35080b085f36a_9366/Giay_Gazelle_Bold_x_Liberty_London_DJen_JI2572_05_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/812a0e44887d4eff8921acf963e53c43_9366/Giay_Gazelle_Bold_x_Liberty_London_DJen_JI2572_06_standard.jpg'
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '2700000',
                
            ],
            [
                'name' => 'Giày adidas Superstar',
                'price' => '600000',
                'categories' => [2, 10,1,5,14],
                'images' => [
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/21915d00539b4bbca35fc5e582dab615_9366/Giay_adidas_Superstar_trang_JR8036_01_00_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/42dba060e0004dcdabf5268e8c95b374_9366/Giay_adidas_Superstar_trang_JR8036_02_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/6e911b2da391428380292f2d57c6db73_9366/Giay_adidas_Superstar_trang_JR8036_03_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/b3a633e7193c4c95a1e5ad5bea529d84_9366/Giay_adidas_Superstar_trang_JR8036_04_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/c5936e882b954644a846e3fe361457c3_9366/Giay_adidas_Superstar_trang_JR8036_05_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/60d6c6a64bdf47fe8db85ff09abfc67c_9366/Giay_adidas_Superstar_trang_JR8036_06_standard.jpg'
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '200000',
                
            ],
            [
                'name' => 'Giày Dame 9',
                'price' => '1200000',
                'categories' => [2, 10,1,7,14],
                'images' => [
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/8b56cea1b837482e90980058b927ece7_9366/Giay_Dame_9_Mau_vang_JH6627_01_00_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/0aec0c48d6c047499b7a606d589311f8_9366/Giay_Dame_9_Mau_vang_JH6627_02_standard_hover_hover.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/810d9a9e445e443c89a43420e5acdf0d_9366/Giay_Dame_9_Mau_vang_JH6627_03_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/9f3545bf4a5d42cca646c493afc58467_9366/Giay_Dame_9_Mau_vang_JH6627_04_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/16b8ab417b194deb89b5222e7fea63b3_9366/Giay_Dame_9_Mau_vang_JH6627_05_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/deadc42c00944e1db7a457aa1420653d_9366/Giay_Dame_9_Mau_vang_JH6627_06_standard.jpg',
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '600000',
                
            ],
            [
                'name' => 'Giày Superstar II Trẻ Em',
                'price' => '900000',
                'categories' => [1,4,14],
                'images' => [
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/7e9cf00f0a4641e0add96f6e4cc1348e_9366/Giay_Superstar_II_Tre_Em_trang_JR8084_01_00_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/991d72fd7ddf467a8d935fe4305d92b9_9366/Giay_Superstar_II_Tre_Em_trang_JR8084_02_standard_hover.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/5f2db4cfb09146b0a9fff273c83c2553_9366/Giay_Superstar_II_Tre_Em_trang_JR8084_03_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/169d5372c2ca4c16bd86f1f26d69ff79_9366/Giay_Superstar_II_Tre_Em_trang_JR8084_04_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/f363584569584571851e7305835e2443_9366/Giay_Superstar_II_Tre_Em_trang_JR8084_05_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/4d0f4d1083a94d8584be41f3e9ed915f_9366/Giay_Superstar_II_Tre_Em_trang_JR8084_06_standard.jpg'
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '600000',
                
            ],
            [
                'name' => 'ANTHONY EDWARDS 1 LOW',
                'price' => '900000',
                'categories' => [1,2,12,7,14],
                'images' => [
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/de468b8c392246c9b9a53d45606d50c6_9366/ANTHONY_EDWARDS_1_LOW_Mau_xanh_da_troi_JQ6139_01_00_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/48596679dfc9412d83f798897e415583_9366/ANTHONY_EDWARDS_1_LOW_Mau_xanh_da_troi_JQ6139_02_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/52d0e5a1491b43599e377d1c6cfcb2ae_9366/ANTHONY_EDWARDS_1_LOW_Mau_xanh_da_troi_JQ6139_03_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/45ce6263d1fe40a6bdce49701189ab7b_9366/ANTHONY_EDWARDS_1_LOW_Mau_xanh_da_troi_JQ6139_04_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/c0b28016b03442258ffca13c76064b09_9366/ANTHONY_EDWARDS_1_LOW_Mau_xanh_da_troi_JQ6139_05_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/df17cec0ce39450dba58657e246d7be0_9366/ANTHONY_EDWARDS_1_LOW_Mau_xanh_da_troi_JQ6139_06_standard.jpg'
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '600000',
                
            ],
            [
                'name' => 'ATHLETICS II BASKETBALL',
                'price' => '1500000',
                'categories' => [1,2,12,7,14],
                'images' => [
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/44224e4a029b48aca1fd01b14ab8543b_9366/ATHLETICS_II_BASKETBALL_nau_JS0977_HM1.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/9384a5db0d9f4d3eb1fd6383f15644c6_9366/ATHLETICS_II_BASKETBALL_nau_JS0977_HM3_hover.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/22c1f01ded0f4080b9c76cb9269532ef_9366/ATHLETICS_II_BASKETBALL_nau_JS0977_HM4.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/92d006a55603402992b3e09edd1f339f_9366/ATHLETICS_II_BASKETBALL_nau_JS0977_HM5.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/51672e82226a4ad88732ad523bcf17b5_9366/ATHLETICS_II_BASKETBALL_nau_JS0977_HM6.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/d830f74c3d0d4a75b69926f897bae33d_9366/ATHLETICS_II_BASKETBALL_nau_JS0977_HM7.jpg'
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '900000',
                
            ],
            [
                'name' => 'BW ARMY HARTCOPY',
                'price' => '1300000',
                'categories' => [1,3,11,14],
                'images' => [
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/c65e31a0fa3f438f9771eaa9d42c13b5_9366/BW_ARMY_HARTCOPY_trang_IE6271_01_00_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/50b204c01b8b4bb692daa3ff51ddad4b_9366/BW_ARMY_HARTCOPY_trang_IE6271_01_02_hover_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/4b241d65d61a4a9f8aef99fd5c7297ad_9366/BW_ARMY_HARTCOPY_trang_IE6271_02_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/de7c95373a6c4c528221806b1ba63390_9366/BW_ARMY_HARTCOPY_trang_IE6271_03_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/9f343da285c14ec99e888d0fd0a582b2_9366/BW_ARMY_HARTCOPY_trang_IE6271_04_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/b6604222f826459694f9f92d319462e1_9366/BW_ARMY_HARTCOPY_trang_IE6271_05_standard.jpg'
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '900000',
                
            ],
            [
                'name' => 'Giày Chạy Bộ Supernova Stride 2',
                'price' => '1200000',
                'categories' => [1,3,11,14],
                'images' => [
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/3a1e8aae58b747d9ab033257133431a1_9366/Giay_Chay_Bo_Supernova_Stride_2_DJen_IG2169_01_00_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/677d90393966478586a936e088435d74_9366/Giay_Chay_Bo_Supernova_Stride_2_DJen_IG2169_02_standard_hover_hover_hover_hover_hover_hover.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/93d1458a22784bbdbaee6ea7742187da_9366/Giay_Chay_Bo_Supernova_Stride_2_DJen_IG2169_03_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/8c5946f32262457e9576365ee54be562_9366/Giay_Chay_Bo_Supernova_Stride_2_DJen_IG2169_04_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/1c6abc25421c4e839eee59f9a77b76cf_9366/Giay_Chay_Bo_Supernova_Stride_2_DJen_IG2169_05_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/4220d4762e5d4704b4338a39bbc5cfc6_9366/Giay_Chay_Bo_Supernova_Stride_2_DJen_IG2169_06_standard.jpg'
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '900000',
                
            ],
            [
                'name' => 'Giày Chạy Bộ Supernova Rise 2',
                'price' => '1500000',
                'categories' => [1,2,3,10,11,7,14],
                'images' => [
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/752f408bba354fdbb7840c6b88b55dde_9366/Giay_Chay_Bo_Supernova_Rise_2_Hong_IH8702_01_00_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/02a6c285e2f249be95807396f0ea236d_9366/Giay_Chay_Bo_Supernova_Rise_2_Hong_IH8702_02_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/50b98abca6bd40be9aba789b0262dbb8_9366/Giay_Chay_Bo_Supernova_Rise_2_Hong_IH8702_03_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/e6026abdab154ef199217be14434b45b_9366/Giay_Chay_Bo_Supernova_Rise_2_Hong_IH8702_04_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/9e9256bd4536493ea5b6b6010869c3b3_9366/Giay_Chay_Bo_Supernova_Rise_2_Hong_IH8702_05_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/49dcb43470ca465fa6ea5b9b9cedd89c_9366/Giay_Chay_Bo_Supernova_Rise_2_Hong_IH8702_06_standard.jpg',
                    'https://assets.adidas.com/images/w_600,f_auto,q_auto/a384aeed6a284662a8b399af8f93d32b_9366/Giay_Chay_Bo_Supernova_Rise_2_Hong_IH8702_09_standard.jpg'
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '900000',
                
            ],
            [
                'name' => 'Nike Air Max Nuaxis',
                'price' => '650000',
                'categories' => [3,5,6,7,11,13],
                'images' => [
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/367333d4-baa0-481e-ad35-fa35dfaceb7e/W+NIKE+AIR+MAX+NUAXIS.png',
                     'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/6ed2d66d-970f-435e-896e-115867f3175a/W+NIKE+AIR+MAX+NUAXIS.png',
                     'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/e2d9ae76-1d55-4879-b9d2-7359d06ac4fc/W+NIKE+AIR+MAX+NUAXIS.png',
                     'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/4ab21608-3332-4971-b5e0-9168878e4ea9/W+NIKE+AIR+MAX+NUAXIS.png',
                     'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/24e522e1-a75c-4fa0-9b06-0b91ab054864/W+NIKE+AIR+MAX+NUAXIS.png',
                     'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/67eca3b0-c12d-4438-84d0-92d71e264eeb/W+NIKE+AIR+MAX+NUAXIS.png',
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '650000',
                
            ],
            [
                'name' => 'Nike Air Force 1 Shadow',
                'price' => '380000',
                'categories' => [3,5,6,7,11,13],
                'images' => [
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/4761e8ec-c273-4ccf-9cc4-2590ac85816b/W+AF1+SHADOW.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/4805a98c-30e9-4689-a785-0be6536dd328/W+AF1+SHADOW.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/3a8aed99-2b4d-4f57-976e-041698daac8e/W+AF1+SHADOW.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/75c42a17-4b70-4552-8fdf-6ae3aef89c98/W+AF1+SHADOW.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/c66d9720-ce83-464d-bd02-aeea51d32b6d/W+AF1+SHADOW.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/46f007bf-6c77-4bed-8c75-76746a4b8a9d/W+AF1+SHADOW.png',
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '380000',
                
            ],
            [
                'name' => 'Nike Air Force 1 Mid 07',
                'price' => '320000',
                'categories' => [2,5,6,7,11,8,9],
                'images' => [
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/04421d64-7214-4718-ba73-93e6d1447bac/AIR+FORCE+1+MID+%2707.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/0c249def-bb46-484b-a2ef-50417fe13123/AIR+FORCE+1+MID+%2707.png',
    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/fbb1cb50-cda2-4809-9d96-f7c56c7ba379/AIR+FORCE+1+MID+%2707.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/c9e9e7fe-ca47-4e18-b3f6-8fcd660ce913/AIR+FORCE+1+MID+%2707.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/7992dea9-25ab-40a5-a923-4fba6e801c3b/AIR+FORCE+1+MID+%2707.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/0a0e2493-cb0c-4b5b-9627-a4afc9a42428/AIR+FORCE+1+MID+%2707.png',
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '320000',
                
            ],
            [
                'name' => 'Air Jordan 1 Low',
                'price' => '350000',
                'categories' => [2,5,6,7,11,8,9],
                'images' => [
                   'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco,u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/1c0c434c-9802-4556-89c7-a8600b2828d8/AIR+JORDAN+1+LOW.png',
                'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco,u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/7ce75f02-661e-4726-a940-bdcaff08caab/AIR+JORDAN+1+LOW.png',
                'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco,u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/fe657d71-ee16-43ca-b7de-3e9313b288a1/AIR+JORDAN+1+LOW.png',
                'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco,u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/a21d548e-eb3d-4a1b-a086-fffc780f0e0a/AIR+JORDAN+1+LOW.png',
                'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco,u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/398bd28b-18d5-4a79-9433-a3b80a564dc7/AIR+JORDAN+1+LOW.png',
                'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco,u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/ade1053d-9b68-49dc-9eeb-278b29daa5d7/AIR+JORDAN+1+LOW.png',
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '350000',
                
            ],
            [
                'name' => 'Nike Cortez EasyOn',
                'price' => '125000',
                'categories' => [4,5,6,7,11,8,9],
                'images' => [
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/97d483f7-5265-4059-bae0-94a50665aa09/NIKE+KIDS+CORTEZ+EASYON+%28TDV%29.png',
    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/87dc4c63-675b-4bee-aed6-0b79fc439336/NIKE+KIDS+CORTEZ+EASYON+%28TDV%29.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/4a6ce09f-4c9b-4cc8-9548-c80c0a3cf3a9/NIKE+KIDS+CORTEZ+EASYON+%28TDV%29.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/c0dea8e8-8e5f-475a-84b4-48683ca514be/NIKE+KIDS+CORTEZ+EASYON+%28TDV%29.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/6ebcc2e6-aa71-47e0-9188-5ac11f5921c6/NIKE+KIDS+CORTEZ+EASYON+%28TDV%29.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/e9a77f33-3634-4c67-a8c8-4d9abe79baf5/NIKE+KIDS+CORTEZ+EASYON+%28TDV%29.png',
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '125000',
                
            ],
            [
                'name' => 'Nike Revolution 7',
                'price' => '325000',
                'categories' => [4,5,6,7,11,8,9],
                'images' => [
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/5ce1294a-92e2-409c-ad02-72d6fbc32a80/NIKE+REVOLUTION+7+%28TDV%29.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/de23b9f3-eccd-493a-84ea-e2ecd304fbc4/NIKE+REVOLUTION+7+%28TDV%29.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/9e8c0bb6-3e51-4a4e-a1c1-238cbc6cffa3/NIKE+REVOLUTION+7+%28TDV%29.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/3687b0c1-0039-424c-9897-538ceb4bc569/NIKE+REVOLUTION+7+%28TDV%29.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/2b34e1e6-3c73-4b66-84e2-d0f37a1fef37/NIKE+REVOLUTION+7+%28TDV%29.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/84b9b5ca-ffbf-4b73-a352-acf218329b62/NIKE+REVOLUTION+7+%28TDV%29.png',
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '325000',
                
            ],
            [
                'name' => 'Air Jordan 1 Mid',
                'price' => '260000',
                'categories' => [13,5,6,7,11,8,9],
                'images' => [
                    'https://static.nike.com/a/images/t_default/u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/89b9c5f5-9049-422d-aa76-19ea5323ef58/WMNS+AIR+JORDAN+1+MID.png',
                    'https://static.nike.com/a/images/t_default/u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/385ea041-15f9-48cf-9f75-14673a46d517/WMNS+AIR+JORDAN+1+MID.png',
                    'https://static.nike.com/a/images/t_default/u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/d1f28ee2-522f-4561-b03c-7fd12d90a034/WMNS+AIR+JORDAN+1+MID.png',
                    'https://static.nike.com/a/images/t_default/u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/00ef30e4-5e80-49a6-9afc-3364216bcf57/WMNS+AIR+JORDAN+1+MID.png',
                    'https://static.nike.com/a/images/t_default/u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/27ac11e1-7ccb-4782-9054-b69656483cb2/WMNS+AIR+JORDAN+1+MID.png',
                    'https://static.nike.com/a/images/t_default/u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/dc9cdc97-dfed-4849-bbe5-62921c97a4cd/WMNS+AIR+JORDAN+1+MID.png'
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '260000',
                
            ],
            [
                'name' => 'Nike Interact Run',
                'price' => '180000',
                'categories' => [13,5,6,7,11,8,9],
                'images' => [
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/4f13f1f6-b1ff-4e27-b1ef-3c6fe35257cf/W+NIKE+INTERACT+RUN.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/20ee4b54-ad69-425e-8201-8ae880e66997/W+NIKE+INTERACT+RUN.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/f5d3d7d5-095c-4433-a1db-0ac884e39fa5/W+NIKE+INTERACT+RUN.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/65fa6d23-8649-4dcc-baf7-5ee336c27674/W+NIKE+INTERACT+RUN.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/882b16d0-42ab-487f-ae79-6147f3cbfd9a/W+NIKE+INTERACT+RUN.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/b8479227-0c51-48dc-bc26-98fda56e1f91/W+NIKE+INTERACT+RUN.png',
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '180000',
                
            ],
            [
                'name' => 'Nike Dunk Low Next Nature',
                'price' => '290000',
                'categories' => [13,5,6,7,11,8,9],
                'images' => [
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/9d76c763-efa8-4b69-9499-b095e7bec6f8/W+NIKE+DUNK+LOW+NEXT+NATURE.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/479c9884-f18f-4fc0-8033-01f3bf04c1cb/W+NIKE+DUNK+LOW+NEXT+NATURE.png',
    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/c0bd5239-852d-4156-8acb-a6c53f94f459/W+NIKE+DUNK+LOW+NEXT+NATURE.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/35741930-4238-4071-953b-9ced1fc79fe6/W+NIKE+DUNK+LOW+NEXT+NATURE.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/d468891b-5a88-4346-b047-5cf4dc4808ff/W+NIKE+DUNK+LOW+NEXT+NATURE.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/ecc491d7-6cd4-4c33-b49e-0d49679b32e2/W+NIKE+DUNK+LOW+NEXT+NATURE.png',
                    ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '290000',
                
            ],
            [
                'name' => 'Nike Killshot 2 Leather',
                'price' => '270000',
                'categories' => [2,5,6,7,11,8,9],
                'images' => [
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/73627869-5239-40d9-b41f-5dcdaba413e4/KILLSHOT+2+LEATHER.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/645d7b2c-d9b7-437c-b236-9777921997ab/KILLSHOT+2+LEATHER.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/93230cad-8b41-4849-b806-54f3a7d36329/KILLSHOT+2+LEATHER.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/4ea35e8b-c72f-419b-b0a1-1c5a3f8b9866/KILLSHOT+2+LEATHER.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/5845c969-a832-466e-bbf3-fbcaf9d108c6/KILLSHOT+2+LEATHER.png',
                    'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/2848e04b-8834-442a-a5f2-8f8218cd8acf/KILLSHOT+2+LEATHER.png',
                ],
                'description' => '',
                'short_description' => '',
                'stock_qty' => random_int(21, 100),
                'qty_sold' => random_int(0, 20),
                'import_price' => '270000',
                
            ],
        ];
        
        $listData = [
            ...$data
        ];
        foreach($listData as $data){
            $product = Product::create([
                'name' => $data['name'],
                'price' => $data['price'],
                'description' => $data['description'],
               'short_description' => $data['short_description'],
               'stock_qty' => $data['stock_qty'],
                'qty_sold' => $data['qty_sold'],
                
               'image_url' => $data['images'][0],
            ]);
            foreach($data['images'] as $i){
                $image = Image::create([
                    'url' => $i
                ]);
                $product->images()->attach($image->id);
            }
            $product->slug = Str::slug($product->name) . '.' . $product->id;
             $product->save();
            $product->categories()->attach($data['categories']);
        }
        // Product::factory(50)->create();
        // $allPs = Product::all();
        // foreach ($allPs as $p) {
        //     $p->slug = Str::slug($p->name) . '.' . $p->id;
        //     $p->save();
        // }
        // foreach (Product::query()->take(15)->get() as $product) {
        //     $product->categories()->attach([random_int(4,7),random_int(5,9)]);
        //     for ($i = 0; $i < 3; $i++) {
        //         $image = Image::factory()->create();
        //         DB::table('product_image')->insert([
        //             'product_id' => $product->id,
        //             'image_id' => $image->id,
        //         ]);
        //     }
        // }

    }
    
}
<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // php artisan db:seed --class=ImageSeeder
        
        $images = [
            'https://static.nike.com/a/images/t_default/9df887f6-b642-4788-9db0-8502f0be219f/AIR+ZOOM+ALPHAFLY+NEXT%25+3+EK.png',
            'https://static.nike.com/a/images/t_default/9df887f6-b642-4788-9db0-8502f0be219f/AIR+ZOOM+ALPHAFLY+NEXT%25+3+EK.png',
            'https://static.nike.com/a/images/t_default/7ef4090b-99cb-41d8-a074-a79998cba709/AIR+ZOOM+ALPHAFLY+NEXT%25+3+EK.png',
            'https://static.nike.com/a/images/t_default/5de3eaf4-7ad4-41b7-b8cb-7c242cb596da/AIR+ZOOM+ALPHAFLY+NEXT%25+3+EK.png',
            'https://static.nike.com/a/images/t_default/6129c48e-f75a-4388-8660-acb02e2dea17/AIR+ZOOM+ALPHAFLY+NEXT%25+3+EK.png',
            'https://static.nike.com/a/images/t_default/e8f3622c-63f9-460b-8ba2-974ace52fdd5/AIR+ZOOM+ALPHAFLY+NEXT%25+3+EK.png',
            'https://static.nike.com/a/images/t_default/8f3e7133-a9c7-4cca-905a-4ea8154e0888/AIR+ZOOM+ALPHAFLY+NEXT%25+3+EK.png',
            'https://static.nike.com/a/images/t_default/ccdb1bd3-15b5-4061-83b2-893d4d41c1bc/AIR+ZOOM+ALPHAFLY+NEXT%25+3+EK.png',
            'https://static.nike.com/a/images/t_default/879e026f-9724-47c4-867d-71f012783d0b/AIR+ZOOM+ALPHAFLY+NEXT%25+3+EK.png',
            'https://static.nike.com/a/images/t_default/24c147d0-96ef-4e7b-ba9e-d9fc0ad9f19a/AIR+ZOOM+ALPHAFLY+NEXT%25+3+PRM.png',
            'https://static.nike.com/a/images/t_default/08013b18-2d08-43eb-96c3-319c0272a6ea/AIR+ZOOM+ALPHAFLY+NEXT%25+3+PRM.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/3d5aaf55-e2c6-4b73-b981-812c887010fd/AIR+ZOOM+ALPHAFLY+NEXT%25+3+PRM.png',
            'https://static.nike.com/a/images/t_default/736f7293-6ff8-4749-8a52-019032dd9f15/AIR+ZOOM+ALPHAFLY+NEXT%25+3+PRM.png',
            'https://static.nike.com/a/images/t_default/25407aeb-3b0d-4812-a149-7d2d073bb4cc/AIR+ZOOM+ALPHAFLY+NEXT%25+3+PRM.png',
            'https://static.nike.com/a/images/t_default/0528c54e-771c-4f1a-9656-a23cf11077f3/AIR+ZOOM+ALPHAFLY+NEXT%25+3+PRM.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/9124fee5-bc71-4c6a-828b-bb78d628d670/AIR+ZOOM+ALPHAFLY+NEXT%25+3+PRM.png',
            'https://static.nike.com/a/images/t_default/0a9dfeca-8aa9-40b8-b0bd-88605590bece/AIR+ZOOM+ALPHAFLY+NEXT%25+3+PRM.png',
            'https://static.nike.com/a/images/t_default/24c147d0-96ef-4e7b-ba9e-d9fc0ad9f19a/AIR+ZOOM+ALPHAFLY+NEXT%25+3+PRM.png',
            'https://static.nike.com/a/images/t_default/08013b18-2d08-43eb-96c3-319c0272a6ea/AIR+ZOOM+ALPHAFLY+NEXT%25+3+PRM.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/3d5aaf55-e2c6-4b73-b981-812c887010fd/AIR+ZOOM+ALPHAFLY+NEXT%25+3+PRM.png',
            'https://static.nike.com/a/images/t_default/736f7293-6ff8-4749-8a52-019032dd9f15/AIR+ZOOM+ALPHAFLY+NEXT%25+3+PRM.png',
            'https://static.nike.com/a/images/t_default/25407aeb-3b0d-4812-a149-7d2d073bb4cc/AIR+ZOOM+ALPHAFLY+NEXT%25+3+PRM.png',
            'https://static.nike.com/a/images/t_default/0528c54e-771c-4f1a-9656-a23cf11077f3/AIR+ZOOM+ALPHAFLY+NEXT%25+3+PRM.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/9124fee5-bc71-4c6a-828b-bb78d628d670/AIR+ZOOM+ALPHAFLY+NEXT%25+3+PRM.png',
            'https://static.nike.com/a/images/t_default/0a9dfeca-8aa9-40b8-b0bd-88605590bece/AIR+ZOOM+ALPHAFLY+NEXT%25+3+PRM.png',
            'https://static.nike.com/a/images/t_default/396b20ca-f17f-4574-aab7-ef95c9bb1191/ZOOMX+VAPORFLY+NEXT%25+3+FK+PRM.png',
            'https://static.nike.com/a/images/t_default/97bc0dbc-44a7-4f94-890f-e85f248bbace/ZOOMX+VAPORFLY+NEXT%25+3+FK+PRM.png',
            'https://static.nike.com/a/images/t_default/97ac53e9-a106-4191-8925-f62dce9aff67/ZOOMX+VAPORFLY+NEXT%25+3+FK+PRM.png',
            'https://static.nike.com/a/images/t_default/0a359ff3-3ee0-428b-9190-f3bb81ccc07d/ZOOMX+VAPORFLY+NEXT%25+3+FK+PRM.png',
            'https://static.nike.com/a/images/t_default/ef95da43-0bb3-4271-900c-5db0e1facdc0/ZOOMX+VAPORFLY+NEXT%25+3+FK+PRM.png',
            'https://static.nike.com/a/images/t_default/952bdc72-8b51-4d7d-8836-7bc11d887c6a/ZOOMX+VAPORFLY+NEXT%25+3+FK+PRM.png',
            'https://static.nike.com/a/images/t_default/632bc37b-2a07-4baa-b206-c8a403e3b478/ZOOMX+VAPORFLY+NEXT%25+3+FK+PRM.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/83ebf9a2-547e-493a-ab33-144b46e0ed78/ZOOM+FLY+6+EK.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/fa484182-6325-4ece-8df5-66e0bb0d0c9c/ZOOM+FLY+6+EK.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/7dc36ab7-0f08-4683-af1a-ada7b4fa7eca/ZOOM+FLY+6+EK.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/4c807490-f4fb-42ac-82b2-cfb33ba04478/ZOOM+FLY+6+EK.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/c2ccbe71-8edd-4744-90fa-62a24f7bee84/ZOOM+FLY+6+EK.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/199553dd-77ec-4b8d-8497-dd60381a9dd1/ZOOM+FLY+6+EK.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/abc85e8d-18fc-4503-b830-6c98e0b1d74f/ZOOM+FLY+6+EK.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/84a803d6-f135-4cf6-9388-7393bde488e5/ZOOM+FLY+6+EK.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/c2469cf1-37af-48a6-a41b-e50b5fa43d12/ZOOM+FLY+6+EK.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/3a635c99-414e-4e0f-b734-d30e78d5e261/ZOOM+FLY+6+EK.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/103fb6b6-11c8-4280-bd31-12745d1fba92/ZOOM+FLY+6+EK.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/f7620b88-89b3-4d93-af57-036f25f5c319/ZOOM+FLY+6+EK.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/5cb2ff9f-b2cd-4d38-beb0-d12fe0e37598/NIKE+AIR+ZOOM+RIVAL+FLY+4.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/23962935-88b7-48b4-b01d-e361889a859b/NIKE+AIR+ZOOM+RIVAL+FLY+4.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/11a73cc4-35ef-43b3-91ba-5309c492b75f/NIKE+AIR+ZOOM+RIVAL+FLY+4.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/7d0058d5-d0de-48d9-9b6c-b864c0210054/NIKE+AIR+ZOOM+RIVAL+FLY+4.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/62e8fafa-45ff-4822-abad-5ab0738062d8/NIKE+AIR+ZOOM+RIVAL+FLY+4.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/973e69f8-a9ea-497f-a3fe-a10e0db68d12/NIKE+AIR+ZOOM+RIVAL+FLY+4.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/7f95b8b6-89f3-43b4-b618-688ea7862f42/NIKE+AIR+ZOOM+RIVAL+FLY+4.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/d196ad4b-d53c-4ff8-a054-0d9e4c5266d1/NIKE+AIR+ZOOM+RIVAL+FLY+4.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/c7d3b297-0ab7-47e5-bf14-da6aae432218/AIR+ZOOM+G.T.+CUT+ACADEMY+EP.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/83c09b09-b9b5-4a56-a2b2-89a4bd84f8cd/AIR+ZOOM+G.T.+CUT+ACADEMY+EP.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/8f3e60a1-3ca7-4d45-9101-dbdf1141454a/AIR+ZOOM+G.T.+CUT+ACADEMY+EP.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/e2cec9f8-73a9-41c9-b933-e48e1aae2f7c/AIR+ZOOM+G.T.+CUT+ACADEMY+EP.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/01afd7e2-9338-42cd-9ca9-df32e2e1a978/AIR+ZOOM+G.T.+CUT+ACADEMY+EP.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/51ffcc2f-3a12-43bd-8f3a-62984a6087ee/AIR+ZOOM+G.T.+CUT+ACADEMY+EP.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/a7f3b52b-b924-47e0-9012-b077f8f2f73b/AIR+ZOOM+G.T.+CUT+ACADEMY+EP.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/d482d99b-18a8-48c1-8008-9c4dcbf3e6ae/AIR+ZOOM+G.T.+CUT+ACADEMY+EP.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/c2c984e4-3d7a-433d-b4fa-3fd242ae3b98/G.T.+HUSTLE+ACADEMY+EP.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/f122506a-f3ea-4195-932b-1268368d54d5/G.T.+HUSTLE+ACADEMY+EP.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/281d24fd-c9a8-4322-b7e9-414b59378f1c/G.T.+HUSTLE+ACADEMY+EP.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/f885f474-d11a-4a38-b040-c15272fde15b/G.T.+HUSTLE+ACADEMY+EP.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/59c1a250-3461-4c2f-b1c1-1d3041b72480/G.T.+HUSTLE+ACADEMY+EP.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/cb214c6d-4ad9-43ae-b4c7-222f01fed340/G.T.+HUSTLE+ACADEMY+EP.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/d457eb13-4c0f-4f1c-ac3d-251a33be372f/G.T.+HUSTLE+ACADEMY+EP.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/97473dd5-e4ee-4fbf-a2da-e89f423a6a82/G.T.+HUSTLE+ACADEMY+EP.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/c9159264-6cbe-4c8c-8546-ed4b40a50231/AIR+MAX+1+%2786+OG+G+ESG+NRG.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/a4b697cf-769d-4c28-b33d-f21412d589d7/AIR+MAX+1+%2786+OG+G+ESG+NRG.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/c0c9b0d0-4d11-48a1-a529-2e4b243944a7/AIR+MAX+1+%2786+OG+G+ESG+NRG.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/48183f44-faa0-4da8-8d1b-d420851c60c1/AIR+MAX+1+%2786+OG+G+ESG+NRG.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/b7ede2a8-8199-4326-95b7-de535570b70d/AIR+MAX+1+%2786+OG+G+ESG+NRG.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/3eb0e938-5f90-4b81-b146-96d63b2db1b5/AIR+MAX+1+%2786+OG+G+ESG+NRG.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/11e3dce4-e6dd-4f97-a495-d6f2be4d226d/AIR+MAX+1+%2786+OG+G+ESG+NRG.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/f20a76eb-ba27-4efa-bd85-e24ab268f557/AIR+MAX+1+%2786+OG+G+ESG+NRG.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/db400a4b-7cc2-4b45-9a20-db7526c31221/AIR+MAX+1+%2786+OG+G+ESG+NRG.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/2dace559-a741-425d-884b-29db292c3555/AIR+MAX+1+%2786+OG+G+ESG+NRG.png'
        ];
        foreach($images as $img){
            Image::create([
                'url' => $img
            ]);
        }
    }
}

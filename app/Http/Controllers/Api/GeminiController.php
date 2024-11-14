<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Gemini\Data\Blob;
use Gemini\Enums\MimeType;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Http\Request;
class GeminiController extends Controller
{
    public function text(Request $request)
    {
        try{
            $result = Gemini::geminiPro()->generateContent([$request->question , new Blob(MimeType::TEXT_PLAIN , data:base64_encode( $request->question))]);
            return response()->json(["message"=>$result->text() ] , 200);
        }catch (\Exception $e){
            return response()->json(["message"=>$e->getMessage() ] , 500);
        }
    }

}

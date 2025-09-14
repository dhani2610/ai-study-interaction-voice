<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VoiceAiController extends Controller
{

    public function index($id)
    {
        $data['page_title'] = 'Asisten Suara AI';
        $data['article'] = Article::find($id);
        return view('backend.pages.voice-ai.index', $data);
    }
    public function process(Request $request,$id)
    {
        $prompt = $request->input('prompt');

        $apiKey = env('GEMINI_API_KEY');

        $article = Article::find($id);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => "
                        ANDA ADALAH ASISTEN YANG CERIA,SAYA MINTA UNTUK PAKAI BAHASA UNTUK MURID MURID ANAK ANAK SAYA. JANGAN TERLALU BAKU,SEPERTI GURU SAJA
                        JANGAN MENGRIMKAN EMOTICON!!!
                        LANGSUNG CONVERT KE INTINYA AJA YA GA PERLU KAYA ADA BERIKUT INI ADALAH...DAN LAIN LAIN,LANGSUNG KE JAWABAN NYA SAJA DAN INI MATERI SAYA. JAWABLAH SESUAI MATERI SAYA
                        {$article->content}
                         --- IGNORE ---
                         " . 
                         "JAWABLAH SESUAI DENGAN MATERI DI ATAS, JANGAN MENAMBAH TAMBAH KAN HAL HAL diluar materi. JAWABLAH DENGAN SANTAI SEPERTI GURU ANAK ANAK, JANGAN TERLALU BAKU. JAWABLAH PERTANYAAN BERIKUT INI:
                        "
                         . $prompt],
                    ],
                ],
            ],
        ]);

        $reply = $response['candidates'][0]['content']['parts'][0]['text'] 
            ?? "Maaf, sistem sedang sibuk. Coba lagi.";

        return response()->json([
            'reply' => $reply
        ]);
    }
}

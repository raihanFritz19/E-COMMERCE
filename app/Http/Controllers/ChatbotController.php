<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function webhook(Request $request)
    {
        $data = $request->all();

        // ambil chatId & pesan terakhir
        $chatId = $data['chatId'] ?? null;
        $message = strtolower($data['message']['text'] ?? '');

        if (!$chatId || !$message) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // logika chatbot sederhana
        $reply = "Maaf, saya belum paham. 😊";

        if (str_contains($message, 'halo') || str_contains($message, 'hi')) {
            $reply = "Halo, ada yang bisa saya bantu? 🙌";
        } elseif (str_contains($message, 'harga')) {
            $reply = "Harga produk kami mulai dari Rp50.000.";
        } elseif (str_contains($message, 'stok')) {
            $reply = "Stok produk kami selalu update, silakan cek di website ya!";
        }

        // kirim balasan ke Tawk.to REST API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.tawk.api_key'),
            'Content-Type'  => 'application/json',
        ])->post("https://api.tawk.to/conversation/{$chatId}/message", [
            'type' => 'text',
            'text' => $reply,
        ]);

        return response()->json([
            'status' => 'ok',
            'tawk_response' => $response->json()
        ]);
    }
}

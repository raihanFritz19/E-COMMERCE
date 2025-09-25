<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatLog; 

class ChatLogController extends Controller
{
    public function index()
    {
        $logs = ChatLog::latest()->paginate(20);
        return view('admin.chatlogs.index', compact('logs'));
    }

    public function import(Request $request)
    {
        $file = $request->file('csv');
        $rows = array_map('str_getcsv', file($file));
        $headers = array_shift($rows); 

        foreach ($rows as $row) {
            ChatLog::create([
                'user_message' => $row[0] ?? '',
                'bot_reply' => $row[1] ?? '',
                'source' => 'chatbase',
            ]);
        }

        return back()->with('success', 'Chat berhasil diimport.');
    }
}

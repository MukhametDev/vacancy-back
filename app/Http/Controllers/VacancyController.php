<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Vacancy::all();
    }

    public function publicIndex()
    {
        return Vacancy::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function respond(Request $request, Vacancy $vacancy)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'message' => 'required|string'
        ]);

        $text = "<b>🔔 Новый отклик на вакансию: {$vacancy->title}</b>\n"
            . "👤 Имя: {$data['name']}\n"
            . "📧 Email: {$data['email']}\n"
            . "📱 Телефон: {$data['phone']}\n"
            . "💬 Сообщение: {$data['message']}";

        $this->sendTelegram($text);

        return response()->json(['message' => 'Отклик успешно отправлен!']);
    }

    public function sendTelegram($message) {
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'company' => 'nullable|string',
            'description' => 'required|string',
            'skills' => 'nullable|string',
        ]);

        $vacancy = Vacancy::create([
            'title' => $request->title,
            'company' => $request->company,
            'description' => $request->description,
            'skills' => $request->skills,
            'user_id' => auth()->id(),
        ]);

        return response()->json($vacancy, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vacancy $vacancy)
    {
        return Vacancy::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vacancy $vacancy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vacancy $vacancy)
    {
        $vacancy->update($request->only('title','company', 'description', 'skills', 'additional_info'));
        return response()->json($vacancy);
    }

    public function getVacancy($id) {
        return Vacancy::findOrFail($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class QuotationController extends Controller
{
    public function getQuotation($date)
    {
        $formattedDate = DateTime::createFromFormat('d-m-Y', $date)->format('d/m/Y');
        // Получение данных котировок
        $url = "http://www.cbr.ru/scripts/XML_daily.asp?date_req=$formattedDate";
        $response = Http::get($url);

        if (!$response) {
            return response()->json(['error' => 'Data not found'], 404);
        }
        // Преобразование XML в массив
        $xml = simplexml_load_string($response);
        $json = json_encode($xml);
        $data = json_decode($json, true);
        // Возвращение данных котировок в JSON формате
        return response()->json($data);
    }
}
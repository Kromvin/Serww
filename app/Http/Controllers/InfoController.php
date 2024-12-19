<?php

namespace App\Http\Controllers;

use App\DTOs\ServerInfoDTO;
use App\DTOs\ClientInfoDTO;
use App\DTOs\DatabaseInfoDTO;
use Illuminate\Http\Request;

class InfoController extends Controller
{

    public function getServerInfo()
    {
        // Сохраняем результат phpinfo() в строку
        ob_start();
        phpinfo(INFO_GENERAL); // Вывод только общей информации
        $phpInfo = ob_get_clean();
    
        // Удаляем HTML-теги и очищаем специальные символы
        $cleanedPhpInfo = strip_tags($phpInfo);
    
        // Создаем DTO
        $dto = new ServerInfoDTO(
            phpVersion: phpversion(),
        );
    
        return response()->json($dto->toArray());
    }
    
    

    public function getClientInfo(Request $request)
    {
        $dto = new ClientInfoDTO(
            ip: $request->ip(),
            userAgent: $request->header('User-Agent', 'unknown')
        );

        return response()->json($dto->toArray());
    }

    public function getDatabaseInfo()
    {
        $dto = DatabaseInfoDTO::fromConfig();

        return response()->json($dto->toArray());
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MessageNotification;
use Illuminate\Http\Request;

class UtilityController extends Controller
{
    public function encryptData($data)
    {
        $key = env('LOCKKEY');

        $encryptedData = encryptWithKey($data, $key);

        return $encryptedData;
    }

    public function decryptData($data)
    {
        $key = env('LOCKKEY');

        $decryptedData = decryptWithKey($data, $key);

        return $decryptedData;
    }

    public function insertMessageNotifications(
        string $subject,
        string $message,
        string $sourceType,
        string $sourceId
        ){

        MessageNotification::create([
            'subject' => $subject,
            'message' => $message,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
        ]);

    }
    public function showNotification(MessageNotification $data)
    {

        $data->is_read = true;
        $data->save();

        return redirect()->route("{$data->source_type}.show", $data->source_id);
    }
}

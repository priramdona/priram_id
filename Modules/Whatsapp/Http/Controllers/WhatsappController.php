<?php

namespace Modules\Whatsapp\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SdkWhatsappWebMultiDevice\api\AppApi;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use SdkWhatsappWebMultiDevice\Api\SendApi;

use Illuminate\Support\Str;
use Modules\People\Entities\Customer;
use Modules\People\Entities\Supplier;

class WhatsappController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $apiInstance = new AppApi(new Client());

        try {

            $response = $apiInstance->appDevices();

            $body = json_decode($response, true);
            if (isset($body['code']) && $body['code'] === 'SUCCESS' && $body['message'] === 'Fetch device success') {
                $results = $body['results'];

                $name = $results[0]['name']; // "Munggi Priramdona"
                $device = $results[0]['device']; // "6281287841327:35@s.whatsapp.net"

                foreach ($results as $result){
                    $result = $result['name'];
                }
                // Mengembalikan data sebagai response atau diproses lebih lanjut

            } else {
                $result = response()->json([
                    'error' => 'Failed to fetch device data'
                ], 400);
            }

        } catch (Exception $e) {
            $result ='';
        }

        return view('whatsapp::index', compact(['result']));
    }
    public function waCekDevice(): bool
    {

        $apiInstance = new AppApi(new Client());

        try {

            $response = $apiInstance->appDevices();

            $body = json_decode($response, true);
            if (isset($body['code']) && $body['code'] === 'SUCCESS' && $body['message'] === 'Fetch device success') {
                return true; //$body['results'];
            } else {
                return false;
            }

        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function sendMessageImage(Request $request)
{
    $request->validate([
        'phone_number' => 'required|string',
        'caption' => 'nullable|string',
        'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:5120', // Validasi file hingga 5MB
    ]);

    $phone = $request->input('phone_number');
    $caption = $request->input('caption', '');
    $image = $request->file('image');
    $view_once = false;
    $compress = true;

    $client = new Client();
    $apiInstance = new SendApi($client);

    try {
        if ($request->hasFile('image')) {
        $response = $client->post('http://localhost:60001/send/image', [
            'multipart' => [
                [
                    'name'     => 'phone',
                    'contents' => $phone,
                ],
                [
                    'name'     => 'caption',
                    'contents' => $caption,
                ],
                [
                    'name'     => 'view_once',
                    'contents' => $view_once ? 'true' : 'false',
                ],
                [
                    'name'     => 'image',
                    'contents' => fopen($image->getPathname(), 'r'),
                    'filename' => $image->getClientOriginalName(),
                    'headers'  => [
                        'Content-Type' => $image->getMimeType(),
                    ]
                ],
                [
                    'name'     => 'compress',
                    'contents' => $compress ? 'true' : 'false',
                ],
            ],
        ]);
        }elseif ($request->hasFile('file')) {
            $file = $request->file('file');

            $result = $apiInstance->sendFile(
                $phone,
                $caption,
                $file->getPathname()
            );

            toast(__('controller.sent'), 'success');
            return redirect()->route('whatsapp.index');
        } else {
                $send_message_request = new \SdkWhatsappWebMultiDevice\Model\SendMessageRequest();
                $send_message_request->setPhone($phone);
                $send_message_request->setMessage($caption);

                $apiInstance->sendMessage($send_message_request);
        }
        toast(__('controller.sent'), 'success');
        return redirect()->route('whatsapp.index');
    } catch (Exception $e) {

        toast(__('controller.error'), 'error');
        return redirect()->route('whatsapp.index');
    }
}

public function broadcastMessage(Request $request)
{
    $request->validate([
        // 'phone_numbers' => 'required|string', // Diharapkan satu nomor per baris
        'caption' => 'nullable|string',
        'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // Validasi gambar
        'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:5120', // Validasi file hingga 5MB
    ]);

    if ($request->destination == 'custom') {
        $phoneNumbers = explode("\n", trim($request->input('phone_numbers'))); // Pecah setiap baris menjadi array nomor telepon
    }elseif($request->destination == '1'){
        $data = Customer::where('business_id', Auth::user()->business_id)
        ->pluck('customer_phone')
        ->map(function ($phone) {
            return trim($phone);
        })
        ->toArray();

        $phoneNumbers = $data;

    }else
    {
        $data = Supplier::where('business_id', Auth::user()->business_id)
        ->pluck('supplier_phone')
        ->map(function ($phone) {
            return trim($phone);
        })
        ->toArray();

        $phoneNumbers = $data;

    }


    $caption = $request->input('caption', '');
    $image = $request->file('image');
    $view_once = false;
    $compress = true;

    $client = new Client();
    $apiInstance = new SendApi($client);
    if ($phoneNumbers){
        foreach ($phoneNumbers as $phone) {
            $phone = trim($phone);

            try {
                if ($request->hasFile('image')) {
                    $response = $client->post('http://localhost:60001/send/image', [
                    'multipart' => [
                        [
                            'name'     => 'phone',
                            'contents' => $phone,
                        ],
                        [
                            'name'     => 'caption',
                            'contents' => $caption,
                        ],
                        [
                            'name'     => 'view_once',
                            'contents' => $view_once ? 'true' : 'false',
                        ],
                        [
                            'name'     => 'image',
                            'contents' => fopen($image->getPathname(), 'r'),
                            'filename' => $image->getClientOriginalName(),
                            'headers'  => [
                                'Content-Type' => $image->getMimeType(),
                            ]
                        ],
                        [
                            'name'     => 'compress',
                            'contents' => $compress ? 'true' : 'false',
                        ],
                    ],
                    ]);

                    toast(__('controller.sent'), 'success');

                } elseif ($request->hasFile('file')) {
                    $file = $request->file('file');

                    $result = $apiInstance->sendFile(
                        $phone,
                        $caption,
                        $file->getPathname()
                    );

                    toast(__('controller.sent'), 'success');
                } else {
                    $send_message_request = new \SdkWhatsappWebMultiDevice\Model\SendMessageRequest();
                    $send_message_request->setPhone($phone);
                    $send_message_request->setMessage($caption);

                    $result = $apiInstance->sendMessage($send_message_request);

                    toast(__('controller.sent'), 'success');

                }

            } catch (Exception $e) {

                    return response()->json(['success' => false, 'message' => 'Error sending file: ' . $e->getMessage()]);
                    toast(__('controller.error'), 'error');
                    return redirect()->route('whatsapp.index');}
        }
    }else{

        return response()->json(['success' => false, 'message' => 'Tidak ada destination']);
        toast(__('controller.error'), 'error');
        return redirect()->route('whatsapp.index');
    }

    return redirect()->route('whatsapp.index');

}
    public function sendMessageWa(string $msg)
    {

        $apiInstance = new SendApi(new Client());

        try {
            $dataMessage = [
                'phone'=> '6285290372340',
                'message' => $msg,
                'reply_message_id' => null// Str::orderedUuid()->toString()
            ];
            $send_message_request = new \SdkWhatsappWebMultiDevice\Model\SendMessageRequest($dataMessage); // \SdkWhatsappWebMultiDevice\Model\SendMessageRequest

            $response = $apiInstance->sendMessage($send_message_request);

            $body = json_decode($response, true);
            if (isset($body['code']) && $body['code'] === 'SUCCESS') {
                $results = 'connected'; //$body['results'];

                // $name = $results[0]['name']; // "Munggi Priramdona"
                // $device = $results[0]['device']; // "6281287841327:35@s.whatsapp.net"

                // foreach ($results as $result){
                //     $result = $result['name'];
                // }
                // Mengembalikan data sebagai response atau diproses lebih lanjut

            } else {
                return false;
            }

        } catch (Exception $e) {
            return false;
        }

        return true;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('whatsapp::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request): RedirectResponse
    // {
    //     //
    // }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('whatsapp::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('whatsapp::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id): RedirectResponse
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}

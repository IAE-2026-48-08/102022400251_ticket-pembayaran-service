<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Ticket;
use App\Models\User;

class CentralIntegrationController extends Controller
{
    public function index()
    {
        $ticket = Ticket::first(); 
        return view('central_integration', compact('ticket'));
    }

    public function login(Request $request)
    {
        $baseUrl = env('IAE_SSO_URL', 'https://iae-sso.virtualfri.id');
        $apiKey = $request->input('api_key', 'KEY-MHS-264');

        $response = Http::post($baseUrl . '/api/v1/auth/token', [
            'api_key' => $apiKey,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $token = $data['token'] ?? $data['data']['token'] ?? null;
            session(['jwt_token' => $token]);
            session(['jwt_login_type' => 'm2m']);
            session(['jwt_identifier' => $apiKey]);

            if ($token) {
                $parts = explode('.', $token);
                if (count($parts) === 3) {
                    $payloadJson = base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1]));
                    $payload = json_decode($payloadJson, true);

                    $user = User::updateOrCreate(
                        ['email' => $apiKey . '@mhs.iae.id'],
                        [
                            'name' => 'Student API Client (' . $apiKey . ')',
                            'password' => bcrypt('M2M_DUMMY_PASSWORD_' . uniqid()),
                            'role' => 'mahasiswa'
                        ]
                    );
                    auth()->login($user);
                }
            }

            return redirect()->back()->with('response_data', $data);
        }

        return redirect()->back()->with('response_data', [
            'error' => 'Gagal login SSO menggunakan API Key Mahasiswa',
            'status' => $response->status(),
            'details' => $response->json()
        ]);
    }

    public function audit(Request $request)
    {
        $baseUrl = env('IAE_SSO_URL', 'https://iae-sso.virtualfri.id');
        $teamId = env('MY_TEAM_ID', 'TEAM-12');
        $token = session('jwt_token');

        $xmlPayload = '<?xml version="1.0" encoding="UTF-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:iae="http://iae.central/audit">
    <soap:Body>
        <iae:AuditRequest>
            <iae:TeamID>' . $teamId . '</iae:TeamID>
            <iae:ActivityName>TicketTransactionCreated</iae:ActivityName>
            <iae:LogContent><![CDATA[{"order_id":"' . $request->order_id . '","seat":"' . $request->seat . '"}]]></iae:LogContent>
        </iae:AuditRequest>
    </soap:Body>
</soap:Envelope>';

        $requestBuilder = Http::withBody($xmlPayload, 'text/xml');
        if ($token) {
            $requestBuilder = $requestBuilder->withToken($token);
        }

        $response = $requestBuilder->post($baseUrl . '/soap/v1/audit');
        $rawResponse = $response->body();

        $receiptNumber = null;
        if (preg_match('/<iae:ReceiptNumber>(.*?)<\/iae:ReceiptNumber>/', $rawResponse, $matches)) {
            $receiptNumber = $matches[1];
        }

        if ($receiptNumber && $request->order_id) {
            $ticket = Ticket::find($request->order_id);
            if ($ticket) {
                $ticket->update([
                    'receipt_number' => $receiptNumber,
                    'status' => 'AUDITED'
                ]);
            }
        }

        return redirect()->back()->with('response_data', [
            'info' => 'SOAP XML Sent & Audited!',
            'status_code' => $response->status(),
            'extracted_receipt' => $receiptNumber ?? 'Not found in response',
            'raw_response' => $rawResponse
        ]);
    }

    public function publish(Request $request)
    {
        $baseUrl = env('IAE_SSO_URL', 'https://iae-sso.virtualfri.id');
        $token = session('jwt_token');

        if (!$token) {
            return redirect()->back()->with('response_data', [
                'error' => 'Anda belum login SSO, silakan lakukan otentikasi terlebih dahulu!'
            ]);
        }

        $payload = json_decode($request->message, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->with('response_data', [
                'error' => 'Format JSON pesan tidak valid!',
                'message' => $request->message
            ]);
        }

        $response = Http::withToken($token)->post($baseUrl . '/api/v1/messages/publish', [
            'exchange' => 'iae.central.exchange',
            'routing_key' => 'ticket.purchased', 
            'payload' => $payload
        ]);

        return redirect()->back()->with('response_data', [
            'info' => 'Published to RabbitMQ via Central Server!',
            'status_code' => $response->status(),
            'response' => $response->json()
        ]);
    }
}

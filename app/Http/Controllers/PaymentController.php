<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    private $apiKey;
    private $privateKey;
    private $merchantCode;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('tripay.api_key');
        $this->privateKey = config('tripay.private_key');
        $this->merchantCode = config('tripay.merchant_code');
        $this->apiUrl = config('tripay.api_url');
    }

    public function index()
    {
        $payments = Payment::latest()->paginate(10);
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $channels = $this->getPaymentChannels();
        return view('payments.create', compact('channels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'payment_method' => 'required|string',
        ]);

        $merchantRef = 'INV-' . time();
        $amount = $request->amount;

        $signature = hash_hmac('sha256', $this->merchantCode . $merchantRef . $amount, $this->privateKey);

        $data = [
            'method' => $request->payment_method,
            'merchant_ref' => $merchantRef,
            'amount' => $amount,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'order_items' => [
                [
                    'name' => 'Pembayaran Invoice',
                    'price' => $amount,
                    'quantity' => 1
                ]
            ],
            'signature' => $signature
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey
        ])->post($this->apiUrl . '/transaction/create', $data);

        if ($response->successful()) {
            $tripayResponse = $response->json();

            $payment = Payment::create([
                'merchant_ref' => $merchantRef,
                'tripay_reference' => $tripayResponse['data']['reference'],
                'method' => $request->payment_method,
                'amount' => $amount,
                'status' => 'pending',
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'order_items' => json_encode($data['order_items']),
            ]);

            return redirect()->route('payments.show', $payment);
        } else {
            return back()->withErrors('Gagal membuat transaksi. Silakan coba lagi.');
        }
    }

    public function show(Payment $payment)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey
        ])->get($this->apiUrl . '/transaction/detail', [
            'reference' => $payment->tripay_reference
        ]);

        if ($response->successful()) {
            $tripayData = $response->json()['data'];
            return view('payments.detail', compact('payment', 'tripayData'));
        } else {
            return back()->withErrors('Gagal mengambil detail transaksi.');
        }
    }

    public function callback(Request $request)
    {
        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
        $json = $request->getContent();

        $signature = hash_hmac('sha256', $json, $this->privateKey);

        if ($signature !== (string) $callbackSignature) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid signature',
            ]);
        }

        $data = json_decode($json);

        $uniqueRef = $data->merchant_ref;
        $status = strtolower($data->status);

        $payment = Payment::where('merchant_ref', $uniqueRef)->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'No order found',
            ]);
        }

        switch ($status) {
            case 'paid':
                $payment->update(['status' => 'paid', 'paid_at' => now()]);
                break;
            case 'expired':
                $payment->update(['status' => 'expired']);
                break;
            case 'failed':
                $payment->update(['status' => 'failed']);
                break;
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Unrecognized status',
                ]);
        }

        return response()->json(['success' => true]);
    }

    private function getPaymentChannels()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey
        ])->get($this->apiUrl . '/merchant/payment-channel');

        \Log::info('Tripay API Response:', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        return [];
    }

    public function checkStatus(Payment $payment)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey
        ])->get($this->apiUrl . '/transaction/detail', [
            'reference' => $payment->tripay_reference
        ]);

        if ($response->successful()) {
            $tripayData = $response->json()['data'];
            $newStatus = strtolower($tripayData['status']);

            if ($newStatus !== $payment->status) {
                $payment->update([
                    'status' => $newStatus,
                    'paid_at' => $newStatus === 'paid' ? now() : null
                ]);
            }

            return redirect()->route('payments.show', $payment)->with('success', 'Status pembayaran telah diperbarui.');
        }

        return redirect()->route('payments.show', $payment)->with('error', 'Gagal memeriksa status pembayaran.');
    }
}

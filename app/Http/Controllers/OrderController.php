<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;

class OrderController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    /**
     * Tampilkan halaman checkout
     */
    public function checkout()
    {
        $user = Auth::user();
        $cart = Order::cart($user->id)->with('details.product')->first();

        if (!$cart || $cart->details->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Get available shipping methods (example)
        $shippingMethods = [
            ['code' => 'jne', 'name' => 'JNE'],
            ['code' => 'pos', 'name' => 'POS Indonesia'],
            ['code' => 'tiki', 'name' => 'TIKI']
        ];

        // Get payment methods (example)
        // $paymentMethods = [
        //     ['code' => 'bank_transfer', 'name' => 'Bank Transfer'],
        //     ['code' => 'credit_card', 'name' => 'Credit Card'],
        //     ['code' => 'gopay', 'name' => 'GoPay'],
        //     ['code' => 'qris', 'name' => 'QRIS']
        // ];

        $paymentMethods = [
            [
                'code' => 'midtrans',
                'name' => 'Pay with Midtrans'
            ]
        ];

        $title = 'Checkout';
        return view('pages.orders.checkout', compact('title', 'cart', 'shippingMethods', 'paymentMethods'));
    }

    /**
     * Process checkout order
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|max:255',
            'courier' => 'required|string|max:255',
            'courier_service' => 'required|string|max:255',
            'shipping_cost' => 'required|numeric',
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
        ]);

        $user = Auth::user();

        // Get current cart
        $cart = Order::cart($user->id)->first();

        if (!$cart || $cart->details->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        DB::beginTransaction();

        try {
            // Update cart to become an order
            $cart->update([
                'payment_method' => $request->payment_method,
                'shipping_cost' => $request->shipping_cost,
                'grand_total' => $cart->total_amount + $request->shipping_cost,
                'status' => 'pending',
                'recipient_name' => $request->recipient_name,
                'phone_number' => $request->phone_number,
                'shipping_address' => $request->shipping_address,
                'province' => $request->province,
                'city' => $request->city,
                'district' => $request->district,
                'postal_code' => $request->postal_code,
                'courier' => $request->courier,
                'courier_service' => $request->courier_service,
                'shipping_status' => 'preparing',
                'notes' => $request->notes ?? null
            ]);

            // Prepare order items for Midtrans
            $items = [];
            foreach ($cart->details as $detail) {
                $items[] = [
                    'id' => $detail->product_id,
                    'price' => $detail->price,
                    'quantity' => $detail->quantity,
                    'name' => substr($detail->product->name, 0, 50), // Midtrans has character limits
                ];

                $product = $detail->product;
                $product->decrement('stock', $detail->quantity);
            }

            // Tambahkan item untuk shipping
            $items[] = [
                'id' => 'SHIPPING-' . $request->courier,
                'price' => $request->shipping_cost,
                'quantity' => 1,
                'name' => 'Shipping Cost (' . $request->courier . ' - ' . $request->courier_service . ')',
            ];

            // Set up Midtrans transaction parameters
            $transactionDetails = [
                'order_id' => 'ORDER-' . $cart->id . '-' . time(),
                'gross_amount' => $cart->grand_total,
            ];

            $billingAddress = [
                'first_name' => $user->name,
                'last_name' => '',
                'email' => $user->email,
                'phone' => $request->phone_number,
                'address' => $request->shipping_address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'country_code' => 'IDN'
            ];

            $customerDetails = [
                'first_name' => $user->name,
                'last_name' => '',
                'email' => $user->email,
                'phone' => $request->phone_number,
                'billing_address' => $billingAddress,
                'shipping_address' => $billingAddress
            ];

            // Setup payment options with Midtrans
            $snapToken = Snap::getSnapToken([
                'transaction_details' => $transactionDetails,
                'item_details' => $items,
                'customer_details' => $customerDetails
            ]);

            // Create payment transaction (placeholder for Midtrans implementation)
            $transaction = PaymentTransaction::create([
                'order_id' => $cart->id,
                'provider' => 'midtrans',
                'transaction_id' => 'ORDER-' . $cart->id . '-' . time(),
                'transaction_status' => 'pending',
                'snap_token' => $snapToken
            ]);

            DB::commit();

            // Return success response with snap token
            $title = 'Snap Token';
            return view('pages.orders.payment', [
                'title' => $title,
                'snapToken' => $snapToken,
                'order' => $cart,
                'clientKey' => config('services.midtrans.client_key')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }
    /**
     * Midtrans payment notification handler
     * Route: /payments/notification (POST)
     */
    public function handlePaymentNotification(Request $request)
    {
        try {
            $notification = new Notification();
            $orderId = $notification->order_id;
            $status = $notification->transaction_status;
            $type = $notification->payment_type;
            $fraud = $notification->fraud_status;

            // Extract the order ID from the Midtrans order ID format
            $explode = explode('-', $orderId);
            $realOrderId = $explode[1];

            // Find transaction and order
            $transaction = PaymentTransaction::where('transaction_id', $orderId)->first();

            if (!$transaction) {
                return response('Transaction not found', 404);
            }

            $order = Order::find($transaction->order_id);

            if (!$order) {
                return response('Order not found', 404);
            }

            // Update transaction data
            $transactionData = [
                'transaction_status' => $status,
                'payment_type' => $type,
            ];

            // Handle specific payment types
            if ($type == 'bank_transfer') {
                $vaNumber = isset($notification->va_numbers[0]->va_number)
                    ? $notification->va_numbers[0]->va_number : null;
                $bank = isset($notification->va_numbers[0]->bank)
                    ? $notification->va_numbers[0]->bank : null;

                $transactionData['va_number'] = $vaNumber;
                $transactionData['bank'] = $bank;
            } elseif ($type == 'qris' || $type == 'gopay') {
                $transactionData['qr_code'] = $notification->actions[0]->url ?? null;
            }

            // Update transaction first
            $transaction->update($transactionData);

            // Update order status based on transaction status
            if ($status == 'capture' || $status == 'settlement') {
                // Payment success
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing', // Order is now being processed
                ]);

                // Kirim notifikasi WhatsApp
                $orderCode = '#ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
                $phoneNumber = env('WA_TARGET'); // Nomor tujuan
                $message = "Halo admin,\n\nPesanan {$orderCode} telah berhasil dibayar.\n\nTotal Pembayaran: Rp " . number_format($order->grand_total, 0, ',', '.') . "\n\nMohon segera diproses agar pelanggan mendapat pelayanan terbaik.\n\nTerima kasih. 🙏";

                $this->sendWhatsAppNotification($phoneNumber, $message);
            } elseif ($status == 'pending') {
                // Payment still pending
                $order->update([
                    'payment_status' => 'pending'
                ]);
            } elseif ($status == 'deny' || $status == 'cancel' || $status == 'expire') {
                // Payment failed
                $order->update([
                    'payment_status' => 'failed',
                    'status' => 'payment_failed'
                ]);
            } elseif ($status == 'refund') {
                // Payment refunded
                $order->update([
                    'payment_status' => 'refunded',
                    'status' => 'refunded'
                ]);
            }

            return response('Notification handled', 200);
        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
            return response('Error processing notification: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get payment status (for Ajax polling)
     */
    public function getPaymentStatus($orderId)
    {
        $order = Order::with('payment')->findOrFail($orderId);

        // Ensure the order belongs to logged in user
        if ($order->user_id !== Auth::user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check payment status from Midtrans
        if ($order->payment && $order->payment->transaction_id) {
            try {
                // /** @var \stdClass $statusResponse */
                $statusResponse = Transaction::status($order->payment->transaction_id);
                $order->payment->update([
                    'transaction_status' => $statusResponse->transaction_status
                ]);

                // Update order status based on transaction status
                if ($statusResponse->transaction_status == 'capture' || $statusResponse->transaction_status == 'settlement') {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing'
                    ]);
                } elseif ($statusResponse->transaction_status == 'deny' || $statusResponse->transaction_status == 'cancel' || $statusResponse->transaction_status == 'expire') {
                    $order->update([
                        'payment_status' => 'failed',
                        'status' => 'payment_failed'
                    ]);
                }
            } catch (\Exception $e) {
                // Error fetching status, use existing data
            }
        }

        return response()->json([
            'order_status' => $order->status,
            'payment_status' => $order->payment_status,
            'transaction_status' => $order->payment ? $order->payment->transaction_status : null
        ]);
    }

    /**
     * Display success page after payment completed
     */
    public function paymentSuccess(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::with(['details.product', 'payment'])->findOrFail($orderId);
        $order->update(['payment_status' => 'paid']);

        // Kirim notifikasi WhatsApp
        $orderCode = '#ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
        $phoneNumber = env('WA_TARGET'); // Nomor tujuan
        $message = "Halo admin,\n\nPesanan {$orderCode} telah berhasil dibayar.\n\nTotal Pembayaran: Rp " . number_format($order->grand_total, 0, ',', '.') . "\n\nMohon segera diproses agar pelanggan mendapat pelayanan terbaik.\n\nTerima kasih. 🙏";
        $this->sendWhatsAppNotification($phoneNumber, $message);

        // Ensure the order belongs to logged in user
        if ($order->user_id !== Auth::user()->id) {
            abort(403);
        }

        $title = 'Success';
        return view('pages.orders.midtrans.success', compact(
            'title',
            'order'
        ));
    }

    /**
     * Display page for payment pending/waiting
     */
    public function paymentPending(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::with(['details.product', 'payment'])->findOrFail($orderId);

        // Ensure the order belongs to logged in user
        if ($order->user_id !== Auth::user()->id) {
            abort(403);
        }

        $title = 'Payment Pending';
        return view('pages.orders.midtrans.pending', compact('title', 'order'));
    }

    /**
     * Display page for payment failed
     */
    public function paymentFailed(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::with(['details.product', 'payment'])->findOrFail($orderId);

        // Ensure the order belongs to logged in user
        if ($order->user_id !== Auth::user()->id) {
            abort(403);
        }

        $title = 'Payment Failed';
        return view('pages.orders.midtrans.failed', compact('title', 'order'));
    }

    /**
     * Display user orders
     */
    public function myOrders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->where('status', '!=', 'in_cart')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $title = 'My Orders';
        return view('pages.orders.orders', compact('title', 'orders'));
    }

    /**
     * Show order detail
     */
    public function show($id)
    {
        $order = Order::with(['details.product', 'payment'])->findOrFail($id);

        // Ensure the order belongs to logged in user
        if ($order->user_id !== Auth::user()->id) {
            abort(403);
        }

        // Prepare order items for Midtrans
        $items = [];
        foreach ($order->details as $detail) {
            $items[] = [
                'id' => $detail->product_id,
                'price' => $detail->price,
                'quantity' => $detail->quantity,
                'name' => substr($detail->product->name, 0, 50), // Midtrans has character limits
            ];
        }

        // Tambahkan item untuk shipping
        $items[] = [
            'id' => 'SHIPPING-' . $order->courier,
            'price' => $order->shipping_cost,
            'quantity' => 1,
            'name' => 'Shipping Cost (' . $order->courier . ' - ' . $order->courier_service . ')',
        ];

        // Set up Midtrans transaction parameters
        $transactionDetails = [
            'order_id' => 'ORDER-' . $order->id . '-' . time(),
            'gross_amount' => $order->grand_total,
        ];

        $billingAddress = [
            'first_name' => $order->user->name,
            'last_name' => '',
            'email' => $order->user->email,
            'phone' => $order->phone_number,
            'address' => $order->shipping_address,
            'city' => $order->city,
            'postal_code' => $order->postal_code,
            'country_code' => 'IDN'
        ];

        $customerDetails = [
            'first_name' => $order->user->name,
            'last_name' => '',
            'email' => $order->user->email,
            'phone' => $order->phone_number,
            'billing_address' => $billingAddress,
            'shipping_address' => $billingAddress
        ];

        // Setup payment options with Midtrans
        $snapToken = Snap::getSnapToken([
            'transaction_details' => $transactionDetails,
            'item_details' => $items,
            'customer_details' => $customerDetails
        ]);


        $clientKey = config('services.midtrans.client_key');

        $title = 'Order ###' . $order->id;
        return view('pages.orders.show', compact('title', 'order', 'snapToken', 'clientKey'));
    }

    /**
     * Generate invoice PDF
     */
    public function invoice($id)
    {
        $order = Order::with(['details.product', 'payment', 'user'])->findOrFail($id);

        $pdf = Pdf::loadView('pages.orders.invoice', [
            'order' => $order,
            'title' => 'Invoice #' . $order->id
        ]);

        return $pdf->stream('invoice-' . $order->id . '.pdf');
    }

    /**
     * Confirm order received
     */
    public function confirmOrder($id)
    {
        $order = Order::findOrFail($id);

        // Ensure the order belongs to logged in user
        if ($order->user_id !== Auth::user()->id) {
            abort(403);
        }

        // Only allow confirmation if order is delivered
        if ($order->status != 'delivered') {
            return back()->with('error', 'Cannot confirm this order.');
        }

        $order->update([
            'status' => 'success'
        ]);

        return back()->with('success', 'Order confirmed as received.');
    }

    /**
     * Cancel order
     */
    public function cancelOrder($id)
    {
        $order = Order::findOrFail($id);

        // Ensure the order belongs to logged in user
        if ($order->user_id !== Auth::user()->id) {
            abort(403);
        }

        // Only allow cancellation if order is not already shipped or delivered
        if (in_array($order->status, ['in_shipping', 'delivered', 'success', 'cancelled'])) {
            return back()->with('error', 'Cannot cancel this order.');
        }

        // Jika sudah dibayar, harus direfund melalui Midtrans
        if ($order->payment_status == 'paid') {
            try {
                // Refund harus dilakukan secara manual oleh admin
                // Di sini kita hanya mencatat permintaan cancel
                $order->update([
                    'status' => 'cancel_request'
                ]);

                return redirect()->route('orders.index')
                    ->with('success', 'Cancellation request submitted. We will process your refund shortly.');
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to request cancellation: ' . $e->getMessage());
            }
        } else {
            // Belum dibayar, bisa langsung cancel
            $order->update([
                'status' => 'cancelled'
            ]);

            return redirect()->route('orders.index')->with('success', 'Order cancelled successfully.');
        }
    }

    /**
     * Retry payment for failed orders
     */
    public function retryPayment($id)
    {
        $order = Order::with('payment')->findOrFail($id);

        // Ensure the order belongs to logged in user
        if ($order->user_id !== Auth::user()->id) {
            abort(403);
        }

        // Only allow retry if order payment failed or expired
        if (
            !in_array($order->status, ['payment_failed', 'pending']) ||
            !in_array($order->payment_status, ['failed', 'unpaid', 'pending'])
        ) {
            return back()->with('error', 'Cannot retry payment for this order.');
        }

        try {
            // Prepare order items for Midtrans
            $items = [];
            foreach ($order->details as $detail) {
                $items[] = [
                    'id' => $detail->product_id,
                    'price' => $detail->price,
                    'quantity' => $detail->quantity,
                    'name' => substr($detail->product->name, 0, 50),
                ];
            }

            // Add shipping cost item
            $items[] = [
                'id' => 'SHIPPING-' . $order->courier,
                'price' => $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Shipping Cost (' . $order->courier . ' - ' . $order->courier_service . ')',
            ];

            // Create new transaction ID
            $transactionId = 'ORDER-' . $order->id . '-' . time();

            // Set up Midtrans transaction parameters
            $transactionDetails = [
                'order_id' => $transactionId,
                'gross_amount' => $order->grand_total,
            ];

            $user = $order->user;
            $customerDetails = [
                'first_name' => $user->name,
                'last_name' => '',
                'email' => $user->email,
                'phone' => $order->phone_number,
                'billing_address' => [
                    'first_name' => $order->recipient_name,
                    'last_name' => '',
                    'email' => $user->email,
                    'phone' => $order->phone_number,
                    'address' => $order->shipping_address,
                    'city' => $order->city,
                    'postal_code' => $order->postal_code,
                    'country_code' => 'IDN'
                ],
                'shipping_address' => [
                    'first_name' => $order->recipient_name,
                    'last_name' => '',
                    'email' => $user->email,
                    'phone' => $order->phone_number,
                    'address' => $order->shipping_address,
                    'city' => $order->city,
                    'postal_code' => $order->postal_code,
                    'country_code' => 'IDN'
                ]
            ];

            // Get new snap token
            $snapToken = Snap::getSnapToken([
                'transaction_details' => $transactionDetails,
                'item_details' => $items,
                'customer_details' => $customerDetails
            ]);

            // Update or create payment transaction
            if ($order->payment) {
                $order->payment->update([
                    'transaction_id' => $transactionId,
                    'transaction_status' => 'pending',
                    'snap_token' => $snapToken,
                ]);
            } else {
                PaymentTransaction::create([
                    'order_id' => $order->id,
                    'provider' => 'midtrans',
                    'transaction_id' => $transactionId,
                    'transaction_status' => 'pending',
                    'snap_token' => $snapToken,
                ]);
            }

            // Update order status
            $order->update([
                'status' => 'pending',
                'payment_status' => 'pending'
            ]);

            // Redirect to payment page
            return view('pages.orders.payment', [
                'title' => 'Snap Token',
                'snapToken' => $snapToken,
                'order' => $order,
                'clientKey' => config('services.midtrans.client_key')
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create payment: ' . $e->getMessage());
        }
    }

    /**
     * Send WhatsApp notification using Fonnte API
     */
    private function sendWhatsAppNotification($phoneNumber, $message)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $phoneNumber,
                'message' => $message,
                'countryCode' => '62', //optional
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . env('FONNTE_TOKEN') // Gunakan token dari config
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            Log::error('Fonnte API error: ' . $error_msg);
        }
        curl_close($curl);

        return $response;
    }
}

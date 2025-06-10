<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use App\Models\Categories;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function home()
    {
        $products = Product::orderBy('created_at', 'desc')->limit(3)->get();
        $categories = Categories::orderBy('categories_name', 'asc')->limit(4)->get();
        $title = "Home";

        return view('pages.home', compact(
            'products',
            'categories',
            'title'
        ));
    }

    public function about()
    {
        $title = 'About Us';

        return view('pages.about', compact('title'));
    }

    public function contact()
    {
        $title = 'Contact Us';

        return view('pages.contact', compact('title'));
    }

    public function contactSend(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        // Format phone number (remove leading 0 if any and add 62)
        $phoneNumber = preg_replace('/^0/', '62', $validated['phone']);
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Prepare WhatsApp message
        $whatsappMessage = "📩 *Formulir Kontak Baru Diterima!*\n\n";
        $whatsappMessage .= "Berikut detail pengirim:\n";
        $whatsappMessage .= "*Nama*    : {$validated['name']}\n";
        $whatsappMessage .= "*Email*   : {$validated['email']}\n";
        $whatsappMessage .= "*Telepon* : {$validated['phone']}\n\n";
        $whatsappMessage .= "*Pesan*: {$validated['message']}\n\n";
        $whatsappMessage .= "🙏 Mohon segera ditindaklanjuti sesuai kebutuhan.\nTerima kasih atas perhatiannya! 🌟";



        // Send WhatsApp notification to admin
        $adminPhone = env('WA_TARGET'); // Replace with your admin's phone number
        $this->sendWhatsAppNotification($adminPhone, $whatsappMessage);

        return redirect()->route('contact')->with('success', 'Terima kasih atas pesan Anda! Kami akan segera menghubungi Anda.');
    }

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
                'Authorization: ' . env('FONNTE_TOKEN') // Make sure to add this to your .env
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

    public function blog()
    {
        $blogs = Blogs::with('user')
            ->latest()->get();
        $title = 'Artikel';

        return view('pages.blog', compact('blogs', 'title'));
    }

    public function blogShow($slug)
    {
        $blog = Blogs::with('user')->where('slug', $slug)->firstOrFail();
        $title = $blog->title;

        return view('pages.blog_show', compact('blog', 'title'));
    }

    public function products(Request $request)
    {
        $query = Product::with(['categories']);

        // Filter pencarian
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->has('category') && $request->category != 'all') {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('id', $request->category);
            });
        }

        // Sorting harga
        if ($request->has('sort')) {
            if ($request->sort == 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort == 'price_desc') {
                $query->orderBy('price', 'desc');
            }
        }

        $products = $query->get();
        $categories = Categories::all(); // Untuk dropdown kategori
        $title = 'Product';

        return view('pages.products.index', compact('title', 'products', 'categories'));
    }

    public function product_detail($id)
    {
        $product = Product::findOrFail($id);
        $relatedProducts = Product::where('categories_id', $product->categories_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();
        $title = 'Detail Produk ' . $product->name;

        return view('pages.products.detail', compact('title', 'product', 'relatedProducts'));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Redirect ke halaman setelah logout
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048', // 2MB max
        ]);

        // Cek apakah user sudah memberikan review untuk produk ini di order ini
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('order_id', $request->order_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        // Cek apakah order milik user dan statusnya success
        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->where('status', 'success')
            ->first();

        if (!$order) {
            return back()->with('error', 'Order tidak valid.');
        }

        // Cek apakah produk ada di order ini
        $orderDetail = $order->details()->where('product_id', $request->product_id)->first();
        if (!$orderDetail) {
            return back()->with('error', 'Produk tidak ditemukan dalam order ini.');
        }

        $reviewData = [
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('reviews', 'public');
            $reviewData['image'] = $imagePath;
        }

        Review::create($reviewData);

        return back()->with('success', 'Ulasan berhasil dikirim. Terima kasih!');
    }

    public function update(Request $request, Review $review)
    {
        // Pastikan user hanya bisa edit review miliknya sendiri
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengedit ulasan ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $reviewData = [
            'rating' => $request->rating,
            'comment' => $request->comment,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($review->image) {
                Storage::disk('public')->delete($review->image);
            }

            $image = $request->file('image');
            $imagePath = $image->store('reviews', 'public');
            $reviewData['image'] = $imagePath;
        }

        $review->update($reviewData);

        return back()->with('success', 'Ulasan berhasil diperbarui.');
    }

    public function destroy(Review $review)
    {
        // Pastikan user hanya bisa hapus review miliknya sendiri
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus ulasan ini.');
        }

        // Delete image if exists
        if ($review->image) {
            Storage::disk('public')->delete($review->image);
        }

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}

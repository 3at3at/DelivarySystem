@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Rate Your Driver</h2>

    <form method="POST" action="{{ route('client.reviews.store') }}">
        @csrf

        {{-- Hidden inputs --}}
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <input type="hidden" name="driver_id" value="{{ $order->delivery->driver_id }}">

        {{-- ⭐ Star Rating --}}
        <div class="mb-4">
            <label class="block mb-1 font-bold">Rate the Driver:</label>
            <div class="flex gap-1">
                @for ($i = 1; $i <= 5; $i++)
                    <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" class="hidden" required>
                    <label for="star{{ $i }}" class="cursor-pointer text-2xl text-gray-300"
                           onclick="updateStars({{ $i }})">
                        &#9733;
                    </label>
                @endfor
            </div>
        </div>

        {{-- 📝 Optional Review Message --}}
        <div class="mb-4">
            <label for="review" class="block font-bold mb-1">Write a review (optional):</label>
            <textarea name="review" id="review" rows="4" class="w-full border p-2 rounded" placeholder="Your feedback..."></textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Submit Review</button>
    </form>
</div>
@endsection

{{-- ✅ JS placed safely outside content --}}
@push('scripts')
<script>
    function updateStars(rating) {
        const stars = document.querySelectorAll("label[for^='star']");
        stars.forEach((el, index) => {
            if (index < rating) {
                el.classList.add('text-yellow-400');
                el.classList.remove('text-gray-300');
            } else {
                el.classList.remove('text-yellow-400');
                el.classList.add('text-gray-300');
            }
        });

        const input = document.getElementById('star' + rating);
        if (input) input.checked = true;
    }
</script>
@endpush

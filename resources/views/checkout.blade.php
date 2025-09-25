@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-semibold mb-4 text-pink-600">Checkout</h2>

    <form id="checkoutForm">
        @csrf
        <input type="hidden" name="trx_id" value="TRX{{ time() }}">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" class="mt-1 block w-full rounded border-gray-300 shadow-sm" value="{{ auth()->user()->name }}" disabled>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" class="mt-1 block w-full rounded border-gray-300 shadow-sm" value="{{ auth()->user()->email }}" disabled>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
            <input type="text" name="telepon" id="telepon" class="mt-1 block w-full rounded border-gray-300 shadow-sm" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Alamat</label>
            <textarea name="alamat" id="alamat" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm" required></textarea>
        </div>

        <button type="button" id="pay-button" class="bg-pink-600 text-white px-4 py-2 rounded shadow hover:bg-pink-700">
            Bayar Sekarang
        </button>
    </form>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', function (e) {
        e.preventDefault();

        let telepon = document.getElementById('telepon').value;
        let alamat = document.getElementById('alamat').value;
        let trx_id = document.querySelector('input[name="trx_id"]').value;
        let tokenUrl = "{{ route('checkout.payment') }}";

        fetch(tokenUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                telepon: telepon,
                alamat: alamat,
                trx_id: trx_id
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.token) {
                snap.pay(data.token, {
                    onSuccess: function(result) {
                        fetch("{{ route('keranjang.clear') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            }
                        }).then(() => {
                            window.location.href = "{{ route('order.index') }}";
                        });
                    },
                    onPending: function(result) {
                        alert("Pembayaran tertunda. Pesanan akan masuk setelah pembayaran selesai.");
                    },
                    onError: function(result) {
                        alert("Pembayaran gagal.");
                        console.error(result);
                    },
                    onClose: function() {
                        alert("Anda menutup popup sebelum menyelesaikan pembayaran.");
                    }
                });
            } else {
                alert("Gagal mendapatkan token pembayaran.");
                console.error(data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Terjadi kesalahan saat memproses pembayaran.");
        });
    });
</script>
@endsection

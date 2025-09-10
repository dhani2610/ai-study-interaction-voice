@extends('backend.layouts-new.app')

@section('content')
<style>
    .avatar-circle {
        width: 50px;
        height: 50px;
        background-color: #4a90e2;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 20px;
    }
</style>

<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="container py-4">
                <h5 class="text-center fw-bold">Call With</h5>

                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search" id="searchInput">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                </div>

                @foreach ($admins as $admin)
                    @if ($admin->roles[0]->name == 'Masseur')
                        <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3">
                                    <span class="initials">{{ strtoupper(substr($admin->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <div class="fw-bold">Masseur</div>
                                    <div>{{ $admin->name }}</div>
                                    <div class="text-muted small">Rp. 10.000</div>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-success btn-sm" onclick="openBookingModal({{ $admin->id }}, '{{ $admin->name }}')">Hubungi</button>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Modal Booking -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="bookingForm">
            @csrf
            <input type="hidden" name="masseur_id" id="masseur_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Booking Masseur: <span id="masseurName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-success mb-3 w-100">Hubungi via WhatsApp</a>

                    <div class="mb-3">
                        <label for="tanggal_waktu">Tanggal & Waktu</label>
                        <input type="datetime-local" name="tanggal_waktu" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="button" onclick="checkoutMidtrans()" class="btn btn-primary w-100">Checkout (Rp 10.000)</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<!-- Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
    function openBookingModal(masseurId, masseurName) {
        document.getElementById('masseur_id').value = masseurId;
        document.getElementById('masseurName').textContent = masseurName;
        new bootstrap.Modal(document.getElementById('bookingModal')).show();
    }

    function checkoutMidtrans() {
        const form = document.getElementById('bookingForm');
        const formData = new FormData(form);

        fetch('{{ route("midtrans.token") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.snap_token) {
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        formData.append('invoice_id', result.order_id);
                        formData.append('total', result.gross_amount);
                        fetch('{{ route("booking.store") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(resp => {
                            alert("Booking berhasil!");
                            location.reload();
                        });
                    },
                    onError: function(err) {
                        alert("Pembayaran gagal. Silakan coba lagi.");
                    }
                });
            }
        });
    }

    // Search filter
    document.getElementById('searchInput').addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        document.querySelectorAll('.d-flex.align-items-center.justify-content-between').forEach(function (card) {
            const name = card.querySelector('div > div > div:nth-child(2)').textContent.toLowerCase();
            card.style.display = name.includes(keyword) ? 'flex' : 'none';
        });
    });
</script>
@endsection

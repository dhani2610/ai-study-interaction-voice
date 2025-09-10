@extends('backend.layouts-new.app')

@section('content')
    <style>
        .form-check-label {
            text-transform: capitalize;
        }

        .select2 {
            width: 100% !important;
        }

        label {
            float: left;
        }
    </style>
    @php
        $usr = Auth::guard('admin')->user();
        if ($usr != null) {
            $userRole = Auth::guard('admin')->user()->getRoleNames()->first(); // Get the first role name
        }

    @endphp

    <div class="main-content-inner">
        <div class="row">
            <!-- data table start -->
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title float-left">Data {{ Request::get('type') }}</h4>
                        <p class="float-right mb-2">
                            <a href="{{ route('spip.create') }}?type={{ Request::get('type') }}"
                                class="btn btn-primary text-white mb-3">
                                Tambah Data {{ Request::get('type') }}
                            </a>
                        </p>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            @include('backend.layouts.partials.messages')
                            <table id="dataTable" class="table text-center">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th>NO</th>
                                        <th>Jenis</th>
                                        <th>Merek</th>
                                        <th>Jenis Unit</th>
                                        <th>Perusahaan</th>
                                        <th>Nomor Unit</th>
                                        <th>Commisioner</th>
                                        <th>Tanggal Commisioning</th>
                                        <th>Admin Name</th>
                                        <th>Tanggal Expired</th>
                                        <th>Sisa Hari</th>
                                        <th>Status</th>
                                        <th>File</th>
                                        <th>Foto Deviasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($spips as $index => $spip)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $spip->jenis }}</td>
                                            <td>{{ $spip->merek }}</td>
                                            <td>{{ $spip->jenis_unit }}</td>
                                            <td>{{ $spip->perusahaan }}</td>
                                            <td>{{ $spip->nomor_unit }}</td>
                                            <td>{{ $spip->commisioner }}</td>
                                            <td>{{ $spip->tanggal_commisioning }}</td>
                                            @php
                                                $userct = App\Models\Admin::where('id', $spip->user)->first();
                                            @endphp
                                            @if ($userct)
                                                <td>{{ $userct->name ?? '-' }}</td>
                                            @else
                                                <td>{{ '-' }}</td>
                                            @endif
                                            <td>{{ $spip->tanggal_expired }}</td>

                                            <td>
                                                @php
                                                    $now = \Carbon\Carbon::now();
                                                    $expiredDate = $spip->tanggal_expired
                                                        ? \Carbon\Carbon::parse($spip->tanggal_expired)
                                                        : null;
                                                    $status =
                                                        $expiredDate && $expiredDate->isFuture() ? 'Active' : 'Expired';
                                                    $bg = $expiredDate && $expiredDate->isFuture() ? 'green' : 'red';
                                                    if ($expiredDate) {
                                                        $days = $now->diffInDays($expiredDate, false);
                                                        $daysFormatted = abs(floor($days));

                                                        $remaining = $days >= 0 ? "$daysFormatted Hari" : '0 Hari';
                                                    } else {
                                                        $remaining = '-';
                                                    }

                                                @endphp

                                                {{ $remaining }}
                                            </td>
                                            <td style="color: white;background: {{ $bg }}">{{ $status }}
                                            </td>

                                            <td>
                                                @if ($spip->upload_foto)
                                                    <a href="{{ asset('documents/' . $spip->upload_foto) }}"
                                                        target="_blank">Download</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <!-- Button trigger modal -->
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal{{ $spip->id }}">
                                                    View
                                                </button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModal{{ $spip->id }}" tabindex="-1"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-scrollable">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Foto
                                                                    Deviasi</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @php
                                                                    $foto = App\Models\FotoDeviasi::where(
                                                                        'id_spip',
                                                                        $spip->id,
                                                                    )->get();
                                                                @endphp
                                                                @foreach ($foto as $item)
                                                                    <hr>
                                                                    <center>
                                                                        <img src="{{ asset('assets/img/foto_deviasi/' . $item->foto) }}"
                                                                            alt="Foto SPIP" style="max-width: 300px;">
                                                                    </center>
                                                                    <hr>
                                                                    <br>
                                                                @endforeach
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($userRole == 'superadmin')
                                                    <a href="{{ route('spip.mail.reminder', $spip->id) }}"
                                                        class="btn btn-info text-white">
                                                        <i class="far fa-bell	"></i>
                                                    </a>
                                                    <a href="{{ route('spip.edit', $spip->id) }}?type={{ Request::get('type') }}"
                                                        class="btn btn-success text-white">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a onclick="confirmDelete('{{ route('spip.destroy', $spip->id) }}?type={{ Request::get('type') }}')"
                                                        class="btn btn-danger text-white">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- data table end -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        function confirmDelete(deleteUrl) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "Are you sure you want to delete this data?",
                text: "This action cannot be undone!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        }
    </script>
@endsection

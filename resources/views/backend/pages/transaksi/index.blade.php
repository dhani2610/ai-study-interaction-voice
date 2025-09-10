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
                        <h4 class="header-title float-left">Data Transaksi</h4>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            @include('backend.layouts.partials.messages')
                            <table id="dataTable" class="table text-center">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th>NO</th>
                                        <th>Invoice</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Masseur</th>
                                        <th>Customer</th>
                                        <th>Alamat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaksis as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->invoice_id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_waktu)->format('d-m-Y H:i') }}</td>
                                            <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                            <td>{{ $item->masseur->name ?? '-' }}</td>
                                            <td>{{ $item->customer->name ?? '-' }}</td>
                                            <td>{{ $item->alamat ?? '-' }}</td>
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
@endsection

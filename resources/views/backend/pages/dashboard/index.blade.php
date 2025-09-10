@extends('backend.layouts-new.app')

@section('title')
    Dashboard Page - Admin Panel
@endsection


@section('content')
    <style>
        .icon-style {
            width: 150px;
            height: 150px;
            border: 3px solid black;
            border-radius: 50%;
            padding: 10px;
            background: #fff;
        }

        .badge-pill-custom {
            padding: 8px 18px;
            background: linear-gradient(to right, #a5d8ff, #845ef7);
            color: black;
            border-radius: 30px;
            font-weight: 600;
            font-size: 17px;
        }

        .divider-line {
            border-top: 2px solid black;
            width: 89%;
            margin: 0px auto;
        }
    </style>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css"
        rel="stylesheet" />
    <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div id="container">
                        <center>
                            <h1>Hi,{{ Auth::guard('admin')->user()->name }}</h1>
                        </center>


                    </div>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script>
            $("#datepicker").datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years"
            });
        </script>
    @endsection
    @push('dashboard')

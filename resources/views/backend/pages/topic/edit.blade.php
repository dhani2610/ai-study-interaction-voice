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
            color: black;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
    </style>

    <div class="main-content-inner">
        <div class="row">
            <form action="{{ route('topic.update',$topic->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title text-center">Edit</h4>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group col-md-12">
                                        <label class="mt-2" for="jenis">Topic</label>
                                        <input type="text" class="form-control" id="topic" name="topic" value="{{ $topic->topic }}" required>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-primary mt-4" type="submit">Simpan Data</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">

    <script>
        // Initialize Dropify
        $('.dropify').dropify();

        // Add new Dropify input
        $('#add-row').on('click', function() {
            const newRow = `
        <div class="dropify-row mb-2">
            <button type="button" class="btn btn-danger btn-sm remove-row" style="float: right">-</button>
            <input type="file" name="foto_deviasi[]" class="form-control" data-height="200" accept="image/*" />
        </div>
    `;
            $('#dropify-wrapper').append(newRow);

            // Reinitialize only new Dropify elements
            $('#dropify-wrapper .dropify').each(function() {
                if (!$(this).hasClass('dropify-initialized')) {
                    $(this).dropify();
                }
            });
        });


        // Remove Dropify input
        $('#dropify-wrapper').on('click', '.remove-row', function() {
            $(this).closest('.dropify-row').remove();
        });
    </script>
@endsection

@section('title', 'Generic QR Codes')
@extends('layouts.app')
@section('content')

    <!-- Main Content Area Start -->

    <div class="main-content-area">
        <div class="main-content">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 rounded-3 mb-1">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center">
                                        <h5 class="fw-semibold mb-0"> Create QR Codes </h5>
                                    </div>
                                </div>
                                <!-- Trigger Button -->
                                <div class="col-12 col-md-6">
                                    <button type="button" class="btn btn-outline-primary showModal rounded-3"
                                        data-bs-toggle="modal" data-bs-target="#QRCodeModal" data-type="Operator">
                                        Add QR
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 rounded-3">
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table rounded-3">
                                            <thead>
                                                <tr>

                                                    <th scope="col" class="text-445B64 p-3"> QR Code Image <br> QR Value
                                                    </th>
                                                    <th scope="col" class="text-445B64 p-3"> QR For </th>
                                                    <th scope="col" class="text-445B64 p-3"> Action </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @php $i=0; @endphp
                                                @foreach ($data as $datum)
                                                    @php$i++;  @endphp
                                                    <tr>

                                                        <th scope="row" class="sticky-column p-3 bg-white"
                                                            style="position: sticky; left: 10px;">
                                                            <div class="d-flex align-items-center">
                                                                <span
                                                                    class="table-square-icon bg-F0F5F6 rounded-3 img-zoom">
                                                                    <img width="16" height="16" viewBox="0 0 12 12"
                                                                        fill="none"
                                                                        src="{{ asset('storage/generic-qrcodes') . '/' . $datum->qr_code }}"
                                                                        alt="QR Code">
                                                                </span>
                                                                <span class=""> {{ $datum->code }} </span>
                                                            </div>
                                                        </th>

                                                        <td class="p-3"> {{ $datum->qr_use_for }} </td>
                                                        <td class="p-3">
                                                            <a onclick="return confirm('Are you sure you want to delete this QR?');"
                                                                href="{{ route('deleteGenericQR', [$datum->id]) }}">
                                                                <i class="rounded-pill fw-bolder fa-solid fa-trash"></i>
                                                            </a>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div class="modal fade" id="QRCodeModal" tabindex="-1" aria-labelledby="QRCodeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="QRCodeModalLabel">QR Code Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('generate.qr-code') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="qr_code" class="form-label">QR Code Value</label>
                            <input type="text" id="qr_code" name="qr_code" class="form-control" required
                                placeholder="Enter QR Code">
                        </div>

                        <div class="mb-3">
                            <label for="qr_use_for" class="form-label">QR Use For</label>
                            <input type="text" id="qr_use_for" name="qr_use_for" class="form-control" required
                                placeholder="Purpose (e.g. Operator)">
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

@stop

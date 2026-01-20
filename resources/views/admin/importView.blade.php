@section('title', 'Import')
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
                                        <h5 class="fw-semibold mb-0"> Import Data (in CSV Format) </h5>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6"> </div>
                            </div>
                        </div>
                    </div>
 
                    {{-- Import Machine Data --}}
                    <div class="card border-0 rounded-3">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-12 col-md-6 mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center">
                                        <h5 class="fw-semibold mb-0">
                                            <h6> Import Machine Data </h6>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="container mt-6" style="border:1px;">
                                        <form action="{{ route('machine.import') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="csv_file" required>
                                            <button type="submit" class="btn btn-primary">Import Excel</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Import Product Data --}}
                    <div class="card border-0 rounded-3">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-12 col-md-6 mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center">
                                        <h5 class="fw-semibold mb-0">
                                            <h6> Import Product Data </h6>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="container mt-6" style="border:1px;">
                                        <form action="{{ route('product.importProductCSV') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="product_csv_file" required>
                                            <button type="submit" class="btn btn-primary"> Import Excel </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Import Sub Product Data --}}
                    <div class="card border-0 rounded-3">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-12 col-md-6 mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center">
                                        <h5 class="fw-semibold mb-0">
                                            <h6> Import Sub Product Data </h6>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="container mt-6" style="border:1px;">
                                        <form action="{{ route('subproduct.importSubProductCSV') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="sub_product_csv_file" required>
                                            <button type="submit" class="btn btn-primary"> Import Excel </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Import Operation Data --}}
                    <div class="card border-0 rounded-3">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-12 col-md-6 mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center">
                                        <h5 class="fw-semibold mb-0">
                                            <h6> Import Operation Data </h6>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="container mt-6" style="border:1px;">
                                        <form action="{{ route('operation.import') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="operation_csv_file" required>
                                            <button type="submit" class="btn btn-primary">Import Excel</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 rounded-3">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-12 col-md-6 mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center">
                                        <h5 class="fw-semibold mb-0">
                                            <h6> Import Sub Product Operation </h6>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="container mt-6" style="border:1px;">
                                        <form action="{{ route('product.subProductOperation') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="subproductoperation_csv_file" required>
                                            <button type="submit" class="btn btn-primary">Import Excel</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Import users Data --}}
                    <div class="card border-0 rounded-3">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-12 col-md-6 mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center">
                                        <h5 class="fw-semibold mb-0">
                                            <h6> Import User Data </h6>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="container mt-6" style="border:1px;">
                                        <form action="{{ route('users.import') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="user_csv_file" required>
                                            <button type="submit" class="btn btn-primary">Import Excel</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 rounded-3">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-12 col-md-6 mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center">
                                        <h5 class="fw-semibold mb-0">
                                            <h6> Import Cycle Data </h6>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="container mt-6" style="border:1px;">
                                        <form action="{{ route('importIdealCycleData') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="cycle_csv_file" required>
                                            <button type="submit" class="btn btn-primary">Import Data</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content Area End -->
@stop

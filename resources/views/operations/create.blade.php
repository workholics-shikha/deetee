@section('title', 'Add Cycles')
@extends('layouts.app')
@section('content')

    <div class="main-content-area">
        <div class="main-content">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 rounded-3">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-12">

                                    <div class="container mt-6">

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <form method="post" action="{{ route('admin.user.save') }}" enctype="multipart/form-data">

                                            @csrf
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Operation</label>
                                                <select class="form-control" required>
                                                           <option value="">Select Operation</option> 
                                                            @if(count($operations)>0)   
                                                                @foreach($operations as $operation)
                                                                    <option value="{{ $operation->id }}">{{ $operation->operation_name }} </option>
                                                                @endforeach   
                                                            @endif    
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                                    name="email" placeholder="Enter your email"
                                                    value="{{ old('email') }}" autocomplete="off">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Phone</label>
                                                <input type="text"
                                                    class="form-control @error('phone') is-invalid @enderror" id="phone"
                                                    name="phone" placeholder="Enter your Phone">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Shift</label>
                                                <input type="text" class="form-control" placeholder="Enter Shift"
                                                    name="shift">
                                            </div>

                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Upload Profile Image</label>
                                                <input type="file" class="form-control" name="image">
                                            </div>

                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Password</label>
                                                <input type="password" class="form-control" placeholder="Enter Password"
                                                    name="password">
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <button type="submit" class="btn btn-primary"> Submit </button>
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

@stop
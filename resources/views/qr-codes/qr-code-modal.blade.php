<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-body p-4 pb-0">
            <!-- Modal Body Content -->
            <h5 class="modal-title text-0D161A fw-bolder" id="exampleModalLabel">
                QR Code
            </h5>
            <p class="text-6C7D83 fw-light" style="font-size: 14px"> To modify the QR Code please choose from the
                actions
                below </p>
            <div class="container mt-4 mb-4">
                <img width="130px" height="80px" src="{{$qrCode}}" class="img-fluid d-block mx-auto"
                    alt="Responsive Image" />
            </div>

            <div class="row">

                <!-- First Button -->
                <div class="col-6 col-md-6 text-center mb-2">
                    <button data-id="{{ $id }}" data-type="{{ $type }}" btn-type="regenerate"
                        class="btn btn-success bg-00B200 modal-action-btn fw-medium rounded-3 border-0 py-1 py-md-2 px-2 px-md-3 w-100 btn-regenerate">
                        Regenerate </button>
                </div>

                <!-- Third Button -->
                <div class="col-6 col-md-6 text-center mb-2">
                    <button data-id="{{ $id }}" data-type="{{ $type }}" btn-type="delete"
                        class="btn btn-danger bg-CC2200 modal-action-btn fw-medium rounded-3 border-0 py-1 py-md-2 px-2 px-md-3 w-100 btn-delete">
                        Delete </button>
                </div>

            </div>

        </div>
        <div class="modal-footer d-flex justify-content-center border-0">
            <button type="button"
                class="btn btn-secondary close-button text-0D161A bg-F0F5F6 fw-semibold border mb-3 rounded-3 modalCloseBtn"
                data-bs-dismiss="modal"> Close </button>
        </div>
    </div>
</div>
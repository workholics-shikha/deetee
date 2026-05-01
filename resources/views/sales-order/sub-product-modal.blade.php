 <div class="modal-dialog">
     <div class="modal-content rounded-4">
         <div class="modal-header">
             <h3 class="modal-title fw-bolder" id="formModalLabel"> Product Selection </h3>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
             <!-- Form -->
             <form method="post" action="{{ route('admin.update-sub-product') }}" enctype="multipart/form-data" class="subProductForm">
                 @csrf

                 <input type="hidden" name="so_id" value="{{ $data->so_id }}">
                 <input type="hidden" name="so_no" value="@php print_r($so_no) @endphp">
                 <input type="hidden" name="so_product_id" value="{{ $data->id }}">

                 @php $sizeVals = getSizeValue($subProducts[0]->product->group); @endphp

                 <div class="card rounded-4 mb-3 overflow-hidden">
                     <div class="card-header bg-F8FDFF">
                         <small class="text-445B64">#01</small>
                         <h5 class="text-0D161A fw-bolder">{{ $data->item_name }}</h5>
                     </div>
                     <div class="card-body">
                         <div class="row">
                             <div class="col-md-3 mb-3">
                                 <small class="">UQM</small>
                                 <h6 class="fw-semibold">{{ $data->measureunit }}</h6>
                             </div>
                             <div class="col-md-5 border-right border-left ps-md-5">
                                 <small class="">Material</small>
                                 <h6 class="fw-semibold">{{ $data->material }}</h6>
                             </div>
                             <div class="col-md-4 ps-md-5">
                                 <small class="">Hardness</small>
                                 <h6 class="fw-semibold">{{ $data->hardness }}</h6>
                             </div>
                         </div>
                         <div class="row">
                             <div class="col-md-3 mb-3">
                                 <small class=""> SO QTY. </small>
                                 <h6 class="fw-semibold">{{ $data->soquantity }}</h6>
                             </div>
                             <div class="col-md-5 border-right border-left ps-md-5">
                                 <small class="">Drawing No.</small>
                                 <h6 class="fw-semibold">{{ $data->drawingno }}</h6>
                             </div>
                             <div class="col-md-4 ps-md-5">
                                 <small class=""> Scr status </small>
                                 <h6 class="fw-semibold">{{ $data->scr_status }}</h6>
                             </div>
                         </div>
                         <div class="row">
                             <div class="col-md-3 mb-3">
                                 <small class=""> {{ $sizeVals[0] }}</small>
                                 <h6 class="text-0D161A fw-semibold">
                                     {{ $data->size1 }}
                                 </h6>
                             </div>
                             <div class="col-md-5 border-right border-left ps-md-5">
                                 <small class=""> {{ $sizeVals[1] }}</small>
                                 <h6 class="fw-semibold">{{ $data->size2 }}</h6>
                             </div>
                             <div class="col-md-3 mb-3">
                                 <small class=""> {{ $sizeVals[2] }} </small>
                                 <h6 class="fw-semibold">{{ $data->size3 }}</h6>
                             </div>
                         </div>
                     </div>
                 </div>

                 <div class="mb-3">
                     <label for="message" class="form-label"> <small> Please select the sub-product for the Product
                         </small> </label>
                     <select class="form-control p-3 rounded-4 subProductSelect" name="sub_product_id" id="">
                         <option value="">
                             <span class="ms-2">Sub-Product</span>
                         </option>
                         @foreach ($subProducts as $product)
                             <option value="{{ $product->id }}">
                                 {{ $product->product->erp_product }}
                                 ({{ $product->product->unit }}-{{ $product->product->group }})
                                 : {{ $product->sub_product_name }}
                             </option>
                         @endforeach
                     </select>
                 </div>

                 <div class="d-flex justify-content-between w-100">
                     <button type="submit" class="btn btn-primary rounded-3 px-5 submitBtn" id=""> Submit </button>
                 </div>
             </form>
         </div>
     </div>
 </div>

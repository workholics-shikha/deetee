<?php use App\Helpers\MyHelper; ?>

@foreach ($getOperationList as $operation)
    <tr>
        <td colspan="4" class="p-0 {{ in_array(optional($operation->operation_params)->parameter1, ['Outsourced', 'Manual']) ? 'parentDiv' : '' }}">
            <div class="accordion-item border-0 {{ optional($operation->operation_params)->parameter1 ? 'operationTrackingOpen' : 'disabled-div' }}">

                <h2 class="accordion-header" id="headingOne">
                    <table class="table mb-0">
                        <tr data-id="{{ $operation->id }}" data-operation_type="{{ optional($operation->operation_params)->parameter1 }}" class="routeCardOperation">
                            <td class="border-right" style="width: 5%;">

                                @if (!empty($pass_sheet))
                                    <a class="accordion-button table-checkbox bg-white mx-auto text-637381"
                                        style="text-decoration:none;" title="view details"
                                        href="{{ route('admin.route-card-operation-details', $so->id) }}?operation_id={{ $operation->id }}&pass_id={{ $pass_sheet->id }}">
                                        <i class="fa-solid fa-eye text-445B64"></i> </a>                                    
                                @else
                                    <a class="accordion-button table-checkbox bg-white mx-auto text-637381"
                                        style="text-decoration:none;" title="view details"
                                        href="{{ route('admin.route-card-operation-details', ['id' => $so->id, 'operation_id' => $operation->id]) }}">
                                        <i class="fa-solid fa-eye text-445B64"></i> </a>
                                @endif
                            </td>
                            <td class="border-right" style="width: 20%;">
                                <div class="d-flex align-items-center">
                                    <span class="table-square-icon bg-F0F5F6 rounded-3 img-zoom">
                                        @if ($operation->operation_qr_code != 'https://weblaunchpad.in/deetee/public/assets/images/QRImg.png')
                                            <img src="{{ $operation->operation_qr_code }}" alt="Operation QR" class="w-100">
                                        @endif
                                    </span>
                                    <span> {{ $operation->operation_name }} </span>
                                </div>
                            </td>

                            <td class="border-right" style="width: 45%;">
                                <div class="row">
                                    <div class="col-md-6">

                                    @php $label = getParamFirstLabel($operation->operation_id); @endphp

                                     <small> {{ $label }}</small>

                                     <?php  $getVal = $getVal2 = ''; $cycleTime = 0;  $measure_type = '';
                                         
                                      $getVal = getParamFirstValue($operation->operation_id, $operation->id); //param1                            
                                      $getVal2 = getParamSecondValue($operation->operation_id, $operation->id); //param2
                                        
                                      if (optional($operation->operation_params)->parameter1 === 'Manual' || ($operation->operation_params)->parameter1 === 'Manual_ICT') { $getVal = $operation->cycle_time; }
                                       
                                      ?>
                                       
                                        @if ($getVal != 'NA' || $getVal != 'Outsourced' || $label != 'NA' || $label != 'Outsourced') 
                                          <input type="text" value="{{ $getVal }}" disabled class="form-control m-1" > 
                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        <small> {{ getParamSecondLabel($operation->operation_id) }} </small>
                                        @if ($getVal2 != '')
                                          
                                        <?php 
                                            if (optional($operation->operation_params)->parameter2 === 'Manual') {
                                                $getVal2 = $operation->cycle_time;
                                            }
                                        ?>
                                        @if ($getVal2 != 'NA' && $getVal2 != 'Outsourced')
                                         <input type="text" value="{{ $getVal2 }}" disabled
                                            class="form-control m-1"
                                            @if ($getVal2 == 'NA' || $getVal2 == 'Outsourced') {{ 'disabled' }} @endif>
                                        @endif
                                    @endif
                                    </div>
                                </div>
                            </td>

                            <td class="" style="width: 10%;"> </td>

                            <td class="" style="width: 20%;">
                                <div class="cycleTime">
                                    
                                </div>
                            </td>
                        </tr>
                    </table>

                </h2>

                <div id="collapse{{ $operation->id }}" class="accordion-collapse collapse" aria-labelledby="headingOne"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body py-0 pe-0" style="padding-left: 80px;">
                        <table class="table subTable-accordion table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                        Roll data11111
                                    </th>
                                    <th class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                        Operator name
                                    </th>
                                    <th class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                        Machine
                                    </th>
                                    <th class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                        Start time
                                    </th>
                                    <th class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                        Submit time
                                    </th>
                                    <th class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                        Time taken
                                    </th>
                                    <th class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                        Reason
                                    </th>
                                    <th class="bg-F0F5F6 text-445B64 px-3 fw-normal text-end">
                                        Status
                                    </th>
                                    <th class="bg-F0F5F6 text-445B64 px-3 fw-normal text-end">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="oprationTrackingDetails{{ $operation->operation_id }}">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </td>
    </tr>
@endforeach

<?php use App\Helpers\MyHelper; ?>

<style>
    .disabled-row {
        background-color: #f2f2f2;
        opacity: 0.6;
        position: relative;
        cursor: not-allowed;
    }

    /* Disable clickable elements */
    .disabled-row a,
    .disabled-row button,
    .disabled-row input {
        pointer-events: none;
    }

    .disabled-row::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 10px;
        background: #333;
        color: #fff;
        padding: 4px 8px;
        font-size: 12px;
        border-radius: 4px;
        white-space: nowrap;
        opacity: 0;
        transform: translateY(5px);
        transition: all 0.2s ease;
        pointer-events: none;
        z-index: 10;
    }

    .disabled-row:hover::after {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@foreach ($getOperationList as $operation)

    @php
        $disableRow = $title = '';
        if ($operation->parameter1_label == 'Stack Length' || $operation->parameter2_label == 'Stack Length') {
            $disableRow = 'disabled-row';
            $title = 'This operation not applicable';
    } @endphp
    <tr>
        <td colspan="4"
            class="p-0 {{ in_array(optional($operation->operation_type), ['Outsourced', 'Manual']) ? 'parentDiv' : '' }}">

            <div class="accordion-item border-0 {{ optional($operation->operation_type) ? 'operationTrackingOpen' : 'disabled-div' }}"
                detail-id="">

                <h2 class="accordion-header" id="headingOne">
                    <table class="table mb-0">

                        <tr data-id="{{ $operation->operation_id }}" data-sopid="{{ $data->id }}"
                            class="routeCardOperation {{ $disableRow }}" data-tooltip="{{ $title }}"
                            data-operation_type="{{ $operation->operation_type }}">

                            <td class="border-right" style="width: 5%;">
                                @if ($operation->operation_type != 'NA')
                                    @if (!empty($pass_sheet))
                                        <a class="accordion-button table-checkbox bg-white mx-auto text-637381"
                                            style="text-decoration:none;" title="view details"
                                            href="{{ route('admin.route-card-operation-details', $so->id) }}?operation_id={{ $operation->id }}&pass_id={{ $pass_sheet->id }}&sprd_id={{ $data->id }}">
                                            <i class="fa-solid fa-eye text-445B64"></i> </a>
                                    @else
                                        <a class="accordion-button table-checkbox bg-white mx-auto text-637381"
                                            style="text-decoration:none;" title="view details"
                                            href="{{ route('admin.route-card-operation-details', ['id' => $so->id, 'operation_id' => $operation->id, 'sprd_id' => $data->id]) }}">
                                            <i class="fa-solid fa-eye text-445B64"></i> </a>
                                    @endif
                                @endif
                            </td>

                            <td class="border-right" style="width: 20%;">
                                <div class="d-flex align-items-center">
                                    @if ($operation->operationData && $operation->operation_type != 'NA')
                                        <span class="table-square-icon bg-F0F5F6 rounded-3 img-zoom">
                                            <img src="{{ $operation->operationData->operation_qr_code }}"
                                                alt="QR Code">
                                        </span>
                                    @endif
                                    <span> {{ $operation->operation_id }}- {{ $operation->operation_name }} </span>
                                </div>
                            </td>

                            <td class="border-right" style="width: 45%;">

                             <?php
                                if (isset($pass_sheet)) {
                                    $parameter1Val = $operation->parameter1_value_set;
                                    $parameter2Val = $operation->parameter2_value_set;
                                } else {
                                    $parameter1Val = $operation->parameter1_value;
                                    $parameter2Val = $operation->parameter1_value;
                                }
                                
                                $getCycleTimeVal1 = getCycleTimeValue1($operation->operation_id, $data->id);
                                $getCycleTimeVal2 = getCycleTimeValue2($operation->operation_id, $data->id);
                                ?>

                                @if ($operation->operation_type != 'NA')

                                    @if ($operation->operation_type == 'Fixed_ICT')
                                        <div class="row">
                                            <div class="col-md-6"> <small> Fixed ICT </small>
                                                <input type="text" value="{{ $operation->fixed_ICT }}" disabled
                                                    class="form-control m-1">
                                            </div>
                                        </div>
                                    @elseif($operation->operation_type == 'ManualIn' || $operation->operation_type == 'Manual')
                                        <div class="row">
                                            <div class="col-md-6 parentDiv"> <small> {{ $operation->parameter1_label }}
                                                </small>
                                                <input type="text" class="form-control m-1 enterValManually1"
                                                    value="{{ $getCycleTimeVal1 }}" placeholder="Please Enter Value"
                                                    disabled>
                                            </div>

                                            @if ($operation->parameter2_label != 'NA')
                                                <div class="col-md-6 parentDiv"> <small>
                                                        {{ $operation->parameter2_label }} </small>
                                                    <input type="text" class="form-control m-1 enterValManually2"
                                                        placeholder="Please Enter Value" disabled
                                                        value="{{ $getCycleTimeVal2 }}">
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($operation->operation_type == 'Manual_ICT' || $operation->operation_type == 'Manual')
                                        <div class="row">
                                            <div class="col-md-6 parentDiv"> <small> Ideal Cycle Time </small>
                                                <input type="number" class="form-control m-1 enterValManually1"
                                                    value="{{ $getCycleTimeVal1 }}" placeholder="Please Enter Value"
                                                    disabled>
                                            </div>
                                        </div>
                                    @else
                                        @php
                                            $paramVal1 = getParamFirstValueNew(
                                                $operation->operation_id,
                                                $data->id,
                                                $pass_sheet->id ?? null,
                                            );
                                            $paramVal2 = getParamSecondValueNew(
                                                $operation->operation_id,
                                                $data->id,
                                                $pass_sheet->id ?? null,
                                            );
                                        @endphp
                                        @if (isset($pass_sheet))
                                            <div class="row">
                                                <div class="col-md-6"> <small> {{ $operation->parameter1_label }}
                                                    </small>
                                                    <input type="text"
                                                        value="{{ $paramVal1 }}" disabled
                                                        class="form-control m-1 enterValManually1">
                                                </div>
                                                @if ($operation->parameter2_label != 'NA')
                                                    <div class="col-md-6"> <small> {{ $operation->parameter2_label }}
                                                        </small>
                                                        <input type="text"
                                                            value="{{ $paramVal2 }}" disabled
                                                            class="form-control m-1 enterValManually2">
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="row">
                                                <div class="col-md-6"> <small> {{ $operation->parameter1_label }}
                                                        <b>~</b> ({{ $operation->parameter1_value }})
                                                    </small>
                                                    <input type="text"
                                                        value="@if ($paramVal1 == 'NA') {{ $operation->parameter1_value }}@else{{ $paramVal1 }} @endif"
                                                        disabled class="form-control m-1 enterValManually1">
                                                </div>
                                                @if ($operation->parameter2_label != 'NA')
                                                    <div class="col-md-6"> <small> {{ $operation->parameter2_label }}
                                                            <b>~</b> ({{ $operation->parameter2_value }})
                                                        </small>
                                                        <input type="text"
                                                            value="@if ($paramVal2 == 'NA') {{ $operation->parameter2_value }}@else{{ $paramVal2 }} @endif"
                                                            disabled class="form-control m-1 enterValManually2">
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    <div class="row">
                                        <div class="col-md-6"> Not available </div>
                                    </div>
                                @endif

                            </td>

                            <td class="" style="width: 10%;"> </td>

                            <td class="" style="width: 20%;">
                                <div class="cycleTime"> </div>
                            </td>
                        </tr>

                    </table>

                </h2>

            </div>
        </td>
    </tr>
@endforeach

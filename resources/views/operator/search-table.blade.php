@php $i=0; @endphp
  @foreach ($data as $operator)
    @php $i++; @endphp
    <tr>
        <td class="p-3">
            <div class="d-flex align-items-center">
                <div class="img-zoom">
                    <img src="{{ $operator->user_qr_code }}" alt="" class="me-2" style="width: 30px; height: 30px"
                        viewBox="0 0 12 12" fill="none" />
                </div>
                <div class="">
                    <a class="text-0D161A fw-semibold mb-0 text-decoration-none">{{ $operator->name }}<br /><span
                            class="text-445B64" style="font-size: 12px">#{{ $operator->username }}</span></a>
                </div>
            </div>
        </td>

        <td class="p-3">{{ $operator->designation }} </td>
        <td class="p-3">{{ $operator->department }} </td>
        <td class="p-3">{{ $operator->unit }} </td>
        <td class="p-3">{{ $operator->unit_name }} </td>
        <td class="p-3">{{ $operator->employee_group }} </td> 

        <td class="p-3" style="color: #00b200">
            Present
            <img src="{{ asset('assets/images/completeDot.png') }}" alt="" class=""
                style="width: 20px; height: 20px" />
        </td>

        <td> <a class="rounded-pill" type="submit"
                href="{{ route('admin.operator-details', ['id' => $operator->id]) }}">View Details</a> </td>

    </tr>
@endforeach

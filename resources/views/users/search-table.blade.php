@php $i=0;  @endphp

@foreach ($data as $user)
    @php $i++; @endphp

    <tr>
        <td class="p-3">
            <div class="d-flex align-items-center img-zoom">
                <img width="30" height="30" viewBox="0 0 12 12" fill="none" src="{{ $user->user_qr_code }}"
                    alt="" class="me-2" />
                <span class="ps-2">
                    <a class="text-0D161A fw-semibold mb-0 text-decoration-none">
                        {{ $user->name }} <br /> <span class="text-445B64"
                            style="font-size: 12px">#{{ $user->username }}
                        </span> </a>
                </span>
            </div>
        </td>
        <td class="p-3">{{ $user->designation }} </td>
        <td class="p-3">{{ $user->department }} </td>
        <td class="p-3">{{ $user->unit }} </td>
        <td class="p-3">{{ $user->unit_name }} </td>
        <td class="p-3">{{ $user->employee_group }} </td>

        <td class="p-3" style="color: #00b200"> Present
            <img src="{{ asset('assets/images/completeDot.png') }}" style="width: 20px; height: 20px" />
        </td>
        <td>
            <a href="{{ route('admin.user.edit', [$user->id]) }}">
                <i class="rounded-pill fw-bolder fa-solid fa-pencil"></i>
            </a> &nbsp;

            <a onclick="return confirm('Are you sure you want to delete this user?');"
                href="{{ route('admin.user.delete', [$user->id]) }}">
                <i class="rounded-pill fw-bolder fa-solid fa-trash"></i>
            </a>
        </td>
    </tr>
@endforeach

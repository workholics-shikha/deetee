 @php $i=0; @endphp
 @foreach ($data as $order)
     @php
         $i++;

         $orderType2 = 'NA';

         $parts = explode('-', $order->so_no);

         if ($parts[1] == 'I') {
             $orderType2 = 'Tooling';
         } elseif ($parts[1] == 'II') {
             $orderType2 = 'RMR';
         } elseif ($parts[1] == 'IV') {
             $orderType2 = 'TMR';
         }
     @endphp
     <tr>
         <th scope="row" class="sticky-column p-3 bg-white" style="position: sticky; left: 10px;">
             <div class="d-flex align-items-center">
                 <span class="table-square-icon bg-F0F5F6 rounded-3 img-zoom">
                     <img width="16" height="16" viewBox="0 0 12 12" fill="none" src="{{ $order->so_qr_code }}"
                         alt="QR Code">
                 </span>
                 <span class=""> {{ $order->so_no }} </span>
             </div>
         </th>
         <td scope="row" class="sticky-column p-3 bg-white" style="position: sticky; left: 10px;"> <b>
                 {{ $orderType2 ?? 'NA' }} </b> </td>

         <td>
             {{ $order->so_date ? \Carbon\Carbon::parse($order->so_date)->format('d M Y') : 'NA' }}
         </td>
 
         @php
             $addClass = '';
             $group = $order->so_group;
             if ($group == 'A' || $group == 'F') {
                 $addClass = 'D5FFCC text-116600';
             }
             if ($group == 'B') {
                 $addClass = 'FFCCF7 text-B20095';
             }
             if ($group == 'C-SB' || $group == 'C-TCOK') {
                 $addClass = 'CCCCFF text-0000FF';
             }
             if ($group == 'D' || $group == 'E' || $group == 'L') {
                 $addClass = 'FF9898 text-B20095';
             }
             if ($group == 'G') {
                 $addClass = 'CCFFCC text-00B200';
             }
             if ($group == 'H' || $group == 'I' || $group == 'J') {
                 $addClass = 'CCFFCC text-CC2200';
             }
             if ($group == 'K') {
                 $addClass = 'CCFFCC text-006699';
             }
             if ($group == 'M') {
                 $addClass = 'CCEEFF text-006699';
             }
         @endphp

         <td class="text-center"> <span class="rounded-pill bg-{{ $addClass }} px-2 py-1 fw-bolder">{{ $group ?? 'NA' }} </span> </td>
         <td class="text-center"> {{ $order->soquantity }} </td>
         <td class="text-center"> {{ $order->scr_status }} </td>
         <td class="text-445B64 text-center"> <a href="{{ route('admin.sales-order-details', ['id' => $order->id]) }}">
                 View Details </a> </td>
     </tr>
 @endforeach

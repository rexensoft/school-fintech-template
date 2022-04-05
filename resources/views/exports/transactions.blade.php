<table>
    @php
        $bold       = 'font-weight: bold;';
        $textCenter = 'text-align: center;';
        $borderLeft = 'border-left: 2px solid #000;'
    @endphp

    <thead>
        <tr>
            <th style="{{ $bold . $textCenter }}" valign="center" rowspan="2">ID</th>
            <th style="{{ $bold . $textCenter }}" valign="center" rowspan="2">Code</th>
            <th style="{{ $bold . $textCenter . $borderLeft }}" colspan="3">Sender</th>
            <th style="{{ $bold . $textCenter . $borderLeft }}" colspan="3">Receiver</th>
            <th style="{{ $bold . $textCenter . $borderLeft }}" valign="center" rowspan="2">Amount</th>
            <th style="{{ $bold . $textCenter }}" valign="center" rowspan="2">Type</th>
            <th style="{{ $bold . $textCenter }}" valign="center" rowspan="2">Status</th>
            <th style="{{ $bold . $textCenter }}" valign="center" rowspan="2">Created At</th>
            <th style="{{ $bold . $textCenter }}" valign="center" rowspan="2">Updated At</th>
        </tr>
        <tr>
            <th style="{{ $textCenter . $borderLeft}}">ID</th>
            <th style="{{ $textCenter }}">Name</th>
            <th style="{{ $textCenter }}">Email</th>
            <th style="{{ $textCenter . $borderLeft}}">ID</th>
            <th style="{{ $textCenter }}">Name</th>
            <th style="{{ $textCenter }}">Email</th>
        </tr>
    </thead>
    <tbody>

        @foreach($transactions as $key => $transaction)
        
        @php
            $bgColor = $key % 2 === 0 ? '#fff' : '#efefef';
            $bgColor = "background-color: $bgColor;";
        @endphp

        <tr>
            <td style="{{ $bgColor }}">{{ $transaction->id }}</td>
            <td style="{{ $bgColor }}">{{ $transaction->code }}</td>
            <td style="{{ $bgColor . $borderLeft }}">{{ $transaction->sender->id ?? '-' }}</td>
            <td style="{{ $bgColor }}">{{ $transaction->sender->name ?? '-' }}</td>
            <td style="{{ $bgColor }}">{{ $transaction->sender->email ?? '-' }}</td>
            <td style="{{ $bgColor . $borderLeft }}">{{ $transaction->receiver->id }}</td>
            <td style="{{ $bgColor }}">{{ $transaction->receiver->name }}</td>
            <td style="{{ $bgColor }}">{{ $transaction->receiver->email }}</td>
            <td style="{{ $bgColor . $borderLeft }}">{{ $transaction->amount ?? 0 }}</td>
            <td style="{{ $bgColor }}">{{ $transaction->type_name }}</td>
            <td style="{{ $bgColor }}">{{ $transaction->status_name }}</td>
            <td style="{{ $bgColor }}">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
            <td style="{{ $bgColor }}">{{ $transaction->updated_at->format('d/m/Y H:i:s') }}</td>
        </tr>
        @endforeach

    </tbody>
</table>
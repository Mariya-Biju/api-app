<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: DejaVu Sans;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #fffefeff;
        }
    </style>

</head>

<body>
    <h1 style="text-align:center;"> {{$institute}}</h1>
    <h2 style="text-align:center;">{{$paper_name}}--Assessment Report</h2>

    <table>
        <thead>
            <tr>
                <th>S No</th>
                <th>Admission No</th>
                @foreach($values->first() as $key => $value)
                @if(!in_array($key, ['admission_number', 'full_name', 'total']))
                <th>{{ ucwords(str_replace('_', ' ', $key)) }}</th>
                @endif
                @endforeach
                <th>Total</th>

            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach($values as $row)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $row->admission_number }}</td>
                <td>{{ $row->student_name }}</td>

                @foreach($row as $key => $value)
                @if(!in_array($key, ['admission_number', 'student_name', 'total']))
                <td>{{ $value ?? '-' }}</td>
                @endif
                @endforeach

                <td>{{ $row->total }}</td>
            </tr>
            @endforeach
        </tbody>

    </table>

</body>

</html>
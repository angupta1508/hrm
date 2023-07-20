<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: arial, sans-serif;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #222222;
            text-align: left;
            padding: 2px;
            font-size: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>

<body>

    <h2>{{ $top_heading }}</h2>
    @php($page = 1)
    <table>
        @foreach ($header as $key => $val)
            <tr>
                <th style="width: 100px;">{{ $key }}</th>
                <td>{{ $val }}</td>
            </tr>
        @endforeach
    </table>
    <table>
        <tr>
            @foreach ($headings as $heading)
                <th>{{ $heading }}</th>
            @endforeach
        </tr>
        @foreach ($records as $record)
            <tr>
                @foreach ($tabel_keys as $tabel_key)
                    <td class="text-center">
                        <p class="text-xs font-weight-bold mb-0 user_info">
                            {{ $record[$tabel_key] }}
                        </p>
                    </td>
                    @php($page++)
                @endforeach
            </tr>
        @endforeach


    </table>

</body>

</html>

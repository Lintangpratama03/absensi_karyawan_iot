<!DOCTYPE html>
<html>

<head>
    <title>Payroll Slip</title>
    <style>
        table {
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 100%;
            border: 1px solid black;
        }

        th,
        td {
            padding: 5px;
            border: 1px solid black;
            vertical-align: top;
        }

        th {
            background-color: red;
            color: white;
        }

        .bg-gray {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .border-top {
            border-top: 2px solid black;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td colspan="2">
                <img src="{{ $logoPath }}" alt="image" width="80px">
            </td>
            <td colspan="6">
                <p>
                    STMJ VETERAN<br>
                    Susu Telur Madu Jahe Veteran<br>
                    Pertokoan Mojoroto No. 18, Jl. Kawi, Mojoroto, Kec. Mojoroto, Kota Kediri, Jawa Timur 64112
                </p>
            </td>
        </tr>
        <tr>
            <th colspan="8" class="text-center">SLIP GAJI STMJ VETERAN</th>
        </tr>
        <tr>
            <td colspan="8" class="bg-gray">Nama: {{ $payroll->name }}</td>
        </tr>
        <tr>
            <td colspan="8" class="bg-gray">Bulan: {{ $payroll->month }}</td>
        </tr>
        <tr>
            <td>Total Gaji Pokok</td>
            <td>:</td>
            <td colspan="6" class="text-right">Rp {{ number_format($payroll->total_salary, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Bonus</td>
            <td>:</td>
            <td colspan="6" class="text-right">Rp {{ number_format($payroll->bonus, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Uang Bensin</td>
            <td>:</td>
            <td colspan="6" class="text-right">Rp {{ number_format($payroll->total_transport, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Denda</td>
            <td>:</td>
            <td colspan="6" class="text-right">Rp {{ number_format($payroll->cut, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="6" class="border-top">Total Gaji</td>
            <td colspan="2" class="border-top text-right">Rp {{ number_format($payroll->amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="8">
                Hari Kerja: {{ $payroll->count }} hari<br>
                Hari Libur: {{ $payroll->holiday }} hari<br>
                Telat: {{ $payroll->late }} hari<br>
            </td>
        </tr>
    </table>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .card-body {
            background-color: #ffffff;
            padding: 30px;
        }

        table th,
        table td {
            text-align: left;
            padding: 12px;
        }

        table th {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            font-size: 1.2rem;
            font-weight: bold;
            background-color: #f1f1f1;
        }

        .table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }

        hr {
            border-top: 2px solid #007bff;
            margin: 20px 0;
        }

        p {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        @media print {
            .card-header {
                background-color: #007bff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                color: white !important;
            }
        }
    </style>
    <title>Nota Order</title>
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1>Nota A68 Laundry</h1>
            </div>
            <div class="card-body">
                <p><strong>Data Pelanggan</strong></p>
                <table class="table">
                    <tbody>
                        <tr>
                            <td><strong>Nama</strong></td>
                            <td>:</td>
                            <td>{{ $orderan->orderanOffline->nama }}</td>
                        </tr>
                        <tr>
                            <td><strong>No. Hp</strong></td>
                            <td>:</td>
                            <td>{{ $orderan->orderanOffline->no_hp }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>:</td>
                            <td>{{ $orderan->orderanOffline->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Alamat</strong></td>
                            <td>:</td>
                            <td>{{ $orderan->orderanOffline->email }}</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <p><strong>Detail Orderan</strong></p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Order</th>
                            <th>Jenis Laundry</th>
                            <th>Berat</th>
                            <th>Harga</th>
                            <th>Pembayaran</th>
                            <th>Status Pembayaran</th>
                            <th>Status Cucian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>{{ $orderan->kode_order }}</td>
                            <td>{{ $orderan->jenisLaundry->nama }}</td>
                            <td>{{ $orderan->berat }}kg</td>
                            <td>Rp. {{ $orderan->harga }}</td>
                            <td>{{ $orderan->metode_pembayaran }}</td>
                            <td>{{ $orderan->statusPembayaran->last()->status }}</td>
                            <td>{{ $orderan->statusCucian->last()->status }}</td>
                        </tr>
                        <tr class="total-row">
                            <td colspan="7" class="text-right">Total Bayar</td>
                            <td>Rp. {{ $orderan->harga }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script>
    window.onload = function() {
        window.print();
    };
</script>

</html>

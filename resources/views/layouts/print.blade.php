<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | E-Sewa Fasilitas DISPORA</title>
    <style>
        /* Print-specific styling */
        @page {
            size: A4;
            margin: 15mm;
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
        }

        /* General styling */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #333;
            background-color: #fff;
        }
        .print-buttons {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: center;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            border-bottom: 1px solid #ddd;
        }
        .print-buttons button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        .print-buttons button.back {
            background-color: #555;
        }

        /* Receipt styling */
        .receipt-container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px 0;
        }
        .receipt-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 80px;
            margin-right: 20px;
        }
        .logo img {
            width: 100%;
            height: auto;
        }
        .receipt-title h1 {
            font-size: 18pt;
            color: #c00000;
            margin-bottom: 5px;
        }
        .receipt-title p {
            font-size: 10pt;
            color: #666;
            margin: 0;
        }
        .receipt-divider {
            height: 2px;
            background-color: #c00000;
            margin: 10px 0;
        }
        .receipt-info {
            text-align: center;
            margin: 20px 0;
        }
        .receipt-info h2 {
            font-size: 16pt;
            margin-bottom: 10px;
        }
        .receipt-number {
            display: inline-block;
            padding: 5px 15px;
            border: 1px dashed #999;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .receipt-body {
            margin: 30px 0;
        }
        .customer-info, .facility-info {
            margin-bottom: 20px;
        }
        .customer-info table, .facility-info table {
            width: 100%;
        }
        .customer-info td, .facility-info td {
            padding: 5px 0;
        }
        .customer-info td:first-child, .facility-info td:first-child {
            width: 35%;
            font-weight: bold;
        }
        .status-lunas {
            background-color: #28a745;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10pt;
        }
        h3 {
            font-size: 14pt;
            margin: 20px 0 10px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 20px;
        }
        .details-table th, .details-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .details-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .details-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .details-table tfoot {
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .notes {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #c00000;
            margin: 20px 0;
        }
        .notes ol {
            margin-left: 20px;
        }
        .notes li {
            margin: 5px 0;
        }
        .receipt-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px dashed #ddd;
        }
        .receipt-signature {
            text-align: right;
            margin-bottom: 30px;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }
        .signature-line {
            height: 60px;
        }
        .receipt-validation {
            text-align: center;
            font-size: 9pt;
            color: #666;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="no-print print-buttons">
        <button onclick="window.print()">Cetak Dokumen</button>
        <button class="back" onclick="window.history.back()">Kembali</button>
    </div>

    <div style="margin-top: 60px;" class="no-print"></div>

    @yield('content')

    <script>
        // Auto print when page loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>

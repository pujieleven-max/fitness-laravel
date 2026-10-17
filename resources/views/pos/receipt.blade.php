<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran #{{ $order->invoice_no }} - {{ $appSetting->app_name ?? 'FitZone Gym' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=Plus+Jakarta+Sans:wght@500;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Courier Prime', monospace;
            background: #f1f5f9;
            color: #000;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .no-print {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .btn {
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-print {
            background: #10b981;
            color: #000;
        }
        .btn-back {
            background: #334155;
            color: #fff;
        }
        .receipt-container {
            width: 80mm;
            background: #fff;
            padding: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 4px;
        }
        .center {
            text-align: center;
        }
        .right {
            text-align: right;
        }
        .gym-name {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .gym-address {
            font-size: 11px;
            color: #333;
            margin-top: 2px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        .meta-table {
            width: 100%;
            font-size: 11px;
        }
        .meta-table td {
            padding: 2px 0;
        }
        .items-table {
            width: 100%;
            font-size: 11px;
            border-collapse: collapse;
        }
        .items-table th {
            text-align: left;
            border-bottom: 1px dashed #000;
            padding-bottom: 4px;
        }
        .items-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .total-row {
            font-weight: 700;
            font-size: 13px;
        }
        .footer-note {
            font-size: 10px;
            margin-top: 10px;
            line-height: 1.4;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .receipt-container {
                box-shadow: none;
                padding: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn btn-print" onclick="window.print()">
            &#128438; Cetak Struk (Print)
        </button>
        <a href="{{ route('pos.index') }}" class="btn btn-back">
            &larr; Kembali ke Kasir
        </a>
    </div>

    <div class="receipt-container">
        <!-- Header -->
        <div class="center">
            @if(!empty($appSetting->logo_url))
                <img src="{{ $appSetting->logo_url }}" alt="Logo" style="max-height: 48px; max-width: 130px; margin-bottom: 5px; object-fit: contain;">
            @endif
            <div class="gym-name">{{ strtoupper($appSetting->app_name ?? 'FITZONE GYM & FITNESS') }}</div>
            <div class="gym-address">{{ $appSetting->address ?? 'Jl. Gatot Subroto No. 88, Jakarta Selatan' }}</div>
            <div class="gym-address">Telp / WA: {{ $appSetting->phone ?? '0812-3456-7890' }}</div>
            @if(!empty($appSetting->social_instagram))
                <div class="gym-address" style="font-size: 10px; color: #555;">IG: {{ $appSetting->social_instagram }}</div>
            @endif
        </div>

        <div class="divider"></div>

        <!-- Meta -->
        <table class="meta-table">
            <tr>
                <td>No. Invoice:</td>
                <td class="right"><strong>{{ $order->invoice_no }}</strong></td>
            </tr>
            <tr>
                <td>Waktu:</td>
                <td class="right">{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Kasir:</td>
                <td class="right">{{ $order->user ? $order->user->name : 'Kasir' }}</td>
            </tr>
            <tr>
                <td>Pelanggan:</td>
                <td class="right">{{ $order->customer_name }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="center">Qty</th>
                    <th class="right">Harga</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td colspan="4"><strong>{{ $item->name }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color:#555;">{{ $item->item_type }}</td>
                        <td class="center">{{ $item->quantity }}</td>
                        <td class="right">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="right"><strong>{{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <!-- Calculations -->
        <table class="meta-table">
            <tr>
                <td>Subtotal:</td>
                <td class="right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if($order->discount > 0)
                <tr>
                    <td>Diskon:</td>
                    <td class="right">- Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if($order->tax > 0)
                <tr>
                    <td>PPN (Pajak):</td>
                    <td class="right">+ Rp {{ number_format($order->tax, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td style="padding-top: 4px;">TOTAL TAGIHAN:</td>
                <td class="right" style="padding-top: 4px;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="divider" colspan="2"></td>
            </tr>
            <tr>
                <td>Metode Bayar:</td>
                <td class="right"><strong>{{ strtoupper($order->payment_method) }}</strong></td>
            </tr>
            <tr>
                <td>Uang Diterima:</td>
                <td class="right">Rp {{ number_format($order->cash_received, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembalian:</td>
                <td class="right">Rp {{ number_format($order->cash_change, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Footer -->
        <div class="center footer-note">
            <div style="font-weight: 700; margin-bottom: 4px;">*** FIT & HEALTHY ***</div>
            <div style="white-space: pre-line;">{!! nl2br(e($appSetting->receipt_footer ?: 'Terima kasih telah berlatih bersama kami!')) !!}</div>
            <div style="margin-top: 5px; font-size: 10px; color: #555;">Simpan struk ini sebagai bukti transaksi yang sah.</div>
        </div>
    </div>

</body>
</html>

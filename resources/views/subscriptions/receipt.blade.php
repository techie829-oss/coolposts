<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $transaction->transaction_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .receipt {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 300;
        }
        .header .company {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }
        .content {
            padding: 30px;
        }
        .transaction-info {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .label {
            font-weight: 600;
            color: #495057;
        }
        .value {
            color: #212529;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            text-align: center;
            padding: 20px;
            background: #f8fff9;
            border-radius: 6px;
            margin: 20px 0;
        }
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status.completed {
            background: #d4edda;
            color: #155724;
        }
        .status.pending {
            background: #fff3cd;
            color: #856404;
        }
        .status.failed {
            background: #f8d7da;
            color: #721c24;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
        .logo {
            font-size: 32px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="logo">👑</div>
            <h1>Payment Receipt</h1>
            <div class="company">CoolPosts</div>
        </div>

        <div class="content">
            <div class="transaction-info">
                <div class="info-row">
                    <span class="label">Transaction ID:</span>
                    <span class="value">{{ $transaction->transaction_id }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Date:</span>
                    <span class="value">{{ $transaction->created_at->format('M d, Y H:i:s') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Status:</span>
                    <span class="value">
                        <span class="status {{ $transaction->status }}">{{ $transaction->getStatusDisplayName() }}</span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">Payment Gateway:</span>
                    <span class="value">{{ $transaction->gateway->name }}</span>
                </div>
                @if($transaction->gateway_transaction_id)
                <div class="info-row">
                    <span class="label">Gateway ID:</span>
                    <span class="value">{{ $transaction->gateway_transaction_id }}</span>
                </div>
                @endif
                @if($transaction->subscription)
                <div class="info-row">
                    <span class="label">Subscription:</span>
                    <span class="value">{{ $transaction->subscription->plan->name }} ({{ $transaction->subscription->plan->billing_cycle }})</span>
                </div>
                @endif
            </div>

            <div class="amount">
                {{ $transaction->getFormattedAmount() }}
            </div>

            @if($transaction->gateway_fee > 0)
            <div class="transaction-info">
                <div class="info-row">
                    <span class="label">Gateway Fee:</span>
                    <span class="value">{{ $transaction->getFormattedGatewayFee() }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Net Amount:</span>
                    <span class="value">{{ $transaction->getFormattedNetAmount() }}</span>
                </div>
            </div>
            @endif

            @if($transaction->description)
            <div class="transaction-info">
                <div class="info-row">
                    <span class="label">Description:</span>
                    <span class="value">{{ $transaction->description }}</span>
                </div>
            </div>
            @endif

            @if($transaction->payment_method)
            <div class="transaction-info">
                <div class="info-row">
                    <span class="label">Payment Method:</span>
                    <span class="value">{{ ucfirst($transaction->payment_method) }}</span>
                </div>
                @if($transaction->payment_method_details)
                <div class="info-row">
                    <span class="label">Method Details:</span>
                    <span class="value">{{ $transaction->payment_method_details }}</span>
                </div>
                @endif
            </div>
            @endif
        </div>

        <div class="footer">
            <p>Thank you for your payment!</p>
            <p>This receipt serves as proof of your transaction. Please keep it for your records.</p>
            <p>For support, contact us at techie829@gmail.com</p>
        </div>
    </div>
</body>
</html>

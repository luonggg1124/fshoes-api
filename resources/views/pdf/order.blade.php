
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Invoice: #{{$order->id}}</title>


    <!-- Invoice styling -->
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            text-align: center;
            color: #777;
        }

        body h1 {
            font-weight: 300;
            margin-bottom: 0px;
            padding-bottom: 0px;
            color: #000;
        }

        body h3 {
            font-weight: 300;
            margin-top: 10px;
            margin-bottom: 20px;
            font-style: italic;
            color: #555;
        }

        body a {
            color: #06f;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }
    </style>
</head>

<body>
<div class="invoice-box">
    <table>
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <img src="https://sparksuite.github.io/simple-html-invoice-template/images/logo.png" alt="Fshoes logo" style="width: 100%; max-width: 300px" />
                        </td>

                        <td>
                            Invoice #: {{$order->id}}<br />
                            Created: {{\Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i')}}<br />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                           {{$order->address}} <br>
                            {{$order->city}} <br>
                            {{$order->country}}
                        </td>

                        <td>
                            {{$order->receiver_full_name}}.<br />
                            {{$order->phone}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <td>Payment Method</td>

            <td>#{{$order->payment_method}}</td>
        </tr>
        <tr class="heading">
            <td>Status</td>

            <td style="">{{$order->status}}</td>
        </tr>
        <tr class="heading">
            <td>Items</td>

            <td>Price</td>
        </tr>
        <?php $sum =0; ?>
        @foreach($order->orderDetails as $item):

            <tr class="item">
                <td>{{$item->product_id ? $item->product->name  : $item->variation->name}}  (x{{$item->quantity}})</td>
                <?php $sum+=$item->price ?>
                <td>  {{ number_format($item->price, 2, '.', ',') }} VND</td>
            </tr>
        @endforeach


        <tr class="total" >
            <td></td>
            <td >
                <div class="label-value" style="display: flex; justify-content: space-around">
                    <span class="label" style="text-align: left">Subtotal:</span>
                    <span class="value">{{ number_format($sum, 2, '.', ',') }} VND</span>
                </div>
                <div class="label-value"  style="display: flex; justify-content: space-between">
                    <span class="label" style="text-align: left">Delivery Fee:</span>
                    <span class="value">+{{ number_format($item->shipping_cost, 2, '.', ',') }} VND</span>
                </div>
                @if($order->voucher_id)
                    <div class="label-value"  style="display: flex; justify-content: space-between">
                        <span class="label" style="margin-right: 10px">Voucher:</span>
                        <span class="value">-{{ number_format(($sum + $item->shipping_cost) * $voucher->discount / 100, 2, '.', ',') }} VND</span>
                    </div>
                @endif
                <div class="label-value"  style="display: flex; justify-content: space-between">
                    <span class="label" style="margin-right: 10px">Total:</span>
                    <span class="value">{{ number_format($item->total_amount, 2, '.', ',') }} VND</span>
                </div>
            </td>
        </tr>

    </table>
</div>
</body>
</html>

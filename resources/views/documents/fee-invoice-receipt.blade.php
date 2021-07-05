<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fee Receipt {{ $data->id }}</title>

    <style>
        .receipt-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            /* border: 1px solid #eee; */
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); */
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .receipt-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .receipt-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .receipt-box table tr td:nth-child(2) {
            text-align: right;
        }

        .receipt-box table tr td:nth-child(3) {
            text-align: right;
        }
        .receipt-box table tr td:nth-child(4) {
            text-align: right;
        }

        .receipt-box table tr.top table td {
            padding-bottom: 20px;
        }

        .receipt-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .receipt-box table tr.information table td {
            padding-bottom: 40px;
        }

        .receipt-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .receipt-box table tr.details td {
            padding-bottom: 20px;
        }

        .receipt-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .receipt-box table tr.item.last td {
            border-bottom: none;
        }

        .receipt-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .receipt-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .receipt-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .receipt-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .receipt-box.rtl table {
            text-align: right;
        }

        .receipt-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }

    </style>
</head>

<body>
    <div class="receipt-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="{{ env('LOGO') }}"
                                    style="width: 100%; max-width: 200px" />
                            </td>

                            <td>
                                Receipt #: {{ $receipt->id }}<br />

                                Invoice #: {{ $data->id }}<br />

                                Order #: {{ $receipt->feeInvoice->payment->razorpays->first()->order_id }} <br />

                                Payment #: {{ $receipt->feeInvoice->payment->razorpays->first()->payment_id }} <br />

                                Payment Date: {{ $receipt->created_at->toDateTimeString() }} <br />
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
                                Avant SMS.<br />
                                Webrefiner Pvt Ltd<br />
                                Guwahati, Assam, India 781001 <br />
                                GST: 18HAGTS5485RT
                            </td>

                            <td>
                                {{ $data->user->userDetail->name }}<br />
                                {{ $data->user->email }}<br />
                                Class {{ $data->standard->name }}<br />
                                {{ $data->billFee->bill->session->name }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table cellpadding="0" cellspacing="0">

            <tr class="heading">
                <td>Item</td>

                <td>Net (Rs)</td>

                <td>Tax (Rs)</td>

                <td>Gross (Rs)</td>
            </tr>
            @foreach ($data->feeInvoiceItems as $item)
                <tr class="item">
                    <td>
                        <strong>{{ $item->name }}</strong> <br />
                        {{ $item->description }}
                    </td>

                    <td>
                        {{ $item->amount_in_cent/100 }}/-
                    </td>

                    <td>
                        {{ ($item->amount_in_cent/100)*$item->tax_rate/100 }}/-
                    </td>

                    <td>
                        {{ $item->gross_amount_in_cent / 100 }}/-
                    </td>
                </tr>
            @endforeach


            <tr class="total">
                <td></td>

                <td colspan="3">Total: {{ $data->gross_amount_in_cent / 100 }}/-</td>
            </tr>

        </table>
		<table>
			<tr>
				<td></td>
				<td>
					<br />
					<br />
					<br />
					For: Avant SMS<br />
				</td>
			</tr>
		</table>
    </div>
</body>

</html>

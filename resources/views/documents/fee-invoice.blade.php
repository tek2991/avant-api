<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fee Invoice {{ $data->id }}</title>

    <style>
        .invoice-box {
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

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr td:nth-child(3) {
            text-align: right;
        }
        .invoice-box table tr td:nth-child(4) {
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

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }

    </style>
</head>

<body>
    <div class="invoice-box">
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
                                Invoice #: {{ $data->id }}<br />

                                Created: {{ $data->billFee->bill->created_at->toFormattedDateString() }}<br />

                                Due: {{ $data->billFee->bill->bill_due_date->toFormattedDateString() }} <br />

                                For: {{ $data->billFee->bill->bill_from_date->toFormattedDateString() }} to
                                {{ $data->billFee->bill->bill_to_date->toFormattedDateString() }}
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

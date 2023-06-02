<!DOCTYPE html>
<html>

<head>
    <title>Delivery Note</title>
</head>
<style type="text/css">
    body {
        font-family: 'Roboto Condensed', sans-serif;
    }

    .m-0 {
        margin: 0px;
    }

    .p-0 {
        padding: 0px;
    }

    .pt-5 {
        padding-top: 5px;
    }

    .mt-10 {
        margin-top: 10px;
    }

    .text-center {
        text-align: center !important;
    }

    .w-100 {
        width: 100%;
    }

    .w-50 {
        width: 50%;
    }

    .w-85 {
        width: 85%;
    }

    .w-15 {
        width: 15%;
    }

    .logo img {
        width: 200px;
        height: 60px;
    }

    .gray-color {
        color: #5D5D5D;
    }

    .text-bold {
        font-weight: bold;
    }

    .border {
        border: 1px solid black;
    }

    table tr,
    th,
    td {
        border: 1px solid #d2d2d2;
        border-collapse: collapse;
        padding: 7px 8px;
    }

    table tr th {
        background: #F4F4F4;
        font-size: 15px;
    }

    table tr td {
        font-size: 13px;
    }

    table {
        border-collapse: collapse;
    }

    .box-text p {
        line-height: 10px;
    }

    .float-left {
        float: left;
    }

    .total-part {
        font-size: 16px;
        line-height: 12px;
    }

    .total-right p {
        padding-right: 20px;
    }
</style>

<body>
    <div class="head-title">
        <h1 class="text-center m-0 p-0">Delivery Note</h1>
        {{ $delivery_note->delivery_note_detail }}
    </div>
    <div class="add-detail mt-10">
        <div class="w-50 float-left mt-10">
            <p class="m-0 pt-5 text-bold w-100">Delivery Note Id - <span
                    class="gray-color">#{{ $delivery_note->custom_delivery_note_id }}</span></p>
            <p class="m-0 pt-5 text-bold w-100">Delivery Note Date - <span class="gray-color">22-01-2023</span></p>
        </div>
        <div class="w-50 float-left logo mt-10">
            <img src="https://techsolutionstuff.com/frontTheme/assets/img/logo_200_60_dark.png" alt="Logo">
        </div>
        <div style="clear: both;"></div>
    </div>
    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <th class="w-50">Suppliers Details</th>
            </tr>
            <tr>
                <td>
                    <div class="box-text">
                        <p>{{ $delivery_note->supplier_name }}</p>
                        <p>{{ $delivery_note->supplier->address }}</p>
                        <p>{{ $delivery_note->supplier->phone }}</p>
                    </div>
                </td>
                <td>

                </td>
            </tr>
        </table>
    </div>
    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <th class="w-50">Product ID</th>
                <th class="w-50">Product Name</th>
                <th class="w-50">Qty</th>
                <th class="w-50">Unit</th>
                <th class="w-50">Price</th>
                <th class="w-50">Subtotal</th>
            </tr>
            <tr>

            </tr>
            {{-- @foreach ($delivery_note->delivery_note_detail as $dn_items)
            <tr>
                <td>{{ $dn_items->product->custom_product_id }}</td>
                <td>{{ $dn_items->name }}</td>
                <td>{{ $dn_items->quantity }}</td>
                <td>{{ $dn_items->product->unit }}</td>
                <td>{{ $dn_items->price }}</td>
                <td>{{ $dn_items->line_total }}</td>
                </tr>
            @endforeach --}}
            <tr align="center">


            <tr>
                <td colspan="7">
                    <div class="total-part">
                        <div class="total-left w-85 float-left" align="right">
                            <p>Sub Total</p>
                            <p>Total Payable</p>
                        </div>
                        <div class="total-right w-15 float-left text-bold" align="right">
                            <p>{{ $delivery_note->sub_total }}</p>
                            <p>{{ $delivery_note->grand_total }}</p>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</html>

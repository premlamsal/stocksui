<!DOCTYPE html>
<html>

<head>
    <title>Weekly Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            font-size: 16px;
            padding: 20px;
        }

        .header {
            margin-bottom: 20px;
            position: relative;
            height: 316px;
            width: 100%;
        }

        .logo {
            /*            float: left;*/
            width: 150px;
        }


        .section {
            margin-bottom: 40px;
            background-color: #f2f2f2;
            padding: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            text-align: center;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background-color: #ddd;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
        }

        .date-box-container {
            position: relative;
            height: 75px;
        }

        .date-box {
            margin: 16px;
        }

        .date {
            border: 1px solid #000;
            padding: 10px;
            width: 200px;
            margin-top: 10px;
        }

        .date-label {
            text-align: center;
            margin-top: 4px;
        }

        .note-box {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 16px;
        }

        .custom-checkbox {
            border: 1px solid #000;
            padding: 10px;
            width: 2px;
            margin: auto;
        }

        .boat-name-box {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 10px;
            width: 235px;
        }

        .brand-logo {
            text-align: center;
        }

        .boat-name-container {
            margin-top: 32px;
        }

        .picked-container {
            margin-bottom: 17px;
        }

        .signed-container {}

        .footer-inside {
            font-weight: bold;
            font-style: italic;
        }

        .line {
            display: inline-block;
            border-bottom: 1px solid #000;
            padding: 0px 75px 0px 75px;
        }

        .picked-container::after {
            content: "";
            display: table;
            clear: both;
        }

        .head-left {
            width: 260px;
            position: absolute;
            left: 0;
            bottom: 0;
        }

        .head-right {
            width: 480px;
            position: absolute;
            right:0;
            bottom: 0;
        }

        .date-box-order {
            position: absolute;
            left: 0;
        }

        .date-box-delivery {
            position: absolute;
            right: 0;
        }
    </style>
</head>

<body>
    <div style="text-align: center">Weekly Order :: # {{$weeklyorder->id}}</div>
    <div class="header-container">
        <div class="header">

            <div class="head-left">
                <div class="brand-logo">
                    <img class="logo" src="{{ $image }}" alt="Logo">
                </div>
                <div class="boat-name-container">
                    <div class="boat-label">
                        Boat Name:
                    </div>
                    <div class="boat-name-box">
                        Boat Name here
                    </div>
                </div>
            </div>
            <div class="head-right">
                <div class="note-box">
                    Communication / Crockery
                    <br />
                    Tableware etc
                    <br />
                    Requirements
                </div>

                <div class="date-box-container">
                    <div class="date-box-order">
                        <div class="date-label">Date Order Requested:</div>
                        <div class="date">insert date here</div>
                    </div>
                    <div class="date-box-delivery">
                        <div class="date-label">Delivery Date:</div>
                        <div class="date">insert date here</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="section">
        <h3>Cleaning Products</h3>
        <table>
            <tr>
                <th>Shelf Code</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Picked</th>
                <th>Checked</th>
            </tr>
           
            @foreach ($weeklyorder->WeeklyOrderDetailC as$item)
            <tr>
                <td>{{$item->shelf_code}}</td>
                <td>{{$item->product_name}}</td>

                <td>{{$item->quantity}}</td>

                <td>
                    <div class="custom-checkbox-container">
                        <div class="custom-checkbox"></div>
                    </div>
                </td>
                <td>Yes | No</td>

            </tr>
            @endforeach
            <!-- Add more rows with data here -->
        </table>
    </div>

    <div class="section">
        <h3>Miscellaneous</h3>
        <table>
            <tr>
                <th>Shelf Code</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Picked</th>
                <th>Checked</th>

            </tr>
           
            @foreach ($weeklyorder->WeeklyOrderDetailM as$item)
            <tr>
                <td>{{$item->shelf_code}}</td>
                <td>{{$item->product_name}}</td>

                <td>{{$item->quantity}}</td>

                <td>
                    <div class="custom-checkbox-container">
                        <div class="custom-checkbox"></div>
                    </div>
                </td>
                <td>Yes | No</td>

            </tr>
            @endforeach
            <!-- Add more rows with data here -->
        </table>
    </div>

    <div class="section">
        <h3>Documentation</h3>
        <table>
            <tr>
                <th>Shelf Code</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Picked</th>
                <th>Checked</th>

            </tr>

            @foreach ($weeklyorder->WeeklyOrderDetailD as$item)
            <tr>
                <td>{{$item->shelf_code}}</td>
                <td>{{$item->product_name}}</td>

                <td>{{$item->quantity}}</td>

                <td>
                    <div class="custom-checkbox-container">
                        <div class="custom-checkbox"></div>
                    </div>
                </td>
                <td>Yes | No</td>

            </tr>
            @endforeach
           
          
            <!-- Add more rows with data here -->
        </table>
    </div>

    <div class="footer">
        <div class="footer-inside">
            <div class="picked-container">
                <div class="picked-label">Picked By: <div class="line"></div>
                </div>
            </div>
            <div class="signed-container">
                <div class="picked-label">Checked By: <div class="line"></div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

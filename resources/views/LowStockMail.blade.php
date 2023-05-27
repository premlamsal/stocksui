<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Low Stock Mail</title>
</head>
<body>
    <h3>Hi,
        There are ({{$data->count()}} ) Stock Items that have been flagged as low stock. </h3>
       <p> Timestamp: {{\Carbon\Carbon::now()->toDateString() }}</p>
        <p>All stock listed below is currently listed as low stock:</p>
    
    @foreach ($data as $item)
        Product ID:: {{$item->product_id}} 
        STOCK ID :: {{$item->id}}
        STOCK :: {{$item->quantity}}
        <br/>

    @endforeach

    <p>Regards, Admin</p>

   
</body>
</html>
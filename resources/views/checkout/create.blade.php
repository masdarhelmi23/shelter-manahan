<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>

<h1>Checkout Produk</h1>

<h3>{{ $product->name }}</h3>
<p>Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</p>

<form action="{{ route('checkout.pay') }}" method="POST">
    @csrf

    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="hidden" name="total_price" value="{{ $product->price }}">

    <label>Qty:</label>
    <input type="number" name="qty" value="1" min="1">

    <button type="submit">Bayar Sekarang</button>
</form>

</body>
</html>
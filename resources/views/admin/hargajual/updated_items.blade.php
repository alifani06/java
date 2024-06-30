@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Data yang Telah Diubah</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Member Harga</th>
                <th>Diskon Member</th>
                <th>Non Member Harga</th>
                <th>Diskon Non Member</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($updatedItems as $item)
                <tr>
                    <td>{{ $item['produk'] }}</td>
                    <td>{{ $item['updatedFields']['member_harga'] ?? '-' }}</td>
                    <td>{{ $item['updatedFields']['diskon_member'] ?? '-' }}</td>
                    <td>{{ $item['updatedFields']['non_member_harga'] ?? '-' }}</td>
                    <td>{{ $item['updatedFields']['diskon_non_member'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

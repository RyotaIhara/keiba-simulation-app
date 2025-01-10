<!-- resources/views/items/show.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Item Details</h1>
    <p><strong>Item Name:</strong> {{ $item->getItemName() }}</p>
    <p><strong>Amount:</strong> {{ $item->getItemName() }}</p>
    <p><strong>Count:</strong> {{ $item->getCount() }}</p>
    <a href="{{ route('items.index') }}" class="btn btn-secondary">Back</a>
    <a href="{{ route('items.edit', $item->getId()) }}" class="btn btn-warning">Edit</a>
@endsection

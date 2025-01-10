<!-- resources/views/items/create.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Add New Item</h1>
    <form action="{{ route('items.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="item_name">Item Name</label>
            <input type="text" name="item_name" id="item_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="count">Count</label>
            <input type="number" name="count" id="count" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('items.index') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection

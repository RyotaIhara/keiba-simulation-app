<!-- resources/views/items/index.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Items List</h1>
    <a href="{{ route('items.create') }}" class="btn btn-primary">Add New Item</a>
    <table class="table">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Amount</th>
                <th>Count</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->getItemName() }}</td>
                    <td>{{ $item->getAmount() }}</td>
                    <td>{{ $item->getCount() }}</td>
                    <td>
                        <a href="{{ route('items.show', $item->getId()) }}" class="btn btn-info">View</a>
                        <a href="{{ route('items.edit', $item->getId()) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('items.destroy', $item->getId()) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

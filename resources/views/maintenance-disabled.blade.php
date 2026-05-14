@extends('layout')

@section('content')
    @if (!empty($dbError))
        <div class="card error-card">
            <h2>Database Error</h2>
            <p>{{ $dbError }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="card error-card">
            <h2>Save/Delete Error</h2>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="card">
        <h2>Add Maintenance Entry</h2>

        <form action="/maintenance" method="POST">
            @csrf

            <label>Title</label>
            <input type="text" name="title" placeholder="Oil change, exhaust fix..." required>

            <label>Mileage</label>
            <input type="number" name="mileage" placeholder="195000">

            <label>Cost (€)</label>
            <input type="number" step="0.01" name="cost" placeholder="75.50">

            <label>Date</label>
            <input type="date" name="service_date">

            <label>Notes</label>
            <textarea name="notes" placeholder="What was done? Any issues?"></textarea>

            <button type="submit">Save Entry</button>
        </form>
    </div>

    <div class="card">
        <h2>Maintenance History</h2>

        @forelse ($maintenances as $maintenance)
            <div class="entry">
                <h3>{{ $maintenance->title }}</h3>
                <p><strong>Mileage:</strong> {{ $maintenance->mileage ?? 'N/A' }} km</p>
                <p><strong>Cost:</strong> €{{ $maintenance->cost ?? '0.00' }}</p>
                <p><strong>Date:</strong> {{ $maintenance->service_date ?? 'No date' }}</p>
                <p>{{ $maintenance->notes }}</p>

                <form action="/maintenance/{{ $maintenance->id }}" method="POST" onsubmit="return confirm('Delete this maintenance entry?');">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="delete-btn">Delete</button>
                </form>
            </div>
        @empty
            <p>No maintenance entries yet.</p>
        @endforelse

    </div>

@endsection

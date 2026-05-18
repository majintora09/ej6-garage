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
        <h2>Add Mod</h2>

        <form action="/mods" method="POST">
            @csrf

            <label>Mod Name</label>
            <input type="text" name="name" required>

            <label>Category</label>
            <input type="text" name="category" placeholder="Suspension, Exhaust, Wheels...">

            <label>Price (€)</label>
            <input type="number" step="0.01" name="price">

            <label>Priority</label>
            <select name="priority">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>

            <label>Status</label>
            <select name="status">
                <option value="Wanted">Wanted</option>
                <option value="Ordered">Ordered</option>
                <option value="Installed">Installed</option>
            </select>

            <label>Link</label>
            <input type="text" name="link">

            <label>Notes</label>
            <textarea name="notes"></textarea>

            <button type="submit">Save Mod</button>
        </form>
    </div>

    <div class="card">
        <h2>Mods List</h2>

        @forelse ($mods as $mod)
            <div class="entry">
                <h3>{{ $mod->name }}</h3>

                <p><strong>Category:</strong> {{ $mod->category ?? 'N/A' }}</p>

                <p><strong>Price:</strong> €{{ $mod->price ?? '0.00' }}</p>

                <p><strong>Priority:</strong> {{ $mod->priority ?? 'N/A' }}</p>

                <p><strong>Status:</strong> {{ $mod->status ?? 'N/A' }}</p>

                @if($mod->link)
                    <p>
                        <a href="{{ $mod->link }}" target="_blank">
                            View Part
                        </a>
                    </p>
                @endif

                <p>{{ $mod->notes }}</p>

                <form
                    action="/mods/{{ $mod->id }}"
                    method="POST"
                    onsubmit="return confirm('Delete this mod?');"
                >
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="delete-btn">
                        Delete
                    </button>
                </form>
            </div>
        @empty
            <p>No mods added yet.</p>
        @endforelse
    </div>

@endsection

@extends('layout')

@section('content')

    <div class="hero-card mods-hero">
        <div>
            <h1>EJ6 Build Planner</h1>

            <p class="hero-subtitle">
                Smart suggestions based on your EJ6 build style.
            </p>

            <div class="badge-row">
                <span class="badge">Dark Green</span>
                <span class="badge">Majin Style</span>
                <span class="badge">Street JDM</span>
                <span class="badge">Budget Conscious</span>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">

        <div class="card ai-card">
            <h2>AI Build Assistant</h2>

            <p>
                Suggestions tailored for your EJ6 build and current issues.
            </p>

            <div class="ai-buttons">
                <button onclick="showSuggestions('reliability')">
                    Reliability
                </button>

                <button onclick="showSuggestions('visual')">
                    Visual
                </button>

                <button onclick="showSuggestions('performance')">
                    Performance
                </button>

                <button onclick="showSuggestions('budget')">
                    Budget Priority
                </button>
            </div>

            <div id="suggestion-box" class="suggestion-box">
                Select a category to get EJ6-specific suggestions.
            </div>
        </div>

        <div class="card">
            <h2>Add Mod</h2>

            <form action="/mods" method="POST">
                @csrf

                <label>Mod Name</label>
                <input type="text" name="name" required>

                <label>Category</label>
                <input type="text" name="category">

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

                <button type="submit">
                    Save Mod
                </button>
            </form>
        </div>

    </div>

    <div class="card">
        <h2>Current Mods Wishlist</h2>

        @php
            $totalCost = 0;
        @endphp

        @forelse ($mods as $mod)

            @php
                $totalCost += $mod->price ?? 0;
            @endphp

            <div class="mod-card">

                <div class="mod-top">
                    <h3>{{ $mod->name }}</h3>

                    <span class="status-badge">
                    {{ $mod->status }}
                </span>
                </div>

                <div class="mod-grid">
                    <div>
                        <strong>Category:</strong>
                        {{ $mod->category ?? 'N/A' }}
                    </div>

                    <div>
                        <strong>Price:</strong>
                        €{{ $mod->price ?? '0.00' }}
                    </div>

                    <div>
                        <strong>Priority:</strong>
                        {{ $mod->priority ?? 'N/A' }}
                    </div>
                </div>

                @if ($mod->link)
                    <a
                        href="{{ $mod->link }}"
                        target="_blank"
                        class="mod-link"
                    >
                        View Part
                    </a>
                @endif

                <p class="mod-notes">
                    {{ $mod->notes }}
                </p>

                <form
                    action="/mods/{{ $mod->id }}"
                    method="POST"
                    onsubmit="return confirm('Delete this mod?');"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="delete-btn"
                    >
                        Delete
                    </button>
                </form>

            </div>

        @empty

            <p>No mods added yet.</p>

        @endforelse

        <div class="total-cost">
            Total Wishlist Cost:
            €{{ number_format($totalCost, 2) }}
        </div>
    </div>

    <script src="{{ asset('js/mods.js') }}"></script>

@endsection

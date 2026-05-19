@extends('layout')

@section('content')

    <div class="page-head">
        <div>
            <p class="eyebrow">EJ6 BUILD SYSTEM</p>
            <h1>Mods Planner</h1>
            <p>Recommendations based on your 1997 EJ6, G-82P-5 dark green build, current issues and build direction.</p>
        </div>

        <div class="mini-spec-card">
            <span>Car</span>
            <strong>1997 Civic EJ6</strong>
            <small>D16Y7 • Dark Green • Clean JDM / Majin</small>
        </div>
    </div>

    @if (!empty($dbError))
        <div class="alert-card">
            <strong>Database Error</strong>
            <p>{{ $dbError }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="alert-card">
            <strong>Save/Delete Error</strong>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="mods-layout">

        <section class="panel ai-panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">SMART ASSISTANT</p>
                    <h2>EJ6 AI Build Advisor</h2>
                </div>
            </div>

            <p class="muted">
                Picks recommendations from your known issues: rust, exhaust alignment, fuel tank concern,
                bumper alignment, D16Y7 reliability and clean dark-green JDM style.
            </p>

            <div class="ai-controls">
                <button type="button" onclick="generateBuildAdvice('priority')">Priority Plan</button>
                <button type="button" onclick="generateBuildAdvice('reliability')">Reliability</button>
                <button type="button" onclick="generateBuildAdvice('visual')">Visual Style</button>
                <button type="button" onclick="generateBuildAdvice('performance')">Performance</button>
                <button type="button" onclick="generateBuildAdvice('budget')">Budget Route</button>
            </div>

            <div id="ai-output" class="ai-output">
                Select a recommendation mode.
            </div>
        </section>

        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">ADD PART</p>
                    <h2>New Mod</h2>
                </div>
            </div>

            <form action="/mods" method="POST">
                @csrf

                <label>Mod Name</label>
                <input type="text" name="name" placeholder="Example: EK front lip" required>

                <label>Category</label>
                <input type="text" name="category" placeholder="Rust, Exhaust, Suspension, Wheels...">

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
                <input type="text" name="link" placeholder="Part link">

                <label>Notes</label>
                <textarea name="notes" placeholder="Why this part? Fitment? Install notes?"></textarea>

                <button type="submit">Save Mod</button>
            </form>
        </section>

    </div>

    <section class="panel">
        <div class="panel-title">
            <div>
                <p class="eyebrow">WISHLIST</p>
                <h2>Current Build List</h2>
            </div>

            @php $totalCost = 0; @endphp

            @foreach ($mods as $mod)
                @php $totalCost += $mod->price ?? 0; @endphp
            @endforeach

            <div class="price-pill">
                €{{ number_format($totalCost, 2) }}
            </div>
        </div>

        <div class="mod-list">
            @forelse ($mods as $mod)
                <article class="mod-item">
                    <div class="mod-main">
                        <div>
                            <h3>{{ $mod->name }}</h3>
                            <p>{{ $mod->notes ?: 'No notes yet.' }}</p>
                        </div>

                        <span class="status-badge">{{ $mod->status ?? 'Wanted' }}</span>
                    </div>

                    <div class="mod-meta">
                        <span>{{ $mod->category ?? 'No category' }}</span>
                        <span>€{{ $mod->price ?? '0.00' }}</span>
                        <span>{{ $mod->priority ?? 'No priority' }}</span>
                    </div>

                    <div class="mod-actions">
                        @if ($mod->link)
                            <a href="{{ $mod->link }}" target="_blank">Open part</a>
                        @endif

                        <form action="/mods/{{ $mod->id }}" method="POST" onsubmit="return confirm('Delete this mod?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="danger-btn">Delete</button>
                        </form>
                    </div>
                </article>
            @empty
                <p class="muted">No mods added yet.</p>
            @endforelse
        </div>
    </section>

    <script>
        window.currentMods = @json($mods);
    </script>

    <script src="{{ asset('js/mods.js') }}"></script>

@endsection

@extends('layout')

@section('content')

    <div class="page-head">
        <div>
            <p class="eyebrow">BUILD ARCHIVE</p>
            <h1>Gallery</h1>
            <p>Structured space for EJ6 photos, rust references, receipts, parts documentation and before/after progress.</p>
        </div>

        <div class="mini-spec-card">
            <span>Archive layout</span>
            <strong>Ready for uploads</strong>
            <small>Sorted for bodywork, maintenance, parts and build milestones.</small>
        </div>
    </div>

    <div class="gallery-stats">
        <div class="card metric-card">
            <span>Photo Bays</span>
            <strong>6</strong>
            <p>Prepared archive categories.</p>
        </div>

        <div class="card metric-card">
            <span>Priority Album</span>
            <strong>Rust</strong>
            <p>Rear arches, rockers and jacking points.</p>
        </div>

        <div class="card metric-card">
            <span>Build Tone</span>
            <strong>G-82P-5</strong>
            <p>Dark green clean JDM reference board.</p>
        </div>
    </div>

    <section class="panel">
        <div class="panel-title">
            <div>
                <p class="eyebrow">ARCHIVE MAP</p>
                <h2>Garage Photo Bays</h2>
            </div>
        </div>

        <div class="gallery-grid">
            <article class="gallery-bay priority-bay">
                <span>01</span>
                <h3>Rust & Bodywork</h3>
                <p>Rear arches, jacking points, rockers, bumper alignment and repair progress.</p>
                <small>High priority</small>
            </article>

            <article class="gallery-bay">
                <span>02</span>
                <h3>Maintenance Receipts</h3>
                <p>Fuel system, exhaust fixes, oil leak checks, brakes and inspection documents.</p>
                <small>Service proof</small>
            </article>

            <article class="gallery-bay">
                <span>03</span>
                <h3>Parts & Mods</h3>
                <p>New parts, fitment tests, install notes and before/after mod comparisons.</p>
                <small>Build planner link</small>
            </article>

            <article class="gallery-bay">
                <span>04</span>
                <h3>Paint & Styling</h3>
                <p>G-82P-5 dark green references, Majin accents, lighting, wheels and clean JDM details.</p>
                <small>Visual direction</small>
            </article>

            <article class="gallery-bay">
                <span>05</span>
                <h3>Interior</h3>
                <p>TYPE-K DK.GRAY cabin photos, trim condition, seats, plastics and subtle accent plans.</p>
                <small>Cabin archive</small>
            </article>

            <article class="gallery-bay">
                <span>06</span>
                <h3>Inspection Map Captures</h3>
                <p>3D inspection point screenshots, issue references and solved/fixed proof shots.</p>
                <small>Linked to 3D map</small>
            </article>
        </div>
    </section>

    <section class="panel">
        <div class="panel-title">
            <div>
                <p class="eyebrow">UPLOAD QUEUE</p>
                <h2>Suggested First Shots</h2>
            </div>
        </div>

        <div class="shot-list">
            <div class="shot-row">
                <strong>Rear arches</strong>
                <span>Wide shot, close-up, underside angle</span>
            </div>

            <div class="shot-row">
                <strong>Jacking points</strong>
                <span>Both sides, pinch welds, rocker condition</span>
            </div>

            <div class="shot-row">
                <strong>Fuel tank area</strong>
                <span>Leak evidence, straps, corrosion, line condition</span>
            </div>

            <div class="shot-row">
                <strong>Exhaust alignment</strong>
                <span>Hanger position, rear fitment, underside route</span>
            </div>
        </div>
    </section>

@endsection

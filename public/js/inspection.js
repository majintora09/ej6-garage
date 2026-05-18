import * as THREE from 'three';
import { STLLoader } from 'three/addons/loaders/STLLoader.js';

const container = document.getElementById('car-viewer');
const output = document.getElementById('inspection-output');
const editorToggle = document.getElementById('editor-toggle');
const editorPanel = document.getElementById('editor-panel');

let editorMode = false;
let pendingPosition = null;

const scene = new THREE.Scene();
scene.background = new THREE.Color(0x101010);

const camera = new THREE.PerspectiveCamera(
    55,
    container.clientWidth / container.clientHeight,
    0.1,
    10000
);

const renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setSize(container.clientWidth, container.clientHeight);
renderer.setPixelRatio(window.devicePixelRatio);
container.appendChild(renderer.domElement);

const carGroup = new THREE.Group();
scene.add(carGroup);

const lights = {
    ambient: new THREE.AmbientLight(0xffffff, 1.4),
    key: new THREE.DirectionalLight(0xffffff, 2),
    green: new THREE.PointLight(0x76ff9f, 3, 30),
    purple: new THREE.PointLight(0x34205f, 2, 25),
};

lights.key.position.set(5, 6, 5);
lights.green.position.set(-5, 3, 4);
lights.purple.position.set(5, 3, -4);

scene.add(lights.ambient);
scene.add(lights.key);
scene.add(lights.green);
scene.add(lights.purple);

window.setLightingMode = function (mode) {
    if (mode === 'garage') {
        scene.background = new THREE.Color(0x101010);
        lights.ambient.intensity = 1.4;
        lights.key.intensity = 2;
        lights.green.intensity = 3;
        lights.purple.intensity = 2;
    }

    if (mode === 'night') {
        scene.background = new THREE.Color(0x050507);
        lights.ambient.intensity = 0.4;
        lights.key.intensity = 0.6;
        lights.green.intensity = 1.4;
        lights.purple.intensity = 2.8;
    }

    if (mode === 'inspection') {
        scene.background = new THREE.Color(0x171717);
        lights.ambient.intensity = 2.2;
        lights.key.intensity = 3.2;
        lights.green.intensity = 1;
        lights.purple.intensity = 0.5;
    }

    if (mode === 'majin') {
        scene.background = new THREE.Color(0x08040d);
        lights.ambient.intensity = 0.8;
        lights.key.intensity = 1.2;
        lights.green.intensity = 2.5;
        lights.purple.intensity = 5;
    }
};

const carMaterial = new THREE.MeshStandardMaterial({
    color: 0x0f3b24,
    metalness: 0.45,
    roughness: 0.35,
});

const markerMaterial = new THREE.MeshStandardMaterial({
    color: 0xff4444,
    emissive: 0xff1111,
    emissiveIntensity: 2,
});

const markerGeometry = new THREE.SphereGeometry(0.12, 24, 24);
const loader = new STLLoader();

let carMesh = null;
const markers = [];

loader.load('/models/ej6/civic_em1_all.stl', function (geometry) {
    geometry.center();

    carMesh = new THREE.Mesh(geometry, carMaterial);

    carMesh.scale.set(1.5, 1.5, 1.5);
    carMesh.rotation.x = -Math.PI / 2;

    carGroup.add(carMesh);
});

const defaultPoints = [
    {
        name: 'Front Bumper / Headlight Alignment',
        category: 'Body',
        status: 'Open',
        priority: 'Medium',
        x: -2.75,
        y: -0.05,
        z: 0.95,
        description: 'Check bumper clips, brackets, headlight mounts and front support alignment.',
    },
    {
        name: 'Front Jacking Point / Rocker',
        category: 'Rust',
        status: 'Open',
        priority: 'High',
        x: -1.05,
        y: -0.55,
        z: 1.28,
        description: 'Check the front rocker and jacking area for rust, bending or weak metal.',
    },
    {
        name: 'Rear Arch Rust',
        category: 'Rust',
        status: 'Open',
        priority: 'High',
        x: 1.45,
        y: -0.05,
        z: 1.28,
        description: 'Common EJ/EM rear arch rust area. Check bubbling, inner lip corrosion and soft metal.',
    },
    {
        name: 'Fuel Tank Area',
        category: 'Fuel System',
        status: 'Open',
        priority: 'High',
        x: 1.55,
        y: -0.65,
        z: 0.65,
        description: 'Inspect for fuel smell, dripping, wet spots, tank straps and corrosion.',
    },
    {
        name: 'Exhaust Alignment',
        category: 'Exhaust',
        status: 'Open',
        priority: 'Medium',
        x: 0.55,
        y: -0.70,
        z: -1.25,
        description: 'Check hanger, rubber mount, pipe clearance and why the hanger slips out.',
    },
];

const savedPoints = window.savedInspectionPoints || [];
const pointsToRender = savedPoints.length ? savedPoints : defaultPoints;

pointsToRender.forEach(createMarker);

function createMarker(point) {
    const marker = new THREE.Mesh(markerGeometry, markerMaterial);

    marker.position.set(
        parseFloat(point.x),
        parseFloat(point.y),
        parseFloat(point.z)
    );

    marker.userData = point;

    carGroup.add(marker);
    markers.push(marker);
}

camera.position.set(0, 1.4, 5);
camera.lookAt(0, 0, 0);

let isDragging = false;
let previousMouse = { x: 0, y: 0 };

container.addEventListener('mousedown', (event) => {
    isDragging = true;
    previousMouse = { x: event.clientX, y: event.clientY };
});

window.addEventListener('mouseup', () => {
    isDragging = false;
});

container.addEventListener('mousemove', (event) => {
    if (!isDragging) return;

    const deltaX = event.clientX - previousMouse.x;
    const deltaY = event.clientY - previousMouse.y;

    carGroup.rotation.y += deltaX * 0.01;
    carGroup.rotation.x += deltaY * 0.004;

    previousMouse = { x: event.clientX, y: event.clientY };
});

container.addEventListener('wheel', (event) => {
    event.preventDefault();

    camera.position.z += event.deltaY * 0.01;
    camera.position.z = Math.max(2.5, Math.min(camera.position.z, 10));
});

editorToggle.addEventListener('click', () => {
    editorMode = !editorMode;

    editorToggle.textContent = editorMode
        ? 'Editor Mode: ON'
        : 'Editor Mode: OFF';

    editorPanel.classList.toggle('hidden', !editorMode);
});

const raycaster = new THREE.Raycaster();
const mouse = new THREE.Vector2();

container.addEventListener('click', (event) => {
    const rect = container.getBoundingClientRect();

    mouse.x = ((event.clientX - rect.left) / container.clientWidth) * 2 - 1;
    mouse.y = -((event.clientY - rect.top) / container.clientHeight) * 2 + 1;

    raycaster.setFromCamera(mouse, camera);

    const clickedMarker = raycaster.intersectObjects(markers);

    if (clickedMarker.length > 0) {
        showPoint(clickedMarker[0].object.userData);
        return;
    }

    if (!editorMode || !carMesh) return;

    const clickedCar = raycaster.intersectObject(carMesh);

    if (clickedCar.length > 0) {
        const localPoint = clickedCar[0].point.clone();

        carGroup.worldToLocal(localPoint);

        pendingPosition = {
            x: localPoint.x.toFixed(3),
            y: localPoint.y.toFixed(3),
            z: localPoint.z.toFixed(3),
        };

        output.innerHTML = `
            <h3>New point selected</h3>
            <p>X: ${pendingPosition.x}, Y: ${pendingPosition.y}, Z: ${pendingPosition.z}</p>
            <p>Fill the editor form and save.</p>
        `;
    }
});

function showPoint(point) {
    const category = point.category || 'N/A';
    const related = window.maintenanceByCategory?.[category] || [];

    let relatedHtml = '<p>No maintenance linked to this category yet.</p>';

    if (related.length > 0) {
        const last = related[0];

        relatedHtml = `
            <p><strong>Last Maintenance:</strong> ${last.title}</p>
            <p><strong>Date:</strong> ${last.service_date ?? 'No date'}</p>
            <p><strong>Cost:</strong> €${last.cost ?? '0.00'}</p>
        `;
    }

    output.innerHTML = `
        <h3>${point.name}</h3>
        <p><strong>Category:</strong> ${category}</p>
        <p><strong>Status:</strong> ${point.status ?? 'N/A'}</p>
        <p><strong>Priority:</strong> ${point.priority ?? 'N/A'}</p>
        <p>${point.description ?? ''}</p>

        <hr>

        <h4>Linked Maintenance</h4>
        ${relatedHtml}

        ${
        point.id
            ? `<button class="delete-btn" onclick="deleteInspectionPoint(${point.id})">Delete Point</button>`
            : ''
    }
    `;
}

document.getElementById('save-point').addEventListener('click', async () => {
    if (!pendingPosition) {
        alert('Click the car first to choose a marker position.');
        return;
    }

    const payload = {
        name: document.getElementById('point-name').value,
        category: document.getElementById('point-category').value,
        priority: document.getElementById('point-priority').value,
        status: document.getElementById('point-status').value,
        description: document.getElementById('point-description').value,
        x: pendingPosition.x,
        y: pendingPosition.y,
        z: pendingPosition.z,
    };

    const response = await fetch('/inspection-points', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
        },
        body: JSON.stringify(payload),
    });

    const savedPoint = await response.json();

    createMarker(savedPoint);
    showPoint(savedPoint);

    pendingPosition = null;
});

window.deleteInspectionPoint = async function (id) {
    if (!confirm('Delete this inspection point?')) return;

    await fetch(`/inspection-points/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': window.csrfToken,
        },
    });

    window.location.reload();
};

window.addEventListener('resize', () => {
    camera.aspect = container.clientWidth / container.clientHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(container.clientWidth, container.clientHeight);
});

function animate() {
    requestAnimationFrame(animate);

    markers.forEach(marker => {
        marker.scale.setScalar(1 + Math.sin(Date.now() * 0.006) * 0.15);
    });

    carGroup.rotation.y += 0.0008;
    renderer.render(scene, camera);
}

animate();

import * as THREE from 'three';
import { STLLoader } from 'three/addons/loaders/STLLoader.js';

const container = document.getElementById('car-viewer');
const output = document.getElementById('inspection-output');
const editorToggle = document.getElementById('editor-toggle');
const editorPanel = document.getElementById('editor-panel');

let editorMode = false;
let pendingPosition = null;

const scene = new THREE.Scene();
scene.background = new THREE.Color(0x080a09);

const camera = new THREE.PerspectiveCamera(
    55,
    container.clientWidth / container.clientHeight,
    0.1,
    10000
);

const renderer = new THREE.WebGLRenderer({
    antialias: true,
    alpha: true
});

renderer.setSize(container.clientWidth, container.clientHeight);
renderer.setPixelRatio(window.devicePixelRatio);

container.appendChild(renderer.domElement);

const carGroup = new THREE.Group();
scene.add(carGroup);

const ambient = new THREE.AmbientLight(0xffffff, 1.3);
scene.add(ambient);

const mainLight = new THREE.DirectionalLight(0xffffff, 2);
mainLight.position.set(5, 5, 5);
scene.add(mainLight);

const greenLight = new THREE.PointLight(0x76ff9f, 2.2, 30);
greenLight.position.set(-5, 4, 4);
scene.add(greenLight);

const purpleLight = new THREE.PointLight(0x7d4cff, 2, 30);
purpleLight.position.set(5, 3, -4);
scene.add(purpleLight);

window.setLightingMode = function(mode) {
    if (mode === 'garage') {
        scene.background = new THREE.Color(0x080a09);

        ambient.intensity = 1.3;
        mainLight.intensity = 2;

        greenLight.intensity = 2.2;
        purpleLight.intensity = 2;
    }

    if (mode === 'night') {
        scene.background = new THREE.Color(0x040506);

        ambient.intensity = 0.45;
        mainLight.intensity = 0.7;

        greenLight.intensity = 1.2;
        purpleLight.intensity = 3;
    }

    if (mode === 'inspection') {
        scene.background = new THREE.Color(0x141414);

        ambient.intensity = 2;
        mainLight.intensity = 3;

        greenLight.intensity = 0.7;
        purpleLight.intensity = 0.5;
    }

    if (mode === 'majin') {
        scene.background = new THREE.Color(0x09040f);

        ambient.intensity = 0.7;
        mainLight.intensity = 1.1;

        greenLight.intensity = 1.5;
        purpleLight.intensity = 5;
    }
};

const loader = new STLLoader();

const carMaterial = new THREE.MeshStandardMaterial({
    color: 0x0f3b24,
    metalness: 0.45,
    roughness: 0.35
});

const markerMaterial = new THREE.MeshStandardMaterial({
    color: 0xff4444,
    emissive: 0xff1111,
    emissiveIntensity: 2
});

const markerGeometry = new THREE.SphereGeometry(0.12, 24, 24);

let carMesh = null;
const markers = [];

loader.load('/models/ej6/civic_em1_all.stl', function(geometry) {

    geometry.center();

    carMesh = new THREE.Mesh(
        geometry,
        carMaterial
    );

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
        description: 'Check bumper clips, brackets and headlight alignment.'
    },

    {
        name: 'Rear Arch Rust',
        category: 'Rust',
        status: 'Open',
        priority: 'High',
        x: 1.45,
        y: -0.05,
        z: 1.28,
        description: 'Common EJ rear arch rust area.'
    },

    {
        name: 'Fuel Tank Area',
        category: 'Fuel System',
        status: 'Open',
        priority: 'High',
        x: 1.55,
        y: -0.65,
        z: 0.65,
        description: 'Inspect for fuel leaks and corrosion.'
    },

    {
        name: 'Exhaust Alignment',
        category: 'Exhaust',
        status: 'Open',
        priority: 'Medium',
        x: 0.55,
        y: -0.70,
        z: -1.25,
        description: 'Check hanger and exhaust alignment.'
    }
];

const savedPoints = window.savedInspectionPoints || [];

const pointsToRender = savedPoints.length
    ? savedPoints
    : defaultPoints;

pointsToRender.forEach(createMarker);

function createMarker(point) {

    const marker = new THREE.Mesh(
        markerGeometry,
        markerMaterial
    );

    marker.position.set(
        parseFloat(point.x),
        parseFloat(point.y),
        parseFloat(point.z)
    );

    marker.userData = point;

    carGroup.add(marker);

    markers.push(marker);
}

if (window.innerWidth <= 768) {
    camera.position.set(0, 1.2, 7.5);
} else {
    camera.position.set(0, 1.4, 5);
}

camera.lookAt(0, 0, 0);

let isDragging = false;

let previousMouse = {
    x: 0,
    y: 0
};

/* DESKTOP */

container.addEventListener('mousedown', (event) => {

    isDragging = true;

    previousMouse = {
        x: event.clientX,
        y: event.clientY
    };
});

window.addEventListener('mouseup', () => {
    isDragging = false;
});

container.addEventListener('mousemove', (event) => {

    if (!isDragging) return;

    const deltaX =
        event.clientX - previousMouse.x;

    const deltaY =
        event.clientY - previousMouse.y;

    carGroup.rotation.y += deltaX * 0.01;

    carGroup.rotation.x += deltaY * 0.004;

    previousMouse = {
        x: event.clientX,
        y: event.clientY
    };
});

/* MOBILE TOUCH */

container.addEventListener('touchstart', (event) => {

    if (event.touches.length !== 1) return;

    isDragging = true;

    previousMouse = {
        x: event.touches[0].clientX,
        y: event.touches[0].clientY
    };

}, { passive: false });

container.addEventListener('touchmove', (event) => {

    if (!isDragging || event.touches.length !== 1) return;

    event.preventDefault();

    const touch = event.touches[0];

    const deltaX =
        touch.clientX - previousMouse.x;

    const deltaY =
        touch.clientY - previousMouse.y;

    carGroup.rotation.y += deltaX * 0.01;

    carGroup.rotation.x += deltaY * 0.004;

    previousMouse = {
        x: touch.clientX,
        y: touch.clientY
    };

}, { passive: false });

container.addEventListener('touchend', () => {
    isDragging = false;
});

/* ZOOM */

container.addEventListener('wheel', (event) => {

    event.preventDefault();

    camera.position.z += event.deltaY * 0.01;

    camera.position.z =
        Math.max(2.5, Math.min(camera.position.z, 10));
});

/* EDITOR */

editorToggle?.addEventListener('click', () => {

    editorMode = !editorMode;

    editorToggle.textContent =
        editorMode
            ? 'Editor Mode: ON'
            : 'Editor Mode: OFF';

    editorPanel.classList.toggle(
        'hidden',
        !editorMode
    );
});

/* CLICK DETECTION */

const raycaster = new THREE.Raycaster();

const mouse = new THREE.Vector2();

container.addEventListener('click', (event) => {

    const rect =
        container.getBoundingClientRect();

    mouse.x =
        ((event.clientX - rect.left)
            / container.clientWidth) * 2 - 1;

    mouse.y =
        -((event.clientY - rect.top)
            / container.clientHeight) * 2 + 1;

    raycaster.setFromCamera(mouse, camera);

    const clickedMarker =
        raycaster.intersectObjects(markers);

    if (clickedMarker.length > 0) {

        showPoint(
            clickedMarker[0].object.userData
        );

        return;
    }
});

function showPoint(point) {

    output.innerHTML = `
        <h3>${point.name}</h3>

        <p>
            <strong>Category:</strong>
            ${point.category}
        </p>

        <p>
            <strong>Status:</strong>
            ${point.status}
        </p>

        <p>
            <strong>Priority:</strong>
            ${point.priority}
        </p>

        <hr>

        <p>${point.description}</p>
    `;
}

/* RESPONSIVE */

window.addEventListener('resize', () => {

    camera.aspect =
        container.clientWidth /
        container.clientHeight;

    camera.updateProjectionMatrix();

    renderer.setSize(
        container.clientWidth,
        container.clientHeight
    );
});

/* ANIMATION */

function animate() {

    requestAnimationFrame(animate);

    markers.forEach(marker => {

        marker.scale.setScalar(
            1 + Math.sin(Date.now() * 0.006) * 0.15
        );
    });

    if (!isDragging && window.innerWidth > 768) {
        carGroup.rotation.y += 0.0008;
    }

    renderer.render(scene, camera);
}

animate();

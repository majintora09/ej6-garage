import * as THREE from 'three';
import { STLLoader } from 'three/addons/loaders/STLLoader.js';

const container = document.getElementById('car-viewer');

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

// Lights
scene.add(new THREE.AmbientLight(0xffffff, 1.4));

const keyLight = new THREE.DirectionalLight(0xffffff, 2);
keyLight.position.set(5, 6, 5);
scene.add(keyLight);

const greenLight = new THREE.PointLight(0x76ff9f, 3, 30);
greenLight.position.set(-5, 3, 4);
scene.add(greenLight);

const purpleLight = new THREE.PointLight(0x34205f, 2, 25);
purpleLight.position.set(5, 3, -4);
scene.add(purpleLight);

// Group
const carGroup = new THREE.Group();
scene.add(carGroup);

// Materials
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

// Load aligned full model
const loader = new STLLoader();

loader.load('/models/ej6/civic_em1_all.stl', function (geometry) {
    geometry.center();

    const car = new THREE.Mesh(geometry, carMaterial);

    car.scale.set(1.5, 1.5, 1.5);
    car.rotation.x = -Math.PI / 2;

    carGroup.add(car);
});

// Markers
const markerGeometry = new THREE.SphereGeometry(0.12, 24, 24);
const inspectionPoints = [
    {
        name: 'Front Bumper / Headlight Alignment',
        position: [-2.75, -0.05, 0.95],
        description: 'Check bumper clips, brackets, headlight mounts and front support alignment.'
    },
    {
        name: 'Front Jacking Point / Rocker',
        position: [-1.05, -0.55, 1.28],
        description: 'Check the front rocker and jacking area for rust, bending or weak metal.'
    },
    {
        name: 'Rear Arch Rust',
        position: [1.45, -0.05, 1.28],
        description: 'Common EJ/EM rear arch rust area. Check bubbling, inner lip corrosion and soft metal.'
    },
    {
        name: 'Rear Jacking Point / Rocker Rust',
        position: [0.75, -0.55, 1.28],
        description: 'Structural area near the rear rocker. If it crunches when lifted, repair before cosmetics.'
    },
    {
        name: 'Fuel Tank Area',
        position: [1.55, -0.65, 0.65],
        description: 'Inspect for fuel smell, dripping, wet spots, tank straps and corrosion.'
    },
    {
        name: 'Exhaust Alignment',
        position: [0.55, -0.70, -1.25],
        description: 'Check hanger, rubber mount, pipe clearance and why the hanger slips out.'
    }
];
const markers = [];

inspectionPoints.forEach(point => {
    const marker = new THREE.Mesh(markerGeometry, markerMaterial);

    marker.position.set(
        point.position[0],
        point.position[1],
        point.position[2]
    );

    marker.userData = point;

    carGroup.add(marker);
    markers.push(marker);
});

// Camera
camera.position.set(0, 1.4, 5);
camera.lookAt(0, 0, 0);

// Controls
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

// Click markers
const raycaster = new THREE.Raycaster();
const mouse = new THREE.Vector2();

container.addEventListener('click', (event) => {
    const rect = container.getBoundingClientRect();

    mouse.x = ((event.clientX - rect.left) / container.clientWidth) * 2 - 1;
    mouse.y = -((event.clientY - rect.top) / container.clientHeight) * 2 + 1;

    raycaster.setFromCamera(mouse, camera);

    const intersects = raycaster.intersectObjects(markers);

    if (intersects.length > 0) {
        const point = intersects[0].object.userData;

        document.getElementById('inspection-output').innerHTML = `
            <h3>${point.name}</h3>
            <p>${point.description}</p>
        `;
    }
});

// Resize
window.addEventListener('resize', () => {
    camera.aspect = container.clientWidth / container.clientHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(container.clientWidth, container.clientHeight);
});

// Animate
function animate() {
    requestAnimationFrame(animate);

    markers.forEach(marker => {
        marker.scale.setScalar(1 + Math.sin(Date.now() * 0.006) * 0.15);
    });

    renderer.render(scene, camera);
}

animate();

import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.161/build/three.module.js';

const container = document.getElementById('car-viewer');

const scene = new THREE.Scene();

scene.background = new THREE.Color(0x111111);

const camera = new THREE.PerspectiveCamera(
    75,
    container.clientWidth / container.clientHeight,
    0.1,
    1000
);

const renderer = new THREE.WebGLRenderer({
    antialias: true
});

renderer.setSize(
    container.clientWidth,
    container.clientHeight
);

container.appendChild(renderer.domElement);

const light = new THREE.PointLight(0xffffff, 2);

light.position.set(5, 5, 5);

scene.add(light);

const ambient = new THREE.AmbientLight(0xffffff, 1);

scene.add(ambient);

/*
    Placeholder car body
*/

const geometry = new THREE.BoxGeometry(4, 1.2, 2);

const material = new THREE.MeshStandardMaterial({
    color: 0x1b4d2d,
    metalness: 0.5,
    roughness: 0.5
});

const car = new THREE.Mesh(geometry, material);

scene.add(car);

/*
    Rust markers
*/

const markerGeometry = new THREE.SphereGeometry(0.12, 16, 16);

const markerMaterial = new THREE.MeshStandardMaterial({
    color: 0xff4444,
    emissive: 0xff2222,
    emissiveIntensity: 2
});

const points = [

    {
        name: 'Rear Arch Rust',
        position: [-1.7, 0.3, 1],
        description:
            'Common EJ rust area near the rear wheel arch.'
    },

    {
        name: 'Jacking Point Rust',
        position: [-1.2, -0.6, 0.9],
        description:
            'Possible structural rust around the rocker/jacking area.'
    },

    {
        name: 'Fuel Tank Area',
        position: [1.5, -0.4, 0],
        description:
            'Area to inspect for fuel leaks or corrosion.'
    },

    {
        name: 'Exhaust Alignment',
        position: [0, -0.4, -0.9],
        description:
            'Current exhaust/hanger issue area.'
    },

    {
        name: 'Front Bumper Alignment',
        position: [1.9, 0, 0],
        description:
            'Wobbly bumper/headlight alignment zone.'
    }

];

const markers = [];

points.forEach(point => {

    const marker = new THREE.Mesh(
        markerGeometry,
        markerMaterial
    );

    marker.position.set(
        point.position[0],
        point.position[1],
        point.position[2]
    );

    marker.userData = point;

    scene.add(marker);

    markers.push(marker);
});

camera.position.z = 6;

/*
    Mouse controls
*/

let isDragging = false;
let previousMousePosition = {
    x: 0,
    y: 0
};

container.addEventListener('mousedown', () => {
    isDragging = true;
});

container.addEventListener('mouseup', () => {
    isDragging = false;
});

container.addEventListener('mousemove', (event) => {

    if (!isDragging) return;

    const deltaMove = {
        x: event.offsetX - previousMousePosition.x,
        y: event.offsetY - previousMousePosition.y
    };

    car.rotation.y += deltaMove.x * 0.01;

    markers.forEach(marker => {
        marker.rotation.y += deltaMove.x * 0.01;
    });

    previousMousePosition = {
        x: event.offsetX,
        y: event.offsetY
    };
});

/*
    Click detection
*/

const raycaster = new THREE.Raycaster();

const mouse = new THREE.Vector2();

container.addEventListener('click', (event) => {

    mouse.x = (event.offsetX / container.clientWidth) * 2 - 1;

    mouse.y = -(event.offsetY / container.clientHeight) * 2 + 1;

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

function animate() {

    requestAnimationFrame(animate);

    car.rotation.y += 0.002;

    renderer.render(scene, camera);
}

animate();

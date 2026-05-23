import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import { STLLoader } from 'three/addons/loaders/STLLoader.js';

const container = document.getElementById('car-viewer');
const output = document.getElementById('inspection-output');
const editorToggle = document.getElementById('editor-toggle');
const editorPanel = document.getElementById('editor-panel');
const modelStatus = document.getElementById('model-status');

const modelConfig = window.inspectionModelConfig || {};
const ui = window.inspectionUiText || {};
const themeColor = getComputedStyle(document.body).getPropertyValue('--theme').trim() || '#76ff9f';

let editorMode = false;
let pendingPosition = null;
let pendingNormalizedPosition = null;
let normalizedBounds = null;

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

const greenLight = new THREE.PointLight(themeColor, 2.2, 30);
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

const carMaterial = new THREE.MeshStandardMaterial({
    color: new THREE.Color(themeColor),
    metalness: 0.45,
    roughness: 0.35
});

const glassMaterial = new THREE.MeshStandardMaterial({
    color: 0x0b1410,
    metalness: 0.1,
    roughness: 0.18,
    transparent: true,
    opacity: 0.72
});

const markerMaterial = new THREE.MeshStandardMaterial({
    color: 0xff4444,
    emissive: 0xff1111,
    emissiveIntensity: 2
});

const markerGeometry = new THREE.SphereGeometry(0.055, 24, 24);
const markers = [];
let modelObject = null;

loadInspectionModel();

const savedPoints = window.savedInspectionPoints || [];
const defaultPoints = [
    {
        name: 'Front Alignment',
        category: 'Body',
        status: 'Open',
        priority: 'Medium',
        normalized_x: 0.18,
        normalized_y: 0.45,
        normalized_z: 0.70,
        description: 'Generic front bumper, headlight and panel alignment checkpoint.'
    },
    {
        name: 'Rear Quarter / Arch',
        category: 'Body',
        status: 'Open',
        priority: 'High',
        normalized_x: 0.78,
        normalized_y: 0.48,
        normalized_z: 0.74,
        description: 'Generic rear arch, quarter panel and corrosion watch area.'
    },
    {
        name: 'Underside Center',
        category: 'Underbody',
        status: 'Open',
        priority: 'High',
        normalized_x: 0.50,
        normalized_y: 0.18,
        normalized_z: 0.50,
        description: 'Generic underside check for leaks, damage and corrosion.'
    }
];

function loadInspectionModel() {
    const candidates = [
        modelConfig.customModelPath,
        modelConfig.genericModelPath,
        modelConfig.fallbackStlPath
    ].filter(Boolean);

    loadCandidateModel(candidates, 0);
}

function loadCandidateModel(candidates, index) {
    if (!candidates[index]) {
        usePlaceholderModel();
        return;
    }

    loadModelByPath(candidates[index])
        .then((model) => {
            setModelStatus(candidates[index].includes('/generic/')
                ? (ui.genericModelLoaded || 'Generic :type model loaded.').replace(':type', modelConfig.bodyType || 'car')
                : (ui.customModelLoaded || 'Custom garage model loaded.'));
            installModel(model);
        })
        .catch(() => loadCandidateModel(candidates, index + 1));
}

function loadModelByPath(path) {
    return new Promise((resolve, reject) => {
        const extension = path.split('.').pop().toLowerCase();

        if (extension === 'glb' || extension === 'gltf') {
            new GLTFLoader().load(path, (gltf) => resolve(gltf.scene), undefined, reject);
            return;
        }

        if (extension === 'stl') {
            new STLLoader().load(path, (geometry) => {
                const mesh = new THREE.Mesh(geometry, carMaterial);
                mesh.rotation.x = -Math.PI / 2;
                resolve(mesh);
            }, undefined, reject);
            return;
        }

        reject(new Error('Unsupported model type.'));
    });
}

function installModel(model) {
    modelObject = model;
    carGroup.add(modelObject);
    normalizeModel(modelObject);
    renderInitialPoints();
}

function usePlaceholderModel() {
    setModelStatus(ui.placeholderLoaded || 'No custom or generic 3D model found. Showing a clean placeholder body until a .glb model is added.');

    const body = new THREE.Mesh(new THREE.BoxGeometry(3.8, 0.62, 1.55), carMaterial);
    body.position.y = 0.62;

    const cabin = new THREE.Mesh(new THREE.BoxGeometry(1.65, 0.52, 1.18), glassMaterial);
    cabin.position.set(-0.24, 1.15, 0);

    const front = new THREE.Mesh(new THREE.BoxGeometry(0.92, 0.42, 1.36), carMaterial);
    front.position.set(-1.92, 0.52, 0);

    const rear = new THREE.Mesh(new THREE.BoxGeometry(0.82, 0.48, 1.42), carMaterial);
    rear.position.set(1.92, 0.55, 0);

    const placeholder = new THREE.Group();
    placeholder.add(body, cabin, front, rear);

    installModel(placeholder);
}

export function normalizeModel(model) {
    const box = new THREE.Box3().setFromObject(model);
    const size = box.getSize(new THREE.Vector3());
    const center = box.getCenter(new THREE.Vector3());
    const largestAxis = Math.max(size.x, size.y, size.z) || 1;
    const scale = 4 / largestAxis;

    model.position.sub(center);
    model.scale.multiplyScalar(scale);
    model.updateMatrixWorld(true);

    const groundedBox = new THREE.Box3().setFromObject(model);
    model.position.y -= groundedBox.min.y;
    model.updateMatrixWorld(true);

    normalizedBounds = new THREE.Box3().setFromObject(model);
    fitCameraToModel(model);

    return {
        bounds: normalizedBounds,
        scale
    };
}

function fitCameraToModel(model) {
    const box = new THREE.Box3().setFromObject(model);
    const size = box.getSize(new THREE.Vector3());
    const center = box.getCenter(new THREE.Vector3());
    const maxDim = Math.max(size.x, size.y, size.z);
    const fov = camera.fov * (Math.PI / 180);
    let distance = Math.abs(maxDim / Math.sin(fov / 2));

    distance *= window.innerWidth <= 768 ? 1.05 : 0.82;

    camera.position.set(center.x, center.y + maxDim * 0.28, center.z + distance);
    camera.lookAt(center);
    camera.near = Math.max(distance / 100, 0.01);
    camera.far = distance * 100;
    camera.updateProjectionMatrix();
}

function renderInitialPoints() {
    const pointsToRender = savedPoints.length ? savedPoints : defaultPoints;
    pointsToRender.forEach(createMarker);
}

function createMarker(point) {
    const marker = new THREE.Mesh(markerGeometry, markerMaterial);
    const position = pointToModelPosition(point);

    marker.position.copy(position);
    marker.userData = {
        ...point,
        x: position.x,
        y: position.y,
        z: position.z
    };

    carGroup.add(marker);
    markers.push(marker);
}

function pointToModelPosition(point) {
    if (!normalizedBounds) {
        return new THREE.Vector3(parseFloat(point.x || 0), parseFloat(point.y || 0), parseFloat(point.z || 0));
    }

    if (point.normalized_x !== null && point.normalized_x !== undefined) {
        return normalizedToPosition(
            parseFloat(point.normalized_x),
            parseFloat(point.normalized_y),
            parseFloat(point.normalized_z)
        );
    }

    return new THREE.Vector3(parseFloat(point.x || 0), parseFloat(point.y || 0), parseFloat(point.z || 0));
}

function normalizedToPosition(x, y, z) {
    const size = normalizedBounds.getSize(new THREE.Vector3());

    return new THREE.Vector3(
        normalizedBounds.min.x + size.x * x,
        normalizedBounds.min.y + size.y * y,
        normalizedBounds.min.z + size.z * z
    );
}

function positionToNormalized(position) {
    const size = normalizedBounds.getSize(new THREE.Vector3());

    return {
        x: clamp((position.x - normalizedBounds.min.x) / size.x),
        y: clamp((position.y - normalizedBounds.min.y) / size.y),
        z: clamp((position.z - normalizedBounds.min.z) / size.z)
    };
}

function clamp(value) {
    return Math.max(0, Math.min(1, value));
}

function setModelStatus(message) {
    if (modelStatus) {
        modelStatus.textContent = message;
    }
}

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
    const deltaX = touch.clientX - previousMouse.x;
    const deltaY = touch.clientY - previousMouse.y;

    carGroup.rotation.y += deltaX * 0.01;
    carGroup.rotation.x += deltaY * 0.004;

    previousMouse = { x: touch.clientX, y: touch.clientY };
}, { passive: false });

container.addEventListener('touchend', () => {
    isDragging = false;
});

container.addEventListener('wheel', (event) => {
    event.preventDefault();
    camera.position.z += event.deltaY * 0.01;
    camera.position.z = Math.max(2.5, Math.min(camera.position.z, 12));
});

editorToggle?.addEventListener('click', () => {
    editorMode = !editorMode;
    editorToggle.textContent = editorMode ? (ui.editorOn || 'Editor Mode: ON') : (ui.editorOff || 'Editor Mode: OFF');
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

    if (!editorMode || !modelObject || !normalizedBounds) return;

    const clickedModel = raycaster.intersectObject(modelObject, true);

    if (clickedModel.length === 0) return;

    pendingPosition = carGroup.worldToLocal(clickedModel[0].point.clone());
    pendingNormalizedPosition = positionToNormalized(pendingPosition);
    output.innerHTML = `<h3>${escapeHtml(ui.pointReadyTitle || 'Point ready')}</h3><p>${escapeHtml(ui.pointReadyCopy || 'Fill out the editor form, then save this normalized inspection point.')}</p>`;
});

document.getElementById('save-point')?.addEventListener('click', async () => {
    if (!pendingPosition || !pendingNormalizedPosition) {
        output.innerHTML = `<h3>${escapeHtml(ui.noPositionTitle || 'No position selected')}</h3><p>${escapeHtml(ui.noPositionCopy || 'Turn editor mode on and click the model before saving.')}</p>`;
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
        normalized_x: pendingNormalizedPosition.x,
        normalized_y: pendingNormalizedPosition.y,
        normalized_z: pendingNormalizedPosition.z
    };

    const response = await fetch('/inspection-points', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        },
        body: JSON.stringify(payload)
    });

    if (!response.ok) {
        output.innerHTML = `<h3>${escapeHtml(ui.saveFailedTitle || 'Save failed')}</h3><p>${escapeHtml(ui.saveFailedCopy || 'Check the point name and try again.')}</p>`;
        return;
    }

    const point = await response.json();
    createMarker(point);
    showPoint(point);
    pendingPosition = null;
    pendingNormalizedPosition = null;
});

function showPoint(point) {
    output.innerHTML = `
        <h3>${escapeHtml(point.name)}</h3>
        <p><strong>${escapeHtml(ui.categoryLabel || 'Category')}:</strong> ${escapeHtml(point.category || ui.unsorted || 'Unsorted')}</p>
        <p><strong>${escapeHtml(ui.statusLabel || 'Status')}:</strong> ${escapeHtml(point.status || ui.open || 'Open')}</p>
        <p><strong>${escapeHtml(ui.priorityLabel || 'Priority')}:</strong> ${escapeHtml(point.priority || ui.medium || 'Medium')}</p>
        <hr>
        <p>${escapeHtml(point.description || ui.noNotes || 'No notes yet.')}</p>
    `;
}

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

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

    if (!isDragging && window.innerWidth > 768) {
        carGroup.rotation.y += 0.0008;
    }

    renderer.render(scene, camera);
}

animate();

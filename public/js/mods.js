const savedCarProfile = window.carProfile || {};
const ui = window.uiText || {};

const carProfile = {
    car: [
        savedCarProfile.year,
        savedCarProfile.make,
        savedCarProfile.model
    ].filter(Boolean).join(" ") || ui.yourCar || "Your car",
    engine: savedCarProfile.engine || ui.engineNotSet || "engine not set",
    color: [
        savedCarProfile.color_code,
        savedCarProfile.color_name
    ].filter(Boolean).join(" ") || ui.colorNotSet || "color not set",
    interior: savedCarProfile.interior || ui.interiorNotSet || "interior not set",
    vibe: savedCarProfile.build_vibe || ui.personalBuild || "personal garage build",
};

function getExistingModNames() {
    return (window.currentMods || [])
        .map(mod => `${mod.name} ${mod.category} ${mod.notes}`.toLowerCase())
        .join(" ");
}

function generateBuildAdvice(mode) {
    const output = document.getElementById("ai-output");
    const existing = getExistingModNames();

    const recommendations = ui.recommendations || {};

    let list = recommendations[mode] || recommendations.priority || [];
    list = list.map(item => ({
        ...item,
        title: (item.title || '').replace(':engine', carProfile.engine).replace(':color', carProfile.color || 'Paint')
    }));

    list = list.map(item => {
        const alreadyPlanned = item.parts.some(part =>
            existing.includes(part.toLowerCase().split(" ")[0])
        );

        return {
            ...item,
            alreadyPlanned
        };
    });

    output.innerHTML = `
        <div class="ai-summary">
            <strong>${carProfile.car}</strong>
            <span>${carProfile.color} • ${carProfile.engine} • ${carProfile.vibe}</span>
        </div>

        ${list.map(item => `
            <div class="ai-rec ${item.alreadyPlanned ? "ai-rec-warn" : ""}">
                <h3>${item.title}</h3>
                <p>${item.reason}</p>
                <ul>
                    ${item.parts.map(part => `<li>${part}</li>`).join("")}
                </ul>
                ${item.alreadyPlanned ? `<small>${ui.alreadyPlanned || "Some of this may already be in your mod list."}</small>` : ""}
            </div>
        `).join("")}
    `;
}

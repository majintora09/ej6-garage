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
    knownIssues: [
        "reliability and safety checks",
        "maintenance history gaps",
        "fitment and install planning",
        "body and underside condition",
        "future build direction",
    ],
};

function getExistingModNames() {
    return (window.currentMods || [])
        .map(mod => `${mod.name} ${mod.category} ${mod.notes}`.toLowerCase())
        .join(" ");
}

function generateBuildAdvice(mode) {
    const output = document.getElementById("ai-output");
    const existing = getExistingModNames();

    const recommendations = {
        priority: [
            {
                title: "1. Fix safety and leak issues first",
                reason: "Use the garage profile to prioritize safety, leaks, brakes, tires and structural checks before visual mods.",
                parts: ["Baseline inspection", "Leak diagnosis", "Brake and tire check"]
            },
            {
                title: "2. Stabilize the chassis",
                reason: "Older project cars feel better once bushings, suspension wear and alignment are sorted.",
                parts: ["Bushing inspection", "Suspension refresh", "Alignment check"]
            },
            {
                title: "3. Then build the look",
                reason: "Once the base is healthy, choose styling that matches your saved build vibe.",
                parts: ["Wheel plan", "Exterior details", "Lighting refresh"]
            }
        ],

        reliability: [
            {
                title: "Baseline inspection first",
                reason: "A reliable garage starts with known fluids, wear items and leak checks.",
                parts: ["Fluid service", "Leak inspection", "Service history audit"]
            },
            {
                title: `${carProfile.engine} health check`,
                reason: "Use the engine field from your profile to build a targeted service list.",
                parts: ["Oil service", "Cooling check", "Ignition and belt history"]
            },
            {
                title: "Secure underside systems",
                reason: "Loose exhaust, brake, fuel or suspension parts should be solved before cosmetic spend.",
                parts: ["Exhaust mounts", "Brake lines", "Fastener check"]
            }
        ],

        visual: [
            {
                title: `${carProfile.color || "Paint"} visual route`,
                reason: "Let your saved color and build vibe guide exterior choices.",
                parts: ["Paint correction", "Lighting details", "Exterior trim refresh"]
            },
            {
                title: "Accent direction",
                reason: "Keep accent colors consistent with the profile theme color.",
                parts: ["Small decals", "Interior detail", "Hardware accents"]
            },
            {
                title: "Wheel fitment",
                reason: "Wheel and tire specs should match the chassis, ride height and intended use.",
                parts: ["Wheel specs", "Tire sizing", "Ride height plan"]
            }
        ],

        performance: [
            {
                title: "Handling before power",
                reason: "Most builds benefit from tires, brakes and suspension before chasing power.",
                parts: ["Good tires", "Brake refresh", "Suspension setup"]
            },
            {
                title: "Brake confidence",
                reason: "Before swaps or speed, make sure it stops well.",
                parts: ["Fresh discs/pads", "Brake fluid flush", "Caliper check"]
            },
            {
                title: "Future power prep",
                reason: "Plan engine work around your current profile instead of impulse buying parts.",
                parts: ["Engine goal", "Budget plan", "Supporting mods"]
            }
        ],

        budget: [
            {
                title: "€0-€150: inspect and secure",
                reason: "Cheap fixes can prevent expensive problems.",
                parts: ["Exhaust hanger fix", "Rust inspection tools", "Oil leak clean/check"]
            },
            {
                title: "€150-€500: repair base",
                reason: "This range should go to safety and inspection readiness.",
                parts: ["Fuel tank parts", "Rust materials", "Brake service"]
            },
            {
                title: "€500+: style after safety",
                reason: "Spend on visible mods once the car is structurally sorted.",
                parts: ["Wheels", "Suspension", "Front lip", "Paint/body correction"]
            }
        ],
    };

    let list = recommendations[mode] || recommendations.priority;

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

const carProfile = {
    car: "1997 Honda Civic EJ6 Coupe",
    engine: "D16Y7",
    color: "G-82P-5 dark green",
    interior: "TYPE-K, DK.GRAY",
    vibe: "dark green Majin-inspired clean JDM street build",
    knownIssues: [
        "fuel tank leak concern",
        "exhaust alignment and hanger slipping issue",
        "rear arch rust",
        "jacking point / rocker rust",
        "front bumper and headlight alignment",
        "possible oil leak",
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
                reason: "Your EJ6 has fuel tank concern, rust areas and exhaust alignment issues. Those should come before visual mods.",
                parts: ["Fuel tank inspection/replacement", "Rust repair panels", "Exhaust hanger/bracket repair"]
            },
            {
                title: "2. Stabilize the chassis",
                reason: "Old Civics feel much better after bushings, suspension checks and rust-safe jacking points.",
                parts: ["Control arm bushings", "Jacking point repair", "Alignment check"]
            },
            {
                title: "3. Then build the look",
                reason: "Once the base is safe, the dark green Majin/JDM styling will be worth spending on.",
                parts: ["EK front lip", "Amber corners", "15/16 inch wheels", "Subtle midnight-purple accents"]
            }
        ],

        reliability: [
            {
                title: "Fuel system first",
                reason: "A fuel leak risk is higher priority than any mod.",
                parts: ["OEM-style fuel tank", "Fuel tank straps", "Fuel lines inspection"]
            },
            {
                title: "D16Y7 refresh",
                reason: "The D16Y7 is reliable if leaks, cooling and timing history are sorted.",
                parts: ["Valve cover gasket", "Oil leak diagnosis", "Coolant flush", "Timing belt history check"]
            },
            {
                title: "Exhaust security",
                reason: "Your hanger issue should be fixed physically, not just temporarily held.",
                parts: ["New rubber hangers", "Hanger stopper", "Bracket weld/adjustment"]
            }
        ],

        visual: [
            {
                title: "Dark green clean JDM route",
                reason: "Your G-82P-5 paint should stay the main character.",
                parts: ["EK/EJ front lip", "OEM-style side skirts", "Amber corners", "Black housing headlights"]
            },
            {
                title: "Majin accent direction",
                reason: "Keep it subtle so it does not look cheap.",
                parts: ["Midnight-purple small decals", "Purple valve caps", "Small interior accent stitching"]
            },
            {
                title: "Wheel fitment",
                reason: "15/16 inch wheels fit the EJ coupe vibe without ruining drivability.",
                parts: ["15x7 wheels", "195/50R15 tires", "Subtle lowering springs or coilovers"]
            }
        ],

        performance: [
            {
                title: "Handling before power",
                reason: "For a D16Y7 EJ6, handling mods give more fun per euro than engine power chasing.",
                parts: ["Coilovers", "Rear sway bar", "Good tires", "Brake refresh"]
            },
            {
                title: "Brake confidence",
                reason: "Before swaps or speed, make sure it stops well.",
                parts: ["Fresh discs/pads", "Brake fluid flush", "Caliper check"]
            },
            {
                title: "Future swap prep",
                reason: "B-series later makes more sense than overspending on D16Y7 power.",
                parts: ["B-series research list", "Mounts budget", "Brake/suspension prep"]
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
                ${item.alreadyPlanned ? `<small>Some of this may already be in your mod list.</small>` : ""}
            </div>
        `).join("")}
    `;
}

function showSuggestions(type) {

    const box = document.getElementById('suggestion-box');

    const suggestions = {

        reliability: `
            <ul>
                <li>OEM fuel tank inspection/replacement</li>
                <li>Proper exhaust hanger repair</li>
                <li>Rear rust treatment</li>
                <li>Valve cover gasket refresh</li>
                <li>Suspension bushing check</li>
            </ul>
        `,

        visual: `
            <ul>
                <li>EK/EJ front lip</li>
                <li>Amber corners</li>
                <li>15/16 inch wheels</li>
                <li>Subtle midnight-purple accents</li>
                <li>Clean low stance setup</li>
            </ul>
        `,

        performance: `
            <ul>
                <li>Coilovers</li>
                <li>Rear sway bar</li>
                <li>Brake upgrade</li>
                <li>B-series future prep</li>
                <li>Cold air intake</li>
            </ul>
        `,

        budget: `
            <ul>
                <li>Fix rust BEFORE mods</li>
                <li>Fix fuel leak</li>
                <li>Repair exhaust alignment</li>
                <li>Refresh maintenance items</li>
                <li>Save for suspension setup</li>
            </ul>
        `
    };

    box.innerHTML = suggestions[type];
}

/**
 * Vehicle Search — cascade select (Year > Make > Model > Part)
 *
 * Vanilla JS replacement for the jQuery/Select2/Elementor widget.
 * Talks to the parent theme's existing AJAX handler.
 *
 * Figma: "Wyszukiwarka Po modelu" / wariant NavBar
 */

const FIELD_ORDER = ['produced', 'make', 'model'];

function initAll() {
    document.querySelectorAll('.vehicle-search:not([data-vs-init])').forEach(initOne);
}

function initOne(container) {
    container.setAttribute('data-vs-init', '1');

    const ajaxUrl = container.dataset.ajaxUrl;
    const nonce = container.dataset.nonce;
    const button = container.querySelector('.vehicle-search__button');
    const fields = FIELD_ORDER.map((slug) => ({
        slug,
        wrapper: container.querySelector(`.vehicle-search__field[data-slug="${slug}"]`),
        select: container.querySelector(`select[name="${slug}"]`),
    }));

    let vehicleUrl = '';

    // Attach change listeners
    fields.forEach((field, index) => {
        field.select.addEventListener('change', () => {
            onFieldChange(index);
        });
    });

    button.addEventListener('click', () => {
        if (vehicleUrl) {
            window.location.href = vehicleUrl;
        }
    });

    function setActiveStep(index) {
        fields.forEach((f, i) => {
            f.wrapper.classList.toggle('vehicle-search__field--active', i === index);
        });
    }

    function onFieldChange(changedIndex) {
        const value = fields[changedIndex].select.value;

        // Reset all subsequent fields
        for (let i = changedIndex + 1; i < fields.length; i++) {
            resetField(fields[i]);
        }

        // Disable button whenever cascade changes
        vehicleUrl = '';
        button.disabled = true;

        if (!value) {
            // User cleared this field — mark it as active step
            setActiveStep(changedIndex);
            return;
        }

        const nextIndex = changedIndex + 1;

        // Last field selected — value is a JSON-encoded URL
        if (nextIndex >= fields.length) {
            try {
                vehicleUrl = JSON.parse(value);
                button.disabled = false;
            } catch {
                vehicleUrl = value;
                button.disabled = false;
            }
            // Keep last field active
            setActiveStep(changedIndex);
            return;
        }

        // Move active indicator to next field
        setActiveStep(nextIndex);

        // Fetch options for the next field
        fetchOptions(nextIndex);
    }

    function resetField(field) {
        field.select.innerHTML = `<option value="">${field.select.querySelector('option').textContent}</option>`;
        field.select.disabled = true;
        field.wrapper.classList.add('vehicle-search__field--disabled');
        field.wrapper.classList.remove('vehicle-search__field--active');
        field.wrapper.classList.remove('vehicle-search__field--loading');
    }

    async function fetchOptions(targetIndex) {
        const target = fields[targetIndex];
        target.wrapper.classList.add('vehicle-search__field--loading');

        // Build values from all fields before target
        const values = {};
        for (let i = 0; i < targetIndex; i++) {
            let val = fields[i].select.value;
            try { val = JSON.parse(val); } catch { /* use as-is */ }
            values[fields[i].slug] = val;
        }

        // Build form data matching parent theme's expected format
        const params = new URLSearchParams();
        params.append('action', 'autozpro_sputnik_vehicle_select_load_data');
        params.append('nonce', nonce);
        params.append('data[for]', target.slug);
        for (const [key, val] of Object.entries(values)) {
            params.append(`data[values][${key}]`, val);
        }

        try {
            const response = await fetch(ajaxUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params,
            });
            const json = await response.json();

            if (!json.success || !Array.isArray(json.data)) return;

            // Populate options
            const placeholder = target.select.querySelector('option').textContent;
            let html = `<option value="">${placeholder}</option>`;
            json.data.forEach((opt) => {
                const encodedValue = JSON.stringify(opt.value);
                html += `<option value='${encodedValue}'>${escapeHtml(opt.title)}</option>`;
            });

            target.select.innerHTML = html;
            target.select.disabled = false;
            target.wrapper.classList.remove('vehicle-search__field--disabled');
        } catch (err) {
            console.error('Vehicle search fetch error:', err);
        } finally {
            target.wrapper.classList.remove('vehicle-search__field--loading');
        }
    }
}

function escapeHtml(str) {
    const el = document.createElement('span');
    el.textContent = str;
    return el.innerHTML;
}

// Eksport do reinicjalizacji z innych modułów
export { initAll as reinitVehicleSearch };

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
} else {
    initAll();
}

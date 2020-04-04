(function (w, d) {
    'use strict';

    const sets = {};

    function toggleSet(e, set) {
        if (!e.target.checked) {
            sets[set].forEach(i => i.checked = false);
        }
    }

    d.querySelectorAll('[data-eprivacy-set]').forEach(s => {
        const set = s.getAttribute('data-eprivacy-set');
        const input = s.querySelector('[type="checkbox"]');
        if (set.length && input) {
            if (!(set in sets)) {
                sets[set] = [];
            }
            sets[set].push(input);
            input.addEventListener('change', e => toggleSet(e, set));
        }
    });
})(typeof global !== 'undefined' ? global : window, document);

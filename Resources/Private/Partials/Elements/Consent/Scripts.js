(function (w, d) {
    'use strict';

    const sets = {};

    function toggleSet(e, set, main) {
        if (main) {
            sets[set].forEach(i => i.checked = e.target.checked);
        } else if (!e.target.checked) {
            sets[set][0].checked = false;
        } else if (sets[set].reduce((acc, cur) => acc + cur.checked * 1, 0) >= (sets[set].length - 1)) {
            sets[set][0].checked = true;
        }
    }

    d.querySelectorAll('[data-eprivacy-set]').forEach(s => {
        const set = s.getAttribute('data-eprivacy-set');
        const input = s.querySelector('[type="checkbox"]');
        if (set.length && input) {
            let main = false;
            if (!(set in sets)) {
                sets[set] = [];
                main = true;
            }
            sets[set].push(input);
            input.addEventListener('change', e => toggleSet(e, set, main));
        }
    });
})(typeof global !== 'undefined' ? global : window, document);

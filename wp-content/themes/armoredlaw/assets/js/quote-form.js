(() => {
    const root = document.getElementById('quoteMultiForm');
    if (!root) return;

    const steps = [...root.querySelectorAll('.quote__step')];
    const msgEl = document.getElementById('quoteMessage');
    const step1MsgEl = root.querySelector('.quote__message--step1');
    const submitBtn = document.getElementById('quoteSubmitBtn');
    const storageKey = 'al_quote_state_v1';
    const quoteSection = document.getElementById('quoteForm');

    const state = {
        step: 1,
        data: {
            protection_type: '',
            has_ccw: '',
            state: '',
            plan: '',
            full_name: '',
            email: '',
            phone: '',
            terms: false,
            utm: {},
            page_url: window.location.href
        }
    };

    // --- helpers ---
    function setMessage(text, type = 'error', step = 2) {
        const el = step === 1 ? step1MsgEl : msgEl;
        if (!el) return;

        el.textContent = text || '';
        el.dataset.type = type;
    }

    function saveState() {
        try { sessionStorage.setItem(storageKey, JSON.stringify(state)); } catch (e) {}
    }

    function loadState() {
        try {
            const s = sessionStorage.getItem(storageKey);
            if (!s) return;
            const parsed = JSON.parse(s);
            if (parsed?.data) Object.assign(state.data, parsed.data);
            if (parsed?.step) state.step = parsed.step;
        } catch (e) {}
    }

    function showStep(n) {
        state.step = n;

        if (quoteSection) {
            quoteSection.classList.toggle('is-step-3', n === 3);
        }

        steps.forEach(el => {
            const isCurrent = String(n) === el.dataset.step;
            el.hidden = !isCurrent;
        });

        document.querySelectorAll('[data-step-indicator]').forEach(el => {
            el.classList.toggle('is-active', el.dataset.stepIndicator === String(n));
        });

        setMessage('');
        saveState();
    }

    function getUTM() {
        const params = new URLSearchParams(window.location.search);
        const keys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
        const utm = {};
        keys.forEach(k => {
            const v = params.get(k);
            if (v) utm[k] = v;
        });
        state.data.utm = utm;
    }

    function markSelected(groupEl, value) {
        groupEl.querySelectorAll('button').forEach(btn => {
            btn.classList.toggle('is-active', btn.dataset.value === value);
        });
    }

    function validateStep1() {
        const d = state.data;
        if (!d.protection_type) return 'Choose protection type.';
        if (!d.has_ccw) return 'Select CCW option.';
        if (!d.state) return 'Select state.';
        return '';
    }

    function validateStep2() {
        const d = state.data;
        if (!d.plan) return 'Choose your plan.';
        if (!d.full_name.trim()) return 'Enter full name.';
        if (!d.email.trim()) return 'Enter email.';
        if (!/^\S+@\S+\.\S+$/.test(d.email)) return 'Email looks invalid.';
        if (!d.phone.trim()) return 'Enter phone number.';
        if (!d.terms) return 'You must agree to Terms & Privacy Policy.';
        return '';
    }

    function isSentPage() {
        const params = new URLSearchParams(window.location.search);
        return params.get('quote') === 'sent';
    }

    function setSentUrlParam() {
        const url = new URL(window.location.href);
        url.searchParams.set('quote', 'sent');
        history.replaceState(null, '', url.toString());
    }

    async function submit() {
        setMessage('');
        submitBtn.disabled = true;
        submitBtn.classList.add('is-loading');

        try {
            // оновимо page_url на момент submit
            state.data.page_url = window.location.href;

            // (дебаг на 1 хвилину, якщо треба)
            // console.log('AL_QUOTE', window.AL_QUOTE);

            const res = await fetch(window.AL_QUOTE.ajaxUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                body: new URLSearchParams({
                    action: 'al_quote_submit',
                    nonce: window.AL_QUOTE.nonce,
                    data: JSON.stringify(state.data),
                })
            });

            const json = await res.json();
            if (!json?.success) {
                const m = json?.data?.message || 'Submit failed.';
                throw new Error(m);
            }

            // success:
            // 1) фіксуємо thank you через URL
            // 2) чистимо state, щоб не застрягати на step 3 при новому вході
            setSentUrlParam();
            try { sessionStorage.removeItem(storageKey); } catch (e) {}

            showStep(3);
        } catch (e) {
            setMessage(e.message || 'Submit failed.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.classList.remove('is-loading');
        }
    }

    // --- events ---
    root.addEventListener('click', (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;

        const action = btn.dataset.action;

        if (action === 'next') {
            const err = validateStep1();
            if (err) {
                setMessage(err, 'error', 1);
                return;
            }
            setMessage('', 'error', 1); // очистити помилку
            return showStep(2);
        }

        if (action === 'back') {
            return showStep(1);
        }

        // option buttons
        const group = btn.closest('[data-field]');
        if (!group) return;

        const field = group.dataset.field;
        const value = btn.dataset.value;

        state.data[field] = value;
        markSelected(group, value);
        saveState();
        setMessage('', 'error', 1);
    });

    const stateSelect = root.querySelector('#quoteState');
    if (stateSelect) {
        stateSelect.addEventListener('change', () => {
            state.data.state = stateSelect.value;
            saveState();
            setMessage('', 'error', 1);
        });
    }

    root.querySelectorAll('input[name="full_name"], input[name="email"], input[name="phone"]').forEach(inp => {
        inp.addEventListener('input', () => {
            state.data[inp.name] = inp.value;
            saveState();
        });
    });

    const terms = root.querySelector('input[name="terms"]');
    if (terms) {
        terms.addEventListener('change', () => {
            state.data.terms = !!terms.checked;
            saveState();
        });
    }

    root.addEventListener('submit', (e) => {
        e.preventDefault();
        const err = validateStep2();
        if (err) return setMessage(err);
        submit();
    });

    // --- init ---
    // 1) якщо ?quote=sent → показуємо THANK YOU без state
    // 2) якщо без параметра → можна підхопити прогрес з sessionStorage,
    //    але якщо там був step 3 — скидаємо на step 1 (щоб не застрягати)
    if (isSentPage()) {
        getUTM(); // не шкодить
        showStep(3);
        return;
    }

    loadState();
    getUTM();

    // Якщо раптом в sessionStorage залишився step 3 — скидаємо на step 1
    if (state.step === 3) {
        state.step = 1;
        saveState();
    }

    // Restore UI from state
    root.querySelectorAll('[data-field="protection_type"],[data-field="has_ccw"],[data-field="plan"]').forEach(group => {
        const field = group.dataset.field;
        const value = state.data[field];
        if (value) markSelected(group, value);
    });

    if (stateSelect && state.data.state) stateSelect.value = state.data.state;

    ['full_name', 'email', 'phone'].forEach(n => {
        const el = root.querySelector(`input[name="${n}"]`);
        if (el && state.data[n]) el.value = state.data[n];
    });

    if (terms) terms.checked = !!state.data.terms;

    showStep(state.step || 1);
})();

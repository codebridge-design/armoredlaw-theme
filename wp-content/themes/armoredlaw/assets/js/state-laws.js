(function () {
    const mapWrap = document.getElementById("alMapWrap");
    const svg = mapWrap?.querySelector("svg");
    const select = document.getElementById("alStateSelect");

    if (!svg || !select) return;

    // optional blocks (only exist on State Laws page)
    const topicsWrap = document.getElementById("alTopics");
    const topicsBlock = document.getElementById("alTopicsBlock");

    // Next button can exist on State Laws page, but not in template-part
    const uiRoot = select.closest(".slp__ui, .al-map-ui, .al-map-block, .slp__section") || document;
    const nextBtn = uiRoot.querySelector("[data-al-next]");

    const cache = new Map();
    let currentState = "";

    async function loadStateData(state) {
        if (!state) return null;
        if (cache.has(state)) return cache.get(state);

        const base = window.AL_STATE_LAWS?.restUrl || "/wp-json/armoredlaw/v1/state-laws/";
        const res = await fetch(base + state, { credentials: "same-origin" });
        const data = await res.json();

        const topics = data?.topics || {};
        cache.set(state, topics);
        return topics;
    }

    function clearActiveOnMap() {
        svg.querySelectorAll(".sm_state.is-active").forEach(el => el.classList.remove("is-active"));
        svg.querySelectorAll(".sm_label.is-active").forEach(el => el.classList.remove("is-active"));
    }

    function setActiveOnMap(code) {
        if (!code) return;

        clearActiveOnMap();

        const stateEl = svg.querySelector(`.sm_state_${CSS.escape(code)}`);
        const labelEl = svg.querySelector(`.sm_label_${CSS.escape(code)}`);

        if (stateEl) stateEl.classList.add("is-active");
        if (labelEl) labelEl.classList.add("is-active");
    }

    function getCodeFromClass(el, prefix) {
        return [...el.classList]
            .find(c => c.startsWith(prefix))
            ?.replace(prefix, "");
    }

    function setCurrentState(code) {
        currentState = code || "";
        if (topicsWrap) topicsWrap.dataset.state = currentState;
    }

    // ===== Fill select options =====
    const states = [
        ["AL","Alabama"],["AK","Alaska"],["AZ","Arizona"],["AR","Arkansas"],["CA","California"],
        ["CO","Colorado"],["CT","Connecticut"],["DE","Delaware"],["FL","Florida"],["GA","Georgia"],
        ["HI","Hawaii"],["ID","Idaho"],["IL","Illinois"],["IN","Indiana"],["IA","Iowa"],
        ["KS","Kansas"],["KY","Kentucky"],["LA","Louisiana"],["ME","Maine"],["MD","Maryland"],
        ["MA","Massachusetts"],["MI","Michigan"],["MN","Minnesota"],["MS","Mississippi"],["MO","Missouri"],
        ["MT","Montana"],["NE","Nebraska"],["NV","Nevada"],["NH","New Hampshire"],["NJ","New Jersey"],
        ["NM","New Mexico"],["NY","New York"],["NC","North Carolina"],["ND","North Dakota"],["OH","Ohio"],
        ["OK","Oklahoma"],["OR","Oregon"],["PA","Pennsylvania"],["RI","Rhode Island"],["SC","South Carolina"],
        ["SD","South Dakota"],["TN","Tennessee"],["TX","Texas"],["UT","Utah"],["VT","Vermont"],
        ["VA","Virginia"],["WA","Washington"],["WV","West Virginia"],["WI","Wisconsin"],["WY","Wyoming"],
        ["DC","District of Columbia"]
    ];

    const placeholder = select.dataset.placeholder || "Select State";
    select.innerHTML =
        `<option value="">${placeholder}</option>` +
        states.map(([c,n]) => `<option value="${c}">${n}</option>`).join("");

    // ===== Topics helpers (only if topics exist) =====
    function closeAllTopics() {
        if (!topicsWrap) return;

        topicsWrap.querySelectorAll(".al-topic-btn").forEach(btn => {
            btn.classList.remove("is-active");
            btn.setAttribute("aria-expanded", "false");
        });

        topicsWrap.querySelectorAll(".al-topic-panel").forEach(p => (p.hidden = true));
    }

    function openTopic(key) {
        if (!topicsWrap) return;

        const btn = topicsWrap.querySelector(`.al-topic-btn[data-topic="${key}"]`);
        const panel = topicsWrap.querySelector(`.al-topic-panel[data-panel="${key}"]`);
        if (!btn || !panel) return;

        btn.classList.add("is-active");
        btn.setAttribute("aria-expanded", "true");
        panel.hidden = false;
    }

    function renderTopic(key, html) {
        if (!topicsWrap) return;

        const inner = topicsWrap.querySelector(`.al-topic-panel[data-panel="${key}"] .al-topic-panel__inner`);
        if (!inner) return;

        inner.innerHTML = html
            ? html
            : (currentState
                ? `<p>Information for this topic in ${currentState} will be available soon.</p>`
                : `<p>Select a state to view this summary.</p>`);
    }

    async function refreshOpenedTopic() {
        if (!topicsWrap) return;

        const openedBtn = topicsWrap.querySelector(".al-topic-btn.is-active");
        if (!openedBtn) return;

        const key = openedBtn.dataset.topic;
        renderTopic(key, `<p>Loading...</p>`);
        const topics = await loadStateData(currentState);
        renderTopic(key, topics?.[key]);
    }

    // ===== Events: select =====
    select.addEventListener("change", async (e) => {
        const code = e.target.value;
        setCurrentState(code);
        setActiveOnMap(code);

        await refreshOpenedTopic();
    });

    // ===== Events: map click =====
    svg.addEventListener("click", (e) => {
        const stateEl = e.target.closest(".sm_state");
        const labelEl = e.target.closest(".sm_label");
        const el = stateEl || labelEl;
        if (!el) return;

        const prefix = stateEl ? "sm_state_" : "sm_label_";
        const code = getCodeFromClass(el, prefix);
        if (!code) return;

        select.value = code;
        setCurrentState(code);
        setActiveOnMap(code);

        refreshOpenedTopic();
    });

    // ===== Events: topic click =====
    document.addEventListener("click", async (e) => {
        if (!topicsWrap) return;

        const btn = e.target.closest(".al-topic-btn");
        if (!btn || !topicsWrap.contains(btn)) return;

        const key = btn.dataset.topic;
        const isOpen = btn.classList.contains("is-active");

        closeAllTopics();
        if (isOpen) return;

        openTopic(key);

        if (!currentState) {
            renderTopic(key, null);
            return;
        }

        renderTopic(key, `<p>Loading...</p>`);
        const topics = await loadStateData(currentState);
        renderTopic(key, topics?.[key]);
    });

    // ===== Next (optional) =====
    if (nextBtn) {
        nextBtn.addEventListener("click", () => {
            if (!currentState) {
                select.focus();
                return;
            }

            if (!topicsBlock) return;
            topicsBlock.scrollIntoView({ behavior: "smooth", block: "start" });

            if (topicsWrap) {
                const opened = topicsWrap.querySelector(".al-topic-btn.is-active");
                if (!opened) {
                    const first = topicsWrap.querySelector(".al-topic-btn");
                    if (first) first.click();
                }
            }
        });
    }
})();

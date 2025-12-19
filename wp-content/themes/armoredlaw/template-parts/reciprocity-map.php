<div class="al-map-block__wrapper">
	<div class="container">
		<div class="al-map-block">
			<div class="al-map-block__content">
				<div class="al-map-block__text">
					<p class="al-map-block__eyebrow">check your area</p>
          <h2 class="al-map-block__title">Reciprocity Map & Gun Laws by State</h2>
          <p class="al-map-block__subtitle">Select one state or choose multiple states below.</p>
				</div>
			  <div class="al-map-ui">
					<div class="al-select-wrap">
			      <select id="alStateSelect" class="al-select"></select>
					</div>

			    <div class="al-buttons">
			      <button type="button" class="btn btn--back">
			        <span>Back</span>
			      </button>
			      <button type="button" class="btn btn--primary">
							<span>Next</span>
						</button>
			    </div>
			  </div>
			</div>

		  <div class="al-map-wrap" id="alMapWrap">
		    <?php
            echo file_get_contents(
              get_template_directory() . '/assets/img/map.svg'
            );
          ?>
		  </div>
		</div>
	</div>
</div>

<script>
(function () {
  const mapWrap = document.getElementById("alMapWrap");
  const svg = mapWrap?.querySelector("svg");
  const select = document.getElementById("alStateSelect");

  if (!svg || !select) return;

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

  select.innerHTML =
    `<option value="">Select State</option>` +
    states.map(([c,n]) => `<option value="${c}">${n}</option>`).join("");

  // ===== 2. helpers =====
  function clearActive() {
    svg.querySelectorAll(".sm_state.is-active").forEach(el => el.classList.remove("is-active"));
    svg.querySelectorAll(".sm_label.is-active").forEach(el => el.classList.remove("is-active"));
  }

  function setActive(code) {
    if (!code) return;

    clearActive();

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

  select.addEventListener("change", e => {
    setActive(e.target.value);
  });

  svg.addEventListener("click", e => {
    const stateEl = e.target.closest(".sm_state");
    if (!stateEl) return;

    const code = getCodeFromClass(stateEl, "sm_state_");
    if (!code) return;

    select.value = code;
    setActive(code);
  });

  svg.addEventListener("click", e => {
    const labelEl = e.target.closest(".sm_label");
    if (!labelEl) return;

    const code = getCodeFromClass(labelEl, "sm_label_");
    if (!code) return;

    select.value = code;
    setActive(code);
  });

})();
</script>
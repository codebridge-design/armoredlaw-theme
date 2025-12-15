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
		    <img
		      src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/map.png' ); ?>"
		      alt="Map"
		    >
		  </div>
		</div>
	</div>
</div>
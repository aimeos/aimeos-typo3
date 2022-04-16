/**
 * Locale selector actions
 */
AimeosLocaleSelect = {

	/**
	 * Keeps menu open on click resp. closes on second click
	 */
	setupMenuToggle: function() {

		$(".select-menu .select-dropdown").on('click', function() {
			$("ul", this).toggleClass("active");
			$(this).toggleClass("active");
		});
	},


	/**
	 * Initializes the locale selector actions
	 */
	init: function() {
		this.setupMenuToggle();
	}
};


$(function() {
	AimeosLocaleSelect.init();
});
/**
 * Account profile actions
 */
AimeosAccountProfile = {

	/**
	 * Opens address forms activated through WebMCP
	 */
	onToolActivate() {

		window.addEventListener("toolactivated", ev => {
			const form = document.querySelector(`.account-profile-address form[toolname="${CSS.escape(ev.toolName)}"]`);
			const collapse = form?.closest(".accordion-collapse");

			if(collapse) {
				bootstrap.Collapse.getOrCreateInstance(collapse, {toggle: false}).show();
			}
		});
	},


	/**
	 * Show and close the address form
	 */
	onAddressToggle() {

		document.querySelectorAll(".account-profile-address .address-item").forEach(el => {
			el.addEventListener("show.bs.collapse", ev => {
				$(".act-show", ev.currentTarget).removeClass("act-show").addClass("act-hide");
			});
		});

		document.querySelectorAll(".account-profile-address .address-item").forEach(el => {
			el.addEventListener("hidden.bs.collapse", ev => {
				$(".act-hide", ev.currentTarget).removeClass("act-hide").addClass("act-show");
			});
		});
	},


	/**
	 * Initializes the account watch actions
	 */
	init() {
		if(this.once) return;
		this.once = true;

		this.onAddressToggle();
		this.onToolActivate();
	}
};


$(function() {
	AimeosAccountProfile.init();
});

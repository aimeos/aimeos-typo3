/**
 * Account profile actions
 */
AimeosAccountProfile = {

	/**
	 * Enables/disables the address form
	 */
	onAddress() {

		document.querySelectorAll(".account-profile-address .address-item").forEach(el => {
			el.addEventListener("show.bs.collapse", ev => {
				$(".form-item.mandatory input, .form-item.mandatory select, .form-item.optional input, .form-item.optional select", ev.currentTarget).prop("disabled", false);
			});
		});

		document.querySelectorAll(".account-profile-address .address-item").forEach(el => {
			el.addEventListener("hidden.bs.collapse", ev => {
				$(".form-item input, .form-item select", ev.currentTarget).prop("disabled", true);
			});
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

		this.onAddress();
		this.onAddressToggle();
	}
};


$(function() {
	AimeosAccountProfile.init();
});
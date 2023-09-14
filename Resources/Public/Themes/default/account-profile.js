/**
 * Account profile actions
 */
AimeosAccountProfile = {

	/**
	 * Reset and close the new address form
	 */
	onAddress() {

		document.querySelectorAll(".account-profile-address .panel").forEach(el => {
			el.addEventListener("show.bs.collapse", ev => {
				$(".act-show", ev.currentTarget).removeClass("act-show").addClass("act-hide");
			});
		});

		document.querySelectorAll(".account-profile-address .panel").forEach(el => {
			el.addEventListener("hidden.bs.collapse", ev => {
				$(".act-hide", ev.currentTarget).removeClass("act-hide").addClass("act-show");
			});
		});
	},


	/**
	 * Adds a new delivery address form
	 */
	onAddressNew() {

		document.querySelectorAll(".account-profile-address .address-delivery-new").forEach(el => {
			el.addEventListener("show.bs.collapse", ev => {
				$("input,select", ev.currentTarget).prop("disabled", false);
			});
		});

		document.querySelectorAll(".account-profile-address .address-delivery-new").forEach(el => {
			el.addEventListener("hidden.bs.collapse", ev => {
				$("input,select", ev.currentTarget).prop("disabled", true);
			});
		});
	},


	/**
	 * Initializes the account watch actions
	 */
	init() {
		this.onAddress();
		this.onAddressNew();
	}
};


$(function() {
	AimeosAccountProfile.init();
});
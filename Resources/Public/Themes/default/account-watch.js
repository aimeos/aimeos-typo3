/**
 * Account watch actions
 */
AimeosAccountWatch = {

	/**
	 * Deletes a watched item without page reload
	 */
	onRemoveProduct() {

		$("body").on("click", ".account-watch .delete", async ev => {

			const form = $(ev.currentTarget).closest("form");
			$(ev.currentTarget).closest("watch-item").addClass("loading");

			await fetch(form.attr("action"), {
				body: new FormData(form[0]),
				method: 'POST'
			}).then(response => {
				return response.text();
			}).then(data => {
				const doc = $("<html/>").html(data);

				$(".aimeos.account-watch").replaceWith($(".aimeos.account-watch", doc));

				if(!$(".aimeos.account-watch .watch-items").length) {
					Aimeos.removeOverlay();
				}
			});

			return false;
		});
	},


	/**
	 * Saves a modifed watched item without page reload
	 */
	onSaveProduct() {

		$("body").on("click", ".account-watch .btn-action", async ev => {

			const form = $(ev.currentTarget).closest("form");
			form.addClass("loading");

			await fetch(form.attr("action"), {
				body: new FormData(form[0]),
				method: 'POST'
			}).then(response => {
				return response.text();
			}).then(data => {
				const doc = $("<html/>").html(data);
				$(".aimeos.account-watch").replaceWith($(".aimeos.account-watch", doc));
			});

			return false;
		});
	},


	/**
	 * Initializes the account watch actions
	 */
	init() {
		if(this.once) return;
		this.once = true;

		this.onRemoveProduct();
		this.onSaveProduct();
	}
};


$(function() {
	AimeosAccountWatch.init();
});
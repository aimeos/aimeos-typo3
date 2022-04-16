/**
 * Account favorite actions
 */
AimeosAccountFavorite = {

	/**
	 * Deletes a favorite item without page reload
	 */
	onRemoveProduct() {

		$("body").on("click", ".account-favorite .delete", ev => {

			const form = $(ev.currentTarget).closest("form");
			$(ev.currentTarget).closest("favorite-item").addClass("loading");

			fetch(form.attr("action"), {
				body: new FormData(form[0]),
				method: 'POST'
			}).then(response => {
				return response.text();
			}).then(data => {
				const doc = $("<html/>").html(data);

				$(".aimeos.account-favorite").replaceWith($(".aimeos.account-favorite", doc));

				if(!$(".aimeos.account-favorite .favorite-items").length) {
					Aimeos.removeOverlay();
				}
			});

			return false;
		});
	},


	/**
	 * Initializes the account favorite actions
	 */
	init() {
		this.onRemoveProduct();
	}
};


$(function() {
	AimeosAccountFavorite.init();
});
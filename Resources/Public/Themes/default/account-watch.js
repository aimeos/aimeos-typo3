/**
 * Account watch actions
 */
AimeosAccountWatch = {

	/**
	 * Deletes a watched item without page reload
	 */
	onRemoveProduct() {

		$("body").on("click", ".account-watch .delete", async ev => {
			ev.preventDefault();

			const form = $(ev.currentTarget).closest("form");
			$(ev.currentTarget).closest("watch-item").addClass("loading");

			await Aimeos.fetchHtml(form.attr("action"), {
				body: new FormData(form[0]),
				method: 'POST'
			}).then(data => {
				const doc = Aimeos.parseHtml(data);

				$(".aimeos.account-watch").replaceWith($(".aimeos.account-watch", doc));

				if(!$(".aimeos.account-watch .watch-items").length) {
					Aimeos.removeOverlay();
				}
			}).catch(error => {
				$(ev.currentTarget).closest('.watch-item').removeClass('loading');
				console.warn('Unable to update the watch list', error);
			});

			return false;
		});
	},


	/**
	 * Saves a modifed watched item without page reload
	 */
	onSaveProduct() {

		$("body").on("click", ".account-watch .btn-action", async ev => {
			ev.preventDefault();

			const form = $(ev.currentTarget).closest("form");
			form.addClass("loading");

			await Aimeos.fetchHtml(form.attr("action"), {
				body: new FormData(form[0]),
				method: 'POST'
			}).then(data => {
				const doc = Aimeos.parseHtml(data);
				$(".aimeos.account-watch").replaceWith($(".aimeos.account-watch", doc));
			}).catch(error => {
				form.removeClass('loading');
				console.warn('Unable to update the watch list', error);
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

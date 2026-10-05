/**
 * Account favorite actions
 */
AimeosAccountFavorite = {

	/**
	 * Deletes a favorite item without page reload
	 */
	onRemoveProduct() {

        $("body").on("click", ".account-favorite .delete", async ev => {

            ev.preventDefault();

            const $btn = $(ev.currentTarget);
            const form = $btn.closest("form");
            $btn.closest(".favorite-item").addClass("loading");

            try {
                const html = await Aimeos.fetchHtml(form.attr("action"), {
                    method: "POST",
                    body: new FormData(form[0])
                });

                const doc = Aimeos.parseHtml(html);

                $(".aimeos.account-favorite")
                    .replaceWith($(".aimeos.account-favorite", doc));

                if (!$(".aimeos.account-favorite .favorite-items").length) {
                    Aimeos.removeOverlay();
                }
            } catch(error) {
                $btn.closest('.favorite-item').removeClass('loading');
                console.warn('Unable to update favorites', error);
            }
        });
	},


	/**
	 * Initializes the account favorite actions
	 */
	init() {
		if(this.once) return;
		this.once = true;

		this.onRemoveProduct();
	}
};


$(function() {
	AimeosAccountFavorite.init();
});

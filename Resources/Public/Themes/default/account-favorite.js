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

            const response = await fetch(form.attr("action"), {
                method: "POST",
                body: new FormData(form[0])
            });

            const html = await response.text();
            const doc = $("<html/>").html(html);

            $(".aimeos.account-favorite")
                .replaceWith($(".aimeos.account-favorite", doc));

            if (!$(".aimeos.account-favorite .favorite-items").length) {
                Aimeos.removeOverlay();
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
/**
 * Catalog session client actions
 */
AimeosCatalogSession = {

	/**
	 * Toggles the Last Seen filters if hover isn't available
	 */
	onToggleSeen() {

		$('.catalog-session-seen .header').on("click", ev => {
			$(".seen-items", $(ev.currentTarget).closest(".catalog-session-seen")).each((idx, el) => {
				slideToggle(el, 300);
			});
		});
	},


	/**
	 * Toggles pinned items
	 */
	onTogglePinned() {

		$('.catalog-session-pinned .header').on("click", ev => {
			$(".pinned-items", $(ev.currentTarget).closest(".catalog-session-pinned")).each((idx, el) => {
				slideToggle(el, 300);
			});
		});
	},


	/**
	 * Delete a product without page reload
	 */
	onRemovePinned() {

        $("body").on("click", ".catalog-session-pinned .delete", async ev => {

            ev.preventDefault();

            const $btn = $(ev.currentTarget);
            const form = $btn.closest("form");
            const prodid = $btn.closest(".product").data("prodid");
            $btn.closest(".pinned-item").addClass("loading");

            try {
                const data = await Aimeos.fetchHtml(form.attr("action"), {
                    method: "POST",
                    body: new FormData(form[0])
                });

                const doc = Aimeos.parseHtml(data);

                $(".catalog-session-pinned")
                    .replaceWith($(".catalog-session-pinned", doc));

                $('.product[data-prodid="' + prodid + '"] .btn-pin')
                    .removeClass("active");
            } catch(error) {
                $btn.closest('.pinned-item').removeClass('loading');
                console.warn('Unable to update pinned products', error);
            }
        });
	},


	/**
	 * Initializes the catalog session actions
	 */
	init: function() {
		if(this.once) return;
		this.once = true;

		this.onRemovePinned();
		this.onTogglePinned();
		this.onToggleSeen();
	}
};


$(function() {
	AimeosCatalogSession.init();
});

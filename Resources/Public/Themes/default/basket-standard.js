/**
 * Basket standard client actions
 */
AimeosBasketStandard = {

	/**
	 * Hides the update button and show only on quantity change
	 */
	onQuantity() {

		$(".basket-standard .btn-update").hide();

		$(document).on("focusin", ".basket-standard .basket .product-item .quantity .value", {}, ev => {
			const target = $(ev.currentTarget).closest('.basket-standard');

			$(".btn-update", target).show();
			$(".btn-action", target).hide();
		});
	},


	/**
	 * Updates basket without page reload
	 */
	onSubmit() {

		$(document).on("submit", ".basket-standard form", ev => {
			Aimeos.createSpinner();
			const selector = ev.submitter || ev.originalEvent.submitter;

			fetch($(selector).attr("formaction") || $(ev.currentTarget).attr("action"), {
				body: new FormData(ev.currentTarget),
				method: 'POST'
			}).then(response => {
				return response.text();
			}).then(data => {
				$(".basket-standard").html(AimeosBasket.updateBasket(data).html());
			}).finally(() => {
				Aimeos.removeSpinner();
			});

			return false;
		});
	},


	/**
	 * Updates quantity and deletes products without page reload
	 */
	onChange() {

		$(document).on("click", ".basket-standard a.change", ev => {
			Aimeos.createSpinner();

			fetch($(ev.currentTarget).attr("href")).then(response => {
				return response.text();
			}).then(data => {
				$(".basket-standard").html(AimeosBasket.updateBasket(data).html());
			}).finally(() => {
				Aimeos.removeSpinner();
			});

			return false;
		});
	},


	/**
	 * Initializes the basket standard actions
	 */
	init() {
		if(this.once) return;
		this.once = true;

		this.onChange();
		this.onSubmit();
		this.onQuantity();
	}
};


$(function() {
	AimeosBasketStandard.init();
});
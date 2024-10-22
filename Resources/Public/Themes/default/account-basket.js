/**
 * Account basket actions
 */
AimeosAccountBasket = {

	/**
	 * Shows basket details without page reload
	 */
	onToggleDetail() {

		$(".account-basket").on("click", ".basket-item .action .btn", ev => {

			const target = $(ev.currentTarget).closest(".basket-item");
			const details = $(".account-basket-detail", target);

			$(".btn.show", target).toggleClass('hidden');
			$(".btn.close", target).toggleClass('hidden');

			slideToggle(details[0], 300);

			return false;
		});
	},


	/**
	 * Initializes the account basket actions
	 */
	init() {
		if(this.once) return;
		this.once = true;

		this.onToggleDetail();
	}
};


$(() => {
	AimeosAccountBasket.init();
});
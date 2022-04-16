/**
 * Account subscription actions
 */
AimeosAccountSubscription = {

	/**
	 * Shows subscription details without page reload
	 */
	onToggleDetail() {

		$(".account-subscription").on("click", ".subscription-item .action .btn", ev => {

			const target = $(ev.currentTarget).closest(".subscription-item");
			const details = $(".account-subscription-detail", target);

			$(".btn.show", target).toggleClass('hidden');
			$(".btn.close", target).toggleClass('hidden');

			slideToggle(details[0], 300);

			return false;
		});
	},


	/**
	 * Initializes the account subscription actions
	 */
	init() {
		this.onToggleDetail();
	}
};


$(() => {
	AimeosAccountSubscription.init();
});
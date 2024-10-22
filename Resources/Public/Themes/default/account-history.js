/**
 * Account history actions
 */
AimeosAccountHistory = {

	/**
	 * Shows history details without page reload
	 */
	onToggleDetail() {

		$(".account-history").on("click", ".history-item .action .btn", ev => {

			const target = $(ev.currentTarget).closest(".history-item");
			const details = $(".account-history-detail", target);

			$(".btn.show", target).toggleClass('hidden');
			$(".btn.close", target).toggleClass('hidden');

			slideToggle(details[0], 300);

			return false;
		});
	},


	/**
	 * Initializes the account history actions
	 */
	init() {
		if(this.once) return;
		this.once = true;

		this.onToggleDetail();
	}
};


$(() => {
	AimeosAccountHistory.init();
});
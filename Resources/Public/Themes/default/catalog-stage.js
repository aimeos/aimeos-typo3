/**
 * Catalog session client actions
 */
 AimeosCatalogStage = {

	/**
	 * Back to last page
	 */
	onBack() {

		$("body").on("click", ".catalog-stage-breadcrumb a.back", ev => {

			history.back();
			return false;
		});
	},


	/**
	 * Initializes the catalog session actions
	 */
	init: function() {
		this.onBack();
	}
};


$(function() {
	AimeosCatalogStage.init();
});
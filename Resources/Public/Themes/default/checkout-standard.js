/**
 * Checkout standard client actions
 */
AimeosCheckoutStandard = {

	/**
	 * Shows only selected address forms
	 */
	setupAddressForms: function() {

		$(".checkout-standard-address .item-address").has(".header input:not(:checked)").find(".form-list").hide();

		$(".checkout-standard-address-billing .header input, .checkout-standard-address-delivery .header input").on("click", function(ev) {
			$(".checkout-standard-address .item-address").has(".header input:not(:checked)").find(".form-list").each(function() {
				slideUp(this, 300);
			});
			$(".form-list", $(ev.currentTarget).parents(".item-address")).each(function() {
				slideDown(this, 300);
			});
		});
	},


	/**
	 * Shows states only from selected countries
	 */
	setupCountryState: function() {

		$(".checkout-standard-address .form-list .countryid select").each(function() {
			if($(this).val() !== "") {
				$(this).parents(".form-list").find(".state optgroup:not(." + $(this).val() + ")").hide();
			}
		});

		$(".checkout-standard-address .form-list .countryid select").on("change", function() {
			var list = $(this).parents(".form-list");
			$(".state select", list).val("");
			$(".state optgroup", list).hide();
			$(".state ." + $(this).val(), list).show();
		});
	},


	/**
	 * Shows only form fields of selected service option
	 */
	setupServiceForms: function() {

		/* Hide form fields if delivery/payment option is not selected */
		$(".checkout-standard-delivery,.checkout-standard-payment").each(function() {
			$(this).find(".form-list").hide();
			$(this).find(".item-service").has("input.option:checked").find(".form-list").show();
		});

		/* Delivery/payment form slide up/down when selected */
		$(".checkout-standard-delivery .option, .checkout-standard-payment .option").on("click", function() {
			$(".item-service").has("label input:not(:checked)").find(".form-list").each(function() {
				slideUp(this, 300);
			});
			$(this).parents(".item-service").find(".form-list").each(function() {
				slideDown(this, 300);
			});
		});
	},


	/**
	 * Checks for mandatory fields in all forms
	 */
	setupMandatoryCheck: function() {

		$(".checkout-standard .form-item").on("blur", "input,select", function(ev) {
			var node = $(ev.currentTarget).parents(".form-item");
			var regex = new RegExp(node.data('regex'));
			var value = $(this).val();

			if((value !== '' && value.match(regex)) || (value === '' && !node.hasClass("mandatory"))) {
				node.removeClass("error").addClass("success");
			} else {
				node.removeClass("success").addClass("error");
			}
		});

		$(".checkout-standard form").on("submit", function() {
			var retval = true;
			var nodes = [];

			var testfn = function(idx, element) {

				var elem = $(element);
				var value = $("input,select", elem).val();

				if(value === null || value.trim() === "") {
					elem.addClass("error");
					nodes.push(element);
					retval = false;
				} else {
					elem.removeClass("error");
				}
			};

			$(".checkout-standard .item-new, .item-service").each(function() {
				if($(".header,label input", this).is(":checked")) {
					$(".form-list .mandatory", this).each(testfn);
				}
			});

			$(".checkout-standard-process .form-list .mandatory").each(testfn);

			if( nodes.length !== 0 ) {
				$('html, body').animate({
					scrollTop: $(nodes).first().offset().top + 'px'
				});
			}

			return retval;
		});
	},


	/**
	 * Redirect to payment provider / confirm page when order has been created successfully
	 */
	setupPaymentRedirect: function() {

		var form = $(".checkout-standard form").first();
		var node = $(".checkout-standard-process", form);
		var anchor = $("a.btn-action", node);

		if(form.length && node.length && anchor.length > 0) {
			window.location = anchor.attr("href");
		} else if(node.length > 0 && node.has(".mandatory").length === 0 && node.has(".optional").length === 0 && form.attr("action") !== '' ) {
			form.get(0).submit();
		}
	},


	/**
	 * Initializes the checkout standard section
	 */
	init: function() {

		this.setupAddressForms();
		this.setupServiceForms();

		this.setupCountryState();

		this.setupMandatoryCheck();
		this.setupPaymentRedirect();
	}
};


$(function() {
	AimeosCheckoutStandard.init();
});

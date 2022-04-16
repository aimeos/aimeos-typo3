/**
 * Basket mini client actions
 */
AimeosBasketMini = {

	/**
	 * Updates the basket mini content
	 */
	updateBasket(basket) {

		if(!(basket.data && basket.data.attributes)) {
			return;
		}

		const attr = basket.data.attributes;
		const price = Number.parseFloat(attr['order.base.price']);
		const delivery = Number.parseFloat(attr['order.base.costs']);

		const formatter = new Intl.NumberFormat([], {
			currency: attr['order.base.currencyid'],
			style: "currency"
		});

		$(".aimeos .basket-mini-main .value").html(formatter.format(price + delivery));
		$(".aimeos .basket-mini-product .total .price").html(formatter.format(price + delivery));
		$(".aimeos .basket-mini-product .delivery .price").html(formatter.format(delivery));

		if(basket.included) {
			let csrf = '';
			let count = 0;
			const body = $(".aimeos .basket-mini-product .basket-body");
			const prototype = $(".aimeos .basket-mini-product .product.prototype");

			if(basket.meta && basket.meta.csrf) {
				csrf = basket.meta.csrf.name + '=' + basket.meta.csrf.value;
			}

			$(".aimeos .basket-mini-product .product").not(".prototype").remove();

			for(let i=0; i<basket.included.length; i++) {
				let entry = basket.included[i];

				if(entry.type === 'basket/product') {
					let product = prototype.clone().removeClass("prototype");

					if(entry.links && entry.links.self && entry.links.self.href) {
						let urldata = (entry.links.self.href.indexOf('?') === -1 ? '?' : '&') + csrf;
						product.data("url", entry.links.self.href + urldata);
					}

					$(".name", product).html(entry.attributes['order.base.product.name']);
					$(".quantity", product).html(entry.attributes['order.base.product.quantity']);
					$(".price", product).html(formatter.format(entry.attributes['order.base.product.price']));

					if(entry.attributes['order.base.product.flags']) {
						$(".action .delete", product).addClass("hidden");
					}

					body.append(product);
					count += Number.parseInt(entry.attributes["order.base.product.quantity"]);
				}
			}

			$(".aimeos .basket-mini-main .quantity").html(count);
		}
	},


	/**
	 * Delete a product without page reload
	 */
	onDelete() {

		$(".aimeos .basket-mini-product").on("click", ".delete", ev => {

			fetch($(ev.currentTarget).closest(".product").data("url"), {
				method: "DELETE",
				headers: {'Content-Type': 'application/json'}
			}).then(response => {
				return response.json();
			}).then(basket => {
				AimeosBasketMini.updateBasket(basket);
			});

			return false;
		});
	},


	/**
	 * Show basket offscreen menu
	 */
	onShowBasket() {

		$('.basket-mini .menu').on('click', () => {
			$('.basket-mini .aimeos-overlay-offscreen').addClass('show');
			$('.basket-mini .zeynep').addClass('opened');
		});
	},


	/**
	 * Hide basket offscreen menu
	 */
	onHideBasket() {

		$('.basket-mini .close').on('click', () => {
			$('.basket-mini .aimeos-overlay-offscreen').removeClass('show');
			$('.basket-mini .zeynep').removeClass('opened');
		});
	},


	/**
	 * Initializes the basket mini actions
	 */
	init() {
		this.onDelete();
		this.onShowBasket();
		this.onHideBasket();
	}
};


$(function() {
	AimeosBasketMini.init();
});
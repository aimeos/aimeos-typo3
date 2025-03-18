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
		const price = Number.parseFloat(attr['order.price']);
		const delivery = Number.parseFloat(attr['order.costs']);

		const formatter = new Intl.NumberFormat(attr['order.languageid'], {
			currency: attr['order.currencyid'].replace(/_/g, '-'),
			style: "currency"
		});

		$(".aimeos .basket-mini-main .value").html(formatter.format(price + delivery));
		$(".aimeos .basket-mini-product .total .price").html(formatter.format(price + delivery));
		$(".aimeos .basket-mini-product .delivery .price").html(formatter.format(delivery));

		if(basket.included) {
			let csrf = '';
			let count = 0;
			const body = $(".aimeos .basket-mini-product .basket-body");
			const prototype = $(".aimeos .basket-mini-product .product-item.prototype");

			if(basket.meta && basket.meta.csrf) {
				csrf = basket.meta.csrf.name + '=' + basket.meta.csrf.value;
			}

			$(".aimeos .basket-mini-product .product-item").not(".prototype").remove();

			for(let i=0; i<basket.included.length; i++) {
				let entry = basket.included[i];

				if(entry.type === 'basket.product') {
					let product = prototype.clone().removeClass("prototype");

					if(entry.links && entry.links.self && entry.links.self.href) {
						let urldata = (entry.links.self.href.indexOf('?') === -1 ? '?' : '&') + csrf;
						product.data("url", entry.links.self.href + urldata);
					}

					$(".name", product).html(entry.attributes['order.product.name']);
					$(".quantity", product).html(entry.attributes['order.product.quantity']);
					$(".price", product).html(
						formatter.format(Number(entry.attributes['order.product.price'])
						+ Number(entry.attributes['order.product.costs']))
					);

					if(entry.attributes['order.product.flags']) {
						$(".action .delete", product).addClass("hidden");
					}

					body.append(product);
					count += Number.parseInt(entry.attributes["order.product.quantity"]);
				}
			}

			$(".aimeos .basket-mini-main .quantity").html(count);

			$(".aimeos .basket-mini-main").addClass("highlight");
			setTimeout(() => {
					$(".aimeos .basket-mini-main").removeClass("highlight");
			}, 250);
		}
	},


	/**
	 * Delete a product without page reload
	 */
	onDelete() {

		$(".aimeos .basket-mini-product").on("click", ".delete", async ev => {

			await fetch($(ev.currentTarget).closest(".product-item").data("url"), {
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
		if(this.once) return;
		this.once = true;

		this.onDelete();
		this.onShowBasket();
		this.onHideBasket();
	}
};


$(function() {
	AimeosBasketMini.init();
});
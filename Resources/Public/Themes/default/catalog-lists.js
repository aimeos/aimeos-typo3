/**
 * Catalog list actions
 */
AimeosCatalogLists = {

	/**
	 * Shows the basket after submitting the form
	 *
	 * @param DomNode form Form DOM element
	 */
	async showBasket(form) {

		await fetch(form.getAttribute("action"), {
			body: new FormData(form),
			method: 'POST'
		}).then(response => {
			return response.text();
		}).then(data => {
			Aimeos.createContainer(AimeosBasket.updateBasket(data));
		});
	},


	/**
	 * Marks products as pinned
	 */
	setPinned() {

		$('.catalog-list-items').each((idx, el) => {
			$('.product .btn-pin', el).removeClass('active');

			for(id in $(el).data('pinned')) {
				$('.product[data-prodid="' + id + '"] .btn-pin', el).addClass('active');
			}
		});
	},


	/**
	 * Add to basket
	 */
	onAddBasket() {

		$(document).on("click", ".catalog-list-items .product .btn-action", ev => {
			const target = $(ev.currentTarget).closest(".product");
			ev.preventDefault();

			Aimeos.createOverlay();

			if($(".catalog-list-items:not(.list) .basket .items-selection .selection li, .catalog-list-items:not(.list) .basket .items-attribute .selection li", target).length) {
				const node = target.clone();

				$("[id]", node).each((idx, el) => {
					el.setAttribute("id", el.getAttribute("id") + '-2');
				});

				$("[for]", node).each((idx, el) => {
					el.setAttribute("for", el.getAttribute("for") + '-2');
				});

				node.on("click", ".btn-action", (ev) => {
					if(AimeosCatalog.checkVariants(ev.currentTarget)) {
						this.showBasket($(ev.currentTarget).closest("form.basket")[0]);
					}
					ev.stopPropagation();
					ev.preventDefault();
				});

				Aimeos.createContainer($('<div class="catalog-list catalog-list-items list">').append(node));
				return;
			}

			this.showBasket($("form.basket", target)[0]);
		});
	},


	/**
	 * Enables infinite scroll if available
	 */
	async onScroll() {

		if($('.catalog-list-items').data('infiniteurl')) {
			const scroll = async function() {

				const list = $('.catalog-list-items');
				const infiniteUrl = list.data('infiniteurl');

				if(infiniteUrl && list.length && list[0].getBoundingClientRect().bottom < window.innerHeight * 3) {
					list.data('infiniteurl', '');

					await fetch(infiniteUrl).then(response => {
						return response.text();
					}).then(data => {
						const nonce = $('script.items-stock', document)?.attr('nonce');
						const nextPage = $('<html/>').html(data);
						const newList = $('.catalog-list-items', nextPage);
						const ids = newList.data('pinned') || {};

						$('.product', newList).each((idx, node) => {
							ids[node.dataset.prodid] ? $('.btn-pin', node).addClass('active') : null;
							list.append(node);
						});

						$('script.items-stock', nextPage).each((idx, node) => {
							node.setAttribute('nonce', nonce);
							$(document.head).append(node);
						});

						list.data('infiniteurl', newList.data('infiniteurl'));
						$(window).trigger('scroll');
						Aimeos.loadImages();
					});
				}
			};

			$(window).on('scroll', scroll);
			scroll();
		}
	},


	/**
	 * Add products to pinned list
	 */
	onPin() {

		$("body").on("click", ".catalog-list-items .product .btn-pin", async ev => {

			const el = $(ev.currentTarget);
			const url = el.hasClass('active') ? el.data('rmurl') : el.attr('href');

			if(url) {
				const form = new FormData();
				const csrf = el.closest('form').find('.csrf-token');

				form.append(csrf.attr('name'), csrf.attr('value'));
				el.toggleClass('active');

				await fetch(url, {
					method: 'POST',
					body: form
				}).then(response => {
					return response.text();
				}).then(data => {
					const doc = $('<html/>').html(data);
					const pinned = $(".catalog-session-pinned", doc);

					if(pinned.length) {
						$('.catalog-session-pinned').replaceWith(pinned);
					}
				});

				return false;
			}
		});
	},


	/**
	 * Initializes the catalog list actions
	 */
	init() {
		if(this.once) return;
		this.once = true;

		this.setPinned();
		this.onAddBasket();
		this.onScroll();
		this.onPin();
	}
};


$(function() {
	AimeosCatalogLists.init();
});

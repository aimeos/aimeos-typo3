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

		await Aimeos.fetchHtml(form.getAttribute("action"), {
			body: new FormData(form),
			method: 'POST'
		}).then(data => {
			Aimeos.createContainer(AimeosBasket.updateBasket(data));
		}).catch(error => {
			Aimeos.removeOverlay();
			console.warn('Unable to update the basket', error);
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
	 * Identifies a stock endpoint, allowing only numeric product-ID parameters to vary.
	 * Keep routing parameters intact, including namespaced TYPO3 parameters.
	 */
	stockRoute(value) {
		const url = Aimeos.sameOriginUrl(value);
		if(!url) return null;

		for(const key of Array.from(url.searchParams.keys())) {
			if(/(?:^|\[)st_pid\]?(?:\[\d*\])*$/.test(key)
				&& url.searchParams.getAll(key).every(value => /^\d+$/.test(value))) {
				url.searchParams.delete(key);
			}
		}
		url.hash = '';
		url.searchParams.sort();
		return url.href;
	},


	/**
	 * Enables infinite scroll if available
	 */
	async onScroll() {

		// Only stock endpoints declared in the original page head may supply scripts.
		const stocks = Array.from(document.head.querySelectorAll('script.items-stock[src]'), node => ({
			route: this.stockRoute(node.getAttribute('src')), nonce: node.nonce
		})).filter(item => item.route);

		$('.catalog-list-items[data-infiniteurl]').each((idx, element) => {
			const list = $(element);
			const initial = Aimeos.sameOriginUrl(list.data('infiniteurl'));
			if(!initial) return;

			const scroll = async function() {
				const url = Aimeos.sameOriginUrl(list.data('infiniteurl'));

				if(url && url.pathname === initial.pathname && element.getBoundingClientRect().bottom < window.innerHeight * 3) {
					list.data('infiniteurl', '');

					await Aimeos.fetchHtml(url.href).then(data => {
						const nextPage = new DOMParser().parseFromString(data, 'text/html');
						const stockScripts = [];
						for(const node of nextPage.querySelectorAll('script.items-stock[src]')) {
							const src = Aimeos.sameOriginUrl(node.getAttribute('src'));
							const route = src && AimeosCatalogLists.stockRoute(src.href);
							const stock = stocks.find(item => item.route === route);
							if(stock) stockScripts.push({src: src.href, nonce: stock.nonce});
						}
						// Never adopt script elements or inline code from a fetched page.
						Aimeos.cleanHtml(nextPage);
						const newList = $('.catalog-list-items[data-infiniteurl]', nextPage).first();
						if(!newList.length) return;
						const ids = newList.data('pinned') || {};

						$('.product', newList).each((idx, node) => {
							ids[node.dataset.prodid] ? $('.btn-pin', node).addClass('active') : null;
							element.appendChild(node);
						});

						for(const item of stockScripts) {
							const script = document.createElement('script');
							script.src = item.src;
							script.nonce = item.nonce;
							document.head.appendChild(script);
						}

						list.data('infiniteurl', newList.data('infiniteurl'));
						$(window).trigger('scroll');
						Aimeos.loadImages();
					}).catch(error => console.warn('Unable to load the next catalog page', error));
				}
			};

			$(window).on('scroll', scroll);
			scroll();
		});
	},


	/**
	 * Add products to pinned list
	 */
	onPin() {

		$("body").on("click", ".catalog-list-items .product .btn-pin", async ev => {
			ev.preventDefault();

			const el = $(ev.currentTarget);
			const url = Aimeos.sameOriginUrl(el.hasClass('active') ? el.data('rmurl') : el.attr('href'));

			if(url) {
				const form = new FormData();
				const csrf = el.closest('form').find('.csrf-token');

				form.append(csrf.attr('name'), csrf.attr('value'));
				el.toggleClass('active');

				await Aimeos.fetchHtml(url.href, {
					method: 'POST',
					body: form
				}).then(data => {
					const doc = Aimeos.parseHtml(data);
					const pinned = $(".catalog-session-pinned", doc);

					if(pinned.length) {
						$('.catalog-session-pinned').replaceWith(pinned);
					}
				}).catch(error => {
					el.toggleClass('active');
					console.warn('Unable to update pinned products', error);
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

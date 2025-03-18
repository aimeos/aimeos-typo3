/* Nested parameter encoding v1.1.8 https://github.com/knowledgecode/jquery-param (MIT License) */
(function(c,d){"object"===typeof exports&&"undefined"!==typeof module?module.exports=d():"function"===typeof define&&define.amd?define(d):(c="undefined"!==typeof globalThis?globalThis:c||self,c.param=d())})(this,function(){return function(c){var d=[],g=function(e,a){a="function"===typeof a?a():a;a=null===a?"":void 0===a?"":a;d[d.length]=encodeURIComponent(e)+"="+encodeURIComponent(a)},f=function(e,a){var c;if(e)if(Array.isArray(a)){var b=0;for(c=a.length;b<c;b++)f(e+"["+("object"===typeof a[b]&&a[b]?b:"")+"]",a[b])}else if("[object Object]"===Object.prototype.toString.call(a))for(b in a)f(e+"["+b+"]",a[b]);else g(e,a);else if(Array.isArray(a))for(b=0,c=a.length;b<c;b++)g(a[b].name,a[b].value);else for(b in a)f(b,a[b]);return d};return f("",c).join("&")}});

/* slideToggle 44ede23 https://github.com/ericbutler555/plain-js-slidetoggle (MIT License) */
function slideToggle(t,e,o){0===t.clientHeight?j(t,e,o,!0):j(t,e,o)}function slideUp(t,e,o){j(t,e,o)}function slideDown(t,e,o){j(t,e,o,!0)}function j(t,e,o,i){void 0===e&&(e=400),void 0===i&&(i=!1),t.style.overflow="hidden",i&&(t.style.display="block");var p,l=window.getComputedStyle(t),n=parseFloat(l.getPropertyValue("height")),a=parseFloat(l.getPropertyValue("padding-top")),s=parseFloat(l.getPropertyValue("padding-bottom")),r=parseFloat(l.getPropertyValue("margin-top")),d=parseFloat(l.getPropertyValue("margin-bottom")),g=n/e,y=a/e,m=s/e,u=r/e,h=d/e;window.requestAnimationFrame(function l(x){void 0===p&&(p=x);var f=x-p;i?(t.style.height=g*f+"px",t.style.paddingTop=y*f+"px",t.style.paddingBottom=m*f+"px",t.style.marginTop=u*f+"px",t.style.marginBottom=h*f+"px"):(t.style.height=n-g*f+"px",t.style.paddingTop=a-y*f+"px",t.style.paddingBottom=s-m*f+"px",t.style.marginTop=r-u*f+"px",t.style.marginBottom=d-h*f+"px"),f>=e?(t.style.height="",t.style.paddingTop="",t.style.paddingBottom="",t.style.marginTop="",t.style.marginBottom="",t.style.overflow="",i||(t.style.display="none"),"function"==typeof o&&o()):window.requestAnimationFrame(l)})}

/* zeynepjs v2.2.0 https://github.com/hsynlms/zeynepjs (MIT License) */
!function(l,s){var n={htmlClass:!0};function i(e,t){this.element=e,this.eventController=o,this.options=l.extend({},n,t),this.options.initialized=!1,this.init()}i.prototype.init=function(){var s=this.element,e=this.options,i=this.eventController.bind(this);!0!==e.initialized&&(i("loading"),s.find("[data-submenu]").on("click",function(e){e.preventDefault();var t,n=l(this).attr("data-submenu"),o=l("#"+n);o.length&&(i("opening",t={subMenu:!0,menuId:n}),s.find(".submenu.current").removeClass("current"),o.addClass("opened current"),s.hasClass("submenu-opened")||s.addClass("submenu-opened"),s[0].scrollTo({top:0}),i("opened",t))}),s.find("[data-submenu-close]").on("click",function(e){e.preventDefault();var t,n=l(this).attr("data-submenu-close"),o=l("#"+n);o.length&&(i("closing",t={subMenu:!0,menuId:n}),o.removeClass("opened current"),s.find(".submenu.opened").last().addClass("current"),s.find(".submenu.opened").length||s.removeClass("submenu-opened"),o[0].scrollTo({top:0}),i("closed",t))}),i("load"),this.options.htmlClass&&!l("html").hasClass("zeynep-initialized")&&l("html").addClass("zeynep-initialized"),e.initialized=!0)},i.prototype.open=function(){this.eventController("opening",{subMenu:!1}),this.element.addClass("opened"),this.options.htmlClass&&l("html").addClass("zeynep-opened"),this.eventController("opened",{subMenu:!1})},i.prototype.close=function(e){e||this.eventController("closing",{subMenu:!1}),this.element.removeClass("opened"),this.options.htmlClass&&l("html").removeClass("zeynep-opened"),e||this.eventController("closed",{subMenu:!1})},i.prototype.destroy=function(){this.eventController("destroying"),this.close(!0),this.element.find(".submenu.opened").removeClass("opened"),this.element.removeData(s),this.eventController("destroyed"),this.options=n,this.options.htmlClass&&l("html").removeClass("zeynep-initialized"),delete this.element,delete this.options,delete this.eventController},i.prototype.on=function(e,t){r.call(this,e,t)};var o=function(e,t){if(this.options[e]){if("function"!=typeof this.options[e])throw Error("event handler must be a function: "+e);this.options[e].call(this,this.element,this.options,t)}},r=function(e,t){if("string"!=typeof e)throw Error("event name is expected to be a string but got: "+typeof e);if("function"!=typeof t)throw Error("event handler is not a function for: "+e);this.options[e]=t};l.fn[s]=function(e){var t,n,o;return t=l(this[0]),n=e,o=null,t.data(s)?o=t.data(s):(o=new i(t,n||{}),t.data(s,o)),o}}(window.jQuery||window.cash,"zeynep");


/**
 * Aimeos related Javascript code
 *
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2014-2025
 */


/**
 * Aimeos common actions
 */
Aimeos = {

	/**
	 * Creates a floating container over the page displaying the given content node
	 */
	createContainer(content) {
		const container = $("<div/>").addClass("aimeos aimeos-container");

		container.prepend($("<a/>").addClass("minibutton btn-close"));
		$("body").append(container.append(content));

		const resize = function() {
			const win = $(window);
			const left = (win.width() - container.outerWidth()) / 2;
			const top = window.scrollY + (win.height() - container.outerHeight()) / 2;

			container.css("left", (left > 0 ? left : 0));
			container.css("top", (top > 0 ? top : 0));
		};

		$(window).on("resize", resize);
		resize();
	},


	/**
	 *  Adds an overlay on top of the current page
	 */
	createOverlay() {
		$("body").append($("<div/>").addClass("aimeos-overlay show"));
	},


	/**
	 *  Adds a spinner on top of the current page
	 */
	createSpinner() {
		$("body").append($("<div/>").addClass("aimeos-spinner"));
	},


	/**
	 * Delays the function by the given timeout
	 *
	 * @param {Function} fcn Function to delay
	 * @param {int} timeout Timeout in milliseconds
	 * @returns {Function} Function wrapped into timer function
	 */
	 debounce( fcn, timeout = 300) {
		let timer;
		return (...args) => {
		  clearTimeout(timer);
		  timer = setTimeout(() => { fcn.apply(this, args); }, timeout);
		};
	},


	/**
	 * Removes an existing overlay from the current page
	 */
	removeOverlay() {
		const container = $(".aimeos-container");
		const overlay = $(".aimeos-overlay");

		if(container.length + overlay.length > 0) { // remove only if in overlay mode
			container.remove();
			overlay.remove();
			return false;
		}

		return true;
	},


	/**
	 * Removes an existing spinner from the current page
	 */
	removeSpinner() {
		$(".aimeos-spinner").remove();
	},


	/**
	 * Lazy load product image in list views
	 */
	loadImages() {

		const render = function(element) {
			if(element.tagName === 'IMG') {
				element.setAttribute("srcset", element.getAttribute("data-srcset"));
				element.setAttribute("src", element.getAttribute("data-src"));
			} else if(element.getAttribute("data-background")) {
				const srcset = element.getAttribute("data-background");
				let url = '';

				srcset && srcset.split(',').every(function(str) {
					const parts = str.trim().split(' ');
					url = parts[0];

					return parseInt((parts[1] || '').replace('w', '')) > window.innerWidth ? false : true;
				});

				element.style.backgroundImage = "url('" + url + "')";
			}

			element.classList.remove("lazy-image");
		};


		if('IntersectionObserver' in window) {
			const callback = function(entries, observer) {
				for(let entry of entries) {
					if(entry.isIntersecting) {
						observer.unobserve(entry.target);
						render(entry.target);
					}
				};
			};

			$(".aimeos .lazy-image").each((idx, el) => {
				(new IntersectionObserver(callback, {
					root: null,
					rootMargin: '320px',
					threshold: 0
				})).observe(el);
			});
		} else {
			$(".aimeos .lazy-image").each((idx, el) => {
				render(el);
			});
		}
	},


	/**
	 * Sets up the ways to close the container by the user
	 */
	onCloseContainer() {

		$(document).on("click", ".aimeos-overlay, .aimeos-container .btn-close", () => {
			return Aimeos.removeOverlay();
		});

		$(document).on("click", ".aimeos-container .btn-back", () => {
			return Aimeos.removeOverlay();
		});

		$(document).on("keydown", ev => {
			if(ev.key == "Escape") {
				return Aimeos.removeOverlay();
			}
		});
	},


	/**
	 * Initializes the setup methods
	 */
	init() {
		if(this.once) return;
		this.once = true;

		this.loadImages();
		this.onCloseContainer();
	}
};


/**
 * Basket client actions
 */
AimeosBasket = {

	/**
	 * Goes back to underlying page when back or close button of the basket is clicked
	 */
	onBack() {

		$(document).on("click", ".basket-standard .btn-back", () => {
			Aimeos.removeOverlay();
			return false;
		});
	},


	/**
	 * Updates the basket without page reload
	 */
	updateBasket(data) {
		const doc = $("<html/>").html(data);
		const basket = $(".aimeos.basket-standard", doc);

		$('.aimeos .error-list, .aimeos .info-list', doc).each((idx, el) => {
			basket.prepend(el);
		});

		$('link.basket-standard', doc).each((idx, el) => {
			basket.append(el);
		});

		if(!$('body').hasClass('basket')) {
			$('script.basket-standard', doc).each((idx, el) => {
				basket.append(el);
			});
			$('body').addClass('basket');
		}

		$(".btn-update", basket).hide();


		const jsonurl = $(".aimeos.basket-mini[data-jsonurl]").data("jsonurl");

		if(jsonurl && typeof AimeosBasketMini !== 'undefined') {
			fetch(jsonurl, {
				method: "OPTIONS",
				headers: {'Content-Type': 'application/json'}
			}).then(response => {
				return response.json();
			}).then(async options => {
				const url = options.meta.resources['basket']
				const args = {'include': 'basket.product'}
				let params = {}

				if( options.meta.prefix ) {
					params[options.meta.prefix] = args
				} else {
					params = args
				}
				const query = window.param(params)

				await fetch(url + (url.indexOf('?') === -1 ? '?' + query : '&' + query), {
					headers: {'Content-Type': 'application/json'}
				}).then(response => {
					return response.json();
				}).then(basket => {
					AimeosBasketMini.updateBasket(basket);
				});
			});
		}

		return basket;
	},


	/**
	 * Initializes the basket actions
	 */
	init: function() {
		if(this.once) return;
		this.once = true;

		this.onBack();
	}
};


/**
 * Aimeos common catalog actions
 */
 AimeosCatalog = {

	/**
	 * Checks if all selection variants have been choosen
	 *
	 * @param {DOMNode} node
	 * @returns TRUE if selection is complete, FALSE if not
	 */
	checkVariants(node) {
		let result = true;

		$(".items-selection .selection[data-attrdeps] .select-item", $(node).closest(".basket")).each((idx, el) => {

			if($(".select-list", el).val() !== '' || $(".select-option:checked", el).length > 0) {
				$(el).removeClass("error");
			} else {
				$(el).addClass("error");
				result = false;
			}
		});

		return result;
	},


	/**
	 * Checks if all variant attributes of a variant article have been selected
	 *
	 * @param DomNode node Node of the product item
	 * @returns bool TRUE if all variant attributes have been selected, FALSE if not
	 */
	isValidVariant(node) {
		let result = true;

		$(".selection[data-attrdeps] .select-item", node).each((idx, el) => {
			if($(".select-list", el).val() === '' && $(".select-option:checked", el).length <= 0) {
				result = false;
			}
		});

		return result;
	},


	/**
	 * Evaluates the product variant dependencies.
	 *
	 * It does not only work with <select> and <option> tags but also if a
	 *
	 * <div class="select-list" data-index="<index value: 0-31>"> and
	 *
	 * <input class="select-option" type="radio"> or
	 * <input class="select-option" type="checkbox">
	 *
	 * are used. The data-index attribute of the .select-list container is
	 * required to calculate the disabled attributes for each option according
	 * to its dependencies. It must start with "0" and an unique, ascending value
	 * must be assigned to each container. The maximum number of possible indexes
	 * (and therefore dependent containers within an .selection node) is 31
	 * because it's an integer bitmap.
	 */
	onSelectDependencies() {

		$(document).on("change", ".product .selection[data-attrdeps] .select-list", ev => {

			const node = ev.currentTarget;
			const el = $(node);
			const index = el.data("index");
			const target = el.parents(".selection");
			const value = el.find(".select-option:checked").val();

			const attrDeps = target.data("attrdeps") || {}; // {"<attrid>":["prodid",...],...}
			const prodDeps = target.data("proddeps") || {}; // {"<prodid>":["attrid",...],...}
			const attrMap = {};

			if(typeof index === "undefined") {
				throw new Error( "HTML select node has no attribute data-index" );
			}


			// Retrieves the list of available attribute ID => product ID
			// combinations for the selected value
			if( attrDeps.hasOwnProperty(value) ) {

				for(let i=0; i<attrDeps[value].length; i++) {
					let prodId = attrDeps[value][i];

					if(prodDeps.hasOwnProperty(prodId)) {
						for(let j=0; j<prodDeps[prodId].length; j++) {
							attrMap[prodDeps[prodId][j]] = prodId;
						}
					}
				}
			}


			$(".select-list", target).each(function(idx, select) {

				if( node == select ) {
					return;
				}

				if( index === 0 ) {
					$(".select-option", select).prop("checked", false).prop("selected", false)
						.removeAttr("disabled").data("disabled", 0).data("by", {});
				}

				$(".select-option", select).each((idx, option) => {

					const opt = $(option);
					const val = opt.val();
					const by = opt.data("by") || {};
					let disabled = opt.data("disabled") || 0;


					// Sets or removes the disabled bits in the bitmap of the
					// .select-option and by which .select-list it was disabled.
					// Each option can be disabled by multiple dependencies and
					// we can remove each of the bits separately again.
					if( value !== "" && val !== "" && !attrMap.hasOwnProperty(val) ) {
						disabled |= 2<<index;
						by[index] = 1;
					} else if( by.hasOwnProperty(index) ) {
						disabled &= ~(2<<index);
						delete by[index];
					}

					if(idx !== 0 && disabled > 0) {
						opt.attr("disabled", "disabled");
						opt.prop("selected", false);
						opt.prop("checked", false);
					} else {
						opt.removeAttr("disabled");
					}

					opt.data("disabled", disabled);
					opt.data("by", by);
				});
			});
		});
	},


	/**
	 * Displays the associated stock level, price items and attributes for the selected product variant
	 */
	onSelectVariant() {

		$(document).on("change", ".product .selection[data-attrdeps] .select-list", ev => {

			let len = 0;
			let stock = false;

			const map = {};
			const item = $(ev.currentTarget).closest(".product");
			const target = $(ev.currentTarget).closest(".selection");
			const attrDeps = target.data("attrdeps") || {}; // {"<attrid>":["prodid",...],...}


			$(".select-option:checked", target).each((idx, el) => {
				let value = $(el).val();

				if(value !== "" && attrDeps.hasOwnProperty(value)) {

					for(let i=0; i<attrDeps[value].length; i++) {
						if(map.hasOwnProperty(attrDeps[value][i])) {
							map[attrDeps[value][i]]++;
						} else {
							map[attrDeps[value][i]] = 1;
						}
					}
				}

				len++;
			});


			for(let prodId in map) {
				if(map.hasOwnProperty(prodId) && map[prodId] === len) {
					stock = AimeosCatalog.toggleVariantData(item, prodId);
				}
			}

			if(AimeosCatalog.isValidVariant(item) && stock) {
				$(".addbasket .btn-action", item).removeClass("btn-disabled").removeAttr("disabled");
			} else {
				$(".addbasket .btn-action", item).addClass("btn-disabled").attr("disabled", "disabled");
			}
		});
	},


	/**
	 * Checks if all required variant attributes are selected
	 */
	onCheckVariant() {

		$(document).on("click", ".product .addbasket .btn-action", ev => {
			if(!this.checkVariants(ev.currentTarget)) {
				ev.preventDefault();
			}
		});
	},


	/**
	 * Shows the images associated to the variant attributes
	 */
	onImageVariant() {

		$(document).on("change", ".product .selection .select-list", ev => {

			const elem = $(ev.currentTarget);
			const type = elem.data("type");
			const value = elem.find(".select-option:checked").val();

			elem.closest(".product").find(".image-single .item").each((idx, el) => {
				if($(el).data("variant-" + type) == value) {
					swiffyslider.slideTo($(el).closest(".swiffy-slider")[0], idx)
				}
			});
		});
	},


	/**
	 * Adds a product to the favorite list without page reload
	 */
	onFavoriteAction() {

		$(document).on("submit", ".catalog-actions .actions-favorite", async ev => {
			ev.preventDefault();
			Aimeos.createOverlay();

			await fetch($(ev.currentTarget).attr("action"), {
				body: new FormData(ev.currentTarget),
				method: 'POST'
			}).then(response => {
				return response.text();
			}).then(data => {
				const doc = $("<html/>").html(data);
				const content = $(".aimeos.account-favorite", doc);

				if(content.length > 0) {
					$('.aimeos .error-list, .aimeos .info-list', doc).each((idx, el) => {
						content.prepend(el);
					});
					$('link.account-favorite', doc).each((idx, el) => {
						document.head.append(el);
					});
					$('script.account-favorite', doc).each((idx, el) => {
						$(document.head).append($('<script/>').attr('src', el.getAttribute('src')));
					});
					Aimeos.createContainer(content);
				} else {
					document.replaceChild(doc[0], document.documentElement);
				}
			});

			return false;
		});

	},


	/**
	 * Adds a product to the watch list without page reload
	 */
	onWatchAction() {

		$(document).on("submit", ".catalog-actions .actions-watch", async ev => {
			ev.preventDefault();
			Aimeos.createOverlay();

			await fetch($(ev.currentTarget).attr("action"), {
				body: new FormData(ev.currentTarget),
				method: 'POST'
			}).then(response => {
				return response.text();
			}).then(data => {
				const doc = $("<html/>").html(data);
				const content = $(".aimeos.account-watch", doc);

				if(content.length > 0) {
					$('.aimeos .error-list, .aimeos .info-list', doc).each((idx, el) => {
						content.prepend(el);
					});
					$('link.account-watch', doc).each((idx, el) => {
						document.head.append(el);
					});
					$('script.account-watch', doc).each((idx, el) => {
						$(document.head).append($('<script/>').attr('src', el.getAttribute('src')));
					});
					Aimeos.createContainer(content);
				} else {
					document.replaceChild(doc[0], document.documentElement);
				}
			});

			return false;
		});
	},


	/**
	 * Shows the variant data for the given product ID and hides the rest
	 *
	 * @param {DomNode} item Product node
	 * @param {string} prodId Product ID
	 * @return {bool} TRUE if stock is available, FALSE for no stock
	 */
	toggleVariantData(item, prodId) {

		let stock = false;
		let newStock = $('.stock-list [data-prodid="' + prodId + '"]', item);
		let newPrice = $('.price-list [data-prodid="' + prodId + '"]', item);

		if(newStock.length === 0) {
			newStock = $(".stock-list .articleitem:first-child", item);
		}

		if(newPrice.length === 0) {
			newPrice = $(".price-list .articleitem:first-child", item);
		}

		$(".articleitem", item).removeClass("stock-actual");
		newStock.addClass("stock-actual");

		$(".articleitem", item).removeClass("price-actual");
		newPrice.addClass("price-actual");

		if(!(item.data("reqstock") && $(".stockitem", newStock).filter(".stock-out").length == $(".stockitem", newStock).length)) {
			stock = true;
		}

		$(".catalog-detail-additional .subproduct-actual").removeClass("subproduct-actual");
		$(".catalog-detail-additional .subproduct-" + prodId).addClass("subproduct-actual");

		return stock;
	},


	/**
	 * Initializes the common catalog actions
	 */
	init: function() {
		if(this.once) return;
		this.once = true;

		this.onSelectDependencies();
		this.onSelectVariant();
		this.onCheckVariant();
		this.onImageVariant();
		this.onWatchAction();
		this.onFavoriteAction();
	}
};



/**
 * Page actions
 */

AimeosPage = {

	/**
	* Link to top
	*/
	onLinkTop() {
		const backToTop = document.querySelector(".back-to-top");

		if( backToTop) {
			backToTop.addEventListener("click", () => {
				document.documentElement.scrollTo({top: 0, behavior: "smooth"});
			});

			const observer = new IntersectionObserver((entries) => {
				entries.forEach((entry) => {
					if(entry.isIntersecting) {
						backToTop.classList.add("show");
					} else {
						backToTop.classList.remove("show");
					}
				});
			});
			observer.observe(document.querySelector("footer"));
		}
	},


	/**
	 * Menu transition
	 */
	onMenuScroll() {

		const height = parseFloat($(".navbar").outerHeight());
		const relHeight = parseFloat(getComputedStyle(document.documentElement).fontSize) * 4; // 4rem in px
		const scroll = function() {
			const newHeight = height - document.documentElement.scrollTop;
			$(".navbar").css('min-height', Math.max(newHeight, relHeight));
			newHeight < relHeight ? $(".navbar").addClass("scroll") : $(".navbar").removeClass("scroll");
		}

		$(window).on('scroll', scroll);
		scroll();
	},


	/**
	 * Initializes offscreen menus
	 */
	setupOffscreen() {

		$('.zeynep').each((idx, el) => {
			$(el).zeynep({});
		})
	},


	/**
	 * Close offscreen on overlay click
	 */
	onHideOffscreen() {

		$('.aimeos-overlay-offscreen').on('click', () => {
			$('.aimeos-overlay-offscreen').removeClass('show');
			$('.zeynep.opened').removeClass('opened');
		});
	},


	/**
	 * Initializes the menu actions
	 */
	init: function() {
		if(this.once) return;
		this.once = true;

		this.onLinkTop();
		this.onMenuScroll();
		this.onHideOffscreen();
		this.setupOffscreen();
	}
};


/*
 * Disable CSS rules only necessary if no Javascript is available
 */
$("html").removeClass("no-js");


$(function() {
	Aimeos.init();
	AimeosPage.init();
	AimeosCatalog.init();
	AimeosBasket.init();
});

/* Autocomplete 1590fe0 https://github.com/kraaden/autocomplete (MIT License) */
!function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):(e="undefined"!=typeof globalThis?globalThis:e||self).autocomplete=t()}(this,(function(){"use strict";return function(e){var t,n,o=document,i=e.container||o.createElement("div"),r=i.style,f=navigator.userAgent,l=~f.indexOf("Firefox")&&~f.indexOf("Mobile"),s=e.debounceWaitMs||0,a=e.preventSubmit||!1,u=e.disableAutoSelect||!1,d=l?"input":"keyup",c=[],p="",v=2,g=e.showOnFocus,m=0;if(void 0!==e.minLength&&(v=e.minLength),!e.input)throw new Error("input undefined");var h=e.input;function E(){n&&window.clearTimeout(n)}function w(){return!!i.parentNode}function y(){var e;m++,c=[],p="",t=void 0,(e=i.parentNode)&&e.removeChild(i)}function L(){for(;i.firstChild;)i.removeChild(i.firstChild);var n=function(e,t){var n=o.createElement("div");return n.textContent=e.label||"",n};e.render&&(n=e.render);var f=function(e,t){var n=o.createElement("div");return n.textContent=e,n};e.renderGroup&&(f=e.renderGroup);var l=o.createDocumentFragment(),s="#9?$";if(c.forEach((function(o){if(o.group&&o.group!==s){s=o.group;var i=f(o.group,p);i&&(i.className+=" group",l.appendChild(i))}var r=n(o,p);r&&(r.addEventListener("click",(function(t){e.onSelect(o,h),y(),t.preventDefault(),t.stopPropagation()})),o===t&&(r.className+=" selected"),l.appendChild(r))})),i.appendChild(l),c.length<1){if(!e.emptyMsg)return void y();var a=o.createElement("div");a.className="empty",a.textContent=e.emptyMsg,i.appendChild(a)}i.parentNode||o.body.appendChild(i),function(){if(w()){r.height="auto",r.width=h.offsetWidth+"px";var t,n=0;f(),f(),e.customize&&t&&e.customize(h,t,i,n)}function f(){var e=o.documentElement,i=e.clientTop||o.body.clientTop||0,f=e.clientLeft||o.body.clientLeft||0,l=window.pageYOffset||e.scrollTop,s=window.pageXOffset||e.scrollLeft,a=(t=h.getBoundingClientRect()).top+h.offsetHeight+l-i,u=t.left+s-f;r.top=a+"px",r.left=u+"px",(n=window.innerHeight-(t.top+h.offsetHeight))<0&&(n=0),r.top=a+"px",r.bottom="",r.left=u+"px",r.maxHeight=n+"px"}}(),function(){var e=i.getElementsByClassName("selected");if(e.length>0){var t=e[0],n=t.previousElementSibling;if(n&&-1!==n.className.indexOf("group")&&!n.previousElementSibling&&(t=n),t.offsetTop<i.scrollTop)i.scrollTop=t.offsetTop;else{var o=t.offsetTop+t.offsetHeight,r=i.scrollTop+i.offsetHeight;o>r&&(i.scrollTop+=o-r)}}}()}function b(){w()&&L()}function T(){b()}function x(e){e.target!==i?b():e.preventDefault()}function C(t){for(var n=t.which||t.keyCode||0,o=0,i=e.keysToIgnore||[38,13,27,39,37,16,17,18,20,91,9];o<i.length;o++){if(n===i[o])return}n>=112&&n<=123&&!e.keysToIgnore||40===n&&w()||S(0)}function k(n){var o=n.which||n.keyCode||0;if(38===o||40===o||27===o){var i=w();if(27===o)y();else{if(!i||c.length<1)return;38===o?function(){if(c.length<1)t=void 0;else if(t===c[0])t=c[c.length-1];else for(var e=c.length-1;e>0;e--)if(t===c[e]||1===e){t=c[e-1];break}}():function(){if(c.length<1&&(t=void 0),t&&t!==c[c.length-1]){for(var e=0;e<c.length-1;e++)if(t===c[e]){t=c[e+1];break}}else t=c[0]}(),L()}return n.preventDefault(),void(i&&n.stopPropagation())}13===o&&(t&&(e.onSelect(t,h),y()),a&&n.preventDefault())}function N(){g&&S(1)}function S(o){var i=++m,r=h.value,f=h.selectionStart||0;r.length>=v||1===o?(E(),n=window.setTimeout((function(){e.fetch(r,(function(e){m===i&&e&&(p=r,t=(c=e).length<1||u?void 0:c[0],L())}),o,f)}),0===o?s:0)):y()}function D(){setTimeout((function(){o.activeElement!==h&&y()}),200)}return i.className="autocomplete "+(e.className||""),r.position="absolute",i.addEventListener("mousedown",(function(e){e.stopPropagation(),e.preventDefault()})),i.addEventListener("focus",(function(){return h.focus()})),h.addEventListener("keydown",k),h.addEventListener(d,C),h.addEventListener("blur",D),h.addEventListener("focus",N),window.addEventListener("resize",T),o.addEventListener("scroll",x,!0),{destroy:function(){h.removeEventListener("focus",N),h.removeEventListener("keydown",k),h.removeEventListener(d,C),h.removeEventListener("blur",D),window.removeEventListener("resize",T),o.removeEventListener("scroll",x,!0),E(),y()}}}}));


/**
 * Catalog filter actions
 */
AimeosCatalogFilter = {

	MIN_INPUT_LEN: 3,


	/**
	 * Mega menu
	 */
	onMenuHover() {

		const $dropdowns = $('.level-1 > .cat-item');

		$dropdowns.on('mouseover', ev => {
			var $this = $(ev.currentTarget);
			if ($this.prop('hoverTimeout')){
				$this.prop('hoverTimeout', clearTimeout($this.prop('hoverTimeout')));
			}
			$this.prop('hoverIntent', setTimeout(() => {
				$this.addClass('hover');
			}));
		}).on('mouseleave', ev => {
			var $this = $(ev.currentTarget);
			if ($this.prop('hoverIntent')){
				$this.prop('hoverIntent', clearTimeout($this.prop('hoverIntent')));
			}
			$this.prop('hoverTimeout', setTimeout(() => {
				$this.removeClass('hover');
			}));
		});


		// For touch devices
		window.matchMedia('(min-width: 991px)').addEventListener('change', ev => {

			if (ev.matches) {

				$dropdowns.each((idx, el) => {
					const $this = $(el);

					el.addEventListener('touchstart', function(e) {

						if (e.touches.length === 1){
							e.stopPropagation();

							if(!$this.hasClass('hover')) {
								if(e.target === this || e.target.parentNode === this) {
									e.preventDefault();
								}

								$dropdowns.removeClass('hover');
								$this.addClass('hover');

								document.addEventListener('touchstart', closeDropdown = function(ev) {
									ev.stopPropagation();
									$this.removeClass('hover');
									document.removeEventListener('touchstart', closeDropdown);
								});
							}
						}
					}, false);
				});
			}
		});
	},


	/**
	 * Show category offscreen menu
	 */
	onShowCategories() {

		$(".catalog-filter-tree .menu").on('click', () => {
			$('.catalog-filter-tree .zeynep').addClass('opened');
			$('.catalog-filter-tree .aimeos-overlay-offscreen').addClass('show');
		});
	},


	/**
	 * Hide category offscreen menu
	 */
	onHideCategories() {

		$(".catalog-filter-tree .close").on('click', () => {
			$('.catalog-filter-tree .zeynep').removeClass('opened');
			$('.catalog-filter-tree .aimeos-overlay-offscreen').removeClass('show');
		});
	},


	/**
	 * Autocompleter for quick search
	 */
	onLoadSearch() {

		$(".catalog-filter-search .value").each((idx, el) => {
			const url = $(el).data("url");

			autocomplete({
				input: el,
				debounceWaitMs: 200,
				minLength: AimeosCatalogFilter.MIN_INPUT_LEN,
				fetch: function(text, update) {
					fetch(url.replace('_term_', encodeURIComponent(text))).then(response => {
						return response.json();
					}).then(data => {
						update(data);
					});
				},
				render: function(item) {
					return $(item.html.trim()).get(0);
				}
			});
		});
	},


	/**
	 * Registers events for the catalog filter search input reset
	 */
	onResetSearch() {

		$(".catalog-filter-search .value").on("keyup", ev => {
			const val = $(ev.currentTarget).val() !== "" ? "visible" : "hidden";
			$(".reset .symbol", $(ev.currentTarget).closest(".catalog-filter-search")).css("visibility", val);
		});

		$(".catalog-filter-search .reset").on("click", ev => {
			const input = $(ev.currentTarget).parents(".catalog-filter-search").find(".value");

			$(".symbol", ev.currentTarget).css("visibility", "hidden");
			input[0].focus();
			input.val("");

			return false;
		});
	},


	/**
	 * Sets up the form checks
	 */
	onCheckForm() {

		$(".catalog-filter form").on("submit", ev => {
			let result = true;

			$("input.value", ev.currentTarget).each((idx, el) => {

				const input = $(el);

				if(input.val() !== '' && input.val().length < AimeosCatalogFilter.MIN_INPUT_LEN) {

					if(input.has(".search-hint").length === 0) {

						const node = $('<div class="search-hint">' + input.data("hint") + '</div>');
						const pos = node.position();

						node.css("left", pos.left).css("top", pos.top);
						node.delay(3000).fadeOut(1000, () => {
							node.remove();
						});

						$(".catalog-filter-search", ev.currentTarget).after(node);
					}

					result = false;
				}
			});

			return result;
		});
	},


	/**
	 * Toggles the attribute filters if hover isn't available
	 */
	onToggleAttribute() {

		$('.catalog-filter-attribute h2').on("click", ev => {
			$(".attribute-lists", $(ev.currentTarget).closest(".catalog-filter-attribute")).each((idx, el) => {
				slideToggle(el, 300);
			});
		});
	},


	/**
	 * Toggles the attribute filters if hover isn't available
	 */
	onToggleAttributes() {

		$(".catalog-filter-attribute .attribute-lists legend").on("click", ev => {
			$(".attr-list", $(ev.currentTarget).closest("fieldset.attr-sets")).each((idx, el) => {
				slideToggle(el, 300);
			});
		});
	},


	/**
	 * Shows the attribute filter if products are available for
	 */
	onShowAttributes() {

		$(".catalog-filter-attribute .attribute-lists .attr-count").each((idx, el) => {
			$(el).closest("fieldset.attr-sets").show();
		});
	},


	/**
	 * Submits the form when clicking on filter attribute names or counts
	 */
	onSubmitAttribute() {

		$(".catalog-filter-attribute li.attr-item").on("click", ev => {
			const input = $("input", ev.currentTarget);

			input.prop("checked", !input.prop("checked"));
			$(ev.currentTarget).closest(".catalog-filter form")[0].submit();

			return false;
		});
	},


	/**
	 * Syncs the price input field and slider
	 */
	onSyncPrice() {

		$(".catalog-filter-price").on("input", ".price-high", ev => {
			$(".price-slider", $(ev.currentTarget).closest(".catalog-filter-price")).val($(ev.currentTarget).val());
		});

		$(".catalog-filter-price").on("input", ".price-slider", ev => {
			$(".price-high", $(ev.currentTarget).closest(".catalog-filter-price")).val($(ev.currentTarget).val());
		});
	},


	/**
	 * Toggles the price filters if hover isn't available
	 */
	onTogglePrice() {

		$('.catalog-filter-price h2').on("click", ev => {
			$(".price-lists", $(ev.currentTarget).closest(".catalog-filter-price")).each((idx, el) => {
				slideToggle(el, 300);
			});
		});
	},


	/**
	 * Toggles the search filters if hover isn't available
	 */
	onToggleSearch() {

		$('.catalog-filter-search').on("click", 'h2', ev => {
			$(".search-lists", $(ev.currentTarget).closest(".catalog-filter-search")).each((idx, el) => {
				slideToggle(el, 300);
			});
		});
	},


	/**
	 * Toggles the supplier filters if hover isn't available
	 */
	onToggleSupplier() {

		$('.catalog-filter-supplier').on("click", 'h2', ev => {
			$(".supplier-lists", $(ev.currentTarget).closest(".catalog-filter-supplier")).each((idx, el) => {
				slideToggle(el, 300);
			});
		});
	},


	/**
	 * Submits the form when clicking on filter supplier names or counts
	 */
	onSubmitSupplier() {

		$(".catalog-filter-supplier li.attr-item").on("click", ev => {
			const input = $("input", ev.currentTarget);

			input.prop("checked", !input.prop("checked"));
			$(ev.currentTarget).closest(".catalog-filter form")[0].submit();

			return false;
		});
	},


	/**
	 * Initialize the catalog filter actions
	 */
	init() {
		this.onMenuHover();

		this.onShowCategories();
		this.onHideCategories();

		this.onSyncPrice();
		this.onTogglePrice();

		this.onShowAttributes();
		this.onSubmitAttribute();
		this.onToggleAttribute();
		this.onToggleAttributes();

		this.onSubmitSupplier();
		this.onToggleSupplier();

		this.onLoadSearch();
		this.onResetSearch();
		this.onToggleSearch();

		this.onCheckForm();
	}
};


$(function() {
	AimeosCatalogFilter.init();
});
/* Autocomplete 1590fe0 https://github.com/kraaden/autocomplete (MIT License) */
!function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):(e="undefined"!=typeof globalThis?globalThis:e||self).autocomplete=t()}(this,(function(){"use strict";return function(e){var t,n,o=document,i=e.container||o.createElement("div"),r=i.style,f=navigator.userAgent,l=~f.indexOf("Firefox")&&~f.indexOf("Mobile"),s=e.debounceWaitMs||0,a=e.preventSubmit||!1,u=e.disableAutoSelect||!1,d=l?"input":"keyup",c=[],p="",v=2,g=e.showOnFocus,m=0;if(void 0!==e.minLength&&(v=e.minLength),!e.input)throw new Error("input undefined");var h=e.input;function E(){n&&window.clearTimeout(n)}function w(){return!!i.parentNode}function y(){var e;m++,c=[],p="",t=void 0,(e=i.parentNode)&&e.removeChild(i)}function L(){for(;i.firstChild;)i.removeChild(i.firstChild);var n=function(e,t){var n=o.createElement("div");return n.textContent=e.label||"",n};e.render&&(n=e.render);var f=function(e,t){var n=o.createElement("div");return n.textContent=e,n};e.renderGroup&&(f=e.renderGroup);var l=o.createDocumentFragment(),s="#9?$";if(c.forEach((function(o){if(o.group&&o.group!==s){s=o.group;var i=f(o.group,p);i&&(i.className+=" group",l.appendChild(i))}var r=n(o,p);r&&(r.addEventListener("click",(function(t){e.onSelect(o,h),y(),t.preventDefault(),t.stopPropagation()})),o===t&&(r.className+=" selected"),l.appendChild(r))})),i.appendChild(l),c.length<1){if(!e.emptyMsg)return void y();var a=o.createElement("div");a.className="empty",a.textContent=e.emptyMsg,i.appendChild(a)}i.parentNode||o.body.appendChild(i),function(){if(w()){r.height="auto",r.width=h.offsetWidth+"px";var t,n=0;f(),f(),e.customize&&t&&e.customize(h,t,i,n)}function f(){var e=o.documentElement,i=e.clientTop||o.body.clientTop||0,f=e.clientLeft||o.body.clientLeft||0,l=window.pageYOffset||e.scrollTop,s=window.pageXOffset||e.scrollLeft,a=(t=h.getBoundingClientRect()).top+h.offsetHeight+l-i,u=t.left+s-f;r.top=a+"px",r.left=u+"px",(n=window.innerHeight-(t.top+h.offsetHeight))<0&&(n=0),r.top=a+"px",r.bottom="",r.left=u+"px",r.maxHeight=n+"px"}}(),function(){var e=i.getElementsByClassName("selected");if(e.length>0){var t=e[0],n=t.previousElementSibling;if(n&&-1!==n.className.indexOf("group")&&!n.previousElementSibling&&(t=n),t.offsetTop<i.scrollTop)i.scrollTop=t.offsetTop;else{var o=t.offsetTop+t.offsetHeight,r=i.scrollTop+i.offsetHeight;o>r&&(i.scrollTop+=o-r)}}}()}function b(){w()&&L()}function T(){b()}function x(e){e.target!==i?b():e.preventDefault()}function C(t){for(var n=t.which||t.keyCode||0,o=0,i=e.keysToIgnore||[38,13,27,39,37,16,17,18,20,91,9];o<i.length;o++){if(n===i[o])return}n>=112&&n<=123&&!e.keysToIgnore||40===n&&w()||S(0)}function k(n){var o=n.which||n.keyCode||0;if(38===o||40===o||27===o){var i=w();if(27===o)y();else{if(!i||c.length<1)return;38===o?function(){if(c.length<1)t=void 0;else if(t===c[0])t=c[c.length-1];else for(var e=c.length-1;e>0;e--)if(t===c[e]||1===e){t=c[e-1];break}}():function(){if(c.length<1&&(t=void 0),t&&t!==c[c.length-1]){for(var e=0;e<c.length-1;e++)if(t===c[e]){t=c[e+1];break}}else t=c[0]}(),L()}return n.preventDefault(),void(i&&n.stopPropagation())}13===o&&(t&&(e.onSelect(t,h),y()),a&&n.preventDefault())}function N(){g&&S(1)}function S(o){var i=++m,r=h.value,f=h.selectionStart||0;r.length>=v||1===o?(E(),n=window.setTimeout((function(){e.fetch(r,(function(e){m===i&&e&&(p=r,t=(c=e).length<1||u?void 0:c[0],L())}),o,f)}),0===o?s:0)):y()}function D(){setTimeout((function(){o.activeElement!==h&&y()}),200)}return i.className="autocomplete "+(e.className||""),r.position="absolute",i.addEventListener("mousedown",(function(e){e.stopPropagation(),e.preventDefault()})),i.addEventListener("focus",(function(){return h.focus()})),h.addEventListener("keydown",k),h.addEventListener(d,C),h.addEventListener("blur",D),h.addEventListener("focus",N),window.addEventListener("resize",T),o.addEventListener("scroll",x,!0),{destroy:function(){h.removeEventListener("focus",N),h.removeEventListener("keydown",k),h.removeEventListener(d,C),h.removeEventListener("blur",D),window.removeEventListener("resize",T),o.removeEventListener("scroll",x,!0),E(),y()}}}}));

/**
 * Basket bulk order client actions
 */
AimeosBasketBulk = {

	MIN_INPUT_LEN: 3,
	meta: {},


	/**
	 * Sets up autocompletion for the given node
	 *
	 * @param {object} node
	 */
	autocomplete(nodes) {
		nodes.each((idx, node) => {
			autocomplete({
				input: node,
				debounceWaitMs: 200,
				minLength: AimeosBasketBulk.MIN_INPUT_LEN,
				async fetch(text, update) {

					if(AimeosBasketBulk.meta.resources && AimeosBasketBulk.meta.resources['product']) {
						let params = {};
						const relFilter = {};
						const langid = AimeosBasketBulk.meta.locale && AimeosBasketBulk.meta.locale['locale.languageid'] || '';
						relFilter['index.text:relevance("' + langid + '","' + text + '")'] = 0;

						const filter = {
							filter: {'||': [{'=~': {'product.code': text}}, {'>': relFilter}]},
							include: 'attribute,text,price,product'
						};

						if(AimeosBasketBulk.meta.prefix) {
							params[AimeosBasketBulk.meta.prefix] = filter;
						} else {
							params = filter;
						}

						const url = new URL(AimeosBasketBulk.meta.resources['product']);
						url.search = url.search ? url.search + '&' + window.param(params) : '?' + window.param(params);

						await fetch(url).then(response => {
							return response.json();
						}).then(response => {
							let data = [];
							for(var key in (response.data || {})) {
								data = data.concat(AimeosBasketBulk.get(response.data[key], response.included));
							}
							update(data);
						});
					}
				},
				onSelect(item) {
					if($(".aimeos.basket-bulk .bulk-content .details .search").last().val() != '') {
						AimeosBasketBulk.add();
					}

					const product = $(node).parent();
					product.find(".productid").val(item.id);
					product.find(".search").val(item.label);

					const row = product.parent();
					row.data('prices', item['prices'] || []);
					row.data('vattributes', item['vattributes'] || []);
					AimeosBasketBulk.update(product.parent());
				}
			});
		});
	},


	/**
	 * Adds a new line to the bulk order form
	 */
	add() {
		const len = $(".aimeos.basket-bulk .bulk-content .details").length;
		const proto = $(".aimeos.basket-bulk .details.prototype");
		const line = proto.clone();

		AimeosBasketBulk.autocomplete($(".search", line));
		$('[disabled="disabled"]', line).removeAttr("disabled");
		line.removeClass("prototype").insertBefore(proto);

		$('[name]', line).each((idx, el) => {
			$(el).attr("name", $(el).attr("name").replace('_idx_', len));
		});
	},


	/**
	 * Deletes lines if clicked on the delete icon
	 */
	onDelete() {
		$(".aimeos.basket-bulk").on("click", ".buttons .delete", ev => {
			$(ev.currentTarget).parents(".details").remove();
		});
	},


	/**
	 * Returns the data for the current item
	 *
	 * @param {object} attr JSON:API attribute data of one entry
	 * @param {array} included JSON:API included data array
	 * @param {object} Item with "id", "label" and "prices" property
	 */
	get(attr, included) {
		const map = {};
		const rel = attr.relationships || {};

		for(let idx in (included || [])) {
			map[included[idx]['type']] = map[included[idx]['type']] || {};
			map[included[idx]['type']][included[idx]['id']] = included[idx];
		}

		let name = attr['attributes']['product.label'];
		const texts = this.getRef(map, rel, 'text', 'default', 'name');
		const prices = this.getRef(map, rel, 'price', 'default', 'default').sort((a, b) => {
			return a['attributes']['price.quantity'] - b['attributes']['price.quantity'];
		});

		for(let idx in texts) {
			name = texts[idx]['attributes']['text.content'];
		}

		if(attr['attributes']['product.type'] !== 'select') {
			return [{
				'category': '',
				'id': attr.id,
				'label': attr['attributes']['product.code'] + ': ' + name,
				'prices': prices
			}];
		}


		const result = [];
		const variants = this.getRef(map, rel, 'product', 'default');

		for(let idx in variants) {

			const vrel = variants[idx]['relationships'] || {};
			const vattr = this.getRef(map, vrel, 'attribute', 'variant');
			const vprices = this.getRef(map, vrel, 'price', 'default', 'default');
			const vtexts = this.getRef(map, vrel, 'text', 'default', 'name');
			let vname = variants[idx]['attributes']['product.label'];

			for(let index in vtexts) {
				vname = vtexts[index]['attributes']['text.content'];
			}

			result.push({
				'category': name,
				'id': attr.id,
				'label': variants[idx]['attributes']['product.code'] + ': ' + vname,
				'prices': !vprices.length ? prices : vprices.sort((a, b) => {
					return a['attributes']['price.quantity'] - b['attributes']['price.quantity'];
				}),
				'vattributes': vattr
			})
		}

		return result;
	},


	/**
	 * Returns the attributes for the passed domain, type and listtype
	 *
	 * @param {Object} map
	 * @param {Object} rel
	 * @param {String} domain
	 * @param {String} listtype
	 * @param {String} type
	 */
	getRef(map, rel, domain, listtype, type) {
		const list = [];

		if(rel[domain]) {
			for(let idx in (rel[domain]['data'] || [])) {
				let entry = rel[domain]['data'][idx];

				if(map[domain][entry['id']] && map[domain][entry['id']]['attributes']
					&& entry['attributes']['product.lists.type'] === listtype
					&& (!type || map[domain][entry['id']]['attributes'][domain + '.type'] === type)) {

					list.push(map[domain][entry['id']]);
				}
			}
		}

		return list;
	},


	/**
	 * Sets up autocompletion for bulk order form
	 */
	async setup() {
		const jsonurl = $(".aimeos.basket-bulk[data-jsonurl]").data("jsonurl");

		if(jsonurl) {
			await fetch(jsonurl, {
				method: "OPTIONS",
				headers: {'Content-type': 'application/json'}
			}).then(response => {
				return response.json();
			}).then(options => {
				AimeosBasketBulk.meta = options.meta || {};
			});

			$(".aimeos.basket-bulk").on("click", ".buttons .add", this.add);
			this.autocomplete($(".aimeos.basket-bulk .details .search"));

			$(".aimeos.basket-bulk").on("change", ".details .quantity input", ev => {
				AimeosBasketBulk.update($(ev.currentTarget).parents(".details").first());
			});
		}
	},


	/**
	 * Updates the price of the given row element
	 *
	 * @param {DomElement} row HTML DOM node of the table row to update the price for
	 */
	update(row) {
		const qty = $(".quantity input", row).val();
		const prices = $(row).data('prices') || [];
		const vattr = $(row).data('vattributes') || [];
		const input = $(".product > .attrvarid", row);

		for(let idx in prices) {
			if(prices[idx]['attributes']['price.quantity'] <= qty) {
				let style = {style: 'currency', currency: prices[idx]['attributes']['price.currencyid']};
				let value = Number(prices[idx]['attributes']['price.value']) * qty;
				$(row).find(".price").html(value.toLocaleString(undefined, style));
			}
		}

		$(".product .vattributes", row).empty();

		for(let idx in vattr) {
			let elem = input.clone();
			elem.attr("name", input.attr("name").replace('_type_', vattr[idx]['attributes']['attribute.type']));
			elem.val(vattr[idx]['attributes']['attribute.id']);
			$(".product .vattributes", row).append(elem);
		}
	},


	/**
	 * Initializes the basket bulk actions
	 */
	init() {
		if(this.once) return;
		this.once = true;

		this.setup();
		this.onDelete();
	}
}


$(function() {
	AimeosBasketBulk.init();
});
(window.webpackJsonp=window.webpackJsonp||[]).push([[22],{"410":function(e,t,n){"use strict";n.d(t,"a",(function(){return getPinkGoodsPage})),n.d(t,"b",(function(){return getPinkOrder}));var o=n(9);function getPinkGoodsPage(e){return o.a.get("/pinks/get-pink-product-page",e)}function getPinkOrder(e){return o.a.get("/pinks/get-by-order-id/"+e)}},"590":function(e,t,n){},"647":function(e,t,n){"use strict";n.r(t),n.d(t,"default",(function(){return g}));var o=n(1),r=n(3),a=n(43),i=n(613),c=n(614),l=n(16),s=n(410),p=n(68),u=n(306),d=(n(590),function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}()),f=function get(e,t,n){null===e&&(e=Function.prototype);var o=Object.getOwnPropertyDescriptor(e,t);if(void 0===o){var r=Object.getPrototypeOf(e);return null===r?void 0:get(r,t,n)}if("value"in o)return o.value;var a=o.get;return void 0!==a?a.call(n):void 0};function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var g=function(e){function Index(){_classCallCheck(this,Index);var e=_possibleConstructorReturn(this,(Index.__proto__||Object.getPrototypeOf(Index)).apply(this,arguments));return e.config={"backgroundTextStyle":"dark","navigationBarTitleText":"拼团列表","navigationBarTextStyle":"black","navigationBarBackgroundColor":"#FFDD00"},e.state={"loading":!0,"goodsList":[]},e}return function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(Index,e),d(Index,[{"key":"componentDidMount","value":function componentDidMount(){this.getPage()}},{"key":"getPage","value":function getPage(){var e=this;Object(s.a)({"page":1,"limit":10}).then((function(t){var n=t.data.list;e.setState({"goodsList":n},(function(){e.setState({"loading":!1})}))}))}},{"key":"render","value":function render(){var e=this.state,t=e.loading,n=e.goodsList;return o.l.createElement(a.a,{"className":"page"},t&&o.l.createElement(u.J,null),o.l.createElement(a.a,{"className":"page-scroll"},o.l.createElement(i.a,{"src":l.k+"/system/image/pt_bg.png","className":"page-bg"}),o.l.createElement(a.a,{"className":"goods-list"},n.map((function(e){return o.l.createElement(a.a,{"key":e.id,"className":"goods-item","onClick":function onClick(){return Object(p.m)(e)}},o.l.createElement(a.a,{"className":"goods-img"},o.l.createElement(i.a,{"src":e.coverImg,"className":""})),o.l.createElement(a.a,{"className":"goods-info"},o.l.createElement(a.a,{"className":"goods-name t-o-e-2"},e.name),o.l.createElement(a.a,{"className":"d-f a-i-b j-c-b"},o.l.createElement(a.a,{"className":"goods-price d-f a-i-b bnn-number"},o.l.createElement(a.a,{"className":"sale-price"},o.l.createElement(c.a,{"className":"sale-price__unit"},"￥"),e.salePrice),o.l.createElement(a.a,{"className":"origin-price"},"￥",e.originPrice)),o.l.createElement(a.a,{"className":"buy-btn f-g-5","hoverClass":"hover-class--btn"},"立即购买"))))})))))}},{"key":"componentDidShow","value":function componentDidShow(){f(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidShow",this)&&f(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidShow",this).call(this)}},{"key":"componentDidHide","value":function componentDidHide(){f(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this)&&f(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this).call(this)}}]),Index}(r.a.Component)}}]);
(window.webpackJsonp=window.webpackJsonp||[]).push([[60],{"333":function(e,t,o){"use strict";o.d(t,"a",(function(){return r}));var n=o(11),r=function stopPullDownRefresh(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};return new Promise((function(t,o){var r=e.success,a=e.fail,i=e.complete,c={};n.a.eventCenter.trigger("__taroStopPullDownRefresh",{"successHandler":function successHandler(){r&&r(c),i&&i(c),t(c)},"errorHandler":function errorHandler(){a&&a(c),i&&i(c),o(c)}})}))}},"539":function(e,t,o){},"540":function(e,t,o){e.exports={"popularProductItem":"popularProductItem_1KAxRuze","itemImage":"itemImage_2U3nS9KK","itemContent":"itemContent_vOdZkd0y","itemName":"itemName_2C3WwOtO","itemPrice":"itemPrice_382-25yl","needScore":"needScore_1sf3LzQM","salesNumber":"salesNumber_17t0ikuB","originPrice":"originPrice_1L2iOBgA"}},"541":function(e,t,o){e.exports=o.p+"static/images/goods_list_more_cell.png"},"670":function(e,t,o){"use strict";o.r(t);var n=o(1),r=o(333),a=o(128),i=o(3),c=o(43),l=o(614),s=o(454),u=(o(539),o(68)),p=o(306),m=o(16),f=o(312),d=o(8),b=o(540),h=o.n(b),g=o(2),_=o(12),y=o.n(_),O=function(){function defineProperties(e,t){for(var o=0;o<t.length;o++){var n=t[o];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(e,t,o){return t&&defineProperties(e.prototype,t),o&&defineProperties(e,o),e}}();function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var v=function(e){function HomePrompt(){_classCallCheck(this,HomePrompt);var e=_possibleConstructorReturn(this,(HomePrompt.__proto__||Object.getPrototypeOf(HomePrompt)).apply(this,arguments));return e.state={},e}return function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(HomePrompt,e),O(HomePrompt,[{"key":"componentWillMount","value":function componentWillMount(){}},{"key":"render","value":function render(){var e=this.props.info;return n.l.createElement(c.a,{"className":h.a.popularProductItem},n.l.createElement(p.p,{"className":h.a.itemImage,"src":Object(g.q)(e.images)}),n.l.createElement(c.a,{"className":h.a.itemContent},n.l.createElement(c.a,{"className":y()(h.a.itemName,"t-o-e")},e.name),n.l.createElement(c.a,{"className":y()(h.a.itemPrice,"t-o-e")},Object(d.p)(e.needScore)&&n.l.createElement(l.a,{"className":h.a.needScore},e.needScore,Object(d.f)(),"+"),n.l.createElement(l.a,{"className":"f-s-26"},Number(e.salePrice),"元")),!!e.salesNumber&&n.l.createElement(c.a,{"className":y()(h.a.salesNumber,"d-f","j-c-b")},n.l.createElement("div",null,"热度 ",Object(g.p)(e.salesNumber,"+")),n.l.createElement("s",{"className":h.a.originPrice},"￥",Number(e.originPrice)))))}}]),HomePrompt}(i.a.Component);v.options={"addGlobalClass":!0};var P=o(541),j=o.n(P);o.d(t,"default",(function(){return C}));var w=function(){function defineProperties(e,t){for(var o=0;o<t.length;o++){var n=t[o];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(e,t,o){return t&&defineProperties(e.prototype,t),o&&defineProperties(e,o),e}}(),E=function get(e,t,o){null===e&&(e=Function.prototype);var n=Object.getOwnPropertyDescriptor(e,t);if(void 0===n){var r=Object.getPrototypeOf(e);return null===r?void 0:get(r,t,o)}if("value"in n)return n.value;var a=n.get;return void 0!==a?a.call(o):void 0};function popular_classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function popular_possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var C=function(e){function Index(){popular_classCallCheck(this,Index);var e=popular_possibleConstructorReturn(this,(Index.__proto__||Object.getPrototypeOf(Index)).apply(this,arguments));return e.config={"backgroundTextStyle":"dark","navigationBarTitleText":"人气排行","onReachBottomDistance":600},e.state={"loading":!0,"productList":[],"loadStatus":"loading","page":1,"limit":9,"totalCount":0},e}return function popular_inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(Index,e),w(Index,[{"key":"componentDidMount","value":function componentDidMount(){var e=this;Object(f.a)({},9).then((function(){e.getList()}))}},{"key":"onReachBottom","value":function onReachBottom(){this.getList()}},{"key":"getList","value":function getList(){var e=this,t=this.state,o=t.page,n=t.limit,a=t.productList,i=t.loadStatus,c=t.totalCount;"noMore"!==i&&(c>18&&a.length>18?this.setState({"loadStatus":"noMore"}):Object(f.b)({"page":Object(d.k)(),"limit":n}).then((function(t){var n=Object(d.j)(),i=t.data,c=i.list,l=i.totalCount;e.setState({"page":o,"productList":a.length?a.concat(c):c,"loadStatus":n.length?"loading":"noMore","loading":!1,"totalCount":l},(function(){Object(r.a)()}))})))}},{"key":"render","value":function render(){var e=this.state,t=e.loading,o=e.productList,r=e.loadStatus;return n.l.createElement(c.a,{"className":"popular-rank-page"},t&&n.l.createElement(p.J,null),n.l.createElement(c.a,null,n.l.createElement(c.a,null,n.l.createElement(p.p,{"className":"popular-bgImg","src":Object(d.d)("popular-bgImg")}),n.l.createElement(c.a,{"className":"popular-price"},n.l.createElement(l.a,{"className":"price m-r-10"},Object(g.d)(281053,"",!0)),"元")),n.l.createElement(s.a,{"id":"select-list","className":"list"},o.map((function(e,t){return n.l.createElement(c.a,{"className":"list-item","onClick":function onClick(){return Object(u.m)(e)}},t<6&&n.l.createElement(p.p,{"className":"list-item-top","src":m.k+"/system/image/goods-top"+(t+1)+".png"}),n.l.createElement(v,{"info":e}))})),"noMore"===r&&n.l.createElement(p.p,{"className":"goods-more","src":j.a,"onClick":function onClick(){return Object(u.A)()}}),n.l.createElement(p.I,{"status":r}),"noMore"===r&&n.l.createElement(p.X,null))),n.l.createElement(p.U,null))}},{"key":"componentDidShow","value":function componentDidShow(){E(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidShow",this)&&E(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidShow",this).call(this),this._offReachBottom=Object(a.a)({"callback":this.onReachBottom,"ctx":this,"onReachBottomDistance":600})}},{"key":"componentDidHide","value":function componentDidHide(){E(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this)&&E(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this).call(this),this._offReachBottom&&this._offReachBottom()}}]),Index}(i.a.Component)}}]);
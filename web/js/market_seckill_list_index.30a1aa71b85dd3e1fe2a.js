(window.webpackJsonp=window.webpackJsonp||[]).push([[31],{"308":function(e,t,n){"use strict";var o={"Mul":function Mul(e,t){var n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:2,o=e.toString(),a=t.toString(),r=void 0,i=void 0;return r=(o.split(".")[1]?o.split(".")[1].length:0)+(a.split(".")[1]?a.split(".")[1].length:0),i=Number(o.replace(".",""))*Number(a.replace(".",""))/Math.pow(10,r),Number(i.toFixed(n))},"Div":function Div(e,t){var n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:2,o=e.toString(),a=t.toString(),r=void 0,i=void 0;return r=(a.split(".")[1]?a.split(".")[1].length:0)-(o.split(".")[1]?o.split(".")[1].length:0),i=Number(o.replace(".",""))/Number(a.replace(".",""))*Math.pow(10,r),Number(i.toFixed(n))},"Add":function Add(e,t){var n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:2,o=e.toString(),a=t.toString(),r=o.split("."),i=a.split("."),l=2==r.length?r[1]:"",s=2==i.length?i[1]:"",c=Math.max(l.length,s.length),u=Math.pow(10,c),p=Number(((e*u+t*u)/u).toFixed(c));return Number(p.toFixed(n))},"Sub":function Sub(e,t){var n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:2;return o.Add(e,-Number(t),n)}};t.a=o},"547":function(e,t,n){},"626":function(e,t,n){"use strict";n.r(t);var o=n(21),a=n.n(o),r=n(1),i=n(571),l=n(122),s=n(2),c=n(40),u=n(569),p=n(410),d=n(404),m=n(20),g=n(14),f=n(308),h=n(8),b=n.n(h),v=n(302),y=n(322),_=n.n(y),N=n(343),w=n(9);var x=n(303);n(547);n.d(t,"default",(function(){return P}));var k=function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}(),E=function get(e,t,n){null===e&&(e=Function.prototype);var o=Object.getOwnPropertyDescriptor(e,t);if(void 0===o){var a=Object.getPrototypeOf(e);return null===a?void 0:get(a,t,n)}if("value"in o)return o.value;var r=o.get;return void 0!==r?r.call(n):void 0};function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var S={"-1":"已结束","0":"未开始","1":"立即购买"},P=function(e){function Index(){_classCallCheck(this,Index);var e=_possibleConstructorReturn(this,(Index.__proto__||Object.getPrototypeOf(Index)).apply(this,arguments));return e.config={"backgroundTextStyle":"dark","navigationBarTitleText":"限时秒杀","navigationBarTextStyle":"white","navigationBarBackgroundColor":"#E93423"},e.state={"loading":!0,"showBg":!1,"nodeTop":0,"showLoad":!0,"tabKey":0,"tabList":[],"goodsList":[]},e.rolling=!0,e}var t;return function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(Index,e),k(Index,[{"key":"componentWillMount","value":function componentWillMount(){this.getPage().then()}},{"key":"componentDidMount","value":function componentDidMount(){this.getNode()}},{"key":"onPageScroll","value":function onPageScroll(e){var t=e.scrollTop;if(this.rolling)return!1;var n=this.state,o=n.showBg,a=t>=n.nodeTop;o!==a&&this.setState({"showBg":a})}},{"key":"getNode","value":function getNode(){var e=this;Object(i.a)().select("#tab-scroll").boundingClientRect((function(t){e.setState({"nodeTop":t.top},(function(){e.rolling=!1}))})).exec()}},{"key":"getPage","value":(t=function _asyncToGenerator(e){return function(){var t=e.apply(this,arguments);return new Promise((function(e,n){return function step(o,a){try{var r=t[o](a),i=r.value}catch(e){return void n(e)}if(!r.done)return Promise.resolve(i).then((function(e){step("next",e)}),(function(e){step("throw",e)}));e(i)}("next")}))}}(a.a.mark((function _callee(){var e,t,n=this;return a.a.wrap((function _callee$(o){for(;;)switch(o.prev=o.next){case 0:return o.next=2,Object(N.a)();case 2:e=o.sent,t=_.a.unix(e.data).hour(),w.a.get("/sale-strategies/get-hours").then((function(e){var o=e.data,a=o.map((function(e){return{"time":e,"status":e<t?"已结束":e>t?"即将开始":"抢购中"}})),r=o.findIndex((function(e){return e===t}));n.setState({"tabKey":r>=0?r:0,"tabList":a,"timeList":o},(function(){n.getGoods()}))}));case 5:case"end":return o.stop()}}),_callee,this)}))),function getPage(){return t.apply(this,arguments)})},{"key":"getGoods","value":function getGoods(){var e=this,t=this.state,n=t.tabKey;(function getProduct(e){return w.a.get("/sale-strategies/get-product?hour="+e)})(t.timeList[n]).then((function(t){var n=t.data;e.setState({"loading":!1,"showLoad":!1,"goodsList":n})}))}},{"key":"onSwitchTab","value":function onSwitchTab(e){var t=this;this.setState({"tabKey":e,"showLoad":!0,"goodsList":[]},(function(){t.getGoods()}))}},{"key":"render","value":function render(){var e=this,t=this.state,n=t.loading,o=t.tabKey,a=t.tabList,i=t.showBg,l=t.showLoad,s=t.goodsList;return r.l.createElement(c.a,{"className":"page"},n&&r.l.createElement(x.E,null),r.l.createElement(c.a,{"className":"page-scroll"},r.l.createElement(c.a,{"className":"seckill_bg f-g-5"},r.l.createElement(u.a,{"src":m.i+"/system/image/seckill_bg.png","className":"seckill_img"})),r.l.createElement(p.a,{"scrollX":!0,"id":"tab-scroll","className":b()("tab-scroll",{"tab-scroll__bg thin-border__b":i})},r.l.createElement(c.a,{"className":"tab-list"},r.l.createElement(c.a,{"className":"tab-item j-c-c"},r.l.createElement(u.a,{"src":m.i+"/system/image/seckill_time_title.png","className":"tab-img"})),a.map((function(t,n){return r.l.createElement(c.a,{"key":t,"className":b()("tab-item",{"tab-active":n===o}),"onClick":e.onSwitchTab.bind(e,n)},r.l.createElement(c.a,{"className":"tab-time bnn-number"},t.time,":00"),r.l.createElement(c.a,{"className":"tab-text f-g-5"},t.status))})))),r.l.createElement(c.a,{"className":"goods-list"},s.map((function(e){var t=Math.round(100*f.a.Div(e.salesNumber||0,f.a.Add(e.stockNumber||0,e.realSalesNumber||0))),n=e.stockNumber>0&&t<=100?t:100;return r.l.createElement(c.a,{"key":e.id,"className":"goods-item f-g-4","onClick":function onClick(){return Object(v.k)(e)}},r.l.createElement(u.a,{"src":Object(g.o)(e.images),"className":"goods-img"}),r.l.createElement(c.a,{"className":"goods-info d-f f-d-c"},r.l.createElement(c.a,{"className":"goods-name t-o-e-2"},e.productName),r.l.createElement(c.a,{"className":"goods-price d-f a-i-b bnn-number"},r.l.createElement(c.a,{"className":"sale-price"},r.l.createElement(d.a,{"className":"sale-price__unit"},"￥"),e.salePrice),r.l.createElement(c.a,{"className":"origin-price"},"￥",e.originPrice)),r.l.createElement(c.a,{"className":"goods-desc"},e.limitNumber?"限量 "+e.limitNumber+" 件":""),r.l.createElement(c.a,{"className":"progress"},r.l.createElement(c.a,{"className":"progress-bg","style":{"width":n+"%"}}),r.l.createElement(c.a,{"className":"progress-text"},Math.floor(n),"%")),r.l.createElement(c.a,{"className":"buy-btn f-g-5","hoverClass":"hover-class--btn"},S[e.status])))}))),l&&r.l.createElement(x.D,{"status":"loading"})))}},{"key":"componentDidShow","value":function componentDidShow(){E(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidShow",this)&&E(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidShow",this).call(this),this._offPageScroll=Object(l.a)({"callback":this.onPageScroll,"ctx":this})}},{"key":"componentDidHide","value":function componentDidHide(){E(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this)&&E(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this).call(this),this._offPageScroll&&this._offPageScroll()}}]),Index}(s.a.Component)}}]);
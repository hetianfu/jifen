(window.webpackJsonp=window.webpackJsonp||[]).push([[46],{"307":function(e,t,n){"use strict";n.d(t,"a",(function(){return asyncCalcOrder})),n.d(t,"l",(function(){return submitOrder})),n.d(t,"b",(function(){return asyncCalcScoresOrder})),n.d(t,"m",(function(){return submitScoresOrder})),n.d(t,"i",(function(){return goH5ToPay})),n.d(t,"j",(function(){return goToWalletPay})),n.d(t,"g",(function(){return getOrderPage})),n.d(t,"f",(function(){return getOrderInfo})),n.d(t,"h",(function(){return getQrCode})),n.d(t,"d",(function(){return confirmReceipt})),n.d(t,"c",(function(){return cancelOrderInfo})),n.d(t,"k",(function(){return refundOrder})),n.d(t,"e",(function(){return getConnectUser}));var o=n(9);function asyncCalcOrder(e){return o.a.post("/order-pays/calculate-order",e)}function submitOrder(e){return o.a.post("/order-pays/submit-order",e)}function asyncCalcScoresOrder(e){return o.a.post("/order-scores/calculate-order",e)}function submitScoresOrder(e){return o.a.post("/order-scores/submit-order",e)}function goH5ToPay(e){return o.a.get("/order-pays/h5-to-pay/"+e)}function goToWalletPay(e){return o.a.get("/order-pays/wallet-to-pay/"+e)}function getOrderPage(e){return o.a.get("/orders/get-page",{"params":e})}function getOrderInfo(e){return o.a.get("/orders/get-by-id/"+e)}function getQrCode(e){return o.a.get("/orders/get-qr-code/"+e)}function confirmReceipt(e){return o.a.put("/orders/receive-by-id",{"id":e})}function cancelOrderInfo(e){return o.a.put("/orders/cancel-by-id/"+e)}function refundOrder(e){return o.a.post("/orders/refund-by-id",e)}function getConnectUser(){return o.a.get("/orders/get-connect-user")}},"355":function(e,t,n){"use strict";n.d(t,"a",(function(){return i}));var o=n(2),r=n(10),a=function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}();function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var i=function(e){function FyComponent(e){_classCallCheck(this,FyComponent);var t=_possibleConstructorReturn(this,(FyComponent.__proto__||Object.getPrototypeOf(FyComponent)).apply(this,arguments));return t.timer=void 0,t.seconds=e.seconds,t.prevProps={},t}return function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(FyComponent,e),a(FyComponent,[{"key":"componentDidMount","value":function componentDidMount(){this.prevProps=r.a.cloneDeep(this.props),this.setTimer(this.prevProps.seconds)}},{"key":"componentWillReceiveProps","value":function componentWillReceiveProps(e){JSON.stringify(this.prevProps)!==JSON.stringify(e)&&(this.prevProps=r.a.cloneDeep(e),this.clearTimer(),this.setTimer(e.seconds))}},{"key":"componentWillUnmount","value":function componentWillUnmount(){this.clearTimer()}},{"key":"setTimer","value":function setTimer(e){this.seconds=e,this.timer||this.countdown()}},{"key":"clearTimer","value":function clearTimer(){this.timer&&(clearTimeout(this.timer),this.timer=void 0)}},{"key":"countdown","value":function countdown(){var e=this;if(void 0!==this.seconds){if(this.onCallBack&&this.onCallBack(this.seconds),this.seconds--,this.seconds<0)return this.clearTimer(),void(this.onTimeUp&&this.onTimeUp());this.timer=setTimeout((function(){e.countdown()}),1e3)}}},{"key":"formatNum","value":function formatNum(e){return e<10?"0"+e:""+e}}]),FyComponent}(o.a.Component);i.options={"addGlobalClass":!0}},"515":function(e,t,n){},"623":function(e,t,n){"use strict";n.r(t);var o=n(1),r=n(126),a=n(2),i=n(40),s=n(364),c=(n(515),n(14)),l=n(17),u=n(355),d=n(8),p=n.n(d),f=function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}();var m=function(e){function UnpaidCountdown(e){!function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,UnpaidCountdown);var t=function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}(this,(UnpaidCountdown.__proto__||Object.getPrototypeOf(UnpaidCountdown)).call(this,e)),n=Object(c.b)(),o=n.minutes,r=n.seconds;return t.state={"minutes":o,"seconds":r},t}return function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(UnpaidCountdown,e),f(UnpaidCountdown,[{"key":"onCallBack","value":function onCallBack(){var e=Object(c.b)(this.seconds),t=e.minutes,n=e.seconds;this.setState({"minutes":t,"seconds":n})}},{"key":"onTimeUp","value":function onTimeUp(){this.props.onTimeUp&&this.props.onTimeUp()}},{"key":"render","value":function render(){var e=this.props.className,t=this.state,n=t.minutes,r=t.seconds;return o.l.createElement(i.a,{"className":p()("order-count-down",e)},this.formatNum(n),":",this.formatNum(r))}}]),UnpaidCountdown}(u.a),h=n(303),b=n(307),y=n(305),g=n(319);n.d(t,"default",(function(){return w}));var v=function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}(),O=function get(e,t,n){null===e&&(e=Function.prototype);var o=Object.getOwnPropertyDescriptor(e,t);if(void 0===o){var r=Object.getPrototypeOf(e);return null===r?void 0:get(r,t,n)}if("value"in o)return o.value;var a=o.get;return void 0!==a?a.call(n):void 0};function unpaid_classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function unpaid_possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var w=function(e){function Index(){unpaid_classCallCheck(this,Index);var e=unpaid_possibleConstructorReturn(this,(Index.__proto__||Object.getPrototypeOf(Index)).apply(this,arguments));e.config={"navigationBarTitleText":"订单详情","navigationBarTextStyle":"black","navigationBarBackgroundColor":"#FFDD00"};var t=e.$router.params;return e.state={"id":t.id,"seconds":void 0,"loading":!0,"cartList":[],"userInfo":{},"orderInfo":{},"showWallet":!1},e}return function unpaid_inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(Index,e),v(Index,[{"key":"componentDidShow","value":function componentDidShow(){this.getOrderInfo()}},{"key":"onShareAppMessage","value":function onShareAppMessage(){return{"title":""+this.state.cartList.map((function(e){return e.name})).join("、"),"path":Object(l.i)()}}},{"key":"getOrderInfo","value":function getOrderInfo(){var e=this,t=this.state.id;Object(b.f)(t).then((function(t){var n=t.data;e.setState({"seconds":n.unPaidSeconds,"cartList":n.cartSnap,"userInfo":n.addressSnap,"orderInfo":n,"storeInfo":n.cooperateShopAddressSnap,"loadingStatus":!1},(function(){e.setState({"loading":!1})}))}))}},{"key":"onTimeUp","value":function onTimeUp(){console.log("onTimeUp")}},{"key":"onCloseOrder","value":function onCloseOrder(e){Object(r.b)({"title":"加载中","mask":!0}),Object(b.c)(e).then((function(){Object(r.a)(),Object(r.d)({"title":"订单已取消","icon":"success"}),a.a.navigateBack()}))}},{"key":"onPayOrder","value":function onPayOrder(e){Object(r.b)({"title":"加载中","mask":!0}),Object(b.i)(e.id).then((function(e){var t=e.data;Object(r.a)(),window.location.replace(t)}))}},{"key":"showSelectPopup","value":function showSelectPopup(e,t){this.setState(function _defineProperty(e,t,n){return t in e?Object.defineProperty(e,t,{"value":n,"enumerable":!0,"configurable":!0,"writable":!0}):e[t]=n,e}({},e,t))}},{"key":"onConfirmWalletPay","value":function onConfirmWalletPay(){var e=this,t=this.state.orderInfo;wx.showLoading({"title":"请稍后","mask":!0}),Object(b.j)(t.id).then((function(){wx.hideLoading(),wx.showToast({"title":"扣款成功","icon":"success"}),e.showSelectPopup("showWallet",!1),e.getOrderInfo()}))}},{"key":"render","value":function render(){var e=this.state,t=e.loading,n=e.cartList,r=e.userInfo,a=e.orderInfo,l=e.storeInfo,u=e.seconds,d=e.showWallet;return o.l.createElement(i.a,{"className":"detail-page unpaid-page"},t&&o.l.createElement(h.E,{"isCover":!0}),o.l.createElement(i.a,{"className":"order-card detail-header d-f f-d-c p-0"},o.l.createElement(i.a,{"className":"count-down d-f f-d-c a-i-c j-c-c"},o.l.createElement(i.a,{"className":"count-text"},"支付剩余时间"),o.l.createElement(m,{"seconds":u,"className":"count-num bnn-number","onTimeUp":this.onTimeUp.bind(this)})),1===a.distribute&&o.l.createElement(i.a,{"className":"order-card__body user-info d-f f-d-c f-w-w"},o.l.createElement(i.a,{"className":"user-box d-f a-i-c j-c-b"},o.l.createElement(i.a,{"className":"user-name t-o-e"},l.shopName),o.l.createElement(i.a,{"className":"user-phone t-o-e"},l.phone)),o.l.createElement(i.a,{"className":"user-box user-address"},l.address)),0===a.distribute&&o.l.createElement(i.a,{"className":"order-card__body user-info d-f f-d-c f-w-w"},o.l.createElement(i.a,{"className":"user-box d-f a-i-c j-c-b"},o.l.createElement(i.a,{"className":"user-name t-o-e"},r.name),o.l.createElement(i.a,{"className":"user-phone t-o-e"},r.telephone)),o.l.createElement(i.a,{"className":"user-box user-address"},r.city," ",r.detail))),o.l.createElement(i.a,{"className":"order-card order-card__body"},o.l.createElement(i.a,{"className":"cart-list"},n.map((function(e){return o.l.createElement(g.a,{"key":e.id,"info":e,"isJump":!0,"className":"cart-item thin-border__b"})}))),o.l.createElement(i.a,{"className":"detail-list"},o.l.createElement(i.a,{"className":"detail-item"},o.l.createElement(i.a,{"className":"item-left"},"优惠金额："),o.l.createElement(i.a,{"className":"item-right bnn-number"},"￥",a.discountAmount)),o.l.createElement(i.a,{"className":"detail-item"},o.l.createElement(i.a,{"className":"item-left"},"共",n.length,"件商品"),o.l.createElement(i.a,{"className":"item-right pay-amount bnn-number"},"￥",a.payAmount)),o.l.createElement(i.a,{"className":"detail-item"},o.l.createElement(i.a,{"className":"item-left"},"订单备注："),o.l.createElement(i.a,{"className":"item-right"},a.remark||"无")))),o.l.createElement(i.a,{"className":"order-card"},o.l.createElement(i.a,{"className":"order-card__head"},"订单信息"),o.l.createElement(i.a,{"className":"order-card__body"},o.l.createElement(i.a,{"className":"detail-list"},o.l.createElement(i.a,{"className":"detail-item"},o.l.createElement(i.a,{"className":"item-left"},"订单编号："),o.l.createElement(i.a,{"className":"item-right"},a.id)),o.l.createElement(i.a,{"className":"detail-item"},o.l.createElement(i.a,{"className":"item-left"},"订单时间："),o.l.createElement(i.a,{"className":"item-right"},Object(c.g)(a.createdAt)))))),y.e.UNPAID===a.status&&o.l.createElement(i.a,{"className":"float-box--height"}),y.e.UNPAID===a.status&&!a.isProxyPay&&o.l.createElement(i.a,{"className":"fy-popup fy-popup--bottom float-box d-f a-i-c j-c-e"},o.l.createElement(s.a,{"className":"btn-box xf-btn xf-btn-round","onClick":this.onCloseOrder.bind(this,a.id)},"取消订单"),y.g.WECHAT===a.payType&&o.l.createElement(s.a,{"className":"btn-box xf-btn xf-btn-danger xf-btn-round","onClick":this.onPayOrder.bind(this,a)},"立即支付")),o.l.createElement(h.T,{"amount":a.payAmount,"isOpened":d,"onClose":this.showSelectPopup.bind(this,"showWallet",!1),"onCancel":this.showSelectPopup.bind(this,"showWallet",!1),"onConfirm":this.onConfirmWalletPay.bind(this)}))}},{"key":"componentDidMount","value":function componentDidMount(){O(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidMount",this)&&O(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidMount",this).call(this)}},{"key":"componentDidHide","value":function componentDidHide(){O(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this)&&O(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this).call(this)}}]),Index}(a.a.Component);w.options={"addGlobalClass":!0}}}]);
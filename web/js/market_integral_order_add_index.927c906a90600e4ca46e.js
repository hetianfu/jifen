(window.webpackJsonp=window.webpackJsonp||[]).push([[26],{"304":function(e,t,n){"use strict";n.d(t,"x",(function(){return getUserInfo})),n.d(t,"w",(function(){return getUserCommissions})),n.d(t,"f",(function(){return asyncWechatInfo})),n.d(t,"q",(function(){return getMemberCount})),n.d(t,"r",(function(){return getMemberPermanent})),n.d(t,"m",(function(){return getCartPage})),n.d(t,"b",(function(){return addCart})),n.d(t,"l",(function(){return getCartInfo})),n.d(t,"i",(function(){return delBatchCart})),n.d(t,"C",(function(){return updateCart})),n.d(t,"D",(function(){return updateCartNumber})),n.d(t,"k",(function(){return getAddressList})),n.d(t,"a",(function(){return addAddress})),n.d(t,"B",(function(){return updateAddress})),n.d(t,"j",(function(){return getAddressInfo})),n.d(t,"A",(function(){return setDefaultAddress})),n.d(t,"h",(function(){return delAddressInfo})),n.d(t,"z",(function(){return getWalletPage})),n.d(t,"E",(function(){return userCharges})),n.d(t,"g",(function(){return buyMember})),n.d(t,"t",(function(){return getScoreConfig})),n.d(t,"v",(function(){return getSignScore})),n.d(t,"d",(function(){return addSignScore})),n.d(t,"u",(function(){return getScorePage})),n.d(t,"y",(function(){return getUserScore})),n.d(t,"s",(function(){return getMyCouponPage})),n.d(t,"n",(function(){return getCouponPage})),n.d(t,"c",(function(){return addCoupon})),n.d(t,"e",(function(){return applyDrawToWallet})),n.d(t,"p",(function(){return getGisciplePage})),n.d(t,"o",(function(){return getGiscipleDetailPage}));var r=n(9);n(4),Object.assign;function getUserInfo(){return r.a.get("/users/get-info",{},{"isAuth":!1})}function getUserCommissions(){return r.a.get("/user-commissions/get",{},{"isAuth":!1})}function asyncWechatInfo(e){return r.a.put("/users/async-wechat-info",e)}function getMemberCount(){return r.a.get("/users/count-vip-pay")}function getMemberPermanent(){return r.a.get("/users/permanent-vip-pay")}function getCartPage(e){return r.a.get("/users/get-shop-cart-page",{"params":e},{"isAuth":!1})}function addCart(e){return r.a.post("/users/add-shop-cart",e)}function getCartInfo(e){return r.a.get("/users/get-shop-cart-by-id/"+e)}function delBatchCart(e){return r.a.delete("/users/del-shop-cart",e)}function updateCart(e){return r.a.put("/users/update-shop-cart-by-id",e)}function updateCartNumber(e){return r.a.put("/users/update-shop-cart-number",e)}function getAddressList(){return r.a.get("/users/get-address-list")}function addAddress(e){return r.a.post("/users/add-address",e)}function updateAddress(e){return r.a.put("/users/update-address-by-id",e)}function getAddressInfo(e){return r.a.get("/users/get-address-by-id/"+e)}function setDefaultAddress(e){return r.a.put("/users/set-default-address",e)}function delAddressInfo(e){return r.a.delete("/users/del-address-by-id/"+e)}function getWalletPage(e){return r.a.request({"url":"/users/get-wallet-detail-page","data":e})}function userCharges(e){return r.a.post("/user-charges/charge",e)}function buyMember(e){return r.a.post("/user-charges/buy-vip",e)}function getScoreConfig(){return r.a.get("/user-scores/get-score-config")}function getSignScore(){return r.a.get("/user-scores/get-sign-score")}function addSignScore(){return r.a.post("/user-scores/sign-score")}function getScorePage(e){return r.a.get("/user-scores/get-detail-page",{"params":e})}function getUserScore(){return r.a.get("/user-scores/user-score")}function getMyCouponPage(e){return r.a.get("/users/get-my-coupon-page",{"params":e})}function getCouponPage(e){return r.a.get("/users/get-coupon-page",{"params":e})}function addCoupon(e){return r.a.post("/users/add-coupon",e)}function applyDrawToWallet(e){return r.a.post("/users/apply-draw-to-wallet",e)}function getGisciplePage(e){return r.a.get("/users/get-disciple-page",{"params":e})}function getGiscipleDetailPage(e){return r.a.get("/user-commissions/get-disciple-detail-page",{"params":e})}},"307":function(e,t,n){"use strict";n.d(t,"a",(function(){return asyncCalcOrder})),n.d(t,"l",(function(){return submitOrder})),n.d(t,"b",(function(){return asyncCalcScoresOrder})),n.d(t,"m",(function(){return submitScoresOrder})),n.d(t,"i",(function(){return goH5ToPay})),n.d(t,"j",(function(){return goToWalletPay})),n.d(t,"g",(function(){return getOrderPage})),n.d(t,"f",(function(){return getOrderInfo})),n.d(t,"h",(function(){return getQrCode})),n.d(t,"d",(function(){return confirmReceipt})),n.d(t,"c",(function(){return cancelOrderInfo})),n.d(t,"k",(function(){return refundOrder})),n.d(t,"e",(function(){return getConnectUser}));var r=n(9);function asyncCalcOrder(e){return r.a.post("/order-pays/calculate-order",e)}function submitOrder(e){return r.a.post("/order-pays/submit-order",e)}function asyncCalcScoresOrder(e){return r.a.post("/order-scores/calculate-order",e)}function submitScoresOrder(e){return r.a.post("/order-scores/submit-order",e)}function goH5ToPay(e){return r.a.get("/order-pays/h5-to-pay/"+e)}function goToWalletPay(e){return r.a.get("/order-pays/wallet-to-pay/"+e)}function getOrderPage(e){return r.a.get("/orders/get-page",{"params":e})}function getOrderInfo(e){return r.a.get("/orders/get-by-id/"+e)}function getQrCode(e){return r.a.get("/orders/get-qr-code/"+e)}function confirmReceipt(e){return r.a.put("/orders/receive-by-id",{"id":e})}function cancelOrderInfo(e){return r.a.put("/orders/cancel-by-id/"+e)}function refundOrder(e){return r.a.post("/orders/refund-by-id",e)}function getConnectUser(){return r.a.get("/orders/get-connect-user")}},"356":function(e,t,n){"use strict";n(25);var r=n(1),a=function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}();function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var o=function(e){function Form(){_classCallCheck(this,Form);var e=_possibleConstructorReturn(this,(Form.__proto__||Object.getPrototypeOf(Form)).apply(this,arguments));return e.Forms=[],e.onSubmit=e.onSubmit.bind(e),e.onReset=e.onReset.bind(e),e}return function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(Form,e),a(Form,[{"key":"onSubmit","value":function onSubmit(e){e.preventDefault();for(var t=r.l.findDOMNode(this),n=[],a=t.getElementsByTagName("input"),o=0;o<a.length;o++)n.push(a[o]);var s={},c={};n.forEach((function(e){-1===e.className.indexOf("weui-switch")?"radio"!==e.type?"checkbox"!==e.type?s[e.name]=e.value:e.checked?c[e.name]?s[e.name].push(e.value):(c[e.name]=!0,s[e.name]=[e.value]):c[e.name]||(s[e.name]=[]):e.checked?(c[e.name]=!0,s[e.name]=e.value):c[e.name]||(s[e.name]=""):s[e.name]=e.checked}));for(var i=t.getElementsByTagName("textarea"),u=[],l=0;l<i.length;l++)u.push(i[l]);u.forEach((function(e){s[e.name]=e.value})),Object.defineProperty(e,"detail",{"enumerable":!0,"value":{"value":s}}),this.props.onSubmit(e)}},{"key":"onReset","value":function onReset(e){e.preventDefault(),this.props.onReset()}},{"key":"render","value":function render(){var e=this.props,t=e.className,n=e.style;return r.l.createElement("form",{"className":t,"style":n,"onSubmit":this.onSubmit,"onReset":this.onReset},this.props.children)}}]),Form}(r.l.Component);t.a=o},"562":function(e,t,n){},"628":function(e,t,n){"use strict";n.r(t);var r=n(21),a=n.n(r),o=n(1),s=n(2),c=n(40),i=n(569),u=n(356),l=n(441),d=n(364),f=n(8),p=n.n(f),m=(n(562),n(10)),h=n(305),g=n(9);var b=n(302),y=n(304),v=n(307),w=n(303),S=n(14);n.d(t,"default",(function(){return N}));var E=function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}(),O=function get(e,t,n){null===e&&(e=Function.prototype);var r=Object.getOwnPropertyDescriptor(e,t);if(void 0===r){var a=Object.getPrototypeOf(e);return null===a?void 0:get(a,t,n)}if("value"in r)return r.value;var o=r.get;return void 0!==o?o.call(n):void 0};function _defineProperty(e,t,n){return t in e?Object.defineProperty(e,t,{"value":n,"enumerable":!0,"configurable":!0,"writable":!0}):e[t]=n,e}function _asyncToGenerator(e){return function(){var t=e.apply(this,arguments);return new Promise((function(e,n){return function step(r,a){try{var o=t[r](a),s=o.value}catch(e){return void n(e)}if(!o.done)return Promise.resolve(s).then((function(e){step("next",e)}),(function(e){step("throw",e)}));e(s)}("next")}))}}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var C=function getAddress(e,t){if(t&&t.id)return t;var n=e.filter((function(e){return e.isDefault}));return n.length>=1?m.a.head(n):m.a.head(e)||{}},N=function(e){function Index(){_classCallCheck(this,Index);var e=_possibleConstructorReturn(this,(Index.__proto__||Object.getPrototypeOf(Index)).apply(this,arguments));return e.config={"backgroundTextStyle":"dark","navigationBarTextStyle":"black","navigationBarBackgroundColor":"#FFDD00"},e.state={"type":0,"typeList":[{"title":"快递配送","value":0},{"title":"到店自提","value":1}],"payType":h.g.WECHAT,"loading":!0,"isScore":!1,"orderInfo":{},"showShop":!1,"showAddress":!1,"userInfo":{},"shopInfo":{},"addressInfo":{},"connectSnap":{},"shopList":[],"productList":[],"addressList":[]},e}var t,n;return function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(Index,e),E(Index,[{"key":"componentDidMount","value":function componentDidMount(){this.initOrder(),this.getConnectUser()}},{"key":"componentDidShow","value":(n=_asyncToGenerator(a.a.mark((function _callee(){var e,t,n,r;return a.a.wrap((function _callee$(a){for(;;)switch(a.prev=a.next){case 0:if(e=this.state,t=e.loading,n=e.addressInfo,t){a.next=7;break}return wx.showLoading({"title":"请稍后","mask":!0}),a.next=5,Object(y.k)();case 5:r=a.sent,this.setState({"addressInfo":C(r.data,n),"addressList":r.data},(function(){wx.hideLoading()}));case 7:case"end":return a.stop()}}),_callee,this)}))),function componentDidShow(){return n.apply(this,arguments)})},{"key":"initOrder","value":(t=_asyncToGenerator(a.a.mark((function _callee2(){var e,t,n,r,o=this;return a.a.wrap((function _callee2$(a){for(;;)switch(a.prev=a.next){case 0:return e=this.state.addressInfo,a.next=3,g.a.get("/merchants/get-shop-list");case 3:return t=a.sent,a.next=6,Object(y.k)();case 6:return n=a.sent,a.next=9,Object(y.x)();case 9:r=a.sent,this.setState({"userInfo":r.data,"shopInfo":m.a.head(t.data),"shopList":t.data,"addressInfo":C(n.data,e),"addressList":n.data},(function(){o.calcOrder()}));case 11:case"end":return a.stop()}}),_callee2,this)}))),function initOrder(){return t.apply(this,arguments)})},{"key":"calcOrder","value":function calcOrder(){var e=this,t=this.$router.params,n=this.state,r=n.loading,a=n.orderInfo,o=n.isScore,s=n.type,c=n.typeList,i={"id":a.id,"distribute":c[s].value,"productList":[{"id":t.id,"skuId":t.skuId,"number":t.number}],"scoreDiscount":o?a.deductScoreAmount:void 0};r||wx.showLoading({"title":"请稍后","mask":!0}),Object(v.b)(i).then((function(t){var n=t.data;e.setState({"orderInfo":n,"productList":n.productList},(function(){wx.hideLoading(),e.setState({"loading":!1})}))}))}},{"key":"getConnectUser","value":function getConnectUser(){var e=this;Object(v.e)().then((function(t){e.setState({"connectSnap":t.data||{}})}))}},{"key":"onSwitchType","value":function onSwitchType(e){var t=this;this.setState({"type":e},(function(){t.calcOrder()}))}},{"key":"showSelectPopup","value":function showSelectPopup(e,t){this.setState(_defineProperty({},e,t))}},{"key":"onConfirmSelect","value":function onConfirmSelect(e,t){var n=this;this.setState(_defineProperty({},e,t),(function(){"addressInfo"===e&&n.calcOrder()}))}},{"key":"onSubmit","value":function onSubmit(e){var t=e.detail.value,n=this.state,r=n.type,a=n.orderInfo,o=n.addressInfo,s=n.shopInfo,c={"id":a.id};if(Object.keys(t).map((function(e){m.a.set(c,e,t[e])})),0!==r||o)if(1!==r||s)if(1!==r||c.connectSnap.name)if(1!==r||c.connectSnap.telephone){0===r&&Object.assign(c,{"addressSnap":o}),1===r&&Object.assign(c,{"cooperateShopId":s.id,"cooperateShopAddress":s});var i=r?Object(S.l)("checkOrder"):Object(S.l)("postOrder");wx.requestSubscribeMessage({"tmplIds":i,"complete":function complete(){wx.showLoading({"title":"请稍后","mask":!0}),Object(v.m)(c).then((function(e){wx.hideLoading(),wx.showToast({"title":"兑换成功","icon":"success","complete":function complete(){Object(b.n)(e.data,!0)}})}))}})}else wx.showToast({"title":"请输入联系电话","icon":"none"});else wx.showToast({"title":"请输入联系人","icon":"none"});else wx.showToast({"title":"请选择自提店铺","icon":"none"});else wx.showToast({"title":"请选择收货地址","icon":"none"})}},{"key":"render","value":function render(){var e=this,t=this.state,n=t.loading,r=t.type,a=t.typeList,s=t.addressInfo,f=t.shopInfo,m=t.connectSnap,h=t.addressList,g=t.productList,b=t.shopList,y=t.showShop,v=t.showAddress,E=t.orderInfo;return o.l.createElement(c.a,{"className":"index"},n&&o.l.createElement(w.E,{"isCover":!0}),o.l.createElement(c.a,{"className":"index-scroll"},o.l.createElement(c.a,{"className":"index-head delivery-type"},o.l.createElement(c.a,{"className":"type-list d-f a-i-f-e"},a.map((function(t,n){return o.l.createElement(c.a,{"key":n,"className":p()("type-item d-f a-i-c j-c-c",r===n&&"active"),"onClick":e.onSwitchType.bind(e,n)},t.title)}))),0==r&&o.l.createElement(c.a,{"className":"delivery-box d-f a-i-c j-c-b","onClick":this.showSelectPopup.bind(this,"showAddress",!0)},s.telephone&&o.l.createElement(c.a,{"className":"address-box"},o.l.createElement(c.a,{"className":"base-address d-f a-i-c "},o.l.createElement(c.a,{"className":"user-name t-o-e"},s.name),o.l.createElement(c.a,{"className":"user-phone"},s.telephone)),o.l.createElement(c.a,{"className":"address-text t-o-e-2"},s.city," ",s.detail)),!s.telephone&&o.l.createElement(c.a,{"className":"address-box"},"设置收货地址"),o.l.createElement(c.a,{"className":"bnn-icon right-icon"},"")),1==r&&o.l.createElement(c.a,{"className":"delivery-box d-f a-i-c j-c-b","onClick":this.showSelectPopup.bind(this,"showShop",!0)},o.l.createElement(c.a,{"className":"address-box"},o.l.createElement(c.a,{"className":"base-address d-f a-i-c "},o.l.createElement(c.a,{"className":"user-name t-o-e"},f.shopName),o.l.createElement(c.a,{"className":"user-phone"},f.phone)),o.l.createElement(c.a,{"className":"address-text t-o-e-2"},f.address)),o.l.createElement(c.a,{"className":"bnn-icon right-icon"},""))),o.l.createElement(c.a,{"className":"order-card goods-list"},g.map((function(e){return o.l.createElement(c.a,{"key":e.id,"className":"goods-item thin-border__b"},o.l.createElement(i.a,{"src":Object(S.m)(Object(S.o)(e.images),320,320),"className":"goods-img","lazyLoad":!0}),o.l.createElement(c.a,{"className":"other-box d-f f-d-c j-c-b"},o.l.createElement(c.a,{"className":"other-head"},o.l.createElement(c.a,{"className":"goods-name t-o-e-2"},e.name),e.skuId&&o.l.createElement(c.a,{"className":"attr-name t-o-e"},e.specSnap)),o.l.createElement(c.a,{"className":"other-foot d-f a-i-b j-c-b"},o.l.createElement(c.a,{"className":"goods-price bnn-number"},Number(e.salePrice)," ",o.l.createElement(w.B,null)),o.l.createElement(c.a,{"className":"goods-num"},"x",e.number))))}))),o.l.createElement(u.a,{"onSubmit":this.onSubmit.bind(this)},o.l.createElement(c.a,{"className":"order-card"},o.l.createElement(c.a,{"className":"card-list"},1===r&&o.l.createElement(c.a,{"className":"list-item thin-border__b"},o.l.createElement(c.a,{"className":"item-left"},o.l.createElement(c.a,{"className":"item-title"},"联系人")),o.l.createElement(c.a,{"className":"item-right"},o.l.createElement(l.a,{"name":"connectSnap.name","value":m.name,"maxLength":16,"placeholder":"请填写您的联系姓名","className":"item-input"}))),1===r&&o.l.createElement(c.a,{"className":"list-item thin-border__b"},o.l.createElement(c.a,{"className":"item-left"},o.l.createElement(c.a,{"className":"item-title"},"联系电话")),o.l.createElement(c.a,{"className":"item-right"},o.l.createElement(l.a,{"name":"connectSnap.telephone","type":"number","value":m.telephone,"maxLength":11,"placeholder":"请填写您的联系电话","className":"item-input"}))),o.l.createElement(c.a,{"className":"list-item f-w-w"},o.l.createElement(c.a,{"className":"item-left"},o.l.createElement(c.a,{"className":"item-title"},"备注信息")),o.l.createElement(c.a,{"className":"item-right"},o.l.createElement(l.a,{"name":"remark","maxLength":255,"placeholder":"请添加备注（150字以内）","className":"item-input"}))))),o.l.createElement(c.a,{"className":"index-foot--height"}),o.l.createElement(c.a,{"className":"index-foot d-f a-i-c fy-popup fy-popup--bottom"},o.l.createElement(c.a,{"className":"total-num"},"共",g.length,"件"),o.l.createElement(c.a,{"className":"total-money d-f j-c-e"},"合计：",o.l.createElement(c.a,{"className":"money-num bnn-number"},E.payAmount," ",o.l.createElement(w.B,null))),o.l.createElement(d.a,{"formType":"submit","className":"buy-now d-f a-i-c j-c-c","hoverClass":"hover-class--btn"},"立即兑换")))),o.l.createElement(w.O,{"isOpened":y,"item":f,"list":b,"onClose":this.showSelectPopup.bind(this,"showShop",!1),"onConfirm":this.onConfirmSelect.bind(this,"shopInfo")}),o.l.createElement(w.a,{"isOpened":v,"item":s,"list":h,"onClose":this.showSelectPopup.bind(this,"showAddress",!1),"onConfirm":this.onConfirmSelect.bind(this,"addressInfo")}))}},{"key":"componentDidHide","value":function componentDidHide(){O(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this)&&O(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this).call(this)}}]),Index}(s.a.Component)}}]);
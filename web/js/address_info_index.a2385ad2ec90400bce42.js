(window.webpackJsonp=window.webpackJsonp||[]).push([[7],{"307":function(e,t,n){"use strict";n.d(t,"x",(function(){return getUserInfo})),n.d(t,"w",(function(){return getUserCommissions})),n.d(t,"f",(function(){return asyncWechatInfo})),n.d(t,"q",(function(){return getMemberCount})),n.d(t,"r",(function(){return getMemberPermanent})),n.d(t,"m",(function(){return getCartPage})),n.d(t,"b",(function(){return addCart})),n.d(t,"l",(function(){return getCartInfo})),n.d(t,"i",(function(){return delBatchCart})),n.d(t,"C",(function(){return updateCart})),n.d(t,"D",(function(){return updateCartNumber})),n.d(t,"k",(function(){return getAddressList})),n.d(t,"a",(function(){return addAddress})),n.d(t,"B",(function(){return updateAddress})),n.d(t,"j",(function(){return getAddressInfo})),n.d(t,"A",(function(){return setDefaultAddress})),n.d(t,"h",(function(){return delAddressInfo})),n.d(t,"z",(function(){return getWalletPage})),n.d(t,"E",(function(){return userCharges})),n.d(t,"g",(function(){return buyMember})),n.d(t,"t",(function(){return getScoreConfig})),n.d(t,"v",(function(){return getSignScore})),n.d(t,"d",(function(){return addSignScore})),n.d(t,"u",(function(){return getScorePage})),n.d(t,"y",(function(){return getUserScore})),n.d(t,"s",(function(){return getMyCouponPage})),n.d(t,"n",(function(){return getCouponPage})),n.d(t,"c",(function(){return addCoupon})),n.d(t,"e",(function(){return applyDrawToWallet})),n.d(t,"p",(function(){return getGisciplePage})),n.d(t,"o",(function(){return getGiscipleDetailPage}));var r=n(9);n(5),Object.assign;function getUserInfo(){return r.a.get("/users/get-info",{},{"isAuth":!1})}function getUserCommissions(){return r.a.get("/user-commissions/get",{},{"isAuth":!1})}function asyncWechatInfo(e){return r.a.put("/users/async-wechat-info",e)}function getMemberCount(){return r.a.get("/users/count-vip-pay")}function getMemberPermanent(){return r.a.get("/users/permanent-vip-pay")}function getCartPage(e){return r.a.get("/users/get-shop-cart-page",{"params":e},{"isAuth":!1})}function addCart(e){return r.a.post("/users/add-shop-cart",e)}function getCartInfo(e){return r.a.get("/users/get-shop-cart-by-id/"+e)}function delBatchCart(e){return r.a.delete("/users/del-shop-cart",e)}function updateCart(e){return r.a.put("/users/update-shop-cart-by-id",e)}function updateCartNumber(e){return r.a.put("/users/update-shop-cart-number",e)}function getAddressList(){return r.a.get("/users/get-address-list")}function addAddress(e){return r.a.post("/users/add-address",e)}function updateAddress(e){return r.a.put("/users/update-address-by-id",e)}function getAddressInfo(e){return r.a.get("/users/get-address-by-id/"+e)}function setDefaultAddress(e){return r.a.put("/users/set-default-address",e)}function delAddressInfo(e){return r.a.delete("/users/del-address-by-id/"+e)}function getWalletPage(e){return r.a.request({"url":"/users/get-wallet-detail-page","data":e})}function userCharges(e){return r.a.post("/user-charges/charge",e)}function buyMember(e){return r.a.post("/user-charges/buy-vip",e)}function getScoreConfig(){return r.a.get("/user-scores/get-score-config")}function getSignScore(){return r.a.get("/user-scores/get-sign-score")}function addSignScore(){return r.a.post("/user-scores/sign-score")}function getScorePage(e){return r.a.get("/user-scores/get-detail-page",{"params":e})}function getUserScore(){return r.a.get("/user-scores/user-score")}function getMyCouponPage(e){return r.a.get("/users/get-my-coupon-page",{"params":e})}function getCouponPage(e){return r.a.get("/users/get-coupon-page",{"params":e})}function addCoupon(e){return r.a.post("/users/add-coupon",e)}function applyDrawToWallet(e){return r.a.post("/users/apply-draw-to-wallet",e)}function getGisciplePage(e){return r.a.get("/users/get-disciple-page",{"params":e})}function getGiscipleDetailPage(e){return r.a.get("/user-commissions/get-disciple-detail-page",{"params":e})}},"348":function(e,t,n){"use strict";n(27);var r=n(1),o=function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}();function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var a=function(e){function Form(){_classCallCheck(this,Form);var e=_possibleConstructorReturn(this,(Form.__proto__||Object.getPrototypeOf(Form)).apply(this,arguments));return e.Forms=[],e.onSubmit=e.onSubmit.bind(e),e.onReset=e.onReset.bind(e),e}return function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(Form,e),o(Form,[{"key":"onSubmit","value":function onSubmit(e){e.preventDefault();for(var t=r.l.findDOMNode(this),n=[],o=t.getElementsByTagName("input"),a=0;a<o.length;a++)n.push(o[a]);var i={},s={};n.forEach((function(e){-1===e.className.indexOf("weui-switch")?"radio"!==e.type?"checkbox"!==e.type?i[e.name]=e.value:e.checked?s[e.name]?i[e.name].push(e.value):(s[e.name]=!0,i[e.name]=[e.value]):s[e.name]||(i[e.name]=[]):e.checked?(s[e.name]=!0,i[e.name]=e.value):s[e.name]||(i[e.name]=""):i[e.name]=e.checked}));for(var u=t.getElementsByTagName("textarea"),c=[],l=0;l<u.length;l++)c.push(u[l]);c.forEach((function(e){i[e.name]=e.value})),Object.defineProperty(e,"detail",{"enumerable":!0,"value":{"value":i}}),this.props.onSubmit(e)}},{"key":"onReset","value":function onReset(e){e.preventDefault(),this.props.onReset()}},{"key":"render","value":function render(){var e=this.props,t=e.className,n=e.style;return r.l.createElement("form",{"className":t,"style":n,"onSubmit":this.onSubmit,"onReset":this.onReset},this.props.children)}}]),Form}(r.l.Component);t.a=a},"573":function(e,t,n){},"633":function(e,t,n){"use strict";n.r(t),n.d(t,"default",(function(){return h}));var r=n(1),o=n(131),a=n(3),i=n(43),s=n(348),u=n(485),c=n(615),l=n(422),d=(n(573),n(6)),f=n(307),p=n(306),m=function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}(),g=function get(e,t,n){null===e&&(e=Function.prototype);var r=Object.getOwnPropertyDescriptor(e,t);if(void 0===r){var o=Object.getPrototypeOf(e);return null===o?void 0:get(o,t,n)}if("value"in r)return r.value;var a=r.get;return void 0!==a?a.call(n):void 0};function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var h=function(e){function Index(){_classCallCheck(this,Index);var e=_possibleConstructorReturn(this,(Index.__proto__||Object.getPrototypeOf(Index)).apply(this,arguments));e.config={"navigationBarTitleText":"收货地址","navigationBarBackgroundColor":"#f4f4f4"};var t=e.$router.params;return e.state={"id":t.id,"city":{"code":[]},"loading":!0,"cityArr":[],"isOpened":!1,"addressList":[],"addressInfo":{}},e}return function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(Index,e),m(Index,[{"key":"componentDidShow","value":function componentDidShow(){this.getInfo()}},{"key":"showCityPopup","value":function showCityPopup(e){this.setState({"isOpened":e})}},{"key":"onConfirmCity","value":function onConfirmCity(e,t){this.setState({"cityCode":e,"cityArr":t})}},{"key":"getInfo","value":function getInfo(){var e=this,t=this.state.id;t?Object(f.j)(t).then((function(t){var n=t.data;e.setState({"loading":!1,"cityArr":n.city.split(","),"postcode":n.cityCode,"addressInfo":n})})):this.setState({"loading":!1})}},{"key":"delInfo","value":function delInfo(e){Object(o.b)({"title":"加载中","mask":!0}),Object(f.h)(e).then((function(){Object(o.a)(),Object(o.d)({"icon":"success","title":"删除成功","mask":!0}),a.a.navigateBack()}))}},{"key":"onChange","value":function onChange(e){var t=e.detail,n=t.code,r=t.value,o=d.a.last(n);this.setState({"city":{"code":n,"name":r,"value":o},"cityCode":o,"cityArr":r,"isDefault":!1})}},{"key":"onClickDft","value":function onClickDft(){var e=this.state.isDefault;this.setState({"isDefault":!e})}},{"key":"onSubmit","value":function onSubmit(e){var t=e.detail.value,n=this.state,r=n.id,i=n.cityCode,s=n.cityArr;t.city=s.join(","),t.cityCode=i,t.name?t.telephone?t.city?t.detail?(Object(o.b)({"title":"加载中","mask":!0}),r?(t.id=r,Object(f.B)(t).then((function(){Object(o.a)(),Object(o.d)({"icon":"success","title":"保存成功","mask":!0}),a.a.navigateBack()}))):Object(f.a)(t).then((function(){Object(o.a)(),Object(o.d)({"icon":"success","title":"保存成功","mask":!0}),a.a.navigateBack()}))):Object(o.d)({"title":"请填写详细地址","icon":"none"}):Object(o.d)({"title":"请选择城市","icon":"none"}):Object(o.d)({"title":"请填写手机号","icon":"none"}):Object(o.d)({"title":"请填写收货人","icon":"none"})}},{"key":"render","value":function render(){var e=this.state,t=e.id,n=e.loading,o=e.cityArr,a=e.isOpened,d=e.addressInfo;return r.l.createElement(i.a,{"className":"address-info-page"},n&&r.l.createElement(p.J,{"isCover":!0}),r.l.createElement(s.a,{"onSubmit":this.onSubmit.bind(this)},r.l.createElement(i.a,{"className":"list"},r.l.createElement(i.a,{"className":"list-item thin-border__b"},r.l.createElement(u.a,{"name":"name","value":d.name,"className":"list-left","placeholder":"收货人","placeholderClass":"placeholder"})),r.l.createElement(i.a,{"className":"list-item thin-border__b"},r.l.createElement(u.a,{"name":"telephone","value":d.telephone,"type":"number","maxLength":11,"className":"list-left","placeholder":"手机号码","placeholderClass":"placeholder"})),r.l.createElement(i.a,{"className":"list-item thin-border__b region-box","onClick":this.showCityPopup.bind(this,!0)},r.l.createElement(i.a,{"className":"list-left"},!o.length&&r.l.createElement(i.a,{"className":"placeholder"},"所在地区"),o.map((function(e,t){return r.l.createElement(i.a,{"key":t,"className":"city-text"},e)}))),r.l.createElement(i.a,{"className":"list-right d-f a-i-c j-c-e"},r.l.createElement(i.a,{"className":"bnn-icon right-icon"},""))),r.l.createElement(i.a,{"className":"list-item thin-border__b textarea-box"},r.l.createElement(c.a,{"name":"detail","value":d.detail,"className":"list-left","placeholder":"详细地址:如道路、门牌号、小区、楼栋号、单元室等","placeholderClass":"placeholder"}))),t&&r.l.createElement(i.a,{"className":"list"},r.l.createElement(i.a,{"className":"list-item thin-border__b d-f a-i-c delete-box","onClick":this.delInfo.bind(this,t)},"删除收货地址")),r.l.createElement(l.a,{"formType":"submit","className":"xf-btn xf-btn-round xf-btn-primary btn-box"},"立即保存")),r.l.createElement(p.R,{"isOpened":a,"onConfirm":this.onConfirmCity.bind(this),"onClose":this.showCityPopup.bind(this,!1)}))}},{"key":"componentDidMount","value":function componentDidMount(){g(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidMount",this)&&g(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidMount",this).call(this)}},{"key":"componentDidHide","value":function componentDidHide(){g(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this)&&g(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this).call(this)}}]),Index}(a.a.Component)}}]);
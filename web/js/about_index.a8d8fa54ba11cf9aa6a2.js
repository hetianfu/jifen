(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{"302":function(e,t,n){"use strict";n.d(t,"d",(function(){return toAnyPage})),n.d(t,"l",(function(){return toHomePage})),n.d(t,"y",(function(){return toSearchPage})),n.d(t,"g",(function(){return toCartPage})),n.d(t,"j",(function(){return toCommentListPage})),n.d(t,"i",(function(){return toCommentInfoPage})),n.d(t,"h",(function(){return toCommentGoodsPage})),n.d(t,"C",(function(){return toUserPage})),n.d(t,"B",(function(){return toUserLoginPage})),n.d(t,"A",(function(){return toShopInfoPage})),n.d(t,"e",(function(){return toCalcOrderPage})),n.d(t,"f",(function(){return toCalcVirtualOrder})),n.d(t,"v",(function(){return toOrderListPage})),n.d(t,"w",(function(){return toOrderUnpaidPage})),n.d(t,"u",(function(){return toOrderInfoPage})),n.d(t,"x",(function(){return toOrderVerificationPage})),n.d(t,"t",(function(){return toOrderCommentPage})),n.d(t,"k",(function(){return toGoodsPage})),n.d(t,"p",(function(){return toMemberOpenPage})),n.d(t,"q",(function(){return toMemberPayPage})),n.d(t,"c",(function(){return toAddressListPage})),n.d(t,"b",(function(){return toAddressInfoPage})),n.d(t,"s",(function(){return toNewsListPage})),n.d(t,"r",(function(){return toNewsInfoPage})),n.d(t,"F",(function(){return toWebPage})),n.d(t,"m",(function(){return toIntegralCheckinPage})),n.d(t,"o",(function(){return toIntegralRecordPage})),n.d(t,"a",(function(){return toAddIntegralOrderPage})),n.d(t,"n",(function(){return toIntegralOrderInfoPage})),n.d(t,"E",(function(){return toWalletRechargePage})),n.d(t,"z",(function(){return toSeckillListPage})),n.d(t,"D",(function(){return toWaitGroupPage}));var o=n(301),r=n(14),i=n(305),a=n(20);function toAnyPage(e,t,n){Object(r.p)(e,t,n)}function toHomePage(){window.location.href="/pages/tabbar/home/index"}function toSearchPage(e,t){Object(r.p)("/pages/search/index",e,t)}function toCartPage(){Object(o.b)({"url":"/pages/tabbar/cart/index"})}function toCommentListPage(e,t){Object(r.p)("/pages/comment/list/index",{"id":e},t)}function toCommentInfoPage(e,t){Object(r.p)("/pages/comment/detail/index",{"id":e},t)}function toCommentGoodsPage(e,t){Object(r.p)("/pages/comment/goods/index",{"id":e},t)}function toUserPage(){Object(o.b)({"url":"/pages/tabbar/user/index"})}function toUserLoginPage(e){Object(r.p)("/pages/login/index",{},e)}function toShopInfoPage(e,t){Object(r.p)("/pages/shop/info/index",{"id":e},t)}function toCalcOrderPage(e,t){Object(a.o)({"success":function success(){Object(r.p)("/pages/order/calc/index",e,t)},"fail":function fail(){toUserLoginPage()}})}function toCalcVirtualOrder(e,t){Object(a.o)({"success":function success(){Object(r.p)("/pages/virtual/orderCalc/index",e,t)},"fail":function fail(){toUserLoginPage()}})}function toOrderListPage(e){Object(r.p)("/pages/order/list/index",{},e)}function toOrderUnpaidPage(e,t){Object(r.p)("/pages/order/unpaid/index",{"id":e},t)}function toOrderInfoPage(e,t){Object(r.p)("/pages/order/info/index",{"id":e},t)}function toOrderVerificationPage(e,t){Object(r.p)("/pages/order/verification/index",{"id":e},t)}function toOrderCommentPage(e,t){Object(r.p)("/pages/order/comment/index",{"id":e},t)}function toGoodsPage(e,t){var n=e.id,o=e.type,a=e.saleStrategy;switch(a===i.a.NORMAL?o:a){case i.b.SECKILL:Object(r.p)("/market/pages/seckill/goods/index",{"id":n},t);break;case i.b.PINK:Object(r.p)("/market/pages/group/goods/index",{"id":n},t);break;case i.b.SCORE:Object(r.p)("/market/pages/integral/goods/index",{"id":n},t);break;case i.b.VIRTUAL:Object(r.p)("/pages/virtual/info/index",{"id":n},t);break;case i.b.REAL:Object(r.p)("/pages/real/info/index",{"id":n},t);break;default:Object(r.p)("/pages/real/info/index",{"id":n},t)}}function toMemberOpenPage(e){Object(r.p)("/pages/member/open/index",{},e)}function toMemberPayPage(e){Object(r.p)("/pages/member/pay/index",{},e)}function toAddressListPage(e){Object(r.p)("/pages/address/list/index",{},e)}function toAddressInfoPage(e,t){Object(r.p)("/pages/address/info/index",{"id":e},t)}function toNewsListPage(){Object(r.p)("/pages/news/list/index")}function toNewsInfoPage(e,t){Object(r.p)("/pages/news/info/index",{"id":e},t)}function toWebPage(e,t){Object(r.p)("/pages/web/index",{"url":e},t)}function toIntegralCheckinPage(e){Object(r.p)("/market/pages/integral/checkin/index",{},e)}function toIntegralRecordPage(e,t){Object(r.p)("/market/pages/integral/record/index",e,t)}function toAddIntegralOrderPage(e,t){Object(r.p)("/market/pages/integral/order/add/index",e,t)}function toIntegralOrderInfoPage(e,t){Object(r.p)("/market/pages/integral/order/info/index",{"id":e},t)}function toWalletRechargePage(e){Object(r.p)("/market/pages/wallet/recharge/index",{},e)}function toSeckillListPage(e){Object(r.p)("/market/pages/seckill/list/index",{},e)}function toWaitGroupPage(e,t){Object(r.p)("/market/pages/group/wait/index",{"id":e},t)}},"305":function(e,t,n){"use strict";n.d(t,"b",(function(){return o})),n.d(t,"a",(function(){return r})),n.d(t,"e",(function(){return i})),n.d(t,"f",(function(){return a})),n.d(t,"g",(function(){return c})),n.d(t,"i",(function(){return d})),n.d(t,"j",(function(){return u})),n.d(t,"c",(function(){return s})),n.d(t,"d",(function(){return l})),n.d(t,"h",(function(){return p}));var o={"REAL":"REAL","PINK":"PINK","SCORE":"SCORE","VIRTUAL":"VIRTUAL","SECKILL":"SECKILL"},r={"GROUP":"PINK","SCORE":"SCORE","NORMAL":"NORMAL","SECKILL":"SECKILL"},i={"INIT":"init","UNPAID":"unpaid","PAID":"paid","PINKING":"pinking","UN_SEND":"unsend","SENDING":"sending","UN_CHECK":"uncheck","UN_REPLY":"unreply","CLOSED":"closed","REFUND":"refund","REFUNDING":"refunding","CANCELLED":"cancelled"},a={"init":"初使化","unpaid":"待支付","paid":"已支付","pinking":"组团中","unsend":"待发货","sending":"待收货","uncheck":"待核销","unreply":"待评论","closed":"已完成","refund":"已退单","refunding":"申请退款","cancelled":"已取消"},c={"WECHAT":"wx","ALIPAY":"alipay","WALLET":"wallet"},d={"DRAW":"DRAW","REFUND":"REFUND","CHARGE":"CHARGE","CONSUME":"CONSUME","DISTRIBUTE":"DISTRIBUTE"},u={"DRAW":"提现","REFUND":"退款","CHARGE":"用户余额充值","CONSUME":"消费","DISTRIBUTE":"分销"},s={"SIGN":"SIGN","ORDER":"ORDER","DEDUCT":"DEDUCT"},l={"SIGN":"签到奖励","ORDER":"购买赠送","REFUND":"退单","DEDUCT":"消费抵扣","CONSUME":"积分兑换"},p={"HOME_PAGE":"HOME_PAGE","USER_INFO":"USER_INFO","GOODS_INFO":"GOODS_INFO","NORMAL_GOODS":"NORMAL","REAL_GOODS":"REAL","VIRTUAL_GOODS":"VIRTUAL","SCORE_GOODS":"SCORE","SECKILL_GOODS":"SECKILL","PINK_GOODS":"PINK"}},"532":function(e,t,n){},"593":function(e,t,n){"use strict";n.r(t),n.d(t,"default",(function(){return O}));var o=n(1),r=n(299),i=n(2),a=n(40),c=n(569),d=n(404),u=n(364),s=(n(532),n(20)),l=n(302),p=n(19),f=function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}(),g=function get(e,t,n){null===e&&(e=Function.prototype);var o=Object.getOwnPropertyDescriptor(e,t);if(void 0===o){var r=Object.getPrototypeOf(e);return null===r?void 0:get(r,t,n)}if("value"in o)return o.value;var i=o.get;return void 0!==i?i.call(n):void 0};function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var O=function(e){function Index(){_classCallCheck(this,Index);var e=_possibleConstructorReturn(this,(Index.__proto__||Object.getPrototypeOf(Index)).apply(this,arguments));return e.config={"navigationBarTitleText":"关于我们","navigationBarTextStyle":"black","navigationBarBackgroundColor":"#FFDD00"},e.state={"otherList":[{"id":0,"title":"资质","url":Object(s.c)("appCertification")},{"id":1,"title":"服务协议","url":Object(s.c)("appProtocol")},{"id":2,"title":"隐私政策","url":Object(s.c)("appPrivacyPolicy")}]},e}return function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(Index,e),f(Index,[{"key":"toPage","value":function toPage(e){/^((ht|f)tps?):\/\/[\w\-]+(\.[\w\-]+)+([\w\-.,@?^=%&:\/~+#]*[\w\-@?^=%&\/~+#])?$/.test(e.url)?Object(l.F)(e.url):Object(l.d)(e.url)}},{"key":"render","value":function render(){var e=this,t=(Object(r.a)(),this.state.otherList),n=void 0===t?[]:t;return o.l.createElement(a.a,{"className":"about-page"},o.l.createElement(a.a,{"className":"page-scroll"},o.l.createElement(a.a,{"className":"about-info"},o.l.createElement(c.a,{"src":p.i+"/system/image/about_logo.png","className":"info-avatar"}),o.l.createElement(a.a,{"className":"info-title"},p.c),o.l.createElement(a.a,{"className":"info-content"},o.l.createElement(d.a,{"decode":!0,"space":"nbsp"},Object(s.c)(p.a)))),o.l.createElement(a.a,{"className":"about-list"},n.map((function(t){return o.l.createElement(u.a,{"key":t.id,"className":"list-item thin-border__b","onClick":e.toPage.bind(e,t)},o.l.createElement(a.a,{"className":"item-title"},t.title),o.l.createElement(a.a,{"className":"item-icon bnn-icon"},""))})),o.l.createElement(a.a,{"className":"list-item thin-border__b"},o.l.createElement(a.a,{"className":"item-title"},"版本信息"),o.l.createElement(a.a,{"className":"item-icon"}," ",p.e)),"       ",o.l.createElement(a.a,{"className":"list-item thin-border__b"},o.l.createElement(a.a,{"className":"item-title"},"版权信息"),o.l.createElement(a.a,{"className":"item-icon"},"商城 版权所有")))))}},{"key":"componentDidMount","value":function componentDidMount(){g(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidMount",this)&&g(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidMount",this).call(this)}},{"key":"componentDidShow","value":function componentDidShow(){g(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidShow",this)&&g(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidShow",this).call(this)}},{"key":"componentDidHide","value":function componentDidHide(){g(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this)&&g(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this).call(this)}}]),Index}(i.a.Component)}}]);
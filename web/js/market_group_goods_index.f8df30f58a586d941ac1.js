(window.webpackJsonp=window.webpackJsonp||[]).push([[20],{"309":function(e,t,n){"use strict";n.d(t,"b",(function(){return filterStyles})),n.d(t,"a",(function(){return filterGoodsInfo}));var o=n(308),a=n(10),r=n(305),s=n(14);function filterStyles(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},t=[null,void 0,"","px","rpx","0rpx",!1],n={};return Object.keys(e).filter((function(o){-1==t.indexOf(e[o])&&(n[o]=e[o])})),n}function filterGoodsInfo(e){switch(e.shareAmount=a.a.head(e.sharedAmount),e.saveMoney=o.a.Sub(Number(e.salePrice),Number(e.memberPrice)),e.unitName=e.unitSnap&&e.unitSnap.name||"件",e.isActivity=!!e.strategy,e.description=e.description&&Object(s.e)(e.description),e.shareAmount=e.shareAmount&&Object(s.d)(e.shareAmount),e.type){case r.b.REAL:case r.b.VIRTUAL:case r.b.SCORE:e.disabled=!(e.isOnSale&&e.stockNumber),e.disabledText=e.isOnSale?e.stockNumber?"":"已售罄":"已下架"}var t=e.strategy;switch(t&&e.saleStrategy){case r.b.SECKILL:e.seconds=t.status?t.endSeconds:t.startSeconds,e.status=t.status,e.statusName={"-1":"已结束","0":"未开始"}[t.status],e.limitNumber=t.limitNumber,e.disabled=t.status<1,e.disabledText={"-1":"已结束","0":"未开始"}[t.status]}return e}},"332":function(e,t,n){"use strict";n.d(t,"a",(function(){return m}));var o=n(1),a=n(2),r=n(40),s=n(569),i=n(404),l=(n(333),n(8)),c=n.n(l),u=n(302),p=function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}();function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var m=function(e){function ShopInfoOne(){return _classCallCheck(this,ShopInfoOne),_possibleConstructorReturn(this,(ShopInfoOne.__proto__||Object.getPrototypeOf(ShopInfoOne)).apply(this,arguments))}return function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(ShopInfoOne,e),p(ShopInfoOne,[{"key":"render","value":function render(){var e="shop-info-one",t=this.props,n=t.info,a=void 0===n?{}:n,l=t.className;return o.l.createElement(r.a,{"className":c()(e,l),"onClick":function onClick(){return Object(u.A)(a.id)}},o.l.createElement(s.a,{"src":a.logo,"className":e+"-logo"}),o.l.createElement(r.a,{"className":e+"-name t-o-e"},a.shopName),o.l.createElement(r.a,{"className":e+"-more bnn-icon d-f a-i-c"},o.l.createElement(i.a,{"className":e+"-more-text"},"进店逛逛"),o.l.createElement(i.a,{"className":e+"-more-icon"},"")))}}]),ShopInfoOne}(a.a.Component);m.options={"addGlobalClass":!0}},"333":function(e,t,n){},"334":function(e,t,n){"use strict";n.d(t,"a",(function(){return u}));var o=n(1),a=n(2),r=n(40),s=n(404),i=(n(335),n(8)),l=n.n(i),c=function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}();function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var u=function(e){function ShopInfoTwo(){return _classCallCheck(this,ShopInfoTwo),_possibleConstructorReturn(this,(ShopInfoTwo.__proto__||Object.getPrototypeOf(ShopInfoTwo)).apply(this,arguments))}return function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(ShopInfoTwo,e),c(ShopInfoTwo,[{"key":"callPhone","value":function callPhone(e){var t=e.phone;if(!t)return!1;wx.makePhoneCall({"phoneNumber":t})}},{"key":"openMap","value":function openMap(e){var t=e.lat,n=e.lng,o=e.address,a=e.shopName;if(!t||!n)return!1;wx.openLocation({"name":a,"address":o,"latitude":Number(t),"longitude":Number(n)})}},{"key":"render","value":function render(){var e="shop-info-two",t=this.props,n=t.info,a=void 0===n?{}:n,i=t.className;return o.l.createElement(r.a,{"className":l()(e,i)},o.l.createElement(r.a,{"className":e+"-info"},o.l.createElement(r.a,{"className":e+"-info-left t-o-e"},a.shopName),o.l.createElement(r.a,{"className":e+"-info-right"},o.l.createElement(s.a,{"className":e+"-icon bnn-icon","onClick":this.callPhone.bind(this,a)},""),o.l.createElement(s.a,{"className":e+"-icon bnn-icon","onClick":this.openMap.bind(this,a)},""))),o.l.createElement(r.a,{"className":e+"-li"},a.businessTime),o.l.createElement(r.a,{"className":e+"-li"},a.address))}}]),ShopInfoTwo}(a.a.Component);u.options={"addGlobalClass":!0}},"335":function(e,t,n){},"544":function(e,t,n){},"545":function(e,t,n){},"625":function(e,t,n){"use strict";n.r(t);var o=n(1),a=n(40),r=n(404),s=n(569),i=n(364),l=n(339),c=(n(544),n(315),n(8)),u=n.n(c),p=n(10),m=n(305),f=n(306),d=n(309),h=n(14),b=n(17),g=n(302),y=n(320),_=n(303),E=n(2),N=(n(545),n(20)),k=function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}();function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var v=function(e){function GroupJoin(){return _classCallCheck(this,GroupJoin),_possibleConstructorReturn(this,(GroupJoin.__proto__||Object.getPrototypeOf(GroupJoin)).apply(this,arguments))}return function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(GroupJoin,e),k(GroupJoin,[{"key":"render","value":function render(){var e,t,n,l=this.props,c=l.isOpened,p=l.onClose,m=l.goodsInfo,f=c?"enter":"leave";return c&&o.l.createElement(a.a,{"catchtouchmove":!0},o.l.createElement(a.a,{"className":"xf-overlay xf-fade-"+f,"onClick":function onClick(){return p()}}),o.l.createElement(a.a,{"className":u()("xf-popup xf-popup--center xf-popup--round group-join",(e={},t="xf-fade-"+f,n=!c,t in e?Object.defineProperty(e,t,{"value":n,"enumerable":!0,"configurable":!0,"writable":!0}):e[t]=n,e))},o.l.createElement(s.a,{"mode":"widthFix","src":N.i+"/system/image/tips_bg.png","className":"xf-bg-img"}),o.l.createElement(a.a,{"className":"group-join__head"},"有拼团进行中"),o.l.createElement(a.a,{"className":"group-join__body"},o.l.createElement(r.a,{"className":"group-join__content"},"您有一笔进行中的拼团订单!")),o.l.createElement(a.a,{"className":"group-join__foot d-f j-c-b"},o.l.createElement(i.a,{"className":"group-join__btn xf-btn xf-btn-round","onClick":function onClick(){return p()}},"取消"),o.l.createElement(i.a,{"className":"group-join__btn xf-btn xf-btn-round xf-btn-primary","onClick":function onClick(){return Object(g.k)(m)}},"前往"))))}}]),GroupJoin}(E.a.Component);v.options={"addGlobalClass":!0};var I=n(316),w=n(332),O=n(334);n.d(t,"default",(function(){return P}));var C=function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}(),S=function get(e,t,n){null===e&&(e=Function.prototype);var o=Object.getOwnPropertyDescriptor(e,t);if(void 0===o){var a=Object.getPrototypeOf(e);return null===a?void 0:get(a,t,n)}if("value"in o)return o.value;var r=o.get;return void 0!==r?r.call(n):void 0};var P=function(e){function Index(){!function goods_classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,Index);var e=function goods_possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}(this,(Index.__proto__||Object.getPrototypeOf(Index)).call(this));return e.config={"navigationStyle":"custom"},e.isJoin=!0,e}return function goods_inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(Index,e),C(Index,[{"key":"componentWillMount","value":function componentWillMount(){Object(b.q)({"fail":function fail(){Object(g.B)()}}),this.getGoodsInfo(),this.getGoodsPage(),this.getCommentPage()}},{"key":"componentDidShow","value":function componentDidShow(){var e=this;this.state.loading||this.setState({"loading":!0},(function(){e.updateGoods()}))}},{"key":"onShareAppMessage","value":function onShareAppMessage(){var e=this.state,t=e.pinkInfo,n=e.goodsInfo,o=p.a.cloneDeep(this.$router),a=o.path,r=o.params;return t&&(r.pinkId=t.id),{"title":n.name,"path":Object(b.i)(a,r)}}},{"key":"getGoodsInfo","value":function getGoodsInfo(){var e=this,t={},n=this.$router.params;n.pinkId&&(t.pinkId=n.pinkId),Object(f.b)(n.id,t).then((function(t){var n=t.data,o=n.pinkInfo,a=n.pinkConfig,r=function _objectWithoutProperties(e,t){var n={};for(var o in e)t.indexOf(o)>=0||Object.prototype.hasOwnProperty.call(e,o)&&(n[o]=e[o]);return n}(n,["pinkInfo","pinkConfig"]);if(o){for(var s=[],i=o.totalNum-o.currencyNum,l=o.partakeList.map((function(e){return e.headImg})),c=0;c<o.totalNum;c++)s.push(l[c]?l[c]:c);o.partakeList=s,o.people=i<=0?0:i}r.timestamp=Number(new Date),e.setState({"pinkInfo":o,"pinkConfig":a,"goodsInfo":Object(d.a)(r),"comboList":r.skuDetail||[],"goodsImages":r.images},(function(){e.setState({"loading":!1}),setTimeout((function(){e.getNodeLocation()}),200),a.hasPart&&a.isSamePink&&setTimeout((function(){e.setState({"showIsJoin":!0})}),1500)}))})).catch((function(e){console.log(e),setTimeout((function(){wx.navigateBack()}),800)}))}},{"key":"updateGoods","value":function updateGoods(){var e=this,t={},n=this.$router.params;n.pinkId&&(t.pinkId=n.pinkId),Object(f.b)(n.id,t).then((function(t){var n=t.data,o=n.pinkInfo,a=n.pinkConfig;if(o){for(var r=o.partakeList.map((function(e){return e.headImg})),s=[],i=0;i<o.totalNum;i++)s.push(r[i]?r[i]:i);o.partakeList=s;var l=o.totalNum-o.currencyNum;o.people=l<=0?0:l}e.setState({"pinkInfo":o,"pinkConfig":a,"loading":!1},(function(){setTimeout((function(){e.getNodeLocation()}),200),e.isJoin&&a.hasPart&&a.isSamePink&&setTimeout((function(){e.setState({"showIsJoin":!0})}),1500)}))}))}},{"key":"onCloseJoin","value":function onCloseJoin(){this.isJoin=!1,this.setState({"showIsJoin":!1})}},{"key":"setShowSelect","value":function setShowSelect(e){this.setState({"showSelect":e,"replacePrice":"group"===e?"salePrice":"originPrice"})}},{"key":"onConfirm","value":function onConfirm(e,t,n){var o=this.$router.params;this.setState({"showSelect":!1}),"group"===e?t.type===m.b.VIRTUAL?Object(g.f)({"id":t.productId,"skuId":t.skuId,"number":n,"shopId":t.shopId,"isPink":!0,"pinkId":o.pinkId}):Object(g.e)({"isPink":!0,"pinkId":o.pinkId,"cartList":[{"id":t.productId,"skuId":t.skuId,"number":n}]}):t.type===m.b.VIRTUAL?Object(g.f)({"id":t.productId,"skuId":t.skuId,"number":n,"shopId":t.shopId,"fullPay":1}):Object(g.e)({"fullPay":1,"cartList":[{"id":t.productId,"skuId":t.skuId,"number":n}]})}},{"key":"render","value":function render(){var e=this,t=this.state,n=t.opacity,c=t.nodeKey,p=t.nodeList,f=t.goodsInfo,d=t.goodsList,b=t.showSelect,y=t.showShareImg,E=t.shareImg,N=t.commentRes,k=t.loading,C=t.replacePrice,S=t.pinkInfo,P=t.pinkConfig,j=t.showIsJoin,x=f.salesReport&&f.salesReport.map((function(e){return e.userInfo&&e.userInfo.headImg})),T=(100/p.length).toFixed(2);return o.l.createElement(a.a,{"className":"goods-page"},k&&o.l.createElement(_.E,null),o.l.createElement(_.s,{"opacity":n},o.l.createElement(a.a,{"className":"node-list"},p.map((function(t,n){return o.l.createElement(a.a,{"key":t.id,"className":u()("node-item f-g-5",c===n&&"node-item__active"),"onClick":e.onSwitchNode.bind(e,t,n)},t.name)})),o.l.createElement(a.a,{"className":"under-line","style":{"left":"calc("+c*T+"% + ("+T+"% / 2))"}}))),o.l.createElement(a.a,{"className":"goods-scroll"},o.l.createElement(a.a,{"id":"goods-info","className":"goods-card"},o.l.createElement(_.o,{"images":f.images,"video":{"url":f.video,"poster":f.videoCoverImg}}),o.l.createElement(a.a,{"className":"goods-head"},o.l.createElement(a.a,{"className":"d-f a-i-c j-c-b"},o.l.createElement(a.a,{"className":"price d-f a-i-b"},o.l.createElement(a.a,{"className":"sale-price bnn-number"},o.l.createElement(r.a,{"className":"price-unit"},"￥"),f.salePrice),f.originPrice>f.salePrice&&o.l.createElement(a.a,{"className":"origin-price bnn-number m-l-10"},"￥",f.originPrice)),o.l.createElement(a.a,{"className":"user-like f-g-5"},o.l.createElement(r.a,{"className":"like-icon bnn-icon m-r-10"},""),f.storeCount,"人喜欢")),o.l.createElement(a.a,{"className":"goods-name"},f.name),o.l.createElement(a.a,{"className":"stock-box d-f j-c-b"},o.l.createElement(a.a,{"className":""},"已兑换",f.salesNumber,f.unitName),o.l.createElement(a.a,{"className":""},"剩余库存",f.stockNumber,f.unitName)),o.l.createElement(a.a,{"className":"sales-record d-f j-c-b"},o.l.createElement(_.b,{"list":x,"showMore":!0}),o.l.createElement(a.a,{"className":"invite-btn d-f a-i-c bnn-number","hoverClass":"hover-class--btn","onClick":this.showShareImg.bind(this)},f.shareAmount?"分享赚 "+Object(h.d)(f.shareAmount)+" 元":"分享给好友")),o.l.createElement(a.a,{"className":"tip-list d-f f-w-w"},f.tips&&f.tips.map((function(e,t){return o.l.createElement(a.a,{"key":t,"className":"tip-item d-f a-i-c"},o.l.createElement(a.a,{"className":"bnn-icon tip-icon"},""),o.l.createElement(a.a,{"className":"tip-text l-h-o"},e))}))))),S&&o.l.createElement(a.a,{"id":"goods-group","className":"goods-card groups-goods f-g-5 f-d-c"},o.l.createElement(a.a,{"className":"groups-title"},"拼团中，邀请好友一起参与"),o.l.createElement(a.a,{"className":"groups-number d-f a-i-c"},"还差 ",S.people," 人，距结束",o.l.createElement(_.z,{"seconds":S.leftTime,"onTimeUp":this.setState.bind(this,{"pinkInfo":null})})),o.l.createElement(a.a,{"className":"groups-user f-g-5 f-w-w"},S.partakeList.map((function(e,t){return o.l.createElement(a.a,{"key":t,"className":"user-info"},o.l.createElement(s.a,{"src":e,"className":"user-avatar"}))}))),o.l.createElement(i.a,{"openType":"share","className":"groups-btn f-g-5","hoverClass":"hover-class--btn"},"邀请好友参团")),f.type===m.b.VIRTUAL&&o.l.createElement(a.a,{"className":"goods-card goods-card__body shop-card"},o.l.createElement(a.a,{"className":"goods-card__head"},o.l.createElement(_.R,null,"商家信息")),o.l.createElement(w.a,{"info":f.shopSnap,"className":"shop-card__head"}),o.l.createElement(a.a,{"className":"thin-border__b"}),o.l.createElement(O.a,{"info":f.shopSnap,"className":"shop-card__foot"})),N.list&&N.list.length&&o.l.createElement(a.a,{"id":"goods-comment","className":"goods-card goods-card__body comment-card"},o.l.createElement(a.a,{"className":"goods-card__head"},o.l.createElement(_.R,null,"用户评论")),o.l.createElement(a.a,{"className":"comment-list"},N.list.map((function(e){return o.l.createElement(_.e,{"key":e.id,"info":e,"className":"comment-item"})})),o.l.createElement(a.a,{"className":"see-more f-g-5","onClick":function onClick(){return Object(g.h)(f.id)}},"查看更多好评（",N.totalCount,"）"))),o.l.createElement(a.a,{"className":"goods-body"},f.description&&o.l.createElement(a.a,{"id":"goods-desc","className":"goods-card"},o.l.createElement(a.a,{"className":"goods-card__head"},o.l.createElement(_.R,null,"商品详情")),o.l.createElement(a.a,{"className":"rich-text-wrap goods-card__body"},o.l.createElement(l.a,{"nodes":Object(h.e)(f.description)}))),o.l.createElement(a.a,{"id":"goods-hot","className":"goods-card"},o.l.createElement(a.a,{"className":"goods-card__head"},o.l.createElement(_.R,null,"更多美好生活")),o.l.createElement(_.v,{"list":d}))),o.l.createElement(a.a,{"className":"goods-foot--height"})),o.l.createElement(I.a,{"menus":["home","share","user"],"onShare":this.showShareImg.bind(this)},P.hasPart&&o.l.createElement(i.a,{"openType":"share","className":"goods-foot__btns-btn xf-btn xf-btn-primary"},"邀请好友来参团"),!P.hasPart&&!f.disabled&&o.l.createElement(i.a,{"className":"goods-foot__btns-btn goods-foot__cart-btn xf-btn","onClick":this.setShowSelect.bind(this,"buy")},"单独购买"),!P.hasPart&&o.l.createElement(i.a,{"disabled":f.disabled,"className":"goods-foot__btns-btn xf-btn xf-btn-primary","onClick":this.setShowSelect.bind(this,"group")},f.disabledText||P.people+"人团")),o.l.createElement(v,{"isOpened":j,"goodsInfo":f,"onClose":this.onCloseJoin.bind(this)}),o.l.createElement(_.w,{"goods":f,"isOpened":b,"replacePrice":C,"onClose":this.setState.bind(this,{"showSelect":!1}),"onConfirm":this.onConfirm.bind(this,b)}),o.l.createElement(_.N,{"isOpened":y,"width":500,"height":890,"image":E,"onClose":this.showShareImg.bind(this,!1)}))}},{"key":"componentDidMount","value":function componentDidMount(){S(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidMount",this)&&S(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidMount",this).call(this)}},{"key":"componentDidHide","value":function componentDidHide(){S(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this)&&S(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this).call(this)}}]),Index}(y.a)}}]);
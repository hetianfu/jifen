(window.webpackJsonp=window.webpackJsonp||[]).push([[16],{"307":function(e,t,n){"use strict";n.d(t,"x",(function(){return getUserInfo})),n.d(t,"w",(function(){return getUserCommissions})),n.d(t,"f",(function(){return asyncWechatInfo})),n.d(t,"q",(function(){return getMemberCount})),n.d(t,"r",(function(){return getMemberPermanent})),n.d(t,"m",(function(){return getCartPage})),n.d(t,"b",(function(){return addCart})),n.d(t,"l",(function(){return getCartInfo})),n.d(t,"i",(function(){return delBatchCart})),n.d(t,"C",(function(){return updateCart})),n.d(t,"D",(function(){return updateCartNumber})),n.d(t,"k",(function(){return getAddressList})),n.d(t,"a",(function(){return addAddress})),n.d(t,"B",(function(){return updateAddress})),n.d(t,"j",(function(){return getAddressInfo})),n.d(t,"A",(function(){return setDefaultAddress})),n.d(t,"h",(function(){return delAddressInfo})),n.d(t,"z",(function(){return getWalletPage})),n.d(t,"E",(function(){return userCharges})),n.d(t,"g",(function(){return buyMember})),n.d(t,"t",(function(){return getScoreConfig})),n.d(t,"v",(function(){return getSignScore})),n.d(t,"d",(function(){return addSignScore})),n.d(t,"u",(function(){return getScorePage})),n.d(t,"y",(function(){return getUserScore})),n.d(t,"s",(function(){return getMyCouponPage})),n.d(t,"n",(function(){return getCouponPage})),n.d(t,"c",(function(){return addCoupon})),n.d(t,"e",(function(){return applyDrawToWallet})),n.d(t,"p",(function(){return getGisciplePage})),n.d(t,"o",(function(){return getGiscipleDetailPage}));var a=n(9);n(5),Object.assign;function getUserInfo(){return a.a.get("/users/get-info",{},{"isAuth":!1})}function getUserCommissions(){return a.a.get("/user-commissions/get",{},{"isAuth":!1})}function asyncWechatInfo(e){return a.a.put("/users/async-wechat-info",e)}function getMemberCount(){return a.a.get("/users/count-vip-pay")}function getMemberPermanent(){return a.a.get("/users/permanent-vip-pay")}function getCartPage(e){return a.a.get("/users/get-shop-cart-page",{"params":e},{"isAuth":!1})}function addCart(e){return a.a.post("/users/add-shop-cart",e)}function getCartInfo(e){return a.a.get("/users/get-shop-cart-by-id/"+e)}function delBatchCart(e){return a.a.delete("/users/del-shop-cart",e)}function updateCart(e){return a.a.put("/users/update-shop-cart-by-id",e)}function updateCartNumber(e){return a.a.put("/users/update-shop-cart-number",e)}function getAddressList(){return a.a.get("/users/get-address-list")}function addAddress(e){return a.a.post("/users/add-address",e)}function updateAddress(e){return a.a.put("/users/update-address-by-id",e)}function getAddressInfo(e){return a.a.get("/users/get-address-by-id/"+e)}function setDefaultAddress(e){return a.a.put("/users/set-default-address",e)}function delAddressInfo(e){return a.a.delete("/users/del-address-by-id/"+e)}function getWalletPage(e){return a.a.request({"url":"/users/get-wallet-detail-page","data":e})}function userCharges(e){return a.a.post("/user-charges/charge",e)}function buyMember(e){return a.a.post("/user-charges/buy-vip",e)}function getScoreConfig(){return a.a.get("/user-scores/get-score-config")}function getSignScore(){return a.a.get("/user-scores/get-sign-score")}function addSignScore(){return a.a.post("/user-scores/sign-score")}function getScorePage(e){return a.a.get("/user-scores/get-detail-page",{"params":e})}function getUserScore(){return a.a.get("/user-scores/user-score")}function getMyCouponPage(e){return a.a.get("/users/get-my-coupon-page",{"params":e})}function getCouponPage(e){return a.a.get("/users/get-coupon-page",{"params":e})}function addCoupon(e){return a.a.post("/users/add-coupon",e)}function applyDrawToWallet(e){return a.a.post("/users/apply-draw-to-wallet",e)}function getGisciplePage(e){return a.a.get("/users/get-disciple-page",{"params":e})}function getGiscipleDetailPage(e){return a.a.get("/user-commissions/get-disciple-detail-page",{"params":e})}},"310":function(e,t,n){"use strict";n.d(t,"b",(function(){return filterStyles})),n.d(t,"a",(function(){return filterGoodsInfo}));var a=n(311),r=n(6),o=n(17),s=n(2);function filterStyles(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},t=[null,void 0,"","px","rpx","0rpx",!1],n={};return Object.keys(e).filter((function(a){-1==t.indexOf(e[a])&&(n[a]=e[a])})),n}function filterGoodsInfo(e){switch(e.shareAmount=r.a.head(e.sharedAmount),e.saveMoney=a.a.Sub(Number(e.salePrice),Number(e.memberPrice)),e.unitName=e.unitSnap&&e.unitSnap.name||"件",e.isActivity=!!e.strategy,e.description=e.description&&Object(s.e)(e.description),e.shareAmount=e.shareAmount&&Object(s.d)(e.shareAmount),e.type){case o.c.REAL:case o.c.VIRTUAL:case o.c.SCORE:e.disabled=!(e.isOnSale&&e.stockNumber),e.disabledText=e.isOnSale?e.stockNumber?"":"已售罄":"已下架"}var t=e.strategy;switch(t&&e.saleStrategy){case o.c.SECKILL:e.seconds=t.status?t.endSeconds:t.startSeconds,e.status=t.status,e.statusName={"-1":"已结束","0":"未开始"}[t.status],e.limitNumber=t.limitNumber,e.disabled=t.status<1,e.disabledText={"-1":"已结束","0":"未开始"}[t.status]}return e}},"328":function(e,t,n){"use strict";n.d(t,"a",(function(){return goodsShareImg})),n.d(t,"b",(function(){return shareInfoById}));var a=n(9),r=n(2),o=n(17);function goodsShareImg(e){var t=e.saleStrategy===o.b.NORMAL?e.type:e.saleStrategy,n={"REAL":o.i.REAL_GOODS,"PINK":o.i.PINK_GOODS,"SCORE":o.i.SCORE_GOODS,"VIRTUAL":o.i.VIRTUAL_GOODS,"SECKILL":o.i.SECKILL_GOODS};return a.a.post("/products/create-share-img",{"title":e.name,"price":e.salePrice,"keyType":n[t],"shareImg":e.shareImg,"firstImg":Object(r.q)(e.images),"productId":e.id})}function shareInfoById(e){return a.a.get("/user-shares/get-by-id/"+e)}},"560":function(e,t,n){},"629":function(e,t,n){"use strict";n.r(t),n.d(t,"default",(function(){return w}));var a=n(129),r=n(1),o=n(43),s=n(614),c=n(613),i=n(407),u=n(422),l=(n(560),n(321),n(12)),d=n.n(l),m=n(8),g=n(310),f=n(307),p=n(328),h=n(312),b=n(313),y=n(2),S=n(68),I=n(353),E=n.n(I),N=n(337),O=n(306),_=n(322),v=function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var a=t[n];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(e,a.key,a)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}(),C=function get(e,t,n){null===e&&(e=Function.prototype);var a=Object.getOwnPropertyDescriptor(e,t);if(void 0===a){var r=Object.getPrototypeOf(e);return null===r?void 0:get(r,t,n)}if("value"in a)return a.value;var o=a.get;return void 0!==o?o.call(n):void 0};function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var w=function(e){function Index(){return _classCallCheck(this,Index),_possibleConstructorReturn(this,(Index.__proto__||Object.getPrototypeOf(Index)).apply(this,arguments))}return function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(Index,e),v(Index,[{"key":"componentWillMount","value":function componentWillMount(){this.getGoodsInfo(),this.getPage(),this.getCommentPage()}},{"key":"onShareAppMessage","value":function onShareAppMessage(){return{"title":this.state.goodsInfo.name,"path":Object(m.m)()}}},{"key":"onPageScroll","value":function onPageScroll(e){var t=e.scrollTop,n=this.state,a=n.opacity,r=n.nodeKey,o=n.nodeList,s=(t/200).toFixed(2),c=s>=1?1:s;if(a!==c&&this.setState({"opacity":c}),this.rolling)return!1;var i=o.filter((function(e){return t>=e.top})).length-1;r!==i&&this.setState({"nodeKey":i})}},{"key":"onSwitchNode","value":function onSwitchNode(e,t){var n=this;this.rolling=!0,this.setState({"nodeKey":t},(function(){wx.pageScrollTo({"scrollTop":e.top,"duration":300})})),setTimeout((function(){n.rolling=!1}),400)}},{"key":"getPage","value":function getPage(){var e=this;Object(h.b)({"page":1,"limit":6}).then((function(t){e.setState({"goodsList":t.data.list})}))}},{"key":"getGoodsInfo","value":function getGoodsInfo(){var e=this,t=this.$router.params;Object(h.c)(t.id).then((function(t){var n=t.data;n.timestamp=Number(new Date),e.setState({"goodsInfo":Object(g.a)(n),"comboList":n.skuDetail||[],"goodsImages":n.images},(function(){e.setState({"loadingStatus":!1}),setTimeout((function(){e.getNodeLocation()}),200)}))})).catch((function(e){console.log(e),setTimeout((function(){wx.navigateBack()}),800)}))}},{"key":"getCommentPage","value":function getCommentPage(){var e=this,t=this.state.id;Object(b.d)({"page":1,"limit":2,"productId":t}).then((function(t){e.setState({"commentRes":t.data})}))}},{"key":"setOpenShareImg","value":function setOpenShareImg(e){var t=this;e?Object(m.y)({"success":function success(){t.getGoodsShareImage(),t.setState({"showShareImg":!0})},"fail":function fail(){Object(S.E)()}}):this.setState({"showShareImg":!1})}},{"key":"setShowSelect","value":function setShowSelect(e){this.setState({"showSelect":e})}},{"key":"setShowMore","value":function setShowMore(e){this.setState({"showMore":e})}},{"key":"getGoodsShareImage","value":function getGoodsShareImage(){var e=this,t=this.state.goodsInfo;Object(p.a)(t).then((function(t){e.setState({"shareImg":t.data})}))}},{"key":"onConfirm","value":function onConfirm(e,t,n){"cart"===e?Object(f.b)({"productId":t.productId,"skuId":t.skuId,"number":n}).then((function(){wx.showToast({"title":"添加成功"})})):Object(S.f)({"cartList":[{"id":t.productId,"skuId":t.skuId,"number":n}]}),this.setState({"showSelect":!1})}},{"key":"render","value":function render(){var e=this,t=this.state,n=t.opacity,a=t.nodeKey,l=t.nodeList,m=t.userInfo,g=t.goodsInfo,f=t.goodsList,p=t.showSelect,h=t.showShareImg,b=t.shareImg,I=t.commentRes,N=t.loadingStatus,v=(100/l.length).toFixed(2);return r.l.createElement(o.a,{"className":"goods-page"},N&&r.l.createElement(O.J,null),r.l.createElement(O.x,{"opacity":n},r.l.createElement(o.a,{"className":"node-list"},l.map((function(t,n){return r.l.createElement(o.a,{"key":t.id,"className":d()("node-item f-g-5",a===n&&"node-item__active"),"onClick":e.onSwitchNode.bind(e,t,n)},t.name)})),r.l.createElement(o.a,{"className":"under-line","style":{"left":"calc("+a*v+"% + ("+v+"% / 2))"}}))),r.l.createElement(o.a,{"className":"goods-scroll"},r.l.createElement(o.a,{"id":"goods-info","className":"goods-card"},r.l.createElement(O.r,{"images":g.images,"video":{"url":g.video,"poster":g.videoCoverImg}}),r.l.createElement(o.a,{"className":"goods-head"},r.l.createElement(o.a,{"className":"d-f a-i-c j-c-b"},r.l.createElement(o.a,{"className":"price d-f a-i-b"},r.l.createElement(o.a,{"className":"sale-price bnn-number"},r.l.createElement(s.a,{"className":"price-unit"},"￥"),g.salePrice),g.originPrice>g.salePrice&&r.l.createElement(o.a,{"className":"origin-price bnn-number m-l-10"},"￥",g.originPrice)),r.l.createElement(o.a,{"className":"user-like f-g-5"},r.l.createElement(s.a,{"className":"like-icon bnn-icon m-r-10"},""),g.storeCount,"人喜欢")),r.l.createElement(o.a,{"className":"goods-name"},g.name),r.l.createElement(o.a,{"className":"stock-box d-f j-c-b"},r.l.createElement(o.a,{"className":""},"已兑换",g.salesNumber,g.unitName),r.l.createElement(o.a,{"className":""},"剩余库存",g.stockNumber,g.unitName)),Object(y.z)(g)&&r.l.createElement(o.a,{"className":"member-box d-f a-i-c j-c-b","onClick":function onClick(){return Object(S.r)()}},r.l.createElement(o.a,{"className":"member-left d-f a-i-c"},r.l.createElement(c.a,{"src":E.a,"className":"left-icon"}),r.l.createElement(o.a,{"className":"left-text"},"会员价",r.l.createElement(s.a,{"className":"member-price"},"￥",g.memberPrice),g.saveMoney?"省￥"+g.saveMoney:"")),r.l.createElement(o.a,{"className":"member-right d-f a-i-c"},r.l.createElement(o.a,{"className":"right-text"},m.isVip?"":"立即开通"),r.l.createElement(o.a,{"className":"right-icon bnn-icon"},""))),r.l.createElement(o.a,{"className":"sales-record d-f j-c-b"},r.l.createElement(O.b,{"list":g.salesReport&&g.salesReport.map((function(e){return e.userInfo&&e.userInfo.headImg})),"showMore":!0}),r.l.createElement(o.a,{"className":"invite-btn d-f a-i-c bnn-number","hoverClass":"hover-class--btn","onClick":this.setOpenShareImg.bind(this)},g.shareAmount?"分享赚 "+Object(y.d)(g.shareAmount)+" 元":"分享给好友")),r.l.createElement(o.a,{"className":"tip-list d-f f-w-w"},g.tips&&g.tips.map((function(e,t){return r.l.createElement(o.a,{"key":t,"className":"tip-item d-f a-i-c"},r.l.createElement(o.a,{"className":"bnn-icon tip-icon"},""),r.l.createElement(o.a,{"className":"tip-text l-h-o"},e))}))))),I.list&&I.list.length&&r.l.createElement(o.a,{"id":"goods-comment","className":"goods-card goods-card__body comment-card"},r.l.createElement(o.a,{"className":"goods-card__head"},r.l.createElement(O.W,null,"用户评论")),r.l.createElement(o.a,{"className":"comment-list"},I.list.map((function(e){return r.l.createElement(O.e,{"key":e.id,"info":e,"className":"comment-item"})})),r.l.createElement(o.a,{"className":"see-more f-g-5","onClick":function onClick(){return Object(S.i)(g.id)}},"查看更多好评（",I.totalCount,"）"))),r.l.createElement(o.a,{"className":"goods-body"},g.description&&r.l.createElement(o.a,{"id":"goods-desc","className":"goods-card"},r.l.createElement(o.a,{"className":"goods-card__head"},r.l.createElement(O.W,null,"商品详情")),r.l.createElement(o.a,{"className":"rich-text-wrap goods-card__body"},r.l.createElement(i.a,{"nodes":Object(y.e)(g.description)}))),g.buyDescription&&r.l.createElement(o.a,{"className":"goods-card"},r.l.createElement(o.a,{"className":"goods-card__head"},r.l.createElement(O.W,null,"购买须知")),r.l.createElement(o.a,{"className":"rich-text-wrap goods-card__body"},r.l.createElement(i.a,{"nodes":Object(y.e)(g.buyDescription)}))),r.l.createElement(o.a,{"id":"goods-hot","className":"goods-card"},r.l.createElement(o.a,{"className":"goods-card__head"},r.l.createElement(O.W,null,"更多美好生活")),r.l.createElement(O.A,{"list":f}))),r.l.createElement(o.a,{"className":"goods-foot--height"})),r.l.createElement(_.a,{"menus":["home","share","cart"],"onShare":this.setOpenShareImg.bind(this)},!g.disabled&&r.l.createElement(u.a,{"className":"goods-foot__btns-btn goods-foot__cart-btn xf-btn","onClick":this.setState.bind(this,{"showSelect":"cart"})},"加入购物车"),r.l.createElement(u.a,{"disabled":g.disabled,"className":"goods-foot__btns-btn xf-btn xf-btn-primary","onClick":this.setState.bind(this,{"showSelect":"buy"})},g.disabledText||"立即购买")),r.l.createElement(O.B,{"goods":g,"isOpened":p,"onClose":this.setState.bind(this,{"showSelect":!1}),"onConfirm":this.onConfirm.bind(this,p)}),r.l.createElement(O.S,{"isOpened":h,"width":500,"height":890,"image":b,"onClose":this.setState.bind(this,{"showShareImg":!1})}),r.l.createElement(O.c,null))}},{"key":"componentDidMount","value":function componentDidMount(){C(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidMount",this)&&C(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidMount",this).call(this)}},{"key":"componentDidShow","value":function componentDidShow(){C(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidShow",this)&&C(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidShow",this).call(this),this._offPageScroll=Object(a.a)({"callback":this.onPageScroll,"ctx":this})}},{"key":"componentDidHide","value":function componentDidHide(){C(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this)&&C(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this).call(this),this._offPageScroll&&this._offPageScroll()}}]),Index}(N.a)}}]);
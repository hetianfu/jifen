(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-32f7ef80"],{"1e7d":function(t,e,a){},"73df":function(t,e,a){"use strict";a.d(e,"b",(function(){return u})),a.d(e,"h",(function(){return m})),a.d(e,"c",(function(){return O})),a.d(e,"d",(function(){return v})),a.d(e,"f",(function(){return _})),a.d(e,"g",(function(){return g})),a.d(e,"a",(function(){return I})),a.d(e,"e",(function(){return A}));a("4ec9"),a("a9e3"),a("b64b"),a("d3b7"),a("25f0"),a("3ca3"),a("ddb0");var n=a("b85c"),r=a("2909"),s=a("53ca"),c=a("d4ec"),i=a("bee2"),o=a("2ef0"),u=function(){function t(){Object(c["a"])(this,t)}return Object(i["a"])(t,[{key:"setAttributes",value:function(t){var e=this,a=new Map,n=this.attributes();for(var r in n)a.set(n[r],r);for(var s in t)if(t.hasOwnProperty(s)&&a.get(s)){var c=this.getType;switch(c(e[s])){case"boolean":e[s]=!!t[s];break;case"string":e[s]=t[s]?t[s]:e[s];break;case"object":e[s]="object"===c(t[s])?t[s]:e[s];break;case"array":e[s]="array"===c(t[s])?t[s]:e[s];break;case"number":e[s]=t[s]?Number(t[s]):e[s];break;default:e[s]=t[s]}}return e}},{key:"toObject",value:function(t){var e={},a=this;for(var n in t){var r=Object(s["a"])(t[n]);if("string"===r)e[n]=a[t[n]];else if("function"===r){var c=t[n].apply();null!==c&&(e[n]=c)}}return e}},{key:"fields",value:function(){var t=this.attributes();return Object(o["zipObject"])(t,t)}},{key:"attributes",value:function(){var t=this,e=[];return e.push.apply(e,Object(r["a"])(Object.keys(t))),e}},{key:"delFields",value:function(t,e){var a,r=Object(n["a"])(e);try{for(r.s();!(a=r.n()).done;){var s=a.value;delete t[s]}}catch(c){r.e(c)}finally{r.f()}}},{key:"getType",value:function(t){var e=Object.prototype.toString.apply(t);switch(e){case"[object String]":e="string";break;case"[object Number]":e="number";break;case"[object Boolean]":e="boolean";break;case"[object Undefined]":e="undefined";break;case"[object Null]":e="null";break;case"[object Object]":e="object";break;case"[object Array]":e="array";break;case"[object Function]":e="function";break}return e}}]),t}(),l=(a("b0c0"),a("45eb")),p=a("7e84"),d=a("262e"),b=a("2caf"),f=function(t){Object(d["a"])(a,t);var e=Object(b["a"])(a);function a(){var t;return Object(c["a"])(this,a),t=e.call(this),t.id="",t.name="",t.employeeNumber="",t.telephone="",t.sex=0,t.email="",t.departmentIds=[],t.updatedAt=0,t.userSnap={},t.mpOpenId="",t.mpSendMsg=0,t.openId="",t.status=0,t.roleList=[],t.isAdmin=0,t.shopId="",t.account="",t.shopInfo="",t.password="",t}return Object(i["a"])(a,[{key:"renderData",value:function(){var t=this,e=Object(l["a"])(Object(p["a"])(a.prototype),"fields",this).call(this);return e["updateAt"]=function(){return 1e3*t.updateAt},Object(l["a"])(Object(p["a"])(a.prototype),"toObject",this).call(this,e)}}]),a}(u),m=f,h=function(t){Object(d["a"])(a,t);var e=Object(b["a"])(a);function a(){var t;return Object(c["a"])(this,a),t=e.call(this),t.id="",t.orderId="",t.status="",t.payAmount=0,t.updateTime=0,t.productType="",t.cartSnap=[],t.userSnap={},t.addressSnap={},t.cooperateShopSnap={},t.batchNumber="",t.trackingNumber="",t.trackingName="",t.addressSnap={},t.sharingAmount=0,t.createTime=0,t.incomeAmount=0,t.remark="",t.distribute="",t.productType="",t.expressName="",t.expressNo="",t.freightAmount="",t.couponInfo={},t.deductScore=0,t.connectSnap={},t.cooperateShopAddressSnap={},t.sendSnap={},t.sendType=0,t}return Object(i["a"])(a,[{key:"renderData",value:function(){var t=this,e=Object(l["a"])(Object(p["a"])(a.prototype),"fields",this).call(this),n=this.productType;return"POST"===n&&(e["info"]=function(){return Object(o["get"])(t,"addressSnap")}),"GROUP_BUY"===n&&(e["info"]=function(){return Object(o["get"])(t,"cooperateShopSnap")}),Object(l["a"])(Object(p["a"])(a.prototype),"delFields",this).call(this,e,["cooperateShopSnap"]),Object(l["a"])(Object(p["a"])(a.prototype),"toObject",this).call(this,e)}},{key:"renderInfo",value:function(){var t=this,e=Object(l["a"])(Object(p["a"])(a.prototype),"fields",this).call(this),n=this.productType;return"POST"===n&&(e["consigneeName"]=function(){return Object(o["get"])(t,"addressSnap.name")},e["consigneeStreet"]=function(){return Object(o["get"])(t,"addressSnap.street")},e["consigneeRoom"]=function(){return Object(o["get"])(t,"addressSnap.room")},e["consigneeTelephone"]=function(){return Object(o["get"])(t,"addressSnap.telephone")}),"GROUP_BUY"===n&&(e["shopName"]=function(){return Object(o["get"])(t,"cooperateShopSnap.shopName")},e["shopLogo"]=function(){return Object(o["get"])(t,"cooperateShopSnap.logo")},e["shopAddress"]=function(){return Object(o["get"])(t,"cooperateShopSnap.address")}),Object(l["a"])(Object(p["a"])(a.prototype),"delFields",this).call(this,e,[]),Object(l["a"])(Object(p["a"])(a.prototype),"toObject",this).call(this,e)}}]),a}(u),O=h,j=function(t){Object(d["a"])(a,t);var e=Object(b["a"])(a);function a(){var t;return Object(c["a"])(this,a),t=e.call(this),t.id="",t.name="",t.type="",t.number=0,t.amount=0,t.coverImg="",t.unitSnap={},t.categorySnap={},t.skuDetail=[],t.specSnap="",t}return Object(i["a"])(a,[{key:"renderData",value:function(){var t=this,e=Object(l["a"])(Object(p["a"])(a.prototype),"fields",this).call(this);return e["unitName"]=function(){return Object(o["get"])(t,"unitSnap.name")},e["categoryName"]=function(){return Object(o["get"])(t,"categorySnap.name")},Object(l["a"])(Object(p["a"])(a.prototype),"delFields",this).call(this,e,["unitSnap","categorySnap"]),Object(l["a"])(Object(p["a"])(a.prototype),"toObject",this).call(this,e)}}]),a}(u),v=j,y=function(t){Object(d["a"])(a,t);var e=Object(b["a"])(a);function a(){var t;return Object(c["a"])(this,a),t=e.call(this),t.id="",t.title="",t.couponType="",t.status=0,t.leftNumber=0,t.usedNumber=0,t.totalNumber=0,t.userSnap={},t.updateTime=0,t.orderId="",t.createdAt=0,t.expireTime=0,t}return Object(i["a"])(a,[{key:"renderData",value:function(){var t=Object(l["a"])(Object(p["a"])(a.prototype),"fields",this).call(this);return Object(l["a"])(Object(p["a"])(a.prototype),"toObject",this).call(this,t)}}]),a}(u),_=y,S=function(t){Object(d["a"])(a,t);var e=Object(b["a"])(a);function a(){var t;return Object(c["a"])(this,a),t=e.call(this),t.id="",t.title="",t.couponType="",t.number=0,t.userSnap={},t.checkEmployeeSnap="",t.checkEmployeeName="",t.createdAt=0,t.shopBillAmount=0,t.title="",t}return Object(i["a"])(a,[{key:"renderData",value:function(){var t=Object(l["a"])(Object(p["a"])(a.prototype),"fields",this).call(this);return Object(l["a"])(Object(p["a"])(a.prototype),"toObject",this).call(this,t)}}]),a}(u),g=S,k=function(t){Object(d["a"])(a,t);var e=Object(b["a"])(a);function a(){var t;return Object(c["a"])(this,a),t=e.call(this),t.id=0,t.name="",t.path="",t.parentId=0,t.meta={},t.show=0,t.title="",t.icon="",t.component="",t.createTime=0,t.isMenu=0,t.key="",t.redirect="",t.servicePath="",t.requestMethod="",t.sort=0,t}return Object(i["a"])(a,[{key:"MenuList",value:function(){var t=this,e=Object(l["a"])(Object(p["a"])(a.prototype),"fields",this).call(this);return e["redirect"]=function(){return""===t.redirect?void 0:t.redirect},Object(l["a"])(Object(p["a"])(a.prototype),"toObject",this).call(this,e)}},{key:"renderData",value:function(){var t=Object(l["a"])(Object(p["a"])(a.prototype),"fields",this).call(this);return Object(l["a"])(Object(p["a"])(a.prototype),"toObject",this).call(this,t)}},{key:"exportData",value:function(){var t=this,e=Object(l["a"])(Object(p["a"])(a.prototype),"fields",this).call(this);return e["meta"]=function(){return{icon:t.icon,title:t.title,show:t.show,key:t.key}},e["show"]=function(){},e["title"]=function(){},e["icon"]=function(){},e["key"]=function(){},e["createTime"]=function(){},Object(l["a"])(Object(p["a"])(a.prototype),"toObject",this).call(this,e)}}]),a}(u),I=k,T=function(t){Object(d["a"])(a,t);var e=Object(b["a"])(a);function a(){var t;return Object(c["a"])(this,a),t=e.call(this),t.id=0,t.parentId=0,t.sort=0,t.status=0,t.title="",t.createdAt=0,t}return Object(i["a"])(a,[{key:"RoleList",value:function(){var t=this,e=Object(l["a"])(Object(p["a"])(a.prototype),"fields",this).call(this);return e["createdAt"]=function(){return t.createdAt},Object(l["a"])(Object(p["a"])(a.prototype),"toObject",this).call(this,e)}},{key:"exportData",value:function(){var t=Object(l["a"])(Object(p["a"])(a.prototype),"fields",this).call(this);return Object(l["a"])(Object(p["a"])(a.prototype),"toObject",this).call(this,t)}},{key:"renderData",value:function(){var t=Object(l["a"])(Object(p["a"])(a.prototype),"fields",this).call(this);return t["createdAt"]=function(){},Object(l["a"])(Object(p["a"])(a.prototype),"toObject",this).call(this,t)}}]),a}(u),A=T},"7b7c":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("page-view",{staticClass:"platform-order-info",attrs:{title:"单号："+t.data.id,logo:t.userInfo.headImg}},[a("detail-list",{staticClass:"detail-layout",attrs:{slot:"headerContent",size:"smsall",col:2},slot:"headerContent"},[a("detail-list-item",{attrs:{term:"用户名称"}},[t._v(t._s(t.userSnap.name))]),a("detail-list-item",{attrs:{term:"联系电话"}},[t._v(t._s(t.userSnap.telephone))]),1!==t.data.distribute?a("detail-list-item",{attrs:{term:"收货地址"}},[t._v(t._s(t.userSnap.city)+t._s(t.userSnap.detail))]):a("detail-list-item",{attrs:{term:"收货地址"}},[t._v(t._s(t.data.cooperateShopAddressSnap.shopName))])],1),a("a-row",{staticClass:"status-list",attrs:{slot:"extra"},slot:"extra"},[a("a-col",{attrs:{xs:12,sm:12}},[a("div",{staticClass:"text"},[t._v("状态")]),a("div",{staticClass:"heading"},t._l(t.ORDER_INFO_STATUS,(function(e,n){return t.data.status===e.value?a("span",{key:n,style:{color:e.color}},[t._v(t._s(e.name))]):t._e()})),0)]),a("a-col",{attrs:{xs:12,sm:12}},[a("div",{staticClass:"text"},[t._v("订单金额")]),a("div",{staticClass:"heading"},[t._v(t._s(t._f("moneyFormat")(t.data.payAmount)))])])],1),a("a-card",{staticClass:"margin-top-24",attrs:{bordered:!1,title:"订单信息"}},[a("detail-list",{attrs:{col:1,title:"购物车信息"}},[a("a-table",{staticClass:"product-table",attrs:{rowKey:function(t,e){return e},columns:t.columns,dataSource:t.cartSnap,pagination:!1},scopedSlots:t._u([{key:"coverImg",fn:function(t,e){return[a("img",{staticClass:"coverImg-class",attrs:{src:e.coverImg}})]}},{key:"name",fn:function(e,n){return[a("span",{staticClass:"margin-right-10"},[a("a-popover",{attrs:{placement:"topLeft"}},[a("template",{slot:"content"},[t._v(t._s(e))]),a("span",[t._v(t._s(t._f("textOverflowEllipsis")(e,0,30)))])],2)],1),0!==n.skuDetail.length?a("a-popover",{attrs:{placement:"top"}},[a("div",{staticClass:"tooltip-sku",attrs:{slot:"content"},slot:"content"},t._l(n.skuDetail,(function(e){return a("a-tag",{key:e.id},[t._v(t._s(e.name))])})),1),a("a-icon",{attrs:{type:"info-circle"}})],1):t._e()]}},{key:"type",fn:function(e){return a("div",{},[a("s-table-type",{attrs:{text:e,list:t.PLATFORM_PRODUCT_TYPE}})],1)}},{key:"cartSnap",fn:function(e){return a("div",{},[e.specSnap?a("a-tag",[t._v(t._s(e.specSnap))]):t._e()],1)}},{key:"number",fn:function(e,n){return a("div",{},[t._v(t._s(e)+" "+t._s(n.unitName))])}},{key:"amount",fn:function(e){return a("div",{},[t._v(t._s(t._f("moneyFormat")(e))+" ")])}}])},[a("template",{slot:"footer"},[a("div",{staticClass:"table-footer display-flex justify-content-end"},[a("div",{staticClass:"product-price"},[t.data.couponInfo&&t.data.couponInfo.amount?a("p",[a("span",{staticClass:"price-inner product-freight"},[t._v("优惠券使用：")]),a("span",{staticClass:"product-amount freight-amount"},[t._v(t._s(t._f("moneyFormat")(t.data.couponInfo.amount))+"（"+t._s(t.data.couponInfo.title)+"）")])]):t._e(),t.data.deductScore?a("p",[a("span",{staticClass:"price-inner product-freight"},[t._v("积分抵扣：")]),a("span",{staticClass:"product-amount freight-amount"},[t._v(t._s(t.data.deductScore))])]):t._e(),a("p",[a("span",{staticClass:"price-inner product-freight"},[t._v("运费：")]),a("span",{staticClass:"product-amount freight-amount"},[t._v(t._s(t._f("moneyFormat")(t.data.freightAmount)))])]),a("p",[a("span",{staticClass:"price-inner real-payment"},[t._v("订单合计：")]),a("span",{staticClass:"product-amount payment-amount"},[t._v(t._s(t._f("moneyFormat")(t.data.payAmount)))])]),""!==t.remark?a("p",[a("span",{staticClass:"order-remark"},[t._v("订单备注：")]),a("span",[t._v(t._s(t.remark))])]):t._e()])])])],2)],1),"sending"!==t.data.status&&"closed"!==t.data.status||0!==t.data.sendType?t._e():a("detail-list",{staticClass:"margin-top-16",attrs:{title:"物流信息",col:2}},[a("detail-list-item",{attrs:{term:"快递公司"}},[t._v(t._s(t.data.expressName))]),a("detail-list-item",{attrs:{term:"快递单号"}},[a("span",[t._v(t._s(t.data.expressNo))])])],1),"sending"===t.data.status&&1===t.data.sendType?a("detail-list",{staticClass:"margin-top-16",attrs:{title:"送货人信息",col:2}},[a("detail-list-item",{attrs:{term:"送货人"}},[t._v(t._s(t.data.sendSnap.name))]),a("detail-list-item",{attrs:{term:"送货人电话"}},[t._v(t._s(t.data.sendSnap.phone))])],1):t._e()],1),a("a-modal",{attrs:{title:"物流信息",footer:null},model:{value:t.logistics.visible,callback:function(e){t.$set(t.logistics,"visible",e)},expression:"logistics.visible"}},[a("p",[t._v("快递公司："+t._s(t.data.expressName))]),a("p",[t._v("快递单号："+t._s(t.data.expressNo))]),a("p",[t._v("暂无物流信息")])])],1)},r=[],s=(a("d81d"),a("f121")),c=a("73df"),i=a("2f38"),o=a("ac0d"),u=a("680a"),l=a("2af9"),p=a("c16f"),d=a("17eb"),b=p["a"].Item,f={components:{Template:d["a"],PageView:u["c"],DetailList:p["a"],DetailListItem:b,STableType:l["s"],STableName:l["q"]},mixins:[o["c"]],data:function(){return{id:null,logistics:{visible:!1},PLATFORM_PRODUCT_TYPE:s["q"],ORDER_INFO_STATUS:s["m"],data:{},userSnap:{},columns:[{title:"商品图片",dataIndex:"coverImg",scopedSlots:{customRender:"coverImg"},width:90},{title:"商品名称",dataIndex:"name",scopedSlots:{customRender:"name"}},{title:"数量",dataIndex:"number",scopedSlots:{customRender:"number"},width:90},{title:"规格",scopedSlots:{customRender:"cartSnap"},width:"25%"},{title:"支付金额",dataIndex:"amount",scopedSlots:{customRender:"amount"},width:"25%"}],cartSnap:[],userInfo:{headImg:""},remark:""}},created:function(){var t=this.$route.params.id;this.getOrderInfo(t)},methods:{getOrderInfo:function(t){var e=this;Object(i["f"])(t).then((function(t){var a=new c["c"],n=a.setAttributes(t.data).renderInfo(),r=n.cartSnap.map((function(t){var e=new c["d"];return e.setAttributes(t).renderData()}));e.data=n,e.remark=n.remark,e.cartSnap=r,e.userInfo=n.userSnap,1===n.distribute?e.userSnap=n.connectSnap:e.userSnap=n.addressSnap})).catch((function(){e.$message.warning("获取订单数据失败"),e.$router.go(-1)}))},handleLogistics:function(){this.logistics.visible=!0}}},m=f,h=(a("805b"),a("2877")),O=Object(h["a"])(m,n,r,!1,null,"688d848c",null);e["default"]=O.exports},"805b":function(t,e,a){"use strict";a("1e7d")},c16f:function(t,e,a){"use strict";var n,r,s=a("fa43"),c=s["a"],i=c,o=a("2877"),u=Object(o["a"])(i,n,r,!1,null,null,null);e["a"]=u.exports}}]);
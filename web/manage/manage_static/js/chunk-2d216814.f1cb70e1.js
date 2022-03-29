(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d216814"],{c36d:function(t,e,a){"use strict";a.r(e);var r=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("a-card",{staticClass:"stock-detail",attrs:{bordered:!1}},[a("div",[a("a-card",{attrs:{bordered:!1}},[a("a-row",[a("a-col",{attrs:{sm:8,xs:24}},[a("head-info",{attrs:{title:"成本价",content:t._f("moneyFormat")(t.header.costAmount),bordered:!0}})],1),a("a-col",{attrs:{sm:8,xs:24}},[a("head-info",{attrs:{title:"销售价",content:t._f("moneyFormat")(t.header.salesAmount),bordered:!0}})],1),a("a-col",{attrs:{sm:8,xs:24}},[a("head-info",{attrs:{title:"销售数量",content:(Math.abs(t.header.stockNumber)||0)+" 个",bordered:!1}})],1)],1)],1),a("a-row",{staticClass:"margin-bottom-24",attrs:{gutter:10}},[t.nameSta?a("a-col",{attrs:{span:6}},[a("fuzzy-product",{model:{value:t.queryParam.productId,callback:function(e){t.$set(t.queryParam,"productId",e)},expression:"queryParam.productId"}})],1):t._e(),a("a-col",{attrs:{span:4}},[a("a-select",{staticClass:"width-percent-100",attrs:{allowClear:"",placeholder:"出入库类型"},model:{value:t.queryParam.type,callback:function(e){t.$set(t.queryParam,"type",e)},expression:"queryParam.type"}},t._l(t.stockType,(function(e,r){return a("a-select-option",{key:r,attrs:{value:e.value}},[t._v(t._s(e.name))])})),1)],1),a("a-col",{attrs:{span:5}},[a("a-range-picker",{staticClass:"margin-right-10",on:{change:t.handlePicker}})],1),a("a-col",{attrs:{span:8}},[a("a-button",{attrs:{type:"primary"},on:{click:function(e){return t.$refs.table.refresh(!0)}}},[t._v("查询")]),a("a-button",{staticClass:"margin-left-10",on:{click:function(){return t.queryParam={}}}},[t._v("重置")])],1)],1),a("s-table",{ref:"table",attrs:{rowKey:"id",columns:t.columns,data:t.loadData,pagination:t.pagination},scopedSlots:t._u([{key:"userSnap",fn:function(e){return e?a("span",{},[a("s-table-user",{attrs:{info:{nickName:e.nickName,headImg:e.headImg},maxNum:8}})],1):t._e()}},{key:"stockNumber",fn:function(e){return a("span",{},[t._v(t._s(Math.abs(e)))])}},{key:"specSnap",fn:function(e){return a("span",{},[e.specSnap?a("a-tag",[t._v(t._s(e.specSnap))]):t._e()],1)}},{key:"costAmount",fn:function(e){return a("span",{},[t._v(t._s(t._f("moneyFormat")(e)))])}},{key:"type",fn:function(e){return a("span",{},t._l(t.stockType,(function(r){return e===r.value?a("a-tag",{key:r.value,attrs:{color:r.color}},[t._v(t._s(r.name))]):t._e()})),1)}},{key:"updatedAt",fn:function(e){return a("span",{},[t._v(t._s(t._f("parseTime")(e)))])}},{key:"operator",fn:function(e){return a("span",{},[t._v(t._s(e))])}},{key:"remark",fn:function(e){return a("span",{},[a("a-popover",{attrs:{placement:"topLeft"}},[a("template",{slot:"content"},[t._v(t._s(e))]),a("span",[t._v(t._s(t._f("textOverflowEllipsis")(e,0,5)))])],2)],1)}},{key:"action",fn:function(e,r){return[10===r.type?a("a",{on:{click:function(e){return t.handleOrderDetail(r)}}},[t._v("查看订单")]):t._e()]}}],null,!0)})],1)])],1)},n=[],o=a("5530"),s=a("2af9"),c=a("6a14"),d=a("f121"),u={name:"ProductStockDetail",components:{STable:s["p"],STableType:s["s"],HeadInfo:s["k"],STableUser:s["t"],FuzzyProduct:s["g"]},data:function(){var t=this;return{isInit:!1,PLATFORM_PRODUCT_TYPE:d["q"],stockType:[{name:"销售出库",value:10,color:"#edc988"},{name:"取消入库",value:3,color:"#f1e189"}],id:null,queryParam:{geCreatedAt:"",leCreatedAt:""},productList:[],pagination:{pageSize:10,current:1},columns:[{title:"用户信息",dataIndex:"userSnap",scopedSlots:{customRender:"userSnap"}},{title:"商品名称",dataIndex:"productName",ellipsis:!0},{title:"数量",dataIndex:"stockNumber",scopedSlots:{customRender:"stockNumber"}},{title:"规格",scopedSlots:{customRender:"specSnap"}},{title:"成本合计",dataIndex:"costAmount",scopedSlots:{customRender:"costAmount"}},{title:"类型",dataIndex:"type",scopedSlots:{customRender:"type"}},{title:"操作时间",dataIndex:"updatedAt",scopedSlots:{customRender:"updatedAt"},sorter:!0},{title:"操作",dataIndex:"action",scopedSlots:{customRender:"action"},width:130}],loadData:function(e){var a=e.pageNo,r=e.pageSize,n=e.sortOrder,s=void 0===n?"descend":n,d=t.id,u=t.queryParam,i=u.productId,l=u.type,p=u.geCreatedAt,m=u.leCreatedAt,f={page:a,limit:r,skuId:d,productId:i||void 0,type:l,geCreatedAt:p||void 0,leCreatedAt:m||void 0};return f.orders={updated_at:"ascend"===s?"asc":"desc"},Object(c["c"])(f).then((function(e){t.isInit=!0;var n=e.data,s=n.list,c=n.header,d=n.totalCount;return t.header=Object(o["a"])({},c),{pageNo:a,pageSize:r,data:s,totalCount:d}}))},header:{},categoryOptions:[],nameSta:!0}},created:function(){var t=this.$route.params,e=t.id,a=t.productId;this.nameSta=!e&&!a,this.queryParam.productId=a||void 0,this.id=e},activated:function(){this.isInit&&this.$refs.table.refresh()},beforeRouteLeave:function(t,e,a){t.meta.keepAlive=!0,a()},methods:{handleOrderDetail:function(t){var e="",a=e.id;a=t.orderId,this.$router.push({name:"orderInfo",params:{id:a}})},handlePicker:function(t,e){this.queryParam["geCreatedAt"]=e[0],this.queryParam["leCreatedAt"]=e[1]}}},i=u,l=a("2877"),p=Object(l["a"])(i,r,n,!1,null,null,null);e["default"]=p.exports}}]);
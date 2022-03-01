(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0e0cf500"],{5024:function(t,e,n){"use strict";n("8bf5")},"722b":function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("a-card",[n("a-button",{staticClass:"margin-bottom-16",attrs:{type:"primary"},on:{click:t.addShopHandle}},[n("a-icon",{attrs:{type:"plus"}}),t._v("添加门店")],1),n("s-table",{ref:"table",attrs:{rowKey:function(t,e){return e},columns:t.columns,data:t.loadData,pagination:t.pagination},scopedSlots:t._u([{key:"logo",fn:function(e){return[e?n("s-table-avatar",{attrs:{src:e}}):t._e()]}},{key:"shopName",fn:function(t){return[n("s-table-text",{attrs:{text:t}})]}},{key:"detail",fn:function(t){return[n("s-table-text",{attrs:{text:t}})]}},{key:"status",fn:function(e){return[1===e?n("a-badge",{attrs:{status:"processing",text:"正式营业"}}):t._e(),0===e?n("a-badge",{attrs:{status:"warning",text:"未上线"}}):t._e(),-1===e?n("a-badge",{attrs:{status:"default",text:"已关闭"}}):t._e()]}},{key:"action",fn:function(e,a){return[n("a",{on:{click:function(e){return t.onChangeEdit(a)}}},[t._v("编辑")]),n("a-divider",{attrs:{type:"vertical"}}),n("a",{staticClass:"color-red",on:{click:function(e){return t.onChangeDel(a)}}},[t._v("删除")])]}}])})],1)],1)},s=[],i=n("ed08"),o=n("365c"),r=n("7488"),c=n("2af9"),l=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"s-table-text"},[n("a-popover",{attrs:{placement:"topLeft",arrowPointAtCenter:"",overlayStyle:t.textStyle}},[n("template",{slot:"content"},[t._v(t._s(t.text))]),n("span",[t._v(t._s(t._f("textOverflowEllipsis")(t.text,0,10)))])],2)],1)},u=[],d={props:{text:{required:!0}},watch:{text:function(t){this.text=t}},data:function(){return{textStyle:{"max-width":"400px"}}}},p=d,f=n("2877"),h=Object(f["a"])(p,l,u,!1,null,"590c6604",null),m=h.exports,b=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"s-table-avatar cursor-pointer",on:{click:t.parseImg}},[n("div",{staticClass:"image-curtain"},[t._m(0),n("a-avatar",{attrs:{src:t.src,size:38}})],1),n("a-modal",{attrs:{footer:null,width:"800px"},on:{click:t.handleImgCancel},model:{value:t.visible,callback:function(e){t.visible=e},expression:"visible"}},[n("img",{staticStyle:{width:"100%"},attrs:{src:t.src}})])],1)},v=[function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"curtain"},[n("div",{staticClass:"eye"},[n("div",{staticClass:"super-icon-font font-size-fourteen"})])])}],g={props:{src:{type:String,default:""},name:{type:String,default:""}},data:function(){return{visible:!1}},methods:{parseImg:function(){this.visible=!0},handleImgCancel:function(){this.visible=!1}}},x=g,S=(n("5024"),Object(f["a"])(x,b,v,!1,null,null,null)),_=S.exports,y={name:"StoreManage",components:{STable:c["p"],STableText:m,STableAvatar:_},data:function(){var t=this,e=Object(i["i"])();return{isInit:!1,headers:e,config:{title:"",id:"",form:null,visible:!1,status:null},locate:{visible:!1},action:o["b"].uploadImgs.addShopImage,addVisible:!1,pagination:{pageSize:10,current:1},columns:[{title:"门店Logo",dataIndex:"logo",scopedSlots:{customRender:"logo"},width:100},{title:"门店名称",dataIndex:"shopName",scopedSlots:{customRender:"shopName"},width:200},{title:"店铺简介",dataIndex:"detail",scopedSlots:{customRender:"detail"}},{title:"门店地址",dataIndex:"address",scopedSlots:{customRender:"address"},ellipsis:!0},{title:"联系电话",dataIndex:"phone",scopedSlots:{customRender:"phone"},width:150},{title:"门店状态",dataIndex:"status",scopedSlots:{customRender:"status"}},{title:"营业时间",dataIndex:"businessTime",scopedSlots:{customRender:"businessTime"}},{title:"操作",scopedSlots:{customRender:"action"},width:130}],loadData:function(e){var n=e.pageNo,a=e.pageSize,s={page:n,limit:a};return Object(r["d"])(s).then((function(e){t.isInit=!0;var s=e.data,i=s.list,o=s.totalCount;return{pageNo:n,pageSize:a,data:i,totalCount:o}}))}}},created:function(){},activated:function(){this.isInit&&this.$refs.table.refresh()},beforeRouteLeave:function(t,e,n){t.meta.keepAlive=!0,n()},methods:{addShopHandle:function(){this.$router.push({name:"StoreAdd"})},onChangeEdit:function(t){this.$router.push({name:"StoreEdit",params:{id:t.id}})},onChangeDel:function(t){var e=this.$refs.table,n=this.$notification.success;this.$confirm({title:"警告",content:"是否删除 [ ".concat(t.shopName," ] 门店"),onOk:function(){Object(r["b"])(t.id).then((function(t){n({message:"成功",description:"删除成功"}),e.refresh(!0)}))}})}}},I=y,w=Object(f["a"])(I,a,s,!1,null,"7a791f89",null);e["default"]=w.exports},7488:function(t,e,n){"use strict";n.d(e,"d",(function(){return o})),n.d(e,"a",(function(){return r})),n.d(e,"e",(function(){return c})),n.d(e,"b",(function(){return l})),n.d(e,"c",(function(){return u}));n("99af");var a=n("365c"),s=n("b775"),i=a["b"].shopMag;function o(t){return Object(s["b"])({url:i.getShopPage,method:"get",params:t})}function r(t){return Object(s["b"])({url:i.addshop,method:"post",data:t})}function c(t){return Object(s["b"])({url:i.updateShopById,method:"patch",data:t})}function l(t){return Object(s["b"])({url:"".concat(i.deleteShopById).concat(t),method:"delete"})}function u(t){return Object(s["b"])({url:i.getShopById+t,method:"get"})}},"8bf5":function(t,e,n){}}]);
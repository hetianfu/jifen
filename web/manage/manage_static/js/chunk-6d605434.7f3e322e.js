(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-6d605434"],{2010:function(e,t,a){"use strict";a("a545b")},2430:function(e,t,a){"use strict";a("9572")},"42ce":function(e,t,a){"use strict";a.d(t,"a",(function(){return i})),a.d(t,"c",(function(){return o})),a.d(t,"b",(function(){return c}));var s=a("365c"),n=a("b775"),r=s["b"].refund;function i(e){return Object(n["b"])({url:r.refundGetPage,method:"get",params:e})}function o(e){return Object(n["b"])({url:r.updateForbid+e.id,method:"patch",data:e})}function c(e){return Object(n["b"])({url:r.updateApprove+e.id,method:"patch",data:e})}},"6dfa":function(e,t,a){"use strict";a.d(t,"d",(function(){return i})),a.d(t,"c",(function(){return o})),a.d(t,"a",(function(){return c})),a.d(t,"e",(function(){return l})),a.d(t,"b",(function(){return u}));a("99af");var s=a("365c"),n=a("b775"),r=s["b"].channel;function i(e){return Object(n["b"])({url:r.channelGetPage,method:"get",params:e})}function o(e){return Object(n["b"])({url:r.channelGetList,method:"get",params:e})}function c(e){return Object(n["b"])({url:r.channelAdd,method:"post",data:e})}function l(e){return Object(n["b"])({url:r.channelUpdateById,method:"patch",data:e})}function u(e){return Object(n["b"])({url:"".concat(r.channelDelById).concat(e),method:"delete"})}},9572:function(e,t,a){},a545b:function(e,t,a){},b87f:function(e,t,a){"use strict";a.r(t);var s=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("page-view",{attrs:{title:"店铺订单列表"}},[a("div",{staticClass:"order-list"},[a("a-card",{staticClass:"margin-bottom-16"},[a("a-form",[a("a-row",{staticClass:"margin-bottom-16"},[a("a-col",{attrs:{span:2}},[e._v("订单状态：")]),a("a-col",{attrs:{span:22}},[a("checkable-tag",{attrs:{list:e.ORDER_INFO_STATUS,reset:e.tagReset},model:{value:e.listQuery.status,callback:function(t){e.$set(e.listQuery,"status",t)},expression:"listQuery.status"}})],1)],1),a("a-row",{staticClass:"margin-bottom-16"},[a("a-col",{attrs:{span:2}},[e._v("支付方式：")]),a("a-col",{attrs:{span:22}},[a("checkable-tag",{attrs:{list:e.ORDER_INFO_PAY_TYPE,reset:e.tagReset},model:{value:e.listQuery.payType,callback:function(t){e.$set(e.listQuery,"payType",t)},expression:"listQuery.payType"}})],1)],1),a("a-row",{staticClass:"margin-bottom-16"},[a("a-col",{attrs:{span:2}},[e._v("创建时间：")]),a("a-col",{attrs:{span:22}},[e._l(e.ORDER_INFO_QUERY_DAY,(function(t,s){return a("a-checkable-tag",{key:s,staticClass:"checkable-tag-class",on:{change:function(a){return e.onChangeQueryDay(t,s)}},model:{value:t.isSelected,callback:function(a){e.$set(t,"isSelected",a)},expression:"item.isSelected"}},[e._v(" "+e._s(t.name)+" ")])})),a("a-checkable-tag",{staticClass:"checkable-tag-class",on:{change:e.onChangeTime}},[e._v("自定义")]),e.timeStu?a("a-range-picker",{attrs:{format:"YYYY-MM-DD HH:mm","show-time":{format:"HH:mm"}},on:{change:e.onChangeCreateTime},model:{value:e.listQuery.timeRange,callback:function(t){e.$set(e.listQuery,"timeRange",t)},expression:"listQuery.timeRange"}}):e._e()],2)],1),a("a-row",{staticClass:"margin-bottom-16"},[a("a-col",{attrs:{span:2}},[e._v("渠道来源：")]),a("a-col",{attrs:{span:5}},[a("a-select",{staticClass:"width-percent-100",attrs:{"show-search":"",placeholder:"来源","option-filter-prop":"children","allow-clear":""},model:{value:e.listQuery.sourceId,callback:function(t){e.$set(e.listQuery,"sourceId",t)},expression:"listQuery.sourceId"}},e._l(e.channelList,(function(t){return a("a-select-option",{key:t.id,attrs:{value:t.id}},[e._v(e._s(t.name))])})),1)],1)],1),a("a-row",{staticClass:"margin-bottom-16"},[a("a-col",{attrs:{span:2}},[e._v("供应商：")]),a("a-col",{attrs:{span:5}},[a("a-select",{staticClass:"width-percent-100",attrs:{"show-search":"",placeholder:"请选择供应商","option-filter-prop":"children","allow-clear":""},model:{value:e.listQuery.supplyName,callback:function(t){e.$set(e.listQuery,"supplyName",t)},expression:"listQuery.supplyName"}},e._l(e.supplyList,(function(t){return a("a-select-option",{key:t,attrs:{title:t}},[e._v(e._s(t))])})),1)],1)],1)],1),a("div",[a("a-row",{attrs:{gutter:16}},[a("a-col",{attrs:{span:8}},[a("a-input",{attrs:{"addon-before":"订单查询：",placeholder:"请输入姓名、电话、订单编号","allow-clear":""},model:{value:e.listQuery.searchWord,callback:function(t){e.$set(e.listQuery,"searchWord",t)},expression:"listQuery.searchWord"}})],1),a("a-col",{attrs:{span:8}},[a("a-input",{attrs:{"addon-before":"其他方式：",placeholder:"请输入商品名称","allow-clear":""},model:{value:e.listQuery.productName,callback:function(t){e.$set(e.listQuery,"productName",t)},expression:"listQuery.productName"}})],1),a("a-col",{attrs:{span:8}},[a("a-button",{staticClass:"margin-left-16",attrs:{type:"primary"},on:{click:e.handleRefsTable}},[e._v("查询")]),a("a-button",{staticClass:"margin-left-8",on:{click:e.handleReset}},[e._v("重置")]),a("a-button",{staticClass:"margin-left-8",attrs:{icon:"folder-open"},on:{click:e.getExport}},[e._v("导出")]),a("a-button",{staticClass:"margin-left-8",attrs:{icon:"import"},on:{click:e.onUploadFiles}},[e._v("导入物流 "),a("input",{ref:"uploadFiles",staticClass:"display-none",attrs:{type:"file"},on:{change:e.addFiles}})])],1)],1)],1)],1),a("div",[a("a-row",{attrs:{gutter:16}},[e._l(e.ORDER_INFO_SUM_STATUS,(function(t){return a("a-col",{key:t.value,attrs:{span:4}},[a("a-card",{staticClass:"margin-bottom-16 width-percent-100",attrs:{title:t.name}},[a("div",{staticClass:"amount-slot-class",attrs:{slot:"extra"},slot:"extra"},[e._v(e._s(t.text))]),a("span",{staticClass:"amount-span-class"},[e._v(e._s(e._f("moneyFormat")(t.amount||0)))])])],1)})),e._l(e.ORDER_INFO_SUM_TYPE,(function(t,s){return a("a-col",{key:s,attrs:{span:4}},[a("a-card",{staticClass:"margin-bottom-16",staticStyle:{width:"100%"},attrs:{title:t.name}},[a("div",{staticClass:"amount-slot-class",attrs:{slot:"extra"},slot:"extra"},[e._v(e._s(t.text))]),a("span",{staticClass:"amount-span-class"},[e._v(e._s(e._f("moneyFormat")(t.amount||0)))])])],1)}))],2)],1),a("a-card",{attrs:{bordered:!1}},[a("div",{staticClass:"margin-bottom-10"},[a("a-button",{attrs:{type:"primary"},on:{click:e.handleBatchReminder}},[e._v("批量催单"),e.selectedRowKeys.length?a("span",[e._v("（"+e._s(e.selectedRowKeys.length)+"）")]):e._e()]),a("a-button",{staticClass:"margin-left-8",attrs:{type:"danger"},on:{click:e.handleBatchDel}},[e._v("批量删除"),e.selectedRowKeys.length?a("span",[e._v("（"+e._s(e.selectedRowKeys.length)+"）")]):e._e()])],1),a("div",[a("s-table",{ref:"table",staticClass:"order-table",attrs:{rowKey:function(e,t){return e.id},columns:e.columns,data:e.loadData,rowSelection:{columnWidth:50,selectedRowKeys:e.selectedRowKeys,onChange:e.onSelectChange},pagination:e.pagination,scroll:{x:1400}},scopedSlots:e._u([{key:"userSnap",fn:function(t){return[t?a("div",{staticClass:"user-info display-flex align-items-center"},[a("div",{staticClass:"user-head-img cursor-pointer"},[t.headImg?a("a-avatar",{staticClass:"margin-right-16",attrs:{size:32,src:t.headImg}}):a("a-avatar",{staticClass:"margin-right-16",attrs:{size:32,icon:"user"}})],1),a("div",{staticClass:"user-nick-name display-flex flex-direction-column cursor-pointer"},[a("a-popover",{attrs:{placement:"topLeft"}},[a("div",{attrs:{slot:"content"},slot:"content"},[e._v(e._s(t.nickName))]),a("span",[e._v(e._s(e._f("textOverflowEllipsis")(t.nickName,0,5)))])])],1)]):e._e()]}},{key:"id",fn:function(t){return a("span",{},[e._v(e._s(t))])}},{key:"payType",fn:function(t){return a("span",{},e._l(e.ORDER_INFO_PAY_TYPE,(function(s,n){return t===s.value?a("span",{key:n},[e._v(e._s(s.name))]):e._e()})),0)}},{key:"payAmount",fn:function(t){return a("span",{},[e._v(e._s(e._f("moneyFormat")(t.payAmount)))])}},{key:"createdAt",fn:function(t){return a("span",{},[e._v(e._s(e._f("parseTime")(t.createdAt,"YYYY-MM-DD HH:mm")))])}},{key:"remark",fn:function(t){return a("span",{},[a("a-popover",{attrs:{placement:"topLeft"}},[a("template",{slot:"content"},[e._v(e._s(t))]),a("span",[e._v(e._s(e._f("textOverflowEllipsis")(t,0,5)))])],2)],1)}},{key:"cartSnap",fn:function(t,s){return e._l(s.cartSnap,(function(t,s){return a("div",{key:s,staticClass:"cartSnapClass margin-bottom-4 width-percent-100"},[a("img",{staticClass:"cartsnapImg",attrs:{src:t.images[0]}}),a("div",{staticClass:"margin-left-8 cartSnapName"},[e._v(e._s(t.name)),t.specSnap?a("span",[e._v(" | "+e._s(t.specSnap))]):e._e()]),a("span",{staticClass:"cartPrice"},[e._v("￥"+e._s(t.salePrice)+"×"+e._s(t.number))])])}))}},{key:"status",fn:function(t){return a("span",{},[a("s-table-type",{attrs:{text:t,list:e.ORDER_INFO_STATUS}})],1)}},{key:"distribute",fn:function(t,s){return a("span",{},[1===t?a("span",[e._v("自提")]):a("div",e._l(e.ORDER_SEND_TYPE,(function(t,n){return s.sendType===t.value?a("span",{key:n},[e._v(e._s(t.name))]):e._e()})),0)])}},{key:"action",fn:function(t,s){return[a("div",{staticClass:"table-width-100"},["unsend"===s.status?a("a",{staticClass:"color-red",on:{click:function(t){return e.handleSend(s)}}},[e._v("发货")]):e._e(),"refunding"===s.status?a("a",{staticClass:"color-red",on:{click:function(t){return e.handleRefunding(s)}}},[e._v("审核")]):e._e(),"sending"===s.status?a("a",{staticClass:"color-red",on:{click:function(t){return e.handleSending(s)}}},[e._v("收货")]):e._e(),"uncheck"===s.status?a("a",{staticClass:"color-red",on:{click:function(t){return e.handleCheck(s)}}},[e._v("核销")]):e._e(),"unsend"===s.status||"refunding"===s.status||"sending"===s.status||"uncheck"===s.status?a("a-divider",{attrs:{type:"vertical"}}):e._e(),a("a-dropdown",{attrs:{trigger:["click"]}},[a("a",[e._v("操作 "),a("a-icon",{attrs:{type:"down"}})],1),a("a-menu",{attrs:{slot:"overlay"},slot:"overlay"},[a("a-menu-item",{on:{click:function(t){return e.handleOrderInfo(s)}}},[a("a",[e._v("详情")])]),"unsend"===s.status||"unreply"===s.status||"uncheck"===s.status||"sending"===s.status?a("a-menu-item",{on:{click:function(t){return e.onChangeApprove(s)}}},[a("a",[e._v("立即退款")])]):e._e(),a("a-menu-item",{on:{click:function(t){return e.handlePrint(s)}}},[a("a",[e._v("打印订单")])]),"cancelled"===s.status||"after"===s.payType?a("a-menu-item",{on:{click:function(t){return e.handleDel(s)}}},[a("a",{staticClass:"color-red"},[e._v("删除订单")])]):e._e()],1)],1)],1)]}}])})],1)]),a("a-modal",{attrs:{title:"发货信息",width:"550px"},on:{ok:e.onChangeSend},model:{value:e.send.visible,callback:function(t){e.$set(e.send,"visible",t)},expression:"send.visible"}},[a("a-form",{attrs:{form:e.send.form,"label-col":{span:4},"wrapper-col":{span:18}}},[a("a-form-item",{attrs:{label:"收货人："}},[e._v(" "+e._s(e.addressSnap.name)+" ")]),a("a-form-item",{attrs:{label:"联系电话："}},[e._v(" "+e._s(e.addressSnap.telephone)+" ")]),a("a-form-item",{attrs:{label:"详细地址："}},[e._v(" "+e._s(e.addressSnap.city)+e._s(e.addressSnap.detail||"")+" ")]),a("a-form-item",{attrs:{label:"订单ID"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["id"],expression:"['id']"}],attrs:{disabled:""}})],1),a("a-form-item",{attrs:{label:"选择类型"}},[a("a-radio-group",{directives:[{name:"decorator",rawName:"v-decorator",value:["sendType",{initialValue:0}],expression:"['sendType',{ initialValue: 0 }]"}],on:{change:e.handleSendType}},e._l(e.ORDER_SEND_TYPE,(function(t,s){return a("a-radio",{key:s,attrs:{value:t.value}},[e._v(" "+e._s(t.name)+" ")])})),1)],1),0===e.sendType?a("a-form-item",{attrs:{label:"快递公司"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["expressName",{rules:[{required:!0,message:"请输入快递公司"}]}],expression:"['expressName', { rules: [{ required: true, message: '请输入快递公司' }]}]"}]})],1):e._e(),0===e.sendType?a("a-form-item",{attrs:{label:"快递单号"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["expressNo",{rules:[{required:!0,message:"请输入快递单号"}]}],expression:"['expressNo', { rules: [{ required: true, message: '请输入快递单号' }]}]"}]})],1):e._e(),1===e.sendType?a("a-form-item",{attrs:{label:"送货人姓名"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["sendSnap.name",{rules:[{required:!0,message:"请输入送货人姓名"}]}],expression:"['sendSnap.name', { rules: [{ required: true, message: '请输入送货人姓名' }]}]"}]})],1):e._e(),1===e.sendType?a("a-form-item",{attrs:{label:"送货人电话"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["sendSnap.phone",{rules:[{required:!0,message:"请输入送货人电话"}]}],expression:"['sendSnap.phone', { rules: [{ required: true, message: '请输入送货人电话' }]}]"}]})],1):e._e()],1)],1),a("a-modal",{attrs:{title:"退款"},on:{ok:e.handleApprove},model:{value:e.chargeBack.visible,callback:function(t){e.$set(e.chargeBack,"visible",t)},expression:"chargeBack.visible"}},[a("a-form",{attrs:{form:e.chargeBack.form,"label-col":{span:5},"wrapper-col":{span:18}}},[a("a-form-item",{attrs:{label:"订单ID"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["id"],expression:"['id']"}],attrs:{disabled:""}})],1),a("a-form-item",{staticClass:"display-none",attrs:{label:"原始状态"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["status"],expression:"['status']"}]})],1),a("a-form-item",{attrs:{label:"订单金额"}},[a("a-input-number",{directives:[{name:"decorator",rawName:"v-decorator",value:["payAmount"],expression:"['payAmount']"}],attrs:{disabled:""}})],1),a("a-form-item",{attrs:{label:"退款金额",help:"注：退款金额不能大于订单金额"}},[a("a-input-number",{directives:[{name:"decorator",rawName:"v-decorator",value:["refundAmount",{initialValue:e.chargeBack.payAmount}],expression:"['refundAmount', {initialValue: chargeBack.payAmount}]"}],attrs:{min:0,max:e.chargeBack.payAmount,placeholder:"0.00"}})],1),a("a-form-item",{attrs:{label:"状态"}},[a("a-checkbox",{directives:[{name:"decorator",rawName:"v-decorator",value:["originStatus"],expression:"['originStatus']"}]},[e._v("保持订单原状态")])],1),a("a-form-item",{attrs:{label:"no"===e.chargeBack.status?"禁止退款原因":"备注"}},[a("a-textarea",{directives:[{name:"decorator",rawName:"v-decorator",value:["failMsg",{rules:[{required:"no"===e.chargeBack.status,message:"请输入禁止退款原因"}]}],expression:"['failMsg', { rules: [{ required: chargeBack.status==='no'?true:false, message: '请输入禁止退款原因' }] }]"}],attrs:{placeholder:"no"===e.chargeBack.status?"请填写禁止退款原因":"备注"}})],1)],1)],1)],1),e.infoShow?a("order-info",{attrs:{show:e.infoShow,info:e.orderRow},on:{cancel:e.cancelInfo}}):e._e()],1)},n=[],r=(a("99af"),a("a15b"),a("d81d"),a("b0c0"),a("a9e3"),a("d3b7"),a("15fd")),i=a("b85c"),o=(a("96cf"),a("1da1")),c=a("2909"),l=a("5530"),u=a("2ef0"),d=a("365c"),p=a("b775");function f(e){return Object(p["b"])({url:d["b"].splitOrders.completeOrderInfo,method:"patch",data:e})}var m=a("2f38"),h=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticStyle:{"box-sizing":"border-box"}},e._l(e.tagList,(function(t,s){return a("span",{key:s,staticClass:"tag-item",attrs:{value:t.value}},[a("a-checkable-tag",{staticClass:"checkable-tag-class",on:{change:function(a){return e.onChangeStatus(t,s)}},model:{value:t.isSelected,callback:function(a){e.$set(t,"isSelected",a)},expression:"item.isSelected"}},[e._v(" "+e._s(t.name)+" "),"all"===t.value||t.geCreatedAt?e._e():a("a-tag",{staticClass:"checkable-tag-span-class margin-left-4 margin-right-0",style:{backgroundColor:t.colors,color:t.fcol}},[e._v(e._s(t.title||0))])],1)],1)})),0)},v=[],g={props:{list:{type:Array,default:function(){return[]}},checked:{type:String,default:"all"},reset:{type:Boolean}},model:{prop:"checked",event:"change"},watch:{list:function(e){this.tagList=Object(u["cloneDeep"])(e),this.setOption()},reset:function(e){!1===e&&this.tagList.map((function(e){"all"===e.value?e.isSelected=!0:e.isSelected=!1}))}},data:function(e){return{tagList:Object(u["cloneDeep"])(e.list)}},created:function(){this.setOption()},methods:{setOption:function(){var e=this.checked,t=this.tagList;this.$emit("change",e),t.map((function(t){t.value===e&&(t.isSelected=!0)}))},onChangeStatus:function(e,t){this.$emit("change",e.value);var a=Object(u["cloneDeep"])(this.list);a[t]["isSelected"]=!0,this.tagList=a}}},b=g,_=(a("2010"),a("2877")),y=Object(_["a"])(b,h,v,!1,null,"62612550",null),O=y.exports,k=a("42ce"),R=a("2af9"),S=a("c1df"),w=a.n(S),C="09:00:00",D="18:00:00",T=function(e){var t=w()().subtract(3,"month").startOf("day");return e&&e<t},A={"今天":[w()().subtract(1,"days"),w()()],"一周":[w()().subtract(1,"week"),w()()],"两周":[w()().subtract(2,"week"),w()()],"一个月":[w()().subtract(1,"month"),w()()],"两个月":[w()().subtract(2,"month"),w()()],"三个月":[w()().subtract(3,"month"),w()()]},I=(w()().subtract(1,"days"),w()(),w()().subtract(1,"week"),w()(),w()().subtract(2,"week"),w()(),w()().subtract(1,"month"),w()(),w()().subtract(2,"month"),w()(),w()().subtract(3,"month"),w()(),w()().startOf("days"),w()().endOf("days"),w()().add(1,"days").subtract(1,"week").startOf("days"),w()().endOf("days"),w()().add(1,"days").subtract(2,"week").startOf("days"),w()().endOf("days"),w()().add(1,"days").subtract(1,"month").startOf("days"),w()().endOf("days"),w()().add(1,"days").subtract(2,"month").startOf("days"),w()().endOf("days"),w()().add(1,"days").subtract(3,"month").startOf("days"),w()().endOf("days"),{defaultValue:[w()(C,"HH:mm:ss"),w()(D,"HH:mm:ss")]}),x=a("ed08"),N=a("680a"),E=a("6dfa"),j=a("f121"),$=a("8cd7"),F={name:"OrderList",components:{STableType:R["s"],PageView:N["c"],STable:R["p"],HeadInfo:R["k"],CheckableTag:O,FuzzyProduct:R["g"],OrderInfo:R["m"]},data:function(){var e=this;return{orderRow:{},infoShow:!1,selectedRowKeys:[],selectedRows:[],channelList:[],isInit:!1,chargeBack:{visible:!1,form:null},tagReset:!0,send:{form:null,visible:!1},addressSnap:{},timeStu:!1,isStatus:"",orderStatus:"",typeStatus:"",ORDER_INFO_PAY_TYPE:j["k"],ORDER_INFO_STATUS:j["m"],ORDER_INFO_QUERY_DAY:j["l"],ORDER_INFO_SUM_STATUS:j["n"],ORDER_INFO_SUM_TYPE:j["o"],ORDER_INFO_DISTRIBUTE:j["j"],ORDER_SEND_TYPE:j["p"],ranges:A,disabledDate:T,businessTime:I,params:{},listQuery:{statusData:[],timeData:[],pageData:0,statusDataInit:[],distribute:void 0,orderWay:void 0,timeRange:[]},timeRange:[],columns:[{title:"订单号",dataIndex:"id",width:200},{title:"商品信息",scopedSlots:{customRender:"cartSnap"}},{title:"供应商",dataIndex:"supplyName",width:100},{title:"实际支付",key:"payAmount",scopedSlots:{customRender:"payAmount"},width:110},{title:"支付方式",dataIndex:"payType",scopedSlots:{customRender:"payType"},width:110},{title:"状态",dataIndex:"status",scopedSlots:{customRender:"status"},width:110},{title:"渠道Id",dataIndex:"sourceId",width:80},{title:"创建时间",sorter:!0,scopedSlots:{customRender:"createdAt"},width:170},{title:"操作",scopedSlots:{customRender:"action"},fixed:"right",width:170}],pagination:{pageSize:10,current:1},loadData:function(t){var a=t.pageSize,s=t.pageNo,n=t.sortOrder,r=void 0===n?"ascend":n,i=e.listQuery,o=i.id,c=i.status,u=i.payType,d=i.searchWord,p=i.geCreatedAt,f=i.leCreatedAt,h=i.distribute,v=i.productName,g=i.sourceId,b=i.supplyName,_={page:s,limit:a,id:o||void 0,status:"all"===c?void 0:c,payType:"all"===u?void 0:u,distribute:"all"===h?void 0:h,searchWord:d||void 0,geCreatedAt:p||void 0,leCreatedAt:f||void 0,productName:v||void 0,orders:{created_at:"ascend"===r?"desc":"asc"},sourceId:g,supplyName:b};return e.params=_,Object(m["g"])(_).then((function(t){e.isInit=!0;var n=t.data,r=n.list,i=n.totalCount,o=n.header;return e.tagReset=!0,e.header=Object(l["a"])(Object(l["a"])({},o),{},{totalCount:i}),{pageNo:s,pageSize:a,data:r,totalCount:i}}))},header:{},userList:[],supplyList:[],sendType:0}},created:function(){this.getCountOrderInfoTitle(),this.getSumOrderInfoTitle(this.params),this.getChannel(),this.getSupplier()},activated:function(){this.getChannel(),this.isInit&&this.$refs.table.refresh()},beforeRouteLeave:function(e,t,a){e.meta.keepAlive=!0,a()},methods:{onSelectChange:function(e,t){var a=Object(c["a"])(this.selectedRows);a=Object(u["unionBy"])(a.concat(t),"id"),Object(u["remove"])(a,(function(t){return-1===Object(u["indexOf"])(e,t.id)})),this.selectedRowKeys=e,this.selectedRows=a},handleBatchReminder:function(){var e=this,t=this.selectedRowKeys,a=this.$refs.table,s=this.$notification.success;if(!t.length)return this.$message.warning("请选择订单");this.$confirm({title:"提示",content:"已选择".concat(t.length,"条订单，是否确认批量催单？"),onOk:function(){Object(m["l"])({id:t.join(",")}).then((function(t){e.selectedRowKeys=[],e.selectedRows=[],s({message:"成功",description:"成功"}),a.refresh()}))}})},handleBatchSend:function(){var e=this,t=this.selectedRowKeys,a=this.$refs.table,s=this.$notification.success;if(!t.length)return this.$message.warning("请选择订单");this.$confirm({title:"提示",content:"已选择".concat(t.length,"条订单，是否确认批量发货？"),onOk:function(){Object(m["m"])({id:t.join(",")}).then((function(t){e.selectedRowKeys=[],e.selectedRows=[],s({message:"成功",description:"成功"}),a.refresh()}))}})},handleBatchDel:function(){var e=this.selectedRowKeys,t=this.$notification.success,a=this.$refs.table;this.$confirm({title:"警告",content:"已选择".concat(e.length,"条订单，是否确认删除? 删除后不可恢复，请谨慎操作！"),onOk:function(){Object(m["d"])(e.join(",")).then((function(e){t({message:"成功",description:"删除成功"}),a.refresh()}))}})},getSupplier:function(){var e=this;Object($["c"])({title:"product",son:"supplier"}).then((function(t){var a=t.data,s=a.groupValue.list;e.supplyList=s.map((function(e){return e.value["name"]["value"]}))}))},onUploadFiles:function(){this.$refs.uploadFiles.click()},addFiles:function(e){var t=this;return Object(o["a"])(regeneratorRuntime.mark((function a(){var s,n,r,o,c;return regeneratorRuntime.wrap((function(a){while(1)switch(a.prev=a.next){case 0:s=t.$notification.success,n=e.target.files,r=Object(i["a"])(n),a.prev=3,r.s();case 5:if((o=r.n()).done){a.next=11;break}return c=o.value,a.next=9,Object(m["h"])(c).then((function(e){var a=e.data;t.$refs.uploadFiles.value="",s({message:"成功",description:"已成功更新".concat(a,"条订单")})}));case 9:a.next=5;break;case 11:a.next=16;break;case 13:a.prev=13,a.t0=a["catch"](3),r.e(a.t0);case 16:return a.prev=16,r.f(),a.finish(16);case 19:case"end":return a.stop()}}),a,null,[[3,13,16,19]])})))()},getChannel:function(){var e=this;Object(E["c"])().then((function(t){var a=t.data;e.channelList=a}))},handleSendType:function(e){this.sendType=e.target.value},onChangeApprove:function(e){var t=this;this.chargeBack={payAmount:Number(e.payAmount),form:this.$form.createForm(this),visible:!0},new Promise((function(e){setTimeout(e,0)})).then((function(){t.chargeBack.form.setFieldsValue(Object(u["pick"])(e,["id","payAmount","status"]))}))},handleApprove:function(){var e=this,t=this.$refs.table,a=this.$notification.success;this.chargeBack.form.validateFields((function(s,n){if(!s){n.status,n.payAmount;var i=Object(r["a"])(n,["status","payAmount"]);n.originStatus?i.originStatus=n.status:i.originStatus=void 0,Object(k["b"])(i).then((function(s){a({message:"成功",description:"已成功退款"}),t.refresh(),e.chargeBack.visible=!1}))}}))},handleSending:function(e){var t=this.$refs.table,a=this.$notification.success;this.$confirm({title:"提示",content:"是否确认收货？",onOk:function(){Object(m["k"])({id:e.id,status:"closed"}).then((function(e){a({message:"成功",description:"收货成功"}),t.refresh(!0)}))}})},handleRefsTable:function(){this.$refs.table.refresh(!0),this.getSumOrderInfoTitle(this.params)},getSumOrderInfoTitle:function(e){var t=this;Object(m["p"])(e).then((function(e){var a=e.data,s=a.sumStatus,n=a.sumPayType,r=Object(u["cloneDeep"])(j["n"]),i=Object(u["cloneDeep"])(j["o"]),o=function(e){r.map((function(t){t.value===e&&(t.amount=s[e])}))};for(var c in s)o(c);var l=function(e){i.map((function(t){t.value===e&&(t.amount=n[e]["payAmount"])}))};for(var d in n)l(d);t.ORDER_INFO_SUM_STATUS=r,t.ORDER_INFO_SUM_TYPE=i}))},getCountOrderInfoTitle:function(){var e=this,t=Object(u["cloneDeep"])(j["m"]),a=Object(u["cloneDeep"])(j["k"]),s=Object(u["cloneDeep"])(j["l"]),n=Object(u["cloneDeep"])(j["j"]);Object(m["e"])().then((function(r){var i=r.data,o=i.status,c=i.payType,l=i.queryDay,u=i.distribute,d=function(e){t.map((function(t){t.value===e&&(t.title=o[e])}))};for(var p in o)d(p);var f=function(e){a.map((function(t){t.value===e&&(t.title=c[e])}))};for(var m in c)f(m);var h=function(e){n.map((function(t){t.value===e&&(t.title=u[e])}))};for(var v in u)h(v);var g=function(e){s.map((function(t){t.value===e&&(t.geCreatedAt=l[e]["start"],t.leCreatedAt=l[e]["end"]),"all"===t.value&&(t.isSelected=!0)}))};for(var b in l)g(b);e.ORDER_INFO_STATUS=t,e.ORDER_INFO_PAY_TYPE=a,e.ORDER_INFO_QUERY_DAY=s,e.ORDER_INFO_DISTRIBUTE=n}))},onChangeTime:function(){this.timeStu=!this.timeStu,this.timeRange=[],this.listQuery.geCreatedAt="",this.listQuery.leCreatedAt="",this.timeStu?this.ORDER_INFO_QUERY_DAY.map((function(e){e.isSelected=!1})):this.ORDER_INFO_QUERY_DAY.map((function(e){"all"===e.value&&(e.isSelected=!0)}))},onChangeCreateTime:function(e,t){this.listQuery.geCreatedAt=t[0],this.listQuery.leCreatedAt=t[1]},onChangeQueryDay:function(e,t){this.timeStu=!1;var a=Object(u["cloneDeep"])(this.ORDER_INFO_QUERY_DAY);this.ORDER_INFO_QUERY_DAY=a.map((function(e,a){return e.isSelected=a===t,e})),this.listQuery.geCreatedAt=e.geCreatedAt,this.listQuery.leCreatedAt=e.leCreatedAt},handleOrderInfo:function(e){this.orderRow=e,this.infoShow=!0},cancelInfo:function(e){this.infoShow=e},handleSend:function(e){var t=this;this.addressSnap=e.addressSnap,this.send.form=this.$form.createForm(this),this.sendType=0,this.send.visible=!0,new Promise((function(e){setTimeout(e,0)})).then((function(){t.send.form.setFieldsValue(Object(u["pick"])(e,["id"]))}))},onChangeSend:function(){var e=this;this.send.form.validateFields((function(t,a){if(!t){var s=e.$refs.table,n=e.$notification.success;Object(m["o"])(a).then((function(t){n({message:"成功",description:"发货成功"}),s.refresh(!0),e.send.visible=!1}))}}))},handleCheck:function(e){var t="核销";this.getConfirm(e,m["a"],t)},handlePrint:function(e){var t="打印";this.getConfirm(e,m["n"],t)},getConfirm:function(e,t,a){var s=this.$refs.table,n=this.$notification.success;this.$confirm({title:"提示",content:"是否".concat(a,"该订单？"),onOk:function(){t({id:e.id}).then((function(e){n({message:"成功",description:"".concat(a,"成功")}),s.refresh(!0)}))}})},complete:function(e){Object(x["r"])(Object(l["a"])({},this.listQuery));var t=this.$refs.table,a=this.$notification.success;f({id:e.id}).then((function(e){a({message:"成功",description:"订单已完结"}),t.refresh()}))},handleRefunding:function(){this.$router.push({name:"RefundApply"})},handleReset:function(){this.timeRange=[],this.listQuery={},this.tagReset=!1,this.ORDER_INFO_QUERY_DAY.map((function(e){"all"===e.value?e.isSelected=!0:e.isSelected=!1}))},handleDel:function(e){var t=this.$notification.success,a=this.$refs.table;this.$confirm({title:"警告",content:"是否确认删除? 删除后不可恢复，请谨慎操作！",onOk:function(){Object(m["d"])(e.id).then((function(e){t({message:"成功",description:"删除成功"}),a.refresh()}))}})},getExport:function(){Object(m["i"])(this.params)}}},Q=F,Y=(a("2430"),Object(_["a"])(Q,s,n,!1,null,"71059934",null));t["default"]=Y.exports}}]);
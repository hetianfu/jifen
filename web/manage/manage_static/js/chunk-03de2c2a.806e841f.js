(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-03de2c2a"],{b645:function(t,e,a){},f3c2:function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("a-card",[a("a-row",{staticClass:"margin-bottom-16",attrs:{gutter:10}},[a("a-col",{attrs:{span:5}},[a("a-input",{staticClass:"width-percent-100",attrs:{allowClear:"",placeholder:"商品名称"},model:{value:t.queryParam.productName,callback:function(e){t.$set(t.queryParam,"productName",e)},expression:"queryParam.productName"}})],1),a("a-col",{attrs:{span:3}},[a("a-select",{staticClass:"width-percent-100",attrs:{allowClear:"",placeholder:"状态"},model:{value:t.queryParam.status,callback:function(e){t.$set(t.queryParam,"status",e)},expression:"queryParam.status"}},[a("a-select-option",{attrs:{value:1}},[t._v("开启")]),a("a-select-option",{attrs:{value:0}},[t._v("关闭")])],1)],1),a("a-col",{attrs:{span:8}},[a("a-button",{attrs:{type:"primary"},on:{click:function(e){return t.$refs.table.refresh(!0)}}},[t._v("查询")]),a("a-button",{staticClass:"margin-left-10",on:{click:function(){return t.queryParam={}}}},[t._v("重置")])],1)],1),a("a-button",{staticClass:"margin-bottom-8",attrs:{type:"primary"},on:{click:t.handleAddModal}},[a("a-icon",{attrs:{type:"plus"}}),t._v("添加拼团商品")],1),a("s-table",{ref:"table",attrs:{rowKey:"id",columns:t.columns,data:t.loadData,pagination:t.pagination},scopedSlots:t._u([{key:"product",fn:function(t,e){return[a("preview",{attrs:{img:e.productInfo.images[0],name:e.productInfo.name}})]}},{key:"originPrice",fn:function(e,a){return[t._v(t._s(t._f("moneyFormat")(a.productInfo.originPrice)))]}},{key:"salePrice",fn:function(e,a){return[t._v(t._s(t._f("moneyFormat")(a.productInfo.salePrice)))]}},{key:"time",fn:function(e,i){return[a("div",{staticClass:"startColor"},[t._v("开始："+t._s(t._f("parseTime")(i.startTime,"YYYY-MM-DD HH:mm")))]),a("div",{staticClass:"endColor"},[t._v("结束："+t._s(t._f("parseTime")(i.endTime,"YYYY-MM-DD HH:mm")))])]}},{key:"remainTime",fn:function(e,a){return[t._v(t._s(a.remainTime/3600)+"小时")]}},{key:"isOnSale",fn:function(e){return[a("a-switch",{attrs:{"checked-children":"启动","un-checked-children":"关闭",loading:e.loading,checked:Boolean(e.isOnSale)},on:{change:function(a){return t.handelIsOnSale(e,a)}}})]}},{key:"action",fn:function(e,i){return[a("a",{on:{click:function(e){return t.handleEditModal(i)}}},[t._v("编辑拼团")]),a("a-divider",{attrs:{type:"vertical"}}),a("a-dropdown",{attrs:{trigger:["click"]}},[a("a",[t._v("更多 "),a("a-icon",{attrs:{type:"down"}})],1),a("a-menu",{attrs:{slot:"overlay"},slot:"overlay"},[a("a-menu-item",{on:{click:function(e){return t.productEdit(i)}}},[a("a",[t._v("编辑商品")])]),a("a-menu-item",{on:{click:function(e){return t.productStockDetail(i)}}},[a("a",[t._v("销售记录")])]),a("a-menu-item",{on:{click:function(e){return t.handleClean(i)}}},[a("a",[t._v("清除分享图")])]),a("a-menu-item",{on:{click:function(e){return t.handleDel(i)}}},[a("a",{staticClass:"color-red"},[t._v("删除")])])],1)],1)]}},{key:"expandedRowRender",fn:function(e){return a("a-table",{attrs:{rowKey:function(t){return t.id},columns:t.innerColumns,"data-source":e.productInfo.skuList,pagination:!1},scopedSlots:t._u([{key:"i-action",fn:function(e,i){return[a("a",{on:{click:function(e){return t.handleStockDetail(i)}}},[t._v("销售详情")])]}}])})}}])})],1),a("a-modal",{attrs:{title:t.config.title,"on-ok":"handleOk",width:"600px"},model:{value:t.config.visible,callback:function(e){t.$set(t.config,"visible",e)},expression:"config.visible"}},[a("a-form",{attrs:{form:t.config.form,"label-col":{span:4},"wrapper-col":{span:20}}},["add"===t.config.status?a("a-form-item",{attrs:{label:"选择商品"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["productId",{rules:[{required:!0,message:"选择商品!"}]}],expression:"[ 'productId', { rules: [{ required: true, message: '选择商品!' }] }]"}],staticClass:"display-none"}),a("div",{on:{click:t.handleProduct}},[""===t.product.name?a("a",[t._v("点击选择商品")]):a("a",[t._v(t._s(t.product.name))])])],1):t._e(),"add"===t.config.status?a("a-form-item",{attrs:{label:"拼团名称"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["title",{rules:[{required:!0,message:"请输入拼团名称!"}]}],expression:"[ 'title', { rules: [{ required: true, message: '请输入拼团名称!' }] }]"}]})],1):t._e(),"edit"===t.config.status?a("a-form-item",{staticClass:"display-none",attrs:{label:"ID"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["id"],expression:"[ 'id' ]"}]})],1):t._e(),"edit"===t.config.status?a("a-form-item",{staticClass:"display-none",attrs:{label:"ID"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["productId"],expression:"[ 'productId' ]"}]})],1):t._e(),a("a-form-item",{attrs:{label:"拼团时间"}},[a("a-range-picker",{directives:[{name:"decorator",rawName:"v-decorator",value:["time",{rules:[{type:"array",required:!0,message:"请选择时间"}]}],expression:"['time', {rules: [{ type: 'array', required: true, message: '请选择时间' }]}]"}],attrs:{"show-time":"",format:"YYYY-MM-DD HH:mm:ss"},on:{change:t.handlePicker}})],1),a("a-form-item",{attrs:{label:"持续时间",help:"用户发起拼团到拼团自动结束的时间段"}},[a("a-select",{directives:[{name:"decorator",rawName:"v-decorator",value:["remainTime",{rules:[{required:!0,message:"请选择持续时间!"}]}],expression:"[ 'remainTime', { rules: [{ required: true, message: '请选择持续时间!' }] }]"}],staticClass:"width-percent-70",attrs:{placeholder:"请选择持续时间"}},t._l(t.timeList,(function(e,i){return a("a-select-option",{key:i,attrs:{value:e.key}},[t._v(" "+t._s(e.label)+" ")])})),1)],1),a("a-form-item",{attrs:{label:"拼团人数"}},[a("a-input-number",{directives:[{name:"decorator",rawName:"v-decorator",value:["people",{initialValue:2}],expression:"['people', { initialValue: 2 }]"}],attrs:{min:2}})],1)],1),a("template",{slot:"footer"},[a("a-button",{key:"back",on:{click:t.handleCancel}},[t._v("取消")]),a("a-button",{key:"submit",attrs:{type:"primary",loading:t.loading},on:{click:function(e){"add"===t.config.status?t.handleAdd():t.handleEdit()}}},[t._v("确定")])],1)],2),a("product",{on:{submit:t.handleSub},model:{value:t.productModal,callback:function(e){t.productModal=e},expression:"productModal"}})],1)},n=[],r=(a("d81d"),a("b0c0"),a("a9e3"),a("d3b7"),a("5530")),o=a("15fd"),s=a("c1df"),c=a.n(s),d=a("88bc"),l=a.n(d),u=a("2af9"),m=a("4124"),f=a("4c79"),p=a("17eb"),h={components:{Template:p["a"],STable:u["p"],product:u["x"],preview:u["w"]},data:function(){for(var t=this,e=[],a=1;a<24;a++)e.push({key:3600*a,label:"".concat(a,"小时")});return{queryParam:{},timeList:e,config:{title:"",status:"",form:null,visible:!1},loading:!1,productModal:!1,product:{name:""},startTime:"",endTime:"",columns:[{title:"商品ID",dataIndex:"productId",width:90},{title:"商品信息",scopedSlots:{customRender:"product"},width:300},{title:"原价",scopedSlots:{customRender:"originPrice"},width:100},{title:"拼团价",scopedSlots:{customRender:"salePrice"},width:130},{title:"拼团人数",dataIndex:"people",width:130},{title:"持续时间",scopedSlots:{customRender:"remainTime"},width:130},{title:"拼团时间",scopedSlots:{customRender:"time"},width:220},{title:"状态",scopedSlots:{customRender:"isOnSale"},width:130},{title:"操作",scopedSlots:{customRender:"action"},width:170}],innerColumns:[{title:"商品ID",dataIndex:"productId",width:190,ellipsis:!0},{title:"商品规格",dataIndex:"specSnap",scopedSlots:{customRender:"i-specSnap"},ellipsis:!0,width:270},{title:"库存",dataIndex:"stockNumber",width:"8%"},{title:"商品条码",dataIndex:"barCode",ellipsis:!0},{title:"操作",scopedSlots:{customRender:"i-action"},width:100}],pagination:{pageSize:10,current:1},loadData:function(e){var a=e.pageNo,i=e.pageSize,n=t.queryParam,r=n.status,o=n.productName,s={page:a,limit:i,status:r,productName:o||void 0};return Object(m["g"])(s).then((function(t){t.data.list.map((function(t){t.loading=!1,t.isOnSale=t.productInfo.isOnSale}));var e=t.data.list;return{pageNo:a,pageSize:i,data:e,totalCount:0}}))}}},created:function(){},methods:{setConfig:function(t){this.config=Object.assign(this.config,t)},handelIsOnSale:function(t,e){var a=this;t.loading=!0,t.isOnSale=Number(e);var i={id:t.productInfo.id,isOnSale:t.isOnSale};Object(f["s"])(i).then((function(e){setTimeout((function(){t.loading=!1,a.$message.success("修改成功")}),200)}))},handleAddModal:function(){this.setConfig({title:"添加拼团商品",status:"add",visible:!0,form:this.$form.createForm(this)}),this.product.name="",this.loading=!1},handleAdd:function(){var t=this;this.loading=!0;var e=this.config.form.validateFields,a=this.$refs.table,i=this.$notification,n=i.success,s=i.error,c=this.startTime,d=this.endTime;e((function(e,i){if(!e){var l=Object(r["a"])(Object(r["a"])({},i),{},{startTime:c,endTime:d}),u=(l.time,Object(o["a"])(l,["time"]));Object(m["a"])(u).then((function(e){n({message:"成功",description:"添加成功"}),a.refresh(),t.config.visible=!1})).catch((function(e){s({message:"失败",description:"添加失败"}),t.loading=!1}))}}))},productEdit:function(t){this.$router.push({name:"pinkProduct",params:{id:t.productId}})},handleEditModal:function(t){var e=this;this.setConfig({title:"编辑配置",visible:!0,status:"edit",form:this.$form.createForm(this)}),this.loading=!1,new Promise((function(t){setTimeout(t,0)})).then((function(){e.startTime=c.a.unix(t.startTime).format("YYYY-MM-DD HH:mm:ss"),e.endTime=c.a.unix(t.endTime).format("YYYY-MM-DD HH:mm:ss"),t.time=[e.startTime,e.endTime],e.config.form.setFieldsValue(l()(t,["id","title","time","remainTime","people","productId"]))}))},handleEdit:function(){var t=this;this.loading=!0;var e=this.config.form.validateFields,a=this.$refs.table,i=this.$notification.success,n=this.startTime,s=this.endTime;e((function(e,c){if(!e){var d=Object(r["a"])(Object(r["a"])({},c),{},{startTime:n,endTime:s}),l=(d.time,Object(o["a"])(d,["time"]));Object(m["r"])(l).then((function(e){i({message:"成功",description:"编辑成功"}),a.refresh(),t.config.visible=!1}))}}))},handleDel:function(t){var e=this,a=this.$refs.table,i=this.$notification.success;this.$confirm({title:"警告",content:"是否删除该配置",onOk:function(){Object(m["c"])(t.id).then((function(t){i({message:"成功",description:"删除成功"}),a.refresh()})).catch((function(t){e.$message.error("删除失败")}))}})},handleProduct:function(){this.productModal=!0},handleSub:function(t){this.product=t,this.config.form.setFieldsValue({title:t.name}),this.config.form.setFieldsValue({productId:t.id})},handleCancel:function(){this.setConfig({visible:!1})},handlePicker:function(t,e){this.startTime=e[0],this.endTime=e[1]},handleClean:function(t){var e=this.$refs.table,a=this.$notification.success;this.$confirm({title:"警告",content:"是否清除分享图?",onOk:function(){Object(f["d"])(t.productInfo.id).then((function(t){a({message:"成功",description:"清除成功"}),e.refresh()}))}})},handleStockDetail:function(t){this.$router.push({name:"ProductStockDetail",params:{id:t.id}})},productStockDetail:function(t){this.$router.push({name:"ProductStockDetail",params:{productId:t.id}})}}},v=h,g=(a("f9af"),a("2877")),b=Object(g["a"])(v,i,n,!1,null,"93a59824",null);e["default"]=b.exports},f9af:function(t,e,a){"use strict";a("b645")}}]);
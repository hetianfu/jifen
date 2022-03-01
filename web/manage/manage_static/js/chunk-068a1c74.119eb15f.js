(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-068a1c74"],{"70d5":function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("page-view",{attrs:{title:"单位管理"}},[a("div",{staticClass:"product-unit-list"},[a("a-card",{attrs:{bordered:!1}},[a("div",{staticClass:"table-operator"},[a("a-button",{attrs:{type:"primary",icon:"plus"},on:{click:t.handleAddUnit}},[t._v("添加单位")])],1),a("s-table",{ref:"table",staticClass:"product-list-table",attrs:{rowKey:function(t,e){return e},data:t.loadData,columns:t.columns,pagination:t.pagination},scopedSlots:t._u([{key:"decimalsDigits",fn:function(e,i){return a("span",{},["weight"===i.unitType?a("span",[t._v(t._s(e))]):t._e()])}},{key:"isSystem",fn:function(e){return a("span",{},[-1===e?a("span",[t._v("系统默认")]):t._e(),0===e?a("span",[t._v("后台添加")]):t._e()])}},{key:"unitType",fn:function(e){return a("span",{},["weight"===e?a("a-tag",{attrs:{color:"orange"}},[t._v("重量")]):t._e(),"0"===e?a("a-tag",{attrs:{color:"green"}},[t._v("数量")]):t._e()],1)}},{key:"status",fn:function(e){return a("span",{},[0===e?a("a-badge",{attrs:{status:"processing",text:"正常"}}):t._e()],1)}},{key:"updatedAt",fn:function(e){return a("span",{},[t._v(t._s(t._f("parseTime")(e,"YYYY-MM-DD")))])}},{key:"action",fn:function(e){return a("span",{},[a("a",{on:{click:function(a){return t.handleEditUnit(e)}}},[t._v("编辑")]),a("a-divider",{attrs:{type:"vertical"}}),a("a",{staticStyle:{color:"#f5222d"},on:{click:function(a){return t.handleDeleteUnit(e)}}},[t._v("删除")])],1)}}])})],1),a("a-modal",{attrs:{title:t.confirm.title},on:{ok:function(e){"create"===t.confirm.status?t.createData():t.updateData()}},model:{value:t.confirm.visible,callback:function(e){t.$set(t.confirm,"visible",e)},expression:"confirm.visible"}},[a("a-form",{attrs:{form:t.confirm.form}},[a("a-form-item",{attrs:{label:"单位名称","label-col":t.confirm.labelCol,"wrapper-col":t.confirm.wrapperCol}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["name",{rules:[{required:!0,message:"请输入单位名称"}]}],expression:"['name', {rules: [{ required: true, message: '请输入单位名称' }]}]"}]})],1),a("a-form-item",{attrs:{label:"单位类型","label-col":t.confirm.labelCol,"wrapper-col":t.confirm.wrapperCol}},[a("a-radio-group",{directives:[{name:"decorator",rawName:"v-decorator",value:["unitType",{initialValue:"0"}],expression:"['unitType', { initialValue: '0' }]"}],attrs:{buttonStyle:"solid"},on:{change:t.changeUnitType}},[a("a-radio-button",{attrs:{value:"weight"}},[t._v("重量")]),a("a-radio-button",{attrs:{value:"0"}},[t._v("数量")])],1)],1),a("a-form-item",{directives:[{name:"show",rawName:"v-show",value:"weight"==t.confirm.temp.unitType,expression:"confirm.temp.unitType == 'weight'"}],attrs:{label:"小数位","label-col":t.confirm.labelCol,"wrapper-col":t.confirm.wrapperCol,extra:"小数位只能保留3位"}},[a("a-input-number",{directives:[{name:"decorator",rawName:"v-decorator",value:["decimalsDigits",{initialValue:0}],expression:"['decimalsDigits', { initialValue: 0 }]"}],attrs:{min:0,max:3,precision:0}})],1)],1)],1)],1)])},n=[],r=(a("b0c0"),a("d3b7"),a("5530")),s=a("88bc"),o=a.n(s),c=a("2af9"),u=a("9f60"),l=a("ed08"),d=a("3232"),m=a("c8c3"),f=!1,p={},b={name:"ProductUnitList",components:{STable:c["p"],PageView:m["a"]},data:function(){var t=this;return{queryParam:{},columns:[{title:"名称",dataIndex:"name"},{title:"类型",dataIndex:"unitType",scopedSlots:{customRender:"unitType"}},{title:"小数位",dataIndex:"decimalsDigits",scopedSlots:{customRender:"decimalsDigits"}},{title:"状态",dataIndex:"status",scopedSlots:{customRender:"status"}},{title:"来源",dataIndex:"isSystem",scopedSlots:{customRender:"isSystem"}},{title:"更新时间",dataIndex:"updatedAt",scopedSlots:{customRender:"updatedAt"}},{title:"操作",scopedSlots:{customRender:"action"}}],pagination:{pageSize:10,current:1},pageTemp:0,loadData:function(e){var a=e.pageSize,i=e.pageNo,n={page:i,limit:a};t.queryParam.pageData=n.page;var r=new d["a"];return n.query=r.exportData(),f&&(t.queryParam.pageData=p.pageData,n.page=p.pageData,i=p.pageData),Object(u["d"])(n).then((function(t){var e=t.data.list;return f=!1,Object(l["a"])(),{pageNo:i,pageSize:a,data:e,totalCount:0}}))},confirm:{form:null,temp:{},title:"",status:"",visible:!1,labelCol:{span:8},wrapperCol:{span:12}}}},created:function(){f=!0,p=Object(l["s"])()},methods:{changeUnitType:function(t){var e=t.target.value;this.confirm.temp.unitType=e},handleAddUnit:function(){this.confirm.temp={},this.confirm.status="create",this.confirm.form=this.$form.createForm(this),this.confirm.title="添加单位",this.confirm.visible=!0},createData:function(){var t=this,e=this.confirm.form.validateFields,a=this.$refs.table,i=this.$notification.success;e((function(e,n){if(!e){var s=Object(r["a"])(Object(r["a"])({},n),{},{decimalsDigits:"weight"===n.unitType?n.decimalsDigits:void 0});Object(u["a"])(s).then((function(e){i({message:"成功",description:"添加成功"}),a.refresh(),t.confirm.visible=!1}))}}))},handleEditUnit:function(t){this.confirm.temp=Object(r["a"])({},t),this.confirm.status="update",this.confirm.form=this.$form.createForm(this),this.confirm.title="编辑 [ ".concat(t.name," ] 单位");var e=this.confirm.form;new Promise((function(t){setTimeout(t,0)})).then((function(){e.setFieldsValue(o()(t,["name","unitType","decimalsDigits"]))})),this.confirm.visible=!0},updateData:function(){var t=this,e=this.confirm,a=e.form.validateFields,i=e.temp.id,n=this.$refs.table,s=this.$notification.success;a((function(e,a){if(!e){var o=Object(r["a"])(Object(r["a"])({},a),{},{id:i});Object(u["e"])(o).then((function(e){s({message:"成功",description:"编辑成功"}),n.refresh(),t.confirm.visible=!1}))}}))},handleDeleteUnit:function(t){var e=t.id,a=t.name,i=this.$refs.table,n=this.$notification.success;this.$confirm({title:"警告",content:"是否删除 [ ".concat(a," ] 吗?"),onOk:function(){Object(u["b"])(e).then((function(t){n({message:"成功",description:"删除成功"}),i.refresh()}))}})}}},h=b,v=a("2877"),g=Object(v["a"])(h,i,n,!1,null,null,null);e["default"]=g.exports},"9f60":function(t,e,a){"use strict";a.d(e,"c",(function(){return r})),a.d(e,"d",(function(){return s})),a.d(e,"a",(function(){return o})),a.d(e,"e",(function(){return c})),a.d(e,"b",(function(){return u}));var i=a("365c"),n=a("b775");function r(t){return Object(n["b"])({url:i["b"].units.getUnitList,method:"get",params:t})}function s(t){return Object(n["b"])({url:i["b"].units.getUnitPage,method:"get",params:t})}function o(t){return Object(n["b"])({url:i["b"].units.addUnit,method:"post",data:t})}function c(t){return Object(n["b"])({url:i["b"].units.updateUnit,method:"patch",data:t})}function u(t){return Object(n["b"])({url:i["b"].units.deleteUnit+t,method:"delete"})}}}]);
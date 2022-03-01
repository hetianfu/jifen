(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-3953d480"],{"130a3":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("a-card",[a("a-button",{staticClass:"margin-bottom-16",attrs:{type:"primary"},on:{click:t.handleAdd}},[a("a-icon",{attrs:{type:"plus"}}),t._v("添加配置")],1),a("a-row",{staticClass:"margin-bottom-16",attrs:{gutter:8}},[a("a-col",{attrs:{md:3,sm:24}},[a("a-select",{staticClass:"width-percent-100",attrs:{allowClear:"",placeholder:"是否生效"},model:{value:t.queryParam.status,callback:function(e){t.$set(t.queryParam,"status",e)},expression:"queryParam.status"}},t._l(t.INFO_CONFIG_INFO,(function(e,n){return a("a-select-option",{key:n,attrs:{value:e.value}},[t._v(t._s(e.name))])})),1)],1),a("a-col",{attrs:{md:3,sm:24}},[a("a-select",{staticClass:"width-percent-100",attrs:{allowClear:"",placeholder:"配置类型"},model:{value:t.queryParam.type,callback:function(e){t.$set(t.queryParam,"type",e)},expression:"queryParam.type"}},t._l(t.INFO_CONFIG_TYPE,(function(e,n){return a("a-select-option",{key:n,attrs:{value:e.value}},[t._v(t._s(e.name))])})),1)],1),a("a-col",{attrs:{md:4,sm:24}},[a("a-input",{attrs:{allowClear:"",placeholder:"名称"},model:{value:t.queryParam.title,callback:function(e){t.$set(t.queryParam,"title",e)},expression:"queryParam.title"}})],1),a("a-col",{attrs:{md:4,sm:24}},[a("span",{staticClass:"table-page-search-submitButtons"},[a("a-button",{attrs:{type:"primary"},on:{click:function(e){return t.$refs.table.refresh(!0)}}},[t._v("查询")]),a("a-button",{staticClass:"margin-left-8",on:{click:function(){return t.queryParam={}}}},[t._v("重置")])],1)])],1),a("s-table",{ref:"table",attrs:{rowKey:function(t,e){return e},columns:t.columns,data:t.loadData,pagination:t.pagination},scopedSlots:t._u([{key:"a-title",fn:function(e,n){return[a("a",{on:{click:function(e){return t.plusConfigData(n)}}},[t._v(t._s(n.title))])]}},{key:"status",fn:function(e,n){return[a("a-switch",{attrs:{"checked-children":"生效","un-checked-children":"失效",checked:1===n.status},on:{change:function(e){return t.onChangeStatus(n,e)}}})]}},{key:"type",fn:function(e){return t._l(t.INFO_CONFIG_TYPE,(function(n,r){return n.value===e?a("a-tag",{key:r,attrs:{color:n.color}},[t._v(t._s(n.name))]):t._e()}))}},{key:"engTitle",fn:function(e,n){return[a("edit-name",{attrs:{text:n.engTitle,scope:n},on:{change:function(e){return t.onEditChange(n.id,"engTitle",e)}}})]}},{key:"action",fn:function(e,n){return[a("a",{on:{click:function(e){return t.handleEdit(n)}}},[t._v("编辑")]),a("a-divider",{attrs:{type:"vertical"}}),a("a",{staticClass:"color-red",on:{click:function(e){return t.handleDel(n)}}},[t._v("删除")])]}}])})],1),a("a-modal",{attrs:{title:t.config.title},on:{ok:function(e){"add"===t.config.status?t.getHandleAdd():t.getHandleEdit()}},model:{value:t.config.visible,callback:function(e){t.$set(t.config,"visible",e)},expression:"config.visible"}},[a("a-form",{attrs:{form:t.config.form,"label-col":{span:5},"wrapper-col":{span:18}}},["edit"===t.config.status?a("a-form-item",{staticClass:"display-none",attrs:{label:"ID"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["id"],expression:"['id']"}]})],1):t._e(),a("a-form-item",{attrs:{label:"分类名称"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["title",{rules:[{required:!0,message:"分类名称不能为空!"}]}],expression:"['title', { rules: [{ required: true, message: '分类名称不能为空!' }] }]"}],attrs:{placeholder:"请输入分类名称"}})],1),a("a-form-item",{attrs:{label:"标识"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["engTitle",{rules:[{required:!0,message:"标识不能为空!"}]}],expression:"['engTitle', { rules: [{ required: true, message: '标识不能为空!' }] }]"}],attrs:{placeholder:"请输入标识"}})],1),a("a-form-item",{attrs:{label:"图标"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["icon",{rules:[{required:!0,message:"图标不能为空!"}]}],expression:"['icon', { rules: [{ required: true, message: '图标不能为空!' }] }]"}],attrs:{placeholder:"请输入图标"}})],1),a("a-form-item",{attrs:{label:"类型"}},[a("a-radio-group",{directives:[{name:"decorator",rawName:"v-decorator",value:["type",{initialValue:0}],expression:"['type', { initialValue: 0 }]"}]},t._l(t.INFO_CONFIG_TYPE,(function(e,n){return a("a-radio",{key:n,attrs:{value:e.value}},[t._v(t._s(e.name))])})),1)],1),a("a-form-item",{attrs:{label:"排序"}},[a("a-input-number",{directives:[{name:"decorator",rawName:"v-decorator",value:["showOrder",{initialValue:0},{rules:[{required:!0,message:"请输入排序"}]}],expression:"['showOrder', { initialValue: 0 }, { rules: [{ required: true, message: '请输入排序' }] }]"}],staticStyle:{width:"20%"},attrs:{min:0,max:1e3}})],1),a("a-form-item",{attrs:{label:"是否生效"}},[a("a-radio-group",{directives:[{name:"decorator",rawName:"v-decorator",value:["status",{initialValue:1}],expression:"['status', { initialValue: 1 }]"}]},[a("a-radio",{attrs:{value:1}},[t._v("生效")]),a("a-radio",{attrs:{value:0}},[t._v("失效")])],1)],1)],1)],1)],1)},r=[],i=(a("d3b7"),a("ade3")),o=a("2af9"),s=a("f955"),c=a("f121"),u=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"editable-cell"},[t.editable?a("div",{staticClass:"editable-cell-input-wrapper"},[a("a-input",{staticStyle:{"font-size":"12px"},attrs:{value:t.value},on:{change:t.handleChange,pressEnter:t.check}}),a("a-icon",{staticClass:"editable-cell-icon-check",attrs:{type:"check"},on:{click:t.check}})],1):a("div",{staticClass:"editable-cell-text-wrapper"},[a("a-popover",{attrs:{placement:"topLeft",arrowPointAtCenter:""}},[a("template",{slot:"content"},[t._v(t._s(t.value))]),a("span",[t._v(t._s(t._f("textOverflowEllipsis")(t.value,0,10)))])],2),a("a-icon",{staticClass:"editable-cell-icon",attrs:{type:"edit"},on:{click:t.edit}})],1)])},l=[],d={props:{text:{type:String,required:!0},scope:{type:Object,required:!0}},data:function(){return{value:this.text,row:this.scope,editable:!1}},watch:{text:function(t){this.value=t},scope:function(t){this.row=t}},methods:{handleChange:function(t){var e=t.target.value;this.value=e},check:function(){this.editable=!1,this.$emit("change",this.value)},edit:function(){this.editable=!0}}},f=d,m=(a("3f04"),a("2877")),p=Object(m["a"])(f,u,l,!1,null,"60a7fefc",null),h=p.exports,b=a("88bc"),g=a.n(b),v={components:{STable:o["p"],EditName:h},data:function(){var t=this;return{queryParam:{},INFO_CONFIG_TYPE:c["h"],INFO_CONFIG_INFO:c["g"],config:{form:null,title:"添加配置",status:"add",visible:!1},pagination:{pageSize:10,current:1},columns:[{title:"编号",dataIndex:"id",scopedSlots:{customRender:"id"}},{title:"名称",dataIndex:"title",scopedSlots:{customRender:"a-title"}},{title:"标识",dataIndex:"engTitle",scopedSlots:{customRender:"engTitle"}},{title:"图标",dataIndex:"icon",scopedSlots:{customRender:"icon"}},{title:"是否生效",dataIndex:"status",scopedSlots:{customRender:"status"}},{title:"配置类型",dataIndex:"type",scopedSlots:{customRender:"type"}},{title:"排序",dataIndex:"showOrder",sorter:!0},{title:"操作",scopedSlots:{customRender:"action"}}],loadData:function(e){var a=e.pageNo,n=e.pageSize,r=e.sortOrder,i=void 0===r?"ascend":r,o=t.queryParam,c=o.status,u=o.title,l=o.type,d={page:a,limit:n,status:c,title:u||void 0,type:l,orders:{show_order:"ascend"===i?"asc":"desc"}};return Object(s["n"])(d).then((function(t){var e=t.data.list;return{pageNo:a,pageSize:n,data:e,totalCount:0}}))}}},created:function(){},methods:{onEditChange:function(t,e,a){var n=this;void 0===a&&(a=null),Object(s["r"])(Object(i["a"])({id:t},e,a)).then((function(t){n.$message.success("修改成功")}))},onChangeStatus:function(t,e){var a=this;t.status=!0===e?1:0,Object(s["r"])({id:t.id,status:t.status}).then((function(t){a.$message.success("修改成功")}))},handleAdd:function(){this.config={title:"添加配置",status:"add",visible:!0,form:this.$form.createForm(this)}},getHandleAdd:function(){var t=this.config,e=this.$refs.table,a=this.$notification.success;t.form.validateFields((function(n,r){n||Object(s["d"])(r).then((function(n){a({message:"成功",description:"添加成功"}),e.refresh(),t.visible=!1}))}))},handleEdit:function(t){var e=this;this.config={title:"编辑 [ ".concat(t.title," ] 配置"),status:"edit",visible:!0,form:this.$form.createForm(this)},new Promise((function(t){setTimeout(t,0)})).then((function(){e.config.form.setFieldsValue(g()(t,["id","title","engTitle","icon","type","status","showOrder"]))}))},getHandleEdit:function(){var t=this.config,e=this.$refs.table,a=this.$notification.success;t.form.validateFields((function(n,r){n||Object(s["r"])(r).then((function(n){a({message:"成功",description:"修改成功"}),e.refresh(),t.visible=!1}))}))},handleDel:function(t){var e=this.$refs.table,a=this.$notification.success;this.$confirm({title:"警告",content:"是否删除[ ".concat(t.title," ]"),onOk:function(){Object(s["h"])(t.id).then((function(t){a({message:"成功",description:"删除成功"}),e.refresh()}))}})},plusConfigData:function(t){this.$router.push({name:"configDataInfo",params:{id:t.id}})}}},y=v,O=Object(m["a"])(y,n,r,!1,null,"2893698e",null);e["default"]=O.exports},"3f04":function(t,e,a){"use strict";a("e11e")},e11e:function(t,e,a){},f955:function(t,e,a){"use strict";a.d(e,"n",(function(){return o})),a.d(e,"l",(function(){return s})),a.d(e,"r",(function(){return c})),a.d(e,"o",(function(){return u})),a.d(e,"d",(function(){return l})),a.d(e,"a",(function(){return d})),a.d(e,"h",(function(){return f})),a.d(e,"e",(function(){return m})),a.d(e,"m",(function(){return p})),a.d(e,"b",(function(){return h})),a.d(e,"p",(function(){return b})),a.d(e,"f",(function(){return g})),a.d(e,"i",(function(){return v})),a.d(e,"k",(function(){return y})),a.d(e,"j",(function(){return O})),a.d(e,"c",(function(){return _})),a.d(e,"g",(function(){return w})),a.d(e,"q",(function(){return C}));a("99af");var n=a("365c"),r=a("b775"),i=n["b"].maintain;function o(t){return Object(r["b"])({url:i.getTabPage,method:"get",params:t})}function s(t){return Object(r["b"])({url:i.getSystemConfigPage,method:"get",params:t})}function c(t){return Object(r["b"])({url:i.updateTabById,method:"patch",data:t})}function u(t){return Object(r["b"])({url:i.updateSystemConfigById,method:"patch",data:t})}function l(t){return Object(r["b"])({url:i.addTab,method:"post",data:t})}function d(t){return Object(r["b"])({url:i.addSystemConfig,method:"post",data:t})}function f(t){return Object(r["b"])({url:"".concat(i.delTabById).concat(t),method:"delete"})}function m(t){return Object(r["b"])({url:"".concat(i.delSystemConfigById).concat(t),method:"delete"})}function p(t){return Object(r["b"])({url:i.getSystemGroupPage,method:"get",params:t})}function h(t){return Object(r["b"])({url:i.addSystemGroup,method:"post",data:t})}function b(t){return Object(r["b"])({url:i.updateSystemGroupById,method:"patch",data:t})}function g(t){return Object(r["b"])({url:"".concat(i.delSystemGroupById).concat(t),method:"delete"})}function v(t){return Object(r["b"])({url:i.getNormalGroupDataPag,method:"get",params:t})}function y(t){return Object(r["b"])({url:i.getStrategyGroupDataPag,method:"get",params:t})}function O(t){return Object(r["b"])({url:i.getProductGroupDataPag,method:"get",params:t})}function _(t){return Object(r["b"])({url:i.addSystemGroupData,method:"post",data:t})}function w(t){return Object(r["b"])({url:"".concat(i.delSystemGroupDataById).concat(t),method:"delete"})}function C(t){return Object(r["b"])({url:i.updateSystemGroupDataById,method:"patch",data:t})}}}]);
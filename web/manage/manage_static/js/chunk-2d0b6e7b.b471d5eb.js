(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0b6e7b"],{"1ec4":function(e,t,a){"use strict";a.r(t);var i=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("a-card",[a("a-button",{staticClass:"margin-bottom-8",attrs:{type:"primary"},on:{click:e.handleAdd}},[a("a-icon",{attrs:{type:"plus"}}),e._v("添加页面")],1),a("s-table",{ref:"table",attrs:{columns:e.columns,data:e.loadData,rowKey:function(e,t){return t},pagination:e.pagination},scopedSlots:e._u([{key:"status",fn:function(t){return a("span",{},[a("a-switch",{attrs:{"checked-children":"开启","un-checked-children":"关闭",loading:t.loading,checked:Boolean(t.status)},on:{change:function(a){return e.changeStatus(t,a)}}})],1)}},{key:"isSystem",fn:function(t){return a("span",{},[t.isSystem?a("a-tag",[e._v("系统页面")]):e._e()],1)}},{key:"action",fn:function(t,i){return a("span",{},[i.isSystem?e._e():a("a",{on:{click:function(t){return e.handleEdit(i)}}},[e._v("编辑")]),i.isSystem?e._e():a("a-divider",{attrs:{type:"vertical"}}),a("a",{on:{click:function(t){return e.handleRoute(i)}}},[e._v("编辑页面")]),i.isSystem?e._e():a("a-divider",{attrs:{type:"vertical"}}),i.isSystem?e._e():a("a",{staticClass:"color-red",on:{click:function(t){return e.handleDel(i)}}},[e._v("删除")])],1)}}])})],1),a("a-modal",{attrs:{title:e.page.title},on:{ok:function(t){"add"===e.page.status?e.handleSubAdd():e.handleSubEdit()}},model:{value:e.page.visible,callback:function(t){e.$set(e.page,"visible",t)},expression:"page.visible"}},[a("a-form",{attrs:{form:e.page.form,"label-col":{span:4},"wrapper-col":{span:19}}},[a("a-form-item",{attrs:{label:"页面标识"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["identify",{rules:[{required:!0,message:"请输入页面标识"}]}],expression:"['identify', { rules: [{ required: true, message: '请输入页面标识' }] }]"}],attrs:{placeholder:"请输入页面标识"}})],1),a("a-form-item",{attrs:{label:"页面名称"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["name",{rules:[{required:!0,message:"请输入页面名称"}]}],expression:"['name', { rules: [{ required: true, message: '请输入页面名称' }] }]"}],attrs:{placeholder:"请输入页面名称"}})],1),a("a-form-item",{attrs:{label:"备注"}},[a("a-textarea",{directives:[{name:"decorator",rawName:"v-decorator",value:["remark"],expression:"['remark']"}],attrs:{placeholder:"备注"}})],1),a("a-form-item",{attrs:{label:"排序"}},[a("a-input-number",{directives:[{name:"decorator",rawName:"v-decorator",value:["sort",{initialValue:0}],expression:"['sort', { initialValue: 0 }]"}]})],1),a("a-form-item",{attrs:{label:"状态"}},[a("a-radio-group",{directives:[{name:"decorator",rawName:"v-decorator",value:["status",{initialValue:1}],expression:"['status', { initialValue: 1 }]"}]},[a("a-radio",{attrs:{value:0}},[e._v("未启用")]),a("a-radio",{attrs:{value:1}},[e._v("启用")])],1)],1)],1)],1)],1)},s=[],n=(a("d81d"),a("b0c0"),a("a9e3"),a("d3b7"),a("2af9")),r=a("88bc"),o=a.n(r),c=a("8cd7"),l={components:{STable:n["p"]},data:function(){return{page:{info:{},title:"",status:"",form:null,visible:!1},pagination:{pageSize:10,current:1},columns:[{title:"ID",dataIndex:"id",width:90},{title:"标识",dataIndex:"identify"},{title:"名称",dataIndex:"name",ellipsis:!0},{title:"类型",scopedSlots:{customRender:"isSystem"}},{title:"备注",dataIndex:"remark",ellipsis:!0},{title:"状态",scopedSlots:{customRender:"status"},width:90},{title:"排序",dataIndex:"sort",width:90,sorter:!0},{title:"操作",dataIndex:"action",scopedSlots:{customRender:"action"}}],loadData:function(e){var t=e.pageNo,a=e.pageSize,i=e.sortOrder,s=void 0===i?"ascend":i,n={page:t,limit:a,orders:{sort:"ascend"===s?"asc":"desc"}};return Object(c["g"])(n).then((function(e){var i=e.data.list;return i.map((function(e){e.loading=!1})),{pageNo:t,pageSize:a,data:i,totalCount:0}}))}}},created:function(){},methods:{setPage:function(e){this.page=Object.assign(this.page,e)},handleAdd:function(){this.setPage({visible:!0,title:"添加页面",status:"add",form:this.$form.createForm(this)})},handleSubAdd:function(){var e=this,t=this.page.form.validateFields,a=this.$refs.table,i=this.$notification.success;t((function(t,s){t||Object(c["d"])(s).then((function(t){i({message:"成功",description:"添加成功"}),a.refresh(),e.page.visible=!1}))}))},handleEdit:function(e){var t=this;this.setPage({info:e,visible:!0,title:"编辑 [".concat(e.name,"]"),status:"edit",form:this.$form.createForm(this)}),new Promise((function(e){setTimeout(e,0)})).then((function(){t.page.form.setFieldsValue(o()(e,["identify","name","sort","status","remark"]))}))},handleSubEdit:function(){var e=this,t=this.page.form.validateFields,a=this.$refs.table,i=this.$notification.success;t((function(t,s){if(!t){var n=Object.assign(e.page.info,s);Object(c["h"])(n).then((function(t){i({message:"成功",description:"编辑成功"}),a.refresh(),e.page.visible=!1}))}}))},changeStatus:function(e,t){var a=this;e.loading=!0,setTimeout((function(){e.status=Number(t),Object(c["h"])({id:e.id,status:e.status}).then((function(t){a.$message.success("修改成功"),e.loading=!1}))}),500)},handleRoute:function(e){this.$router.push({name:"HomeLayout",params:{id:e.id}})},handleDel:function(e){var t=this.$refs.table,a=this.$notification.success;this.$confirm({title:"警告",content:"是否删除[ ".concat(e.name," ]"),onOk:function(){Object(c["e"])(e.id).then((function(e){a({message:"成功",description:"删除成功"}),t.refresh()}))}})}}},d=l,u=a("2877"),m=Object(u["a"])(d,i,s,!1,null,null,null);t["default"]=m.exports}}]);
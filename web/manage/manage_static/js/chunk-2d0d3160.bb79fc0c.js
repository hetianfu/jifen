(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0d3160"],{"5aec":function(e,t,a){"use strict";a.r(t);var i=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("a-card",[a("s-table",{ref:"table",attrs:{rowKey:function(e,t){return t},columns:e.columns,data:e.loadData,pagination:e.pagination},scopedSlots:e._u([{key:"isVip",fn:function(t,i){return[a("a-switch",{attrs:{"checked-children":"开","un-checked-children":"关",loading:i.loading,checked:Boolean(i.isVip)},on:{change:function(t){return e.onChangeIsPermanent(i,t)}}})]}},{key:"action",fn:function(t,i){return[a("a",{on:{click:function(t){return e.handleEdit(i)}}},[e._v("编辑")])]}}])})],1),a("a-modal",{attrs:{title:e.user.title},on:{ok:function(t){"add"===e.user.status?e.handleAddSub():e.getHandleEdit()}},model:{value:e.user.visible,callback:function(t){e.$set(e.user,"visible",t)},expression:"user.visible"}},[a("a-form",{attrs:{form:e.user.form,"label-col":{span:4},"wrapper-col":{span:19}}},["edit"===e.user.status?a("a-form-item",{staticClass:"display-none",attrs:{label:"ID"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["id"],expression:"['id']"}],attrs:{placeholder:"Basic usage"}})],1):e._e(),a("a-form-item",{attrs:{label:"套餐名称"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["name"],expression:"['name'] "}],attrs:{placeholder:"请输入套餐名称"}})],1),a("a-form-item",{attrs:{label:"原价"}},[a("a-input-number",{directives:[{name:"decorator",rawName:"v-decorator",value:["originAmount"],expression:"['originAmount'] "}],attrs:{min:0}})],1),a("a-form-item",{attrs:{label:"金额"}},[a("a-input-number",{directives:[{name:"decorator",rawName:"v-decorator",value:["amount"],expression:"['amount'] "}],attrs:{min:0}})],1),a("a-form-item",{attrs:{label:"备注"}},[a("a-textarea",{directives:[{name:"decorator",rawName:"v-decorator",value:["remark"],expression:"['remark'] "}],attrs:{placeholder:"请输入备注"}})],1),a("a-form-item",{attrs:{label:"状态"}},[a("a-radio-group",{directives:[{name:"decorator",rawName:"v-decorator",value:["isVip",{initialValue:1}],expression:"['isVip', { initialValue: 1 }]"}]},[a("a-radio",{attrs:{value:1}},[e._v("开启")]),a("a-radio",{attrs:{value:0}},[e._v("关闭")])],1)],1)],1)],1)],1)},n=[],r=(a("d81d"),a("b0c0"),a("a9e3"),a("d3b7"),a("5530")),o=a("88bc"),s=a.n(o),c=a("2af9"),d=a("7a10"),u={components:{STable:c["p"]},data:function(){return{user:{form:null,visible:!1,status:"add",title:"添加会员"},pagination:{pageSize:10,current:1},columns:[{title:"ID",dataIndex:"id",scopedSlots:{customRender:"id"}},{title:"套餐名称",dataIndex:"name",scopedSlots:{customRender:"name"}},{title:"原价",dataIndex:"originAmount",scopedSlots:{customRender:"originAmount"}},{title:"金额",dataIndex:"amount",scopedSlots:{customRender:"amount"}},{title:"备注",dataIndex:"remark",scopedSlots:{customRender:"remark"}},{title:"状态",dataIndex:"isVip",scopedSlots:{customRender:"isVip"}},{title:"操作",scopedSlots:{customRender:"action"},width:"15%"}],loadData:function(e){var t=e.pageNo,a=e.pageSize,i={page:t,limit:a};return Object(d["i"])(i).then((function(e){var i=e.data,n=i.list,o=i.totalCount,s=n.map((function(e){return Object(r["a"])(Object(r["a"])({},e),{},{loading:!1})}));return{pageNo:t,pageSize:a,data:s,totalCount:o}}))}}},created:function(){},methods:{handleAdd:function(){this.user={form:this.$form.createForm(this),visible:!0,status:"add",title:"添加套餐"}},handleAddSub:function(){var e=this,t=this.user.form.validateFields,a=this.$refs.table,i=this.$notification.success;t((function(t,n){t||Object(d["a"])(n).then((function(t){i({message:"成功",description:"添加成功"}),a.refresh(),e.user.visible=!1}))}))},onChangeIsPermanent:function(e,t){var a=this;e.loading=!0,e.isVip=Number(t),Object(d["k"])({id:e.id,isVip:Number(t)}).then((function(t){setTimeout((function(){e.loading=!1,a.$message.success("修改成功")}),500)}))},handleEdit:function(e){var t=this;this.user={form:this.$form.createForm(this),visible:!0,status:"edit",title:"编辑 [ ".concat(e.name," ]")},new Promise((function(e){setTimeout(e,0)})).then((function(){t.user.form.setFieldsValue(s()(e,["id","name","days","originAmount","amount","isVip"]))}))},getHandleEdit:function(){var e=this,t=this.user.form.validateFields,a=this.$refs.table,i=this.$notification.success;t((function(t,n){t||Object(d["k"])(n).then((function(t){i({message:"成功",description:"编辑成功"}),a.refresh(!0),e.user.visible=!1}))}))},handleDel:function(e){var t=this.$refs.table,a=this.$notification.success;this.$confirm({title:"警告",content:"是否删除 [ ".concat(e.name," ]?"),onOk:function(){Object(d["f"])(e.id).then((function(e){a({message:"成功",description:"删除成功"}),t.refresh()}))}})}}},l=u,m=a("2877"),f=Object(m["a"])(l,i,n,!1,null,null,null);t["default"]=f.exports}}]);
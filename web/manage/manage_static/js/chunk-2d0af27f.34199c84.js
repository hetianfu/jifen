(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0af27f"],{"0cb8":function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("a-card",[n("s-table",{ref:"table",attrs:{rowKey:function(t){return t.id},columns:t.columns,data:t.loadData,pagination:t.pagination},scopedSlots:t._u([{key:"content",fn:function(e){return n("div",{},[n("div",[t._v(t._s(e.content))])])}},{key:"type",fn:function(e){return n("div",{},t._l(e.type,(function(e,a){return n("a-tag",{key:a},[t._v(t._s(t.COMPLAIN_TYPE[e]["title"]))])})),1)}},{key:"action",fn:function(e){return n("div",{},[e.merchantReplyContent?t._e():n("a",{on:{click:function(n){return t.handleEdit(e)}}},[t._v("回复")])])}}])}),n("a-modal",{attrs:{title:"回复"},on:{ok:t.handleSubEdit},model:{value:t.visible,callback:function(e){t.visible=e},expression:"visible"}},[n("a-form",{attrs:{form:t.form,"label-col":{span:4},"wrapper-col":{span:18}}},[n("a-form-item",{staticClass:"display-none",attrs:{label:"ID"}},[n("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["id"],expression:"['id']"}]})],1),n("a-form-item",{attrs:{label:"问题描述"}},[n("a-textarea",{directives:[{name:"decorator",rawName:"v-decorator",value:["content"],expression:"['content']"}],attrs:{disabled:""}})],1),n("a-form-item",{attrs:{label:"回复内容"}},[n("a-textarea",{directives:[{name:"decorator",rawName:"v-decorator",value:["merchantReplyContent",{rules:[{required:!0,message:"请输入回复内容"}]}],expression:"['merchantReplyContent', { rules: [{ required: true, message: '请输入回复内容' }]}]"}]})],1)],1)],1)],1)},i=[],o=(n("d81d"),n("d3b7"),n("ac1f"),n("1276"),n("15fd")),r=n("2af9"),s=n("ed08"),c=n("2f38"),l=n("f121"),d=n("2593"),u=n.n(d),f={components:{STable:r["p"],PopoverText:r["n"]},data:function(){return{visible:!1,form:null,COMPLAIN_TYPE:l["b"],columns:[{title:"联系人",dataIndex:"name"},{title:"联系电话",dataIndex:"telephone"},{title:"类型",scopedSlots:{customRender:"type"}},{title:"订单号",dataIndex:"orderId",width:230},{title:"问题描述",scopedSlots:{customRender:"content"},width:300},{title:"创建时间",customRender:function(t){return"".concat(Object(s["p"])(t.updatedAt))}}],pagination:{pageSize:10,current:1},loadData:function(t){var e=t.pageNo,n=t.pageSize,a={page:e,limit:n};return Object(c["b"])(a).then((function(t){var a=t.data,i=a.list,o=a.totalCount;return i.map((function(t){t.type=t.type.split(",")})),{pageNo:e,pageSize:n,data:i,totalCount:o}}))}}},created:function(){},methods:{handleEdit:function(t){var e=this;this.form=this.$form.createForm(this),this.visible=!0,new Promise((function(t){setTimeout(t,0)})).then((function(){e.form.setFieldsValue(u()(t,["id","content"]))}))},handleSubEdit:function(){var t=this,e=this.$refs.table,n=this.$notification.success;this.form.validateFields((function(a,i){i.content;var r=Object(o["a"])(i,["content"]);a||Object(c["c"])(r).then((function(a){n({message:"成功",description:"回复成功"}),e.refresh(),t.visible=!1}))}))}}},p=f,m=n("2877"),v=Object(m["a"])(p,a,i,!1,null,null,null);e["default"]=v.exports}}]);
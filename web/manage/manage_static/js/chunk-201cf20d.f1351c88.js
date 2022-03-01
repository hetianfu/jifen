(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-201cf20d"],{"013b":function(t,e,a){"use strict";a("13fb")},"13fb":function(t,e,a){},2022:function(t,e,a){"use strict";a("cb25")},c040:function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("page-view",{attrs:{title:"商品分类列表"}},[a("div",{staticClass:"product-category-tree"},[a("a-card",{attrs:{bordered:!1}},[a("div",{staticClass:"table-operator"},[a("a-button",{attrs:{type:"primary",icon:"plus"},on:{click:t.handleAddCategory}},[t._v("添加主分类")])],1),a("s-table",{ref:"table",attrs:{rowKey:function(t){return t.id},columns:t.columns,data:t.loadData,pagination:!1},scopedSlots:t._u([{key:"pic",fn:function(e,i){return[null!==i.pic?a("img",{staticClass:"picImgClass",attrs:{src:i.pic}}):t._e()]}},{key:"status",fn:function(e,i){return[1===i.status?a("a-badge",{attrs:{status:"processing",text:"启用"}}):t._e(),0===i.status?a("a-badge",{attrs:{status:"warning",text:"禁用"}}):t._e()]}},{key:"action",fn:function(e){return["0"===e.parentId?a("a",{on:{click:function(a){return t.handlePushCategory(e)}}},[t._v("添加子分类")]):t._e(),"0"===e.parentId?a("a-divider",{attrs:{type:"vertical"}}):t._e(),a("a",{on:{click:function(a){return t.handleEditCategory(e)}}},[t._v("编辑")]),void 0===e.children?a("a-divider",{attrs:{type:"vertical"}}):t._e(),void 0===e.children?a("a",{staticStyle:{color:"#f5222d"},on:{click:function(a){return t.handleDelete(e)}}},[t._v("删除")]):t._e()]}}])})],1),a("a-modal",{staticClass:"product-category-dialog",attrs:{title:t.confirm.title},on:{ok:function(e){"create"===t.confirm.status?t.createData():t.updateData()}},model:{value:t.confirm.visible,callback:function(e){t.$set(t.confirm,"visible",e)},expression:"confirm.visible"}},[a("a-form",{attrs:{form:t.confirm.form}},[a("a-form-item",{attrs:{label:"分类名称","label-col":t.confirm.labelCol,"wrapper-col":t.confirm.wrapperCol}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["name",{rules:[{required:!0,message:"请输入分类名称"}]}],expression:"['name', {rules: [{ required: true, message: '请输入分类名称' }]}]"}]})],1),a("a-form-item",{attrs:{label:"分类图片","label-col":t.confirm.labelCol,"wrapper-col":t.confirm.wrapperCol}},[a("upload-img",{model:{value:t.imageUrl,callback:function(e){t.imageUrl=e},expression:"imageUrl"}})],1),a("a-form-item",{attrs:{label:"分类状态","label-col":t.confirm.labelCol,"wrapper-col":t.confirm.wrapperCol}},[a("a-radio-group",{directives:[{name:"decorator",rawName:"v-decorator",value:["status",{initialValue:1}],expression:"['status', { initialValue: 1 }]"}]},[a("a-radio",{attrs:{value:1}},[t._v("启用")]),a("a-radio",{attrs:{value:0}},[t._v("禁用")])],1)],1),a("a-form-item",{attrs:{label:"分类排序","label-col":t.confirm.labelCol,"wrapper-col":t.confirm.wrapperCol}},[a("a-input-number",{directives:[{name:"decorator",rawName:"v-decorator",value:["showOrder",{initialValue:0}],expression:"['showOrder', { initialValue: 0 }]"}],staticClass:"show-order",attrs:{min:0,max:1e7,precision:0}})],1)],1)],1),a("a-modal",{attrs:{title:"添加商品",centered:"",width:"1200px"},on:{ok:function(e){return t.getAddGoodsModal()}},model:{value:t.addCategoryGoods.visible,callback:function(e){t.$set(t.addCategoryGoods,"visible",e)},expression:"addCategoryGoods.visible"}},[a("add-category-goods",{attrs:{visible:t.addCategoryGoods.visible},on:{change:t.getSelectProduct}})],1)],1)])},r=[],s=(a("b0c0"),a("d3b7"),a("5530")),o=a("88bc"),n=a.n(o),c=a("2af9"),l=a("66da"),d=a("ed08"),u=a("365c"),f=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"s-table-avatar cursor-pointer",on:{click:t.parseImg}},[a("div",{staticClass:"image-curtain"},[t._m(0),a("a-avatar",{attrs:{src:t.src,size:38}})],1),a("a-modal",{attrs:{footer:null,width:"800px"},on:{click:t.handleImgCancel},model:{value:t.visible,callback:function(e){t.visible=e},expression:"visible"}},[a("img",{staticStyle:{width:"100%"},attrs:{src:t.src}})])],1)},m=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"curtain"},[a("div",{staticClass:"eye"},[a("div",{staticClass:"super-icon-font font-size-fourteen"})])])}],p={props:{src:{type:String,default:""},name:{type:String,default:""}},data:function(){return{visible:!1}},methods:{parseImg:function(){this.visible=!0},handleImgCancel:function(){this.visible=!1}}},h=p,v=(a("2022"),a("2877")),b=Object(v["a"])(h,f,m,!1,null,null,null),g=b.exports,C=a("c8c3"),y={name:"ProductCategoryTree",components:{STable:c["p"],AddCategoryGoods:c["a"],STableAvatar:g,PageView:C["a"]},data:function(){var t=Object(d["i"])();return{pagination:{pageSize:10,current:1},headers:t,imageUrl:"",action:u["b"].uploadImgs.addCategoryImage,columns:[{title:"排序",dataIndex:"showOrder",sorter:!0},{title:"ID",dataIndex:"id",scopedSlots:{customRender:"id"}},{title:"分类图片",dataIndex:"pic",scopedSlots:{customRender:"pic"}},{title:"分类名称",dataIndex:"name"},{title:"状态",dataIndex:"status",scopedSlots:{customRender:"status"}},{title:"操作",scopedSlots:{customRender:"action"}}],loadData:function(t){var e=t.pageNo,a=t.pageSize,i=t.sortOrder,r=void 0===i?"descend":i,s={page:e,limit:a};return s.orders={show_order:"ascend"===r?"desc":"asc"},Object(l["e"])(s).then((function(t){var i=Object(d["u"])(t.data);return{pageNo:e,pageSize:a,data:i,totalCount:0}}))},confirm:{form:null,temp:{},title:"",status:"",visible:!1,labelCol:{span:8},wrapperCol:{span:12}},addCategoryGoods:{select:[],visible:!1},categoryId:""}},created:function(){},methods:{handleAddCategory:function(){this.confirm.temp={},this.confirm.status="create",this.confirm.form=this.$form.createForm(this),this.confirm.title="添加分类",this.confirm.visible=!0,this.imageUrl=""},handlePushCategory:function(t){this.confirm.temp=t,this.confirm.status="create",this.confirm.form=this.$form.createForm(this),this.confirm.title="".concat(t.name," > 添加分类"),this.confirm.visible=!0,this.imageUrl=""},createData:function(){var t=this,e=this.confirm,a=e.form.validateFields,i=e.temp,r=this.$refs.table,o=this.$notification.success,n=this.imageUrl;a((function(e,a){if(!e){var c=Object(s["a"])(Object(s["a"])({},a),{},{parentId:i.id,pic:n||void 0});Object(l["a"])(c).then((function(e){o({message:"成功",description:"添加成功"}),r.refresh(!0),t.confirm.visible=!1}))}}))},handleEditCategory:function(t){this.confirm.temp=t,this.confirm.status="update",this.imageUrl=t.pic,this.confirm.form=this.$form.createForm(this),this.confirm.title="编辑 [ ".concat(t.name," ] 分类");var e=this.confirm.form;new Promise((function(t){setTimeout(t,0)})).then((function(){e.setFieldsValue(n()(t,["name","status","showOrder","synOnline"]))})),this.confirm.visible=!0},updateData:function(){var t=this,e=this.confirm,a=e.form.validateFields,i=e.temp.id,r=this.$refs.table,o=this.$notification.success,n=this.imageUrl;a((function(e,a){if(!e){var c=Object(s["a"])(Object(s["a"])({},a),{},{id:i,pic:n});Object(l["f"])(c).then((function(e){o({message:"成功",description:"编辑成功"}),r.refresh(!0),t.confirm.visible=!1}))}}))},handleDelete:function(t){var e=t.id,a=t.name,i=this.$refs.table,r=this.$notification.success;this.$confirm({title:"警告",content:"是否删除 [ ".concat(a," ] 分类?"),onOk:function(){Object(l["d"])(e).then((function(t){r({message:"成功",description:"删除成功"}),i.refresh(!0)}))}})},getSelectProduct:function(t){this.addCategoryGoods.select=t},addGoods:function(t){this.categoryId=t.id,this.addCategoryGoods.select=[],this.addCategoryGoods.visible=!0},getAddGoodsModal:function(){var t=this,e=this.$refs.table,a=this.$notification.success,i={productList:this.addCategoryGoods.select,categoryId:this.categoryId};Object(l["c"])(i).then((function(i){a({message:"成功",description:"成功添加"+i.data+"个商品"}),t.addCategoryGoods.visible=!1,e.refresh(!0)}))},uploadHandleChange:function(t){var e=t.file;if("done"===e.status){var a=e.response.data.accessUrl;this.imageUrl=a}}}},w=y,_=(a("013b"),Object(v["a"])(w,i,r,!1,null,"60c160c3",null));e["default"]=_.exports},cb25:function(t,e,a){}}]);
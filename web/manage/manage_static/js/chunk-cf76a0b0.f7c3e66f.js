(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-cf76a0b0"],{"72c0":function(t,e,a){},"8a3f":function(t,e,a){"use strict";a("72c0")},"8f98":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("a-card",[a("div",{staticClass:"margin-bottom-16"},[a("a-row",{attrs:{gutter:10}},[a("a-col",{attrs:{span:6}},[a("a-input",{attrs:{placeholder:"标题",allowClear:""},model:{value:t.queryParam.title,callback:function(e){t.$set(t.queryParam,"title",e)},expression:"queryParam.title"}})],1),a("a-col",{attrs:{span:4}},[a("a-select",{staticClass:"width-percent-100",attrs:{placeholder:"展示状态",allowClear:""},model:{value:t.queryParam.isShow,callback:function(e){t.$set(t.queryParam,"isShow",e)},expression:"queryParam.isShow"}},[a("a-select-option",{attrs:{value:"1"}},[t._v("开启")]),a("a-select-option",{attrs:{value:"0"}},[t._v("关闭")])],1)],1),a("a-col",{attrs:{span:8}},[a("a-button",{attrs:{type:"primary"},on:{click:function(e){return t.$refs.table.refresh(!0)}}},[t._v("查询")]),a("a-button",{staticClass:"margin-left-10",on:{click:function(){return t.queryParam={}}}},[t._v("重置")])],1)],1)],1),a("a-button",{attrs:{type:"primary"},on:{click:t.onChangeAdd}},[a("a-icon",{attrs:{type:"plus"}}),t._v("添加资讯")],1),a("s-table",{ref:"table",attrs:{rowKey:function(t,e){return e},columns:t.columns,data:t.loadData,pagination:t.pagination},scopedSlots:t._u([{key:"coverImg",fn:function(t){return[a("preview",{attrs:{img:t.coverImg}})]}},{key:"title",fn:function(t){return[a("s-table-text",{attrs:{text:t}})]}},{key:"cid",fn:function(e){return t._l(t.cidList,(function(n){return e.cid===n.id?a("a-tag",{key:n.id},[t._v(t._s(n.title))]):t._e()}))}},{key:"subTitle",fn:function(t){return[a("s-table-text",{attrs:{text:t}})]}},{key:"isShow",fn:function(e){return[a("a-switch",{attrs:{"checked-children":"开启","un-checked-children":"关闭",loading:e.loading,checked:Boolean(e.isShow)},on:{change:function(a){return t.onChangeIsShow(e,a)}}})]}},{key:"action",fn:function(e,n){return[a("a",{on:{click:function(e){return t.getHandleEdit(n)}}},[t._v("编辑")]),a("a-divider",{attrs:{type:"vertical"}}),a("a",{staticClass:"color-red",on:{click:function(e){return t.getHandleDel(n)}}},[t._v("删除")])]}}])})],1)],1)},i=[],s=(a("d81d"),a("a9e3"),a("5530")),r=a("5880"),o=a("ba41"),c=a("2af9"),l=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"s-table-text"},[a("a-popover",{attrs:{placement:"topLeft",arrowPointAtCenter:"",overlayStyle:t.textStyle}},[a("template",{slot:"content"},[t._v(t._s(t.text))]),a("span",[t._v(t._s(t._f("textOverflowEllipsis")(t.text,0,15)))])],2)],1)},u=[],d={props:{text:{required:!0}},watch:{text:function(t){this.text=t}},data:function(){return{textStyle:{"max-width":"400px"}}}},f=d,p=a("2877"),h=Object(p["a"])(f,l,u,!1,null,"c5fc0fa2",null),m=h.exports,v=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"s-table-avatar cursor-pointer",on:{click:t.parseImg}},[a("div",{staticClass:"image-curtain"},[t._m(0),a("a-avatar",{attrs:{src:t.src,size:38}})],1),a("a-modal",{attrs:{footer:null,width:"800px"},on:{click:t.handleImgCancel},model:{value:t.visible,callback:function(e){t.visible=e},expression:"visible"}},[a("img",{staticStyle:{width:"100%"},attrs:{src:t.src}})])],1)},g=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"curtain"},[a("div",{staticClass:"eye"},[a("div",{staticClass:"super-icon-font font-size-fourteen"})])])}],b={props:{src:{type:String,default:""},name:{type:String,default:""}},data:function(){return{visible:!1}},methods:{parseImg:function(){this.visible=!0},handleImgCancel:function(){this.visible=!1}}},w=b,y=(a("8a3f"),Object(p["a"])(w,v,g,!1,null,null,null)),S=y.exports,x=a("8256"),_=a("17eb"),k={components:{Template:_["a"],STable:c["p"],STableText:m,STableAvatar:S,Tinymce:x["a"],preview:c["w"]},data:function(){var t=this;return{queryParam:{},pagination:{pageSize:10,current:1},cidList:[],imageUrl:"",content:"",columns:[{title:"ID",dataIndex:"id",width:70},{title:"封面图",scopedSlots:{customRender:"coverImg"},width:150},{title:"标题",dataIndex:"title",scopedSlots:{customRender:"title"}},{title:"简介",dataIndex:"subTitle",scopedSlots:{customRender:"subTitle"}},{title:"分类",scopedSlots:{customRender:"cid"},width:150},{title:"阅读次数",dataIndex:"readNumber",width:150},{title:"是否展示",scopedSlots:{customRender:"isShow"},width:150},{title:"排序",dataIndex:"showOrder",width:100},{title:"操作",scopedSlots:{customRender:"action"},width:190}],loadData:function(e){var a=e.pageNo,n=e.pageSize,i=t.queryParam,s=i.title,r=i.isShow,c={page:a,limit:n,title:s||void 0,isShow:r};return Object(o["g"])(c).then((function(t){t.data.list.map((function(t){t.loading=!1}));var e=t.data,i=e.list,s=e.totalCount;return{pageNo:a,pageSize:n,data:i,totalCount:s}}))}}},created:function(){this.getCategoryList()},methods:Object(s["a"])(Object(s["a"])({},Object(r["mapActions"])(["setNewsInfo"])),{},{getCategoryList:function(){var t=this;Object(o["e"])().then((function(e){t.cidList=e.data}))},onChangeAdd:function(){this.$router.push({name:"informationAdd"})},getHandleEdit:function(t){this.$router.push({name:"informationEdit",params:{id:t.id}})},onChangeIsShow:function(t,e){var a=this;t.loading=!0,t.isShow=Number(e),Object(o["i"])({id:t.id,isShow:t.isShow}).then((function(e){setTimeout((function(){t.loading=!1,a.$message.success("修改成功")}),500)}))},getHandleDel:function(t){var e=this.$refs.table,a=this.$notification.success;this.$confirm({title:"警告",content:"是否删除[ ".concat(t.title," ]"),onOk:function(){Object(o["d"])(t.id).then((function(t){a({message:"成功",description:"删除成功"}),e.refresh(!0)}))}})}})},C=k,I=Object(p["a"])(C,n,i,!1,null,null,null);e["default"]=I.exports}}]);
(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-6fbe639a"],{"41d5":function(e,t,a){},7488:function(e,t,a){"use strict";a.d(t,"d",(function(){return i})),a.d(t,"a",(function(){return n})),a.d(t,"e",(function(){return l})),a.d(t,"b",(function(){return c})),a.d(t,"c",(function(){return d}));a("99af");var r=a("365c"),o=a("b775"),s=r["b"].shopMag;function i(e){return Object(o["b"])({url:s.getShopPage,method:"get",params:e})}function n(e){return Object(o["b"])({url:s.addshop,method:"post",data:e})}function l(e){return Object(o["b"])({url:s.updateShopById,method:"patch",data:e})}function c(e){return Object(o["b"])({url:"".concat(s.deleteShopById).concat(e),method:"delete"})}function d(e){return Object(o["b"])({url:s.getShopById+e,method:"get"})}},"7d7a":function(e,t,a){"use strict";a("e3e6")},"8fa8":function(e,t,a){"use strict";a("41d5")},d60f:function(e,t,a){"use strict";a.r(t);var r=function(){var e=this,t=this,a=t.$createElement,r=t._self._c||a;return r("div",{staticClass:"page-header-index-wide"},[r("a-card",{style:{height:"100%"},attrs:{bordered:!1,bodyStyle:{padding:"16px 0",height:"100%"}}},[r("div",{staticClass:"account-settings-info-main",class:t.device},[r("div",{staticClass:"account-settings-info-left"},[r("a-menu",{style:{border:"0",width:"mobile"==t.device?"560px":"auto"},attrs:{mode:"mobile"==t.device?"horizontal":"inline",multiple:!1,type:"inner"},on:{click:t.onOpenChange},model:{value:t.selectedKeys,callback:function(e){t.selectedKeys=e},expression:"selectedKeys"}},[r("a-menu-item",{key:"BasicInfo"},[t._v("基本信息")]),r("a-menu-item",{key:"StoreImg"},[t._v("门店图片")])],1)],1),r("div",{staticClass:"account-settings-info-right"},[r("div",{staticClass:"account-settings-info-title"},[r("span",[t._v(t._s(t.textMap[t.currentComponet]))])]),r("a-form",{directives:[{name:"show",rawName:"v-show",value:"BasicInfo"===t.currentComponet,expression:"currentComponet === 'BasicInfo'"}],attrs:{form:t.basicForm}},[r("basic-info",{attrs:{form:t.basicForm}})],1),r("a-form",{directives:[{name:"show",rawName:"v-show",value:"StoreImg"===t.currentComponet,expression:"currentComponet === 'StoreImg'"}],attrs:{form:t.imgForm}},[r("store-img",{attrs:{form:t.imgForm}})],1)],1)])]),r("footer-tool-bar",{staticClass:"display-flex justify-content-center",style:{width:t.isSideMenu()&&t.isDesktop()?"calc(100% - "+(t.sidebarOpened?200:80)+"px)":"100%"}},[r("a-button",{attrs:{type:"primary"},on:{click:t.handleSubmit}},[t._v("添加")]),r("a-button",{staticClass:"margin-left-10",on:{click:function(){return e.$router.go(-1)}}},[t._v("取消")])],1)],1)},o=[],s=(a("d81d"),a("b64b"),a("ade3")),i=a("5a70"),n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"basic-info"},[a("a-row",{attrs:{gutter:16}},[a("a-col",{attrs:{md:24,lg:18}},[a("a-form-item",{attrs:{labelCol:e.labelCol,wrapperCol:e.wrapperCol,label:"门店名称"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["shopName",{rules:[{required:!0,message:"请输入门店名称"}]}],expression:"['shopName', { rules: [{ required: true, message: '请输入门店名称' }] }]"}],attrs:{placeholder:"门店名称"}})],1),a("a-form-item",{attrs:{labelCol:e.labelCol,wrapperCol:{span:10},label:"联系电话"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["phone",{rules:[{required:!0,message:"请输入联系电话"}]}],expression:"['phone', { rules: [{ required: true, message: '请输入联系电话' }] }]"}],attrs:{placeholder:"联系电话"}})],1),a("a-form-item",{staticClass:"padding-bottom-0 margin-bottom-0",attrs:{labelCol:e.labelCol,wrapperCol:e.wrapperCol,label:"店铺经度"}},[a("a-input-group",{attrs:{compact:""}},[a("a-form-item",{staticClass:"form-item-lat"},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["lat"],expression:"['lat']"}],staticClass:"shop-latitude border-trr-0 border-brr-0",attrs:{placeholder:"经度"}})],1),a("a-form-item",{staticClass:"form-item-lng"},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["lng"],expression:"['lng']"}],staticClass:"shop-longitude border-radius-0",attrs:{placeholder:"纬度"}})],1),a("a-form-item",[a("a-button",{staticClass:"border-tlr-0 border-blr-0",on:{click:e.handleSelectCoordinate}},[e._v("选择坐标")])],1)],1)],1),a("a-form-item",{attrs:{labelCol:e.labelCol,wrapperCol:e.wrapperCol,label:"门店详细地址"}},[a("a-textarea",{directives:[{name:"decorator",rawName:"v-decorator",value:["address"],expression:"['address']"}],attrs:{placeholder:"门店详细地址","allow-clear":""}})],1),a("a-form-item",{attrs:{labelCol:e.labelCol,wrapperCol:{span:6},label:"营业时间",extra:"格式：如09:00:00-16:30:00"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["businessTime"],expression:"['businessTime']"}],attrs:{placeholder:"营业时间"}})],1),a("a-form-item",{attrs:{labelCol:e.labelCol,wrapperCol:e.wrapperCol,label:"店铺简介"}},[a("a-textarea",{directives:[{name:"decorator",rawName:"v-decorator",value:["detail"],expression:"['detail']"}],attrs:{placeholder:"店铺简介","allow-clear":""}})],1),a("a-form-item",{attrs:{labelCol:e.labelCol,wrapperCol:e.wrapperCol,label:"状态"}},[a("a-radio-group",{directives:[{name:"decorator",rawName:"v-decorator",value:["status",{initialValue:1}],expression:"['status', { initialValue: 1 }]"}]},[a("a-radio",{attrs:{value:1}},[e._v("正式营业")]),a("a-radio",{attrs:{value:0}},[e._v("未上线")]),a("a-radio",{attrs:{value:-1}},[e._v("已关闭")])],1)],1)],1)],1),a("a-modal",{attrs:{maskClosable:!1,title:"请选择地点",width:"800px",centered:!0},on:{ok:e.confirmLocate},model:{value:e.locate.visible,callback:function(t){e.$set(e.locate,"visible",t)},expression:"locate.visible"}},[a("div",{staticStyle:{width:"100%",height:"650px"},attrs:{id:"map-container"}})])],1)},l=[],c=(a("99af"),a("b0c0"),a("ac1f"),a("5319"),a("88bc")),d=a.n(c),m=a("ed08"),u={name:"BasicInfo",components:{},data:function(){return{labelCol:{span:5},wrapperCol:{span:18},locate:{visible:!1}}},methods:{setLocate:function(e){this.locate=Object.assign(this.locate,e)},handleSelectCoordinate:function(){var e=this,t=this.$attrs.form.getFieldValue,a=t("lat"),r=t("lng");this.setLocate({visible:!0}),this.$nextTick((function(){e.initMap(a,r)}))},confirmLocate:function(){var e=this.locate,t=this.$attrs.form.setFieldsValue,a=d()(e,["lat","lng","address"]);t(a),this.setLocate({visible:!1})},initMap:function(e,t){var a=this,r=window.qq,o=new r.maps.LatLng(e,t),s=new r.maps.Map(document.getElementById("map-container"),{zoom:16,mapTypeId:r.maps.MapTypeId.ROADMAP}),i=function(e,t,r){a.setLocate({lat:String(e).replace(/^(.*\..{5}).*$/,"$1"),lng:String(t).replace(/^(.*\..{5}).*$/,"$1"),address:r})},n=new r.maps.CityService({complete:function(e){var t=e.detail,a=t.latLng,r=t.name;i(a.lat,a.lng,r),s.setCenter(a),l.setPosition(e.detail.latLng)}}),l=new r.maps.Marker({map:s,animation:r.maps.MarkerAnimation.BOUNCE}),c=function(e){l.setPosition(e.latLng)};e||t?(s.setCenter(o),l.setPosition(o)):n.searchLocalCity(),r.maps.event.addListener(s,"click",(function(e){var t=e.latLng,a=t.lat,r=t.lng;Object(m["g"])("location=".concat(a,",").concat(r),(function(e){var t=e.result.address;return i(a,r,t)}))})),r.maps.event.addListener(s,"click",c)}}},p=u,f=(a("8fa8"),a("2877")),g=Object(f["a"])(p,n,l,!1,null,"423cbe62",null),h=g.exports,b=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("a-row",{attrs:{gutter:16}},[a("a-col",{attrs:{md:24,lg:18}},[a("a-form-item",{attrs:{labelCol:e.labelCol,wrapperCol:e.wrapperCol,label:"门店Logo"}},[a("upload-img",{directives:[{name:"decorator",rawName:"v-decorator",value:["logo",{rules:[{required:!0,message:"请上传门店Logo"}]}],expression:"['logo', { rules: [{ required: true, message: '请上传门店Logo' }] }]"}]})],1),a("a-form-item",{attrs:{labelCol:e.labelCol,wrapperCol:e.wrapperCol,label:"门店照"}},[a("upload-img",{directives:[{name:"decorator",rawName:"v-decorator",value:["frontImg",{rules:[{required:!0,message:"请上传门店照"}]}],expression:"['frontImg', { rules: [{ required: true, message: '请上传门店照' }] }]"}]})],1),a("a-form-item",{attrs:{labelCol:e.labelCol,wrapperCol:e.wrapperCol,label:"门店照"}},[a("upload-img",{directives:[{name:"decorator",rawName:"v-decorator",value:["backImg"],expression:"['backImg']"}]})],1)],1)],1)],1)},v=[],C=a("365c"),w=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("a-upload",{attrs:{action:e.action,headers:e.headers,"show-upload-list":!1,beforeUpload:e.onBeforeUpload,"list-type":"picture-card"},on:{change:e.onChangeUpload}},[a("div",[e.image&&"loading"!==e.image?a("img",{staticStyle:{width:"70px",height:"70px"},attrs:{src:e.image,alt:"avatar"}}):a("div",[a("a-icon",{attrs:{type:e.image?"loading":"plus"}}),a("div",{staticClass:"ant-upload-text"},[e._v(e._s(e.image?"文件上传中":"上传"))])],1)])])],1)},I=[],y={props:{value:{},action:{type:String,required:!0},showUploadList:{type:Boolean,default:!0}},model:{prop:"value",event:"change"},watch:{value:function(e){this.image=e}},data:function(){var e=Object(m["i"])();return{headers:e,image:""}},methods:{onBeforeUpload:function(e){var t="image/jpeg"===e.type||"image/png"===e.type;t||this.$message.error("你只能上传 JPG或PNG 文件！");var a=e.size/1024/1024<2;return a||this.$message.error("图像必须小于2MB！"),t&&a},onChangeUpload:function(e){var t=e.file;e.fileList;if("uploading"===t.status&&(this.image="loading"),"done"===t.status){var a=t.response.data;this.image=a.accessUrl,this.$emit("change",a.accessUrl)}}}},x=y,k=Object(f["a"])(x,w,I,!1,null,null,null),F=k.exports,O={name:"ProductInfo",components:{UploadImage:F},data:function(){var e=Object(m["i"])();return{labelCol:{span:5},wrapperCol:{span:19},headers:e,action:C["b"].uploadImgs.addShopImage}},methods:{handleChangeLogo:function(e){var t=this.$attrs.form.setFieldsValue;t({logo:e})},handleChangeFrontImg:function(e){var t=this.$attrs.form.setFieldsValue;t({frontImg:e})},handleChangeBackImg:function(e){var t=this.$attrs.form.setFieldsValue;t({backImg:e})}}},S=O,$=Object(f["a"])(S,b,v,!1,null,"67f6dd8e",null),j=$.exports,L=a("7488"),B=a("ac0d"),_={components:{FooterToolBar:i["a"],basicInfo:h,storeImg:j},mixins:[B["b"],B["c"]],data:function(){return{currentComponet:"",selectedKeys:["BasicInfo"],textMap:{BasicInfo:"基本信息",StoreImg:"门店图片"},basicForm:null,imgForm:null,info:{logo:"",frontImg:"",backImg:"",shopName:"",phone:"",lat:"",lng:"",address:"",businessTime:"",detail:"",status:1}}},created:function(){this.currentComponet="BasicInfo",this.createForm()},methods:{onOpenChange:function(e){var t=e.key;this.currentComponet=t},setInfo:function(e){this.info=Object.assign(this.info,e)},createForm:function(){var e=this;this.basicForm=this.$form.createForm(this,{onFieldsChange:function(t,a){Object.keys(a).map((function(t){var r=a[t]["value"],o=Object(s["a"])({},t,r);e.setInfo(o)}))}}),this.imgForm=this.$form.createForm(this,{onFieldsChange:function(t,a){Object.keys(a).map((function(t){var r=a[t]["value"],o=Object(s["a"])({},t,r);e.setInfo(o)}))}})},handleSubmit:function(){var e=this,t=this.basicForm,a=this.imgForm,r=this.$notification.success,o=this.info,s=!1;t.validateFields((function(t,a){t&&(e.selectedKeys=["BasicInfo"],e.currentComponet="BasicInfo",s=!0)})),s||(a.validateFields((function(t,a){t&&(e.selectedKeys=["StoreImg"],e.currentComponet="StoreImg",s=!0)})),s||Object(L["a"])(o).then((function(t){r({message:"成功",description:"添加成功"}),e.$router.push({name:"StoreManage"})})))}}},N=_,M=(a("7d7a"),a("dc38"),Object(f["a"])(N,r,o,!1,null,"356c738a",null));t["default"]=M.exports},d78a:function(e,t,a){},dc38:function(e,t,a){"use strict";a("d78a")},e3e6:function(e,t,a){}}]);
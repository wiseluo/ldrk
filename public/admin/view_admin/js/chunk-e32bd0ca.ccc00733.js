(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-e32bd0ca"],{"0c29":function(t,e,a){"use strict";var n=a("de49"),i=a.n(n);i.a},"50cb":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("Card",{staticClass:"ivu-mt",attrs:{bordered:!1,"dis-hover":""}},[a("Form",{ref:"formValidate",attrs:{"label-width":t.labelWidth,"label-position":t.labelPosition},nativeOn:{submit:function(t){t.preventDefault()}}},[a("Row",{attrs:{gutter:16,type:"flex"}},[a("Col",{staticStyle:{"line-height":"40px"},attrs:{xs:10,sm:12,md:16,lg:18}},[a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"7"}},[a("span",{staticClass:"shtitle"},[t._v("姓名 ")])]),a("Col",{attrs:{span:"17"}},[a("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"name",clearable:""},model:{value:t.formValidate.real_name,callback:function(e){t.$set(t.formValidate,"real_name",e)},expression:"formValidate.real_name"}})],1)],1),a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"7"}},[a("span",{staticClass:"shtitle"},[t._v("身份证 ")])]),a("Col",{attrs:{span:"17"}},[a("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"name",clearable:""},model:{value:t.formValidate.id_card,callback:function(e){t.$set(t.formValidate,"id_card",e)},expression:"formValidate.id_card"}})],1)],1),a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"7"}},[a("span",{staticClass:"shtitle"},[t._v("手机号 ")])]),a("Col",{attrs:{span:"17"}},[a("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"name",clearable:""},model:{value:t.formValidate.phone,callback:function(e){t.$set(t.formValidate,"phone",e)},expression:"formValidate.phone"}})],1)],1),a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"7"}},[a("span",{staticClass:"shtitle"},[t._v("代领人姓名 ")])]),a("Col",{attrs:{span:"17"}},[a("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"name",clearable:""},model:{value:t.formValidate.agent_name,callback:function(e){t.$set(t.formValidate,"agent_name",e)},expression:"formValidate.agent_name"}})],1)],1),a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"7"}},[a("span",{staticClass:"shtitle"},[t._v("代领人身份证号 ")])]),a("Col",{attrs:{span:"17"}},[a("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"name",clearable:""},model:{value:t.formValidate.agent_idcard,callback:function(e){t.$set(t.formValidate,"agent_idcard",e)},expression:"formValidate.agent_idcard"}})],1)],1),a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"7"}},[a("span",{staticClass:"shtitle"},[t._v("代领人手机号 ")])]),a("Col",{attrs:{span:"17"}},[a("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"name",clearable:""},model:{value:t.formValidate.agent_phone,callback:function(e){t.$set(t.formValidate,"agent_phone",e)},expression:"formValidate.agent_phone"}})],1)],1)],1),a("Col",{staticClass:"ivu-text-right userFrom",attrs:{xs:14,sm:12,md:8,lg:6}},[a("Button",{staticClass:"mr15",attrs:{type:"primary",icon:"ios-search",label:"default"},on:{click:t.Searchs}},[t._v("搜索")]),a("Button",{staticClass:"ResetSearch",on:{click:function(e){return t.reset("quarantine")}}},[t._v("重置")])],1)],1)],1),a("Form",[a("Row",{staticClass:"mt20",attrs:{type:"flex"}},[a("Button",{staticClass:"bnt mr15",attrs:{type:"success",loading:t.button_loading},on:{click:t.batchExport}},[t._v(t._s(t.downloadstr)+"\n        ")])],1)],1),a("Table",{ref:"table",staticClass:"mt25",attrs:{columns:t.columns,data:t.list,"no-userFrom-text":"暂无数据","no-filtered-userFrom-text":"暂无筛选结果",loading:t.loading,"highlight-row":""},scopedSlots:t._u([{key:"index",fn:function(e){e.row;var n=e.index;return[a("span",[t._v(" "+t._s(n+1)+" ")])]}},{key:"gender",fn:function(e){var n=e.row;e.index;return[1===n.gender?a("span",[t._v("男")]):t._e(),2===n.gender?a("span",[t._v("女")]):t._e(),0===n.gender?a("span",[t._v("未知")]):t._e()]}},{key:"qrcode",fn:function(t){var e=t.row;t.index;return[a("viewer",[a("div",{staticClass:"tabBox_img"},[a("img",{directives:[{name:"lazy",rawName:"v-lazy",value:e.qrcode,expression:"row.qrcode"}]})])])]}},{key:"operation",fn:function(e){var n=e.row,i=e.index;return[a("a",{on:{click:function(e){return t.goedit(n)}}},[t._v("修改")]),a("span",[t._v(" ")]),a("a",{on:{click:function(e){return t.godelete(n,"删除",i)}}},[t._v("删除")])]}}])}),a("div",{staticClass:"acea-row row-right page"},[a("Page",{attrs:{total:t.total,current:t.formValidate.page,"show-elevator":"","show-total":"","show-sizer":"","page-size-opts":[5,10,15,20],"page-size":t.formValidate.size},on:{"on-page-size-change":t.sizeChange,"on-change":t.pageChange}})],1),a("Modal",{attrs:{title:t.edittitle,width:"550","class-name":"vertical-center-modal"},model:{value:t.editmodal,callback:function(e){t.editmodal=e},expression:"editmodal"}},[a("Form",{ref:"editformValidate",attrs:{model:t.editformValidate,rules:t.ruleValidate,"label-width":120}},[a("FormItem",{attrs:{label:"被代领人姓名",prop:"real_name"}},[a("Input",{staticStyle:{width:"380px"},attrs:{placeholder:"请输入被代领人姓名"},model:{value:t.editformValidate.real_name,callback:function(e){t.$set(t.editformValidate,"real_name",e)},expression:"editformValidate.real_name"}})],1),a("FormItem",{attrs:{label:"被代领人手机号",prop:"phone"}},[a("Input",{staticStyle:{width:"380px"},attrs:{placeholder:"请输入被代领人手机号"},model:{value:t.editformValidate.phone,callback:function(e){t.$set(t.editformValidate,"phone",e)},expression:"editformValidate.phone"}})],1),a("FormItem",{attrs:{label:"被代领人紧急手机号",prop:"urgent_phone"}},[a("Input",{staticStyle:{width:"380px"},attrs:{placeholder:"请输入被代领人紧急手机号"},model:{value:t.editformValidate.urgent_phone,callback:function(e){t.$set(t.editformValidate,"urgent_phone",e)},expression:"editformValidate.urgent_phone"}})],1),a("FormItem",{attrs:{label:"代领人姓名",prop:"agent_name"}},[a("Input",{staticStyle:{width:"380px"},attrs:{placeholder:"请输入代领人姓名"},model:{value:t.editformValidate.agent_name,callback:function(e){t.$set(t.editformValidate,"agent_name",e)},expression:"editformValidate.agent_name"}})],1),a("FormItem",{attrs:{label:"代领人身份证号",prop:"agent_idcard"}},[a("Input",{staticStyle:{width:"380px"},attrs:{placeholder:"请输入代领人身份证号"},model:{value:t.editformValidate.agent_idcard,callback:function(e){t.$set(t.editformValidate,"agent_idcard",e)},expression:"editformValidate.agent_idcard"}})],1),a("FormItem",{attrs:{label:"代领人手机号",prop:"agent_phone"}},[a("Input",{staticStyle:{width:"380px"},attrs:{placeholder:"请输入代领人手机号"},model:{value:t.editformValidate.agent_phone,callback:function(e){t.$set(t.editformValidate,"agent_phone",e)},expression:"editformValidate.agent_phone"}})],1)],1),a("div",{attrs:{slot:"footer"},slot:"footer"},[a("Button",{attrs:{type:"primary"},on:{click:function(e){return t.handleSubmit("editformValidate")}}},[t._v("提交")])],1)],1)],1)],1)},i=[],r=a("a34a"),s=a.n(r),o=a("2f62"),l=a("b6bd");function d(t){return Object(l["a"])({url:"personal/code",method:"get",params:t})}function c(t){return Object(l["a"])({url:"csv/personal/code",method:"get",params:t})}function m(t,e){return Object(l["a"])({url:"personal/code/".concat(t),method:"put",params:e})}var u=a("8593"),p=a("d00d");function h(t,e,a,n,i,r,s){try{var o=t[r](s),l=o.value}catch(d){return void a(d)}o.done?e(l):Promise.resolve(l).then(n,i)}function f(t){return function(){var e=this,a=arguments;return new Promise((function(n,i){var r=t.apply(e,a);function s(t){h(r,n,i,s,o,"next",t)}function o(t){h(r,n,i,s,o,"throw",t)}s(void 0)}))}}function g(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,n)}return a}function b(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?g(a,!0).forEach((function(e){v(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):g(a).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}function v(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}var _={name:"personFyCode_list",mixins:[p["a"]],data:function(){return{roleid:0,list:[],loading:!1,editformValidate:{phone:"",urgent_phone:"",agent_name:"",agent_idcard:"",agent_phone:""},edittitle:"修改个人清册信息",editmodal:!1,ruleValidate:{agent_name:[{required:!0,message:"必填",trigger:"blur"}],agent_idcard:[{required:!0,message:"必填",trigger:"blur"}],agent_phone:[{required:!0,message:"必填",trigger:"blur"}]},columns:[{title:"序号",slot:"index",minWidth:50},{title:"姓名",key:"real_name",minWidth:70},{title:"性别",slot:"gender",minWidth:70},{title:"身份证号",key:"id_card",minWidth:70},{title:"手机号",key:"phone",minWidth:70},{title:"紧急手机号",key:"urgent_phone",minWidth:70},{title:"代领人姓名",key:"agent_name",minWidth:70},{title:"代领人身份证号",key:"agent_idcard",minWidth:70},{title:"代领人手机号",key:"agent_phone",minWidth:70},{title:"代领时间",key:"update_time",minWidth:70},{title:"图片",slot:"qrcode",minWidth:70},{title:"操作",slot:"operation",minWidth:70}],total:0,timeVal:[],formValidate:{page:1,size:10}}},computed:b({},Object(o["e"])("admin/layout",["isMobile"]),{labelWidth:function(){return this.isMobile?void 0:50},labelPosition:function(){return this.isMobile?"top":"left"}}),created:function(){},mounted:function(){this.reset()},methods:{godelete:function(t,e,a){var n=this,i={title:e,num:a,url:"personal/code/".concat(t.id),method:"DELETE",ids:""};this.$modalSure(i).then((function(t){console.log(t),200==t.data.code?(n.$Message.success(t.data.msg),n.list.splice(a,1),n.getSmpling()):n.$Message.error(t.data.msg)})).catch((function(t){n.$Message.error(t.data.msg)}))},goedit:function(t){this.editformValidate=t,this.roleid=t.id,this.edittitle="修改个人清册信息",this.editmodal=!0},handleSubmit:function(t){var e=this,a=this;this.$refs[t].validate((function(t){t&&m(e.roleid,e.editformValidate).then((function(t){console.log(t.data.msg),200===t.data.code?(e.$Message.config({top:120,duration:3}),a.$Message.success(t.data.msg),e.editmodal=!1,e.getSmpling()):(e.$Message.config({top:120,duration:3}),a.$Message.error(t.data.msg))}))}))},reset:function(){this.timeVal=[],this.formValidate={page:1,size:10};new Date;this.getSmpling()},getSmpling:function(){var t=this;this.loading=!0,d(this.formValidate).then((function(e){t.loading=!1,t.list=e.data.data.data,t.total=e.data.data.total}))},Searchs:function(){this.formValidate.page=1,this.getSmpling()},batchExport:function(){var t=f(s.a.mark((function t(){var e,a,n,i;return s.a.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return this.button_loading=!0,[],[],[],"",e=Object.assign({},this.formValidate),delete e.size,delete e.page,a=JSON.parse(JSON.stringify(e)),t.next=8,this.getBatchExport(a);case 8:if(n=t.sent,400!=n.code){t.next=13;break}return this.$Message.error(n.msg),this.button_loading=!1,t.abrupt("return");case 13:return i=n.data.path,this.send_count=0,this.getCsvProgress(i),t.abrupt("return");case 17:case"end":return t.stop()}}),t,this)})));function e(){return t.apply(this,arguments)}return e}(),getBatchExport:function(t){return new Promise((function(e,a){c(t).then((function(t){return e(t.data)}))}))},getCsvProgress:function(t){var e=this;Object(u["r"])({path:t}).then((function(a){var n=a.data;if(100==n.data.progress)e.button_loading=!1,window.open(t+"?"+Date.now());else{e.downloadstr=n.data.progress+"%";var i=e;setTimeout((function(){i.getCsvProgress(t)}),1e3)}}))},sizeChange:function(t){this.formValidate.size=t,this.getSmpling(),this.$refs.table.clearCurrentRow()},pageChange:function(t){this.formValidate.page=t,this.getSmpling()}}},C=_,V=(a("0c29"),a("2877")),x=Object(V["a"])(C,n,i,!1,null,"5bf36767",null);e["default"]=x.exports},de49:function(t,e,a){}}]);
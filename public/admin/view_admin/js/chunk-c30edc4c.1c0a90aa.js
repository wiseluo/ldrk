(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-c30edc4c"],{"42ec":function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("Card",{staticClass:"ivu-mt",attrs:{bordered:!1,"dis-hover":""}},[a("Form",{ref:"formValidate",attrs:{"label-width":t.labelWidth,"label-position":t.labelPosition},nativeOn:{submit:function(t){t.preventDefault()}}},[a("Row",{attrs:{gutter:24,type:"flex"}},[a("Col",{staticStyle:{"line-height":"40px"},attrs:{xs:10,sm:12,md:16,lg:18}},[a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"7"}},[a("span",{staticClass:"shtitle"},[t._v("原始数据")])]),a("Col",{attrs:{span:"17"}},[a("Input",{staticClass:"shinput",staticStyle:{},attrs:{placeholder:"请输入行程码原始数据 ","element-id":"travel_content",clearable:""},model:{value:t.formValidate.travel_content,callback:function(e){t.$set(t.formValidate,"travel_content",e)},expression:"formValidate.travel_content"}})],1)],1),a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"7"}},[a("span",{staticClass:"shtitle"},[t._v("行程")])]),a("Col",{attrs:{span:"17"}},[a("Input",{staticClass:"shinput",attrs:{placeholder:"请输入行程","element-id":"travel_route",clearable:""},model:{value:t.formValidate.travel_route,callback:function(e){t.$set(t.formValidate,"travel_route",e)},expression:"formValidate.travel_route"}})],1)],1)],1),a("Col",{staticClass:"ivu-text-right userFrom",attrs:{xs:14,sm:12,md:8,lg:6}},[a("Button",{staticClass:"mr15",attrs:{type:"primary",icon:"ios-search",label:"default"},on:{click:t.Searchs}},[t._v("搜索")]),a("Button",{staticClass:"ResetSearch",on:{click:function(e){return t.reset("leave")}}},[t._v("重置")])],1)],1)],1),a("Form",[a("Row",{staticClass:"mt20",attrs:{type:"flex"}},[a("Button",{staticClass:"bnt mr15",attrs:{type:"success",loading:t.button_loading},on:{click:t.exports}},[t._v(t._s(t.downloadstr))])],1)],1),a("Table",{staticClass:"mt25",attrs:{columns:t.columns1,data:t.list,"no-userFrom-text":"暂无数据","no-filtered-userFrom-text":"暂无筛选结果",loading:t.loading,"highlight-row":""},scopedSlots:t._u([{key:"index",fn:function(e){e.row;var i=e.index;return[a("span",[t._v(" "+t._s(i+1)+" ")])]}},{key:"risk_level",fn:function(e){var i=e.row;e.index;return["middling"===i.risk_level?a("span",[t._v(" 中风险 ")]):t._e(),"low"===i.risk_level?a("span",[t._v(" 低风险 ")]):t._e(),"high"===i.risk_level?a("span",[t._v(" 高风险 ")]):t._e()]}},{key:"isasterisk",fn:function(e){var i=e.row;e.index;return[i.isasterisk?a("span",[t._v("是")]):a("span",[t._v("否")])]}},{key:"travel_img",fn:function(t){var e=t.row;t.index;return[a("viewer",[a("div",{staticClass:"tabBox_img"},[a("img",{directives:[{name:"lazy",rawName:"v-lazy",value:e.travel_img,expression:"row.travel_img"}]})])])]}},{key:"action",fn:function(e){var i=e.row,n=e.index;return[a("a",{on:{click:function(e){return t.edit(i)}}},[t._v("编辑")]),a("Divider",{attrs:{type:"vertical"}}),a("a",{on:{click:function(e){return t.deleteSmping(i,"删除",n)}}},[t._v("删除")]),a("Divider",{attrs:{type:"vertical"}})]}}])}),a("div",{staticClass:"acea-row row-right page"},[a("Page",{attrs:{total:t.total,current:t.formValidate.page,"show-elevator":"","show-total":"","show-sizer":"","page-size-opts":[5,10,15,20],"page-size":t.formValidate.size},on:{"on-page-size-change":t.sizeChange,"on-change":t.pageChange}})],1),a("Modal",{staticStyle:{display:"flex","justify-content":"center","flex-direction":"column"},attrs:{title:"查看二维码"},on:{"on-ok":t.ok,"on-cancel":t.cancel},model:{value:t.modal1,callback:function(e){t.modal1=e},expression:"modal1"}},[a("img",{attrs:{src:t.srcList,alt:"",sizes:"",srcset:""}}),a("div",[t._v("机构名称:"+t._s(t.select_name))])])],1)],1)},n=[],s=a("2f62"),r=a("4328"),l=a.n(r),o=a("8f58"),c=(a("2e83"),a("3f2a")),d=a("5f87"),u=a("995b");function m(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(t);e&&(i=i.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,i)}return a}function f(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?m(a,!0).forEach((function(e){p(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):m(a).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}function p(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}var h={name:"abnormaData_abnormaData_dataerror",mixins:[u["a"]],data:function(){return{token:"Bearer "+Object(d["a"])(),modal1:!1,fileurlL:"/adminapi/file/tmp/upload?token="+Object(d["a"])(),grid:{xl:7,lg:7,md:12,sm:24,xs:24},total:0,loading:!1,roleData:{status1:""},dataList:[],formValidate:{page:1,size:10},list:[],columns1:[{title:"序号",slot:"index",minWidth:50},{title:"申报id",key:"declare_id",minWidth:170},{title:"行程码原始数据",key:"travel_content",minWidth:250},{title:"行程",key:"travel_route",minWidth:120},{title:"行程最新更新时间",key:"travel_time",minWidth:120},{title:"备注",key:"remark",minWidth:120},{title:"行程码图片",slot:"travel_img",minWidth:150}],FromData:null,modalTitleSs:"",ids:Number,srcList:[],excelDatamodals:!1,excelData:!0,select_name:""}},computed:f({},Object(s["e"])("admin/layout",["isMobile"]),{labelWidth:function(){return this.isMobile?void 0:50},labelPosition:function(){return this.isMobile?"top":"left"}}),created:function(){this.getSmpling()},methods:{getExcelData:function(t){return new Promise((function(e,a){Object(c["j"])(t).then((function(t){return e(t.data)}))}))},exportfuntion:function(){return console.log(this.token),"http://localhost:8080/adminapi/export/sampleOrgan?"+l.a.stringify(this.formValidate)},onCancel:function(){},ok:function(){},cancel:function(){},handleSuccess:function(t,e,a){var i=this,n={path:t.data.src};sampleOrganVerify(n).then((function(t){console.log(t.data.data,"resData"),i.excelDatamodals=!0,i.excelData=t.data.data,i.getSmpling()}))},openItImage:function(t,e){this.srcList=[],this.srcList=t,this.select_name=e,this.modal1=!0},getSmpling:function(){var t=this;this.loading=!0,this.formValidate.leave_time=this.selectDate,this.errInfosChange(),Object(o["e"])(this.formValidate).then((function(e){t.loading=!1,t.list=e.data.data.data,t.total=e.data.data.total}))},deleteSmping:function(t,e,a){var i=this,n={title:e,num:a,url:"riskdistrict/".concat(t.id),method:"DELETE",ids:""};this.$modalSure(n).then((function(t){console.log(t),200==t.data.code?(i.$Message.success(t.data.msg),i.list.splice(a,1),i.getSmpling()):i.$Message.error(t.data.msg)})).catch((function(t){i.$Message.error(t.data.msg)}))},sizeChange:function(t){this.formValidate.size=t,this.getSmpling(),this.$refs.table.clearCurrentRow()},pageChange:function(t){this.formValidate.page=t,this.getSmpling()},edit:function(t){this.$router.push({path:"/admin/riskdistrict/riskdistrictAdd/".concat(t.id)});var e=window.localStorage;e.setItem("sampingAdd",JSON.stringify(t))},Searchs:function(){this.formValidate.page=1,this.getSmpling()}}},g=h,v=(a("a693"),a("2877")),b=Object(v["a"])(g,i,n,!1,null,"4d031bd6",null);e["default"]=b.exports},a693:function(t,e,a){"use strict";var i=a("ecdb"),n=a.n(i);n.a},ecdb:function(t,e,a){}}]);
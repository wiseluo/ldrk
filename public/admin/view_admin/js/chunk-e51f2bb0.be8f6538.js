(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-e51f2bb0"],{"14f3":function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("Card",{staticClass:"ivu-mt",attrs:{bordered:!1,"dis-hover":""}},[a("Row",{attrs:{gutter:16,type:"flex"}},[a("Col",{staticStyle:{"line-height":"40px"},attrs:{xs:10,sm:12,md:16,lg:18}},[a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"7"}},[a("span",{staticClass:"shtitle"},[t._v("姓名")])]),a("Col",{attrs:{span:"17"}},[a("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"real_name",clearable:""},model:{value:t.formValidate.real_name,callback:function(e){t.$set(t.formValidate,"real_name",e)},expression:"formValidate.real_name"}})],1)],1),a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"7"}},[a("span",{staticClass:"shtitle"},[t._v("证件类型")])]),a("Col",{attrs:{span:"17"}},[a("Select",{staticClass:"shinput",attrs:{placeholder:"证件类型"},model:{value:t.formValidate.card_type,callback:function(e){t.$set(t.formValidate,"card_type",e)},expression:"formValidate.card_type"}},t._l(t.cardTypeList,(function(e){return a("Option",{key:e.id,attrs:{value:e.id}},[t._v(t._s(e.name))])})),1)],1)],1),a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"7"}},[a("span",{staticClass:"shtitle"},[t._v("证件号")])]),a("Col",{attrs:{span:"17"}},[a("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"id_card",clearable:""},model:{value:t.formValidate.id_card,callback:function(e){t.$set(t.formValidate,"id_card",e)},expression:"formValidate.id_card"}})],1)],1),a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"7"}},[a("span",{staticClass:"shtitle"},[t._v("手机号")])]),a("Col",{attrs:{span:"17"}},[a("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"phone",clearable:""},model:{value:t.formValidate.phone,callback:function(e){t.$set(t.formValidate,"phone",e)},expression:"formValidate.phone"}})],1)],1),t.collapse?[a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"7"}},[a("span",{staticClass:"shtitle"},[t._v("来源地")])]),a("Col",{attrs:{span:"17"}},[a("el-cascader",{staticClass:"shinput",staticStyle:{width:"100%","margin-left":"1%"},attrs:{options:t.dataList,props:t.optionProps,clearable:"",size:"small"},on:{change:t.chanegov},model:{value:t.gov_idchecked,callback:function(e){t.gov_idchecked=e},expression:"gov_idchecked"}})],1)],1),a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"7"}},[a("span",{staticClass:"shtitle"},[t._v("来义居所")])]),a("Col",{attrs:{span:"17"}},[a("Select",{staticClass:"shinput",attrs:{placeholder:"来义居所",clearable:""},on:{"on-change":t.userSearchs},model:{value:t.formValidate.yw_street,callback:function(e){t.$set(t.formValidate,"yw_street",e)},expression:"formValidate.yw_street"}},t._l(t.yiwuStreetList,(function(e){return a("Option",{key:e.id,attrs:{value:e.id}},[t._v(t._s(e.name))])})),1)],1)],1),a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"7"}},[a("span",{staticClass:"shtitle"},[t._v("来义时间")])]),a("Col",{attrs:{span:"17"}},[a("DatePicker",{staticClass:"shinput mr20",attrs:{editable:!1,value:t.formValidate.arrival_time,format:"yyyy-MM-dd",type:"date",placement:"bottom-start",placeholder:"来义时间"},on:{"on-change":t.onchangeDateOne,"on-clear":t.closeIt}})],1)],1),a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"7"}},[a("span",{staticClass:"shtitle"},[t._v("时间范围")])]),a("Col",{attrs:{span:"17"}},[a("DatePicker",{staticClass:"shinput",attrs:{editable:!1,value:t.timeVal,format:"yyyy-MM-dd",type:"daterange",placement:"bottom-start",placeholder:"时间范围",options:t.options},on:{"on-change":t.onchangeTime,"on-clear":t.closeTime}})],1)],1),a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"5"}},[a("span",{staticClass:"shtitle"},[t._v("行程码")])]),a("Col",{attrs:{span:"17"}},[a("Select",{staticClass:"shinput",attrs:{placeholder:"行程码是否带星号",clearable:""},on:{"on-change":t.userSearchs},model:{value:t.formValidate.isasterisk,callback:function(e){t.$set(t.formValidate,"isasterisk",e)},expression:"formValidate.isasterisk"}},t._l(t.xcmList,(function(e){return a("Option",{key:e.value,attrs:{value:e.value}},[t._v(t._s(e.text))])})),1)],1)],1),a("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[a("Col",{staticClass:"shtitle",attrs:{span:"5"}},[a("span",{staticClass:"shtitle"},[t._v("行程途径")])]),a("Col",{attrs:{span:"17"}},[a("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"travel_route  ",clearable:""},model:{value:t.formValidate.travel_route,callback:function(e){t.$set(t.formValidate,"travel_route",e)},expression:"formValidate.travel_route"}})],1)],1)]:t._e()],2),a("Col",{staticClass:"ivu-text-right userFrom",attrs:{xs:14,sm:12,md:8,lg:6}},[a("a",{directives:[{name:"font",rawName:"v-font",value:14,expression:"14"}],staticClass:"ivu-ml-8 mr15",on:{click:function(e){t.collapse=!t.collapse}}},[t.collapse?[a("Button",{attrs:{type:"primary",label:"default"}},[t._v("收起 "),a("Icon",{attrs:{type:"ios-arrow-up"}})],1)]:[a("Button",{attrs:{type:"primary",label:"default"}},[t._v("更多 "),a("Icon",{attrs:{type:"ios-arrow-down"}})],1)]],2),a("Button",{staticClass:"mr15",attrs:{type:"primary",icon:"ios-search",label:"default"},on:{click:t.Searchs}},[t._v("搜索")]),a("Button",{staticClass:"ResetSearch",on:{click:function(e){return t.reset("leave")}}},[t._v("重置")])],1)],1),a("Form",[a("Row",{staticClass:"mt20",attrs:{type:"flex"}},[a("Button",{staticClass:"bnt mr15",attrs:{type:"success",loading:t.button_loading},on:{click:t.exports}},[t._v(t._s(t.downloadstr))])],1)],1),a("Table",{staticClass:"mt25",attrs:{columns:t.columns1,data:t.list,"no-userFrom-text":"暂无数据","no-filtered-userFrom-text":"暂无筛选结果",loading:t.loading,"highlight-row":""},scopedSlots:t._u([{key:"index",fn:function(e){e.row;var s=e.index;return[a("span",[t._v(" "+t._s(s+1)+" ")])]}},{key:"arrival_transport_mode",fn:function(e){var s=e.row;e.index;return["train"===s.arrival_transport_mode?a("span",{staticStyle:{display:"flex","justify-content":"center"}},[t._v("\n          火车\n        ")]):t._e(),"aircraft"===s.arrival_transport_mode?a("span",{staticStyle:{display:"flex","justify-content":"center"}},[t._v("\n          飞机\n        ")]):t._e(),"car"===s.arrival_transport_mode?a("span",{staticStyle:{display:"flex","justify-content":"center"}},[t._v("\n          汽车\n        ")]):t._e()]}},{key:"risk_level",fn:function(e){var s=e.row;e.index;return["middling"===s.risk_level?a("span",[t._v(" 中风险 ")]):t._e(),"low"===s.risk_level?a("span",[t._v(" 低风险 ")]):t._e(),"high"===s.risk_level?a("span",[t._v(" 高风险 ")]):t._e()]}},{key:"isasterisk",fn:function(e){var s=e.row;e.index;return[s.isasterisk?a("span",[t._v("是")]):a("span",[t._v("否")])]}},{key:"travel_img",fn:function(t){var e=t.row;t.index;return[a("el-image",{staticStyle:{width:"100px",height:"100px"},attrs:{src:e.travel_img,fit:"fit"}})]}},{key:"action",fn:function(e){var s=e.row,r=e.index;return[a("a",{on:{click:function(e){return t.edit(s)}}},[t._v("编辑")]),a("Divider",{attrs:{type:"vertical"}}),a("a",{on:{click:function(e){return t.deleteSmping(s,"删除",r)}}},[t._v("删除")]),a("Divider",{attrs:{type:"vertical"}})]}}])}),a("div",{staticClass:"acea-row row-right page"},[a("Page",{attrs:{total:t.total,current:t.formValidate.page,"show-elevator":"","show-total":"","show-sizer":"","page-size-opts":[5,10,15,20],"page-size":t.formValidate.size},on:{"on-page-size-change":t.sizeChange,"on-change":t.pageChange}})],1),a("Modal",{staticStyle:{display:"flex","justify-content":"center","flex-direction":"column"},attrs:{title:"查看二维码"},on:{"on-ok":t.ok,"on-cancel":t.cancel},model:{value:t.modal1,callback:function(e){t.modal1=e},expression:"modal1"}},[a("img",{attrs:{src:t.srcList,alt:"",sizes:"",srcset:""}}),a("div",[t._v("机构名称:"+t._s(t.select_name))])])],1)],1)},r=[],n=a("2f62"),i=a("4328"),l=a.n(i),o=a("22a0"),c=(a("2e83"),a("5f87")),d=a("b204"),u=a("3f2a");function m(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,s)}return a}function p(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?m(a,!0).forEach((function(e){f(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):m(a).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}function f(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}var h={name:"systemAdmin",mixins:[d["a"]],data:function(){return{collapse:!1,token:"Bearer "+Object(c["a"])(),modal1:!1,fileurlL:"/adminapi/file/tmp/upload?token="+Object(c["a"])(),grid:{xl:7,lg:7,md:12,sm:24,xs:24},total:0,loading:!1,roleData:{status1:""},dataList:[],formValidate:{page:1,size:10,status:"",name:"",keyword:""},list:[],columns1:[{title:"序号",slot:"index",minWidth:70},{title:"姓名",key:"real_name",minWidth:120},{title:"证件类型",key:"card_type_text",minWidth:120},{title:"证件号",key:"id_card",minWidth:150},{title:"手机号",key:"phone",minWidth:120},{title:"来源地",key:"destination",minWidth:180},{title:"来义居所",key:"yw_street",minWidth:120},{title:"来义时间",key:"arrival_time",minWidth:150},{title:"详细地址",key:"address",minWidth:120},{title:"离开中高风险地区时间",key:"leave_riskarea_time",minWidth:150},{title:"来义交通工具",slot:"arrival_transport_mode",minWidth:120},{title:"车次/航班/车牌",key:"arrival_sign",minWidth:120},{title:" 行程码 ",slot:"travel_img",minWidth:120},{title:"行程码是否有星号",slot:"isasterisk",minWidth:70},{title:"核酸检测结果",key:"hsjc_result",minWidth:120},{title:"疫苗接种时间",key:"vaccination_date",minWidth:120},{title:"接种剂次",key:"vaccination_times",minWidth:120},{title:"风险判断规则",key:"warning_from_text",minWidth:120},{title:"规则说明",key:"warning_rule_text",minWidth:230},{title:"是否已推送 ",key:"is_to_oa",minWidth:120},{title:"管控措施",key:"control_state",minWidth:120}],FromData:null,modalTitleSs:"",ids:Number,srcList:[],excelDatamodals:!1,excelData:!0,select_name:""}},computed:p({},Object(n["e"])("admin/layout",["isMobile"]),{labelWidth:function(){return this.isMobile?void 0:50},labelPosition:function(){return this.isMobile?"top":"left"}}),created:function(){this.getSmpling()},methods:{getExcelData:function(t){return new Promise((function(e,a){Object(u["n"])(t).then((function(t){return e(t.data)}))}))},closeLeaveData:function(t){console.log(t)},changeDateLeave:function(t){this.formValidate.leave_riskarea_time=t},reset:function(t){this.formValidate={keyword:"",page:1,size:20},this.gov_idchecked=[],this.timeVal=[],this.selectDate="",this.error_infos=[],this.getSmpling()},exportfuntion:function(){return console.log(this.token),"http://localhost:8080/adminapi/export/sampleOrgan?"+l.a.stringify(this.formValidate)},onCancel:function(){},ok:function(){},cancel:function(){},handleSuccess:function(t,e,a){var s=this,r={path:t.data.src};sampleOrganVerify(r).then((function(t){console.log(t.data.data,"resData"),s.excelDatamodals=!0,s.excelData=t.data.data,s.getSmpling()}))},openItImage:function(t,e){this.srcList=[],this.srcList=t,this.select_name=e,this.modal1=!0},getSmpling:function(){var t=this;this.loading=!0,this.formValidate.arrival_time=this.selectDate,Object(o["b"])(this.formValidate).then((function(e){t.loading=!1,t.list=e.data.data.data,t.total=e.data.data.total}))},deleteSmping:function(t,e,a){var s=this,r={title:e,num:a,url:"riskdistrict/".concat(t.id),method:"DELETE",ids:""};this.$modalSure(r).then((function(t){console.log(t),200==t.data.code?(s.$Message.success(t.data.msg),s.list.splice(a,1),s.getSmpling()):s.$Message.error(t.data.msg)})).catch((function(t){s.$Message.error(t.data.msg)}))},sizeChange:function(t){this.formValidate.size=t,this.getSmpling(),this.$refs.table.clearCurrentRow()},pageChange:function(t){this.formValidate.page=t,this.getSmpling()},edit:function(t){this.$router.push({path:"/admin/riskdistrict/riskdistrictAdd/".concat(t.id)});var e=window.localStorage;e.setItem("sampingAdd",JSON.stringify(t))},Searchs:function(){this.formValidate.page=1,this.getSmpling()}}},g=h,v=(a("afa5"),a("fb69"),a("2877")),_=Object(v["a"])(g,s,r,!1,null,"32fd3e42",null);e["default"]=_.exports},"2ddb":function(t,e,a){},"3f2a":function(t,e,a){"use strict";a.d(e,"o",(function(){return r})),a.d(e,"i",(function(){return n})),a.d(e,"h",(function(){return i})),a.d(e,"j",(function(){return l})),a.d(e,"l",(function(){return o})),a.d(e,"k",(function(){return c})),a.d(e,"m",(function(){return d})),a.d(e,"n",(function(){return u})),a.d(e,"r",(function(){return m})),a.d(e,"p",(function(){return p})),a.d(e,"q",(function(){return f})),a.d(e,"s",(function(){return h})),a.d(e,"b",(function(){return g})),a.d(e,"f",(function(){return v})),a.d(e,"e",(function(){return _})),a.d(e,"d",(function(){return y})),a.d(e,"c",(function(){return b})),a.d(e,"a",(function(){return k})),a.d(e,"g",(function(){return C})),a.d(e,"t",(function(){return x}));var s=a("b6bd");function r(t){return Object(s["a"])({url:"/csv/owndeclare",method:"get",params:t})}function n(t){return Object(s["a"])({url:"/csv/dataerror/leave",method:"get",params:t})}function i(t){return Object(s["a"])({url:"/csv/dataerror/come",method:"get",params:t})}function l(t){return Object(s["a"])({url:"/csv/dataerror/ocr",method:"get",params:t})}function o(t){return Object(s["a"])({url:"/csv/dataerror/todayMany",method:"get",params:t})}function c(t){return Object(s["a"])({url:"/csv/dataerror/riskarea",method:"get",params:t})}function d(t){return Object(s["a"])({url:"/csv/datawarning/backouttime",method:"get",params:t})}function u(t){return Object(s["a"])({url:"/csv/datawarning/riskarea",method:"get",params:t})}function m(t){return Object(s["a"])({url:"/csv/user",method:"get",params:t})}function p(t){return Object(s["a"])({url:"/csv/statistic/age",method:"get",params:t})}function f(t){return Object(s["a"])({url:"/csv/statistic/ywstreet",method:"get",params:t})}function h(t){return Object(s["a"])({url:"/csv/getCsvProgress",method:"get",params:t})}function g(t){return Object(s["a"])({url:"/csv/company",method:"get",params:t})}function v(t){return Object(s["a"])({url:"/csv/user",method:"get",params:t})}function _(t){return Object(s["a"])({url:"/csv/unqualified",method:"get",params:t})}function y(t){return Object(s["a"])({url:"/csv/placedeclare",method:"get",params:t})}function b(t){return Object(s["a"])({url:"/csv/statistic/fromwhere",method:"get",params:t})}function k(t){return Object(s["a"])({url:"/csv/placedeclare/abnormal",method:"get",params:t})}function C(t){return Object(s["a"])({url:"csv/place",method:"get",params:t})}function x(t){return Object(s["a"])({url:"csv/querycenter/rygk",method:"get",params:t})}},afa5:function(t,e,a){"use strict";var s=a("2ddb"),r=a.n(s);r.a},fb69:function(t,e,a){"use strict";var s=a("fc00"),r=a.n(s);r.a},fc00:function(t,e,a){}}]);
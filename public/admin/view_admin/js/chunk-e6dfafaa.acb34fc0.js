(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-e6dfafaa"],{"0564":function(t,a,e){"use strict";var s=e("c7c7"),i=e.n(s);i.a},2104:function(t,a,e){"use strict";e.r(a);var s=function(){var t=this,a=this,e=a.$createElement,s=a._self._c||e;return s("div",[s("Card",{staticClass:"ivu-mt",attrs:{bordered:!1,"dis-hover":""}},[s("Form",{ref:"formValidate",attrs:{"label-width":a.labelWidth,"label-position":a.labelPosition},nativeOn:{submit:function(t){t.preventDefault()}}},[s("Row",{attrs:{gutter:16,type:"flex"}},[s("Col",{staticStyle:{"line-height":"40px"},attrs:{xs:10,sm:12,md:16,lg:18}},[s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[a._v("姓名")])]),s("Col",{attrs:{span:"17"}},[s("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"real_name",clearable:""},model:{value:a.formValidate.real_name,callback:function(t){a.$set(a.formValidate,"real_name",t)},expression:"formValidate.real_name"}})],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[a._v("证件类型")])]),s("Col",{attrs:{span:"17"}},[s("Select",{staticClass:"shinput",attrs:{placeholder:"证件类型"},model:{value:a.formValidate.card_type,callback:function(t){a.$set(a.formValidate,"card_type",t)},expression:"formValidate.card_type"}},a._l(a.cardTypeList,(function(t){return s("Option",{key:t.id,attrs:{value:t.id}},[a._v(a._s(t.name))])})),1)],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[a._v("证件号")])]),s("Col",{attrs:{span:"17"}},[s("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"id_card",clearable:""},model:{value:a.formValidate.id_card,callback:function(t){a.$set(a.formValidate,"id_card",t)},expression:"formValidate.id_card"}})],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[a._v("手机号")])]),s("Col",{attrs:{span:"17"}},[s("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"phone",clearable:""},model:{value:a.formValidate.phone,callback:function(t){a.$set(a.formValidate,"phone",t)},expression:"formValidate.phone"}})],1)],1),a.collapse?[s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[a._v("来源地")])]),s("Col",{attrs:{span:"17"}},[s("el-cascader",{staticClass:"shinput",staticStyle:{width:"100%","margin-left":"1%"},attrs:{options:a.dataList,props:a.optionProps,clearable:"",size:"small"},on:{change:a.chanegov},model:{value:a.gov_idchecked,callback:function(t){a.gov_idchecked=t},expression:"gov_idchecked"}})],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[a._v("来义居所")])]),s("Col",{attrs:{span:"17"}},[s("Select",{staticClass:"shinput",attrs:{placeholder:"来义居所",clearable:""},on:{"on-change":a.userSearchs},model:{value:a.formValidate.yw_street_id,callback:function(t){a.$set(a.formValidate,"yw_street_id",t)},expression:"formValidate.yw_street_id"}},a._l(a.yiwuStreetList,(function(t){return s("Option",{key:t.id,attrs:{value:t.id}},[a._v(a._s(t.name))])})),1)],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[a._v("来义时间")])]),s("Col",{attrs:{span:"17"}},[s("DatePicker",{staticClass:"mr20 shinput",attrs:{editable:!1,format:"yyyy-MM-dd",type:"date",placement:"bottom-start",placeholder:"来义时间"},on:{"on-change":a.onchangeDateOne,"on-clear":a.closeIt},model:{value:a.formValidate.arrival_time,callback:function(t){a.$set(a.formValidate,"arrival_time",t)},expression:"formValidate.arrival_time"}})],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[a._v("时间范围")])]),s("Col",{attrs:{span:"17"}},[s("DatePicker",{staticClass:"shinput",attrs:{editable:!1,value:a.timeVal,format:"yyyy-MM-dd",type:"daterange",placement:"bottom-start",placeholder:"时间范围",options:a.options},on:{"on-change":a.onchangeTime,"on-clear":a.closeTime}})],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[a._v("离开时间")])]),s("Col",{attrs:{span:"17"}},[s("DatePicker",{staticClass:"mr20 shinput",attrs:{editable:!1,format:"yyyy-MM-dd",type:"date",placement:"bottom-start",placeholder:"离开高风险地区时间"},on:{"on-change":a.changeDateLeave,"on-clear":a.closeLeaveData},model:{value:a.formValidate.leave_riskarea_time,callback:function(t){a.$set(a.formValidate,"leave_riskarea_time",t)},expression:"formValidate.leave_riskarea_time"}})],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[a._v("交通航班")])]),s("Col",{attrs:{span:"17"}},[s("Input",{staticClass:"shinput",attrs:{placeholder:"车次/航班/车牌\t","element-id":"arrival_sign",clearable:""},model:{value:a.formValidate.arrival_sign,callback:function(t){a.$set(a.formValidate,"arrival_sign",t)},expression:"formValidate.arrival_sign"}})],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[a._v("异常原因")])]),s("Col",{attrs:{span:"17"}},[s("Select",{staticClass:"shinput",attrs:{multiple:""},model:{value:a.error_infos,callback:function(t){a.error_infos=t},expression:"error_infos"}},a._l(a.errorInfoList,(function(t){return s("Option",{key:t.value,attrs:{value:t.value}},[a._v(a._s(t.label))])})),1)],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[a._v("申报时间")])]),s("Col",{attrs:{span:"17"}},[s("DatePicker",{staticClass:"mr20 shinput",attrs:{editable:!1,format:"yyyy-MM-dd",type:"date",placement:"bottom-start",placeholder:"申报时间"},on:{"on-change":function(a){t.formValidate.create_date=a},"on-clear":function(a){t.formValidate.create_date=""}},model:{value:a.formValidate.create_date,callback:function(t){a.$set(a.formValidate,"create_date",t)},expression:"formValidate.create_date"}})],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[a._v("行程码")])]),s("Col",{attrs:{span:"17"}},[s("Select",{staticClass:"shinput",attrs:{placeholder:"行程码是否带星号",clearable:""},on:{"on-change":a.userSearchs},model:{value:a.formValidate.isasterisk,callback:function(t){a.$set(a.formValidate,"isasterisk",t)},expression:"formValidate.isasterisk"}},a._l(a.xcmList,(function(t){return s("Option",{key:t.value,attrs:{value:t.value}},[a._v(a._s(t.text))])})),1)],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[a._v("行程途径")])]),s("Col",{attrs:{span:"17"}},[s("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"travel_route  ",clearable:""},model:{value:a.formValidate.travel_route,callback:function(t){a.$set(a.formValidate,"travel_route",t)},expression:"formValidate.travel_route"}})],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[a._v("详细地址")])]),s("Col",{attrs:{span:"17"}},[s("Input",{staticClass:"shinput",staticStyle:{width:"300px"},attrs:{placeholder:"请输入","element-id":"address",clearable:""},model:{value:a.formValidate.address,callback:function(t){a.$set(a.formValidate,"address",t)},expression:"formValidate.address"}})],1)],1)]:a._e()],2),s("Col",{staticClass:"ivu-text-right userFrom",attrs:{xs:14,sm:12,md:8,lg:6}},[s("a",{directives:[{name:"font",rawName:"v-font",value:14,expression:"14"}],staticClass:"ivu-ml-8 mr15",on:{click:function(t){a.collapse=!a.collapse}}},[a.collapse?[s("Button",{attrs:{type:"primary",label:"default"}},[a._v("收起 "),s("Icon",{attrs:{type:"ios-arrow-up"}})],1)]:[s("Button",{attrs:{type:"primary",label:"default"}},[a._v("更多 "),s("Icon",{attrs:{type:"ios-arrow-down"}})],1)]],2),s("Button",{staticClass:"mr15",attrs:{type:"primary",icon:"ios-search",label:"default"},on:{click:a.Searchs}},[a._v("搜索")]),s("Button",{staticClass:"ResetSearch",on:{click:function(t){return a.reset("leave")}}},[a._v("重置")])],1)],1)],1),s("Form",[s("Row",{staticClass:"mt20",attrs:{type:"flex"}},[s("Button",{staticClass:"bnt mr15",attrs:{type:"success",loading:a.button_loading},on:{click:a.exports}},[a._v(a._s(a.downloadstr))])],1)],1),s("Table",{staticClass:"mt25",attrs:{columns:a.columns1,data:a.list,"no-userFrom-text":"暂无数据","no-filtered-userFrom-text":"暂无筛选结果",loading:a.loading,"highlight-row":""},scopedSlots:a._u([{key:"index",fn:function(t){t.row;var e=t.index;return[s("span",[a._v(" "+a._s(e+1)+" ")])]}},{key:"isasterisk",fn:function(t){var e=t.row;t.index;return[e.isasterisk?s("span",[a._v("是")]):s("span",[a._v("否")])]}},{key:"travel_img",fn:function(t){var a=t.row;t.index;return[s("viewer",[s("div",{staticClass:"tabBox_img"},[s("img",{directives:[{name:"lazy",rawName:"v-lazy",value:a.travel_img,expression:"row.travel_img"}]})])])]}},{key:"arrival_transport_mode",fn:function(t){var e=t.row;t.index;return["train"===e.arrival_transport_mode?s("span",{staticStyle:{display:"flex","justify-content":"center"}},[a._v("\n          火车\n        ")]):a._e(),"aircraft"===e.arrival_transport_mode?s("span",{staticStyle:{display:"flex","justify-content":"center"}},[a._v("\n          飞机\n        ")]):a._e(),"car"===e.arrival_transport_mode?s("span",{staticStyle:{display:"flex","justify-content":"center"}},[a._v("\n          汽车\n        ")]):a._e()]}},{key:"action",fn:function(t){var e=t.row,i=t.index;return[s("a",{on:{click:function(t){return a.edit(e)}}},[a._v("编辑")]),s("Divider",{attrs:{type:"vertical"}}),s("a",{on:{click:function(t){return a.deleteSmping(e,"删除",i)}}},[a._v("删除")]),s("Divider",{attrs:{type:"vertical"}})]}}])}),s("div",{staticClass:"acea-row row-right page"},[s("Page",{attrs:{total:a.total,current:a.formValidate.page,"show-elevator":"","show-total":"","show-sizer":"","page-size-opts":[5,10,15,20],"page-size":a.formValidate.size},on:{"on-page-size-change":a.sizeChange,"on-change":a.pageChange}})],1),s("Modal",{staticStyle:{display:"flex","justify-content":"center","flex-direction":"column"},attrs:{title:"查看二维码"},on:{"on-ok":a.ok,"on-cancel":a.cancel},model:{value:a.modal1,callback:function(t){a.modal1=t},expression:"modal1"}},[s("img",{attrs:{src:a.srcList,alt:"",sizes:"",srcset:""}}),s("div",[a._v("机构名称:"+a._s(a.select_name))])])],1)],1)},i=[],l=e("2f62"),r=e("4328"),n=e.n(r),o=e("8f58"),c=e("3f2a"),d=(e("2e83"),e("5f87")),m=e("995b");function p(t,a){var e=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);a&&(s=s.filter((function(a){return Object.getOwnPropertyDescriptor(t,a).enumerable}))),e.push.apply(e,s)}return e}function u(t){for(var a=1;a<arguments.length;a++){var e=null!=arguments[a]?arguments[a]:{};a%2?p(e,!0).forEach((function(a){f(t,a,e[a])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(e)):p(e).forEach((function(a){Object.defineProperty(t,a,Object.getOwnPropertyDescriptor(e,a))}))}return t}function f(t,a,e){return a in t?Object.defineProperty(t,a,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[a]=e,t}var h={name:"abnormaData_abnormaData_weixian",mixins:[m["a"]],data:function(){return{token:"Bearer "+Object(d["a"])(),modal1:!1,fileurlL:"/adminapi/file/tmp/upload?token="+Object(d["a"])(),grid:{xl:7,lg:7,md:12,sm:24,xs:24},total:0,loading:!1,roleData:{status1:""},dataList:[],formValidate:{page:1,size:10,status:"",name:"",keyword:""},list:[],columns1:[{title:"序号",slot:"index",minWidth:50},{title:"异常原因",key:"error_infos",minWidth:200},{title:"姓名",key:"real_name",minWidth:120},{title:"证件类型",key:"card_type_text",minWidth:120},{title:"证件号",key:"id_card",minWidth:150},{title:"手机号",key:"phone",minWidth:120},{title:"来源地",key:"destination",minWidth:170},{title:"来义居所",key:"yw_street",minWidth:120},{title:"来义时间",key:"arrival_time",minWidth:120},{title:"详细地址",key:"address",minWidth:150},{title:"申报时间",key:"create_time",minWidth:150},{title:"离开高风险地区时间",key:"leave_riskarea_time",minWidth:120},{title:"来义交通工具",slot:"arrival_transport_mode",minWidth:120},{title:"车次/航班/车牌",key:"arrival_sign",minWidth:120},{title:"行程码图片",slot:"travel_img",minWidth:120},{title:"行程码是否有星号",slot:"isasterisk",minWidth:150}],FromData:null,modalTitleSs:"",ids:Number,srcList:[],excelDatamodals:!1,excelData:!0,select_name:""}},computed:u({},Object(l["e"])("admin/layout",["isMobile"]),{labelWidth:function(){return this.isMobile?void 0:50},labelPosition:function(){return this.isMobile?"top":"left"}}),created:function(){this.getSmpling()},methods:{getExcelData:function(t){return new Promise((function(a,e){Object(c["k"])(t).then((function(t){return a(t.data)}))}))},changeDateLeave:function(t){this.formValidate.leave_riskarea_time=t},closeLeaveData:function(t){console.log(t)},exportfuntion:function(){return console.log(this.token),"http://localhost:8080/adminapi/export/sampleOrgan?"+n.a.stringify(this.formValidate)},onCancel:function(){},ok:function(){},cancel:function(){},handleSuccess:function(t,a,e){var s=this,i={path:t.data.src};sampleOrganVerify(i).then((function(t){console.log(t.data.data,"resData"),s.excelDatamodals=!0,s.excelData=t.data.data,s.getSmpling()}))},openItImage:function(t,a){this.srcList=[],this.srcList=t,this.select_name=a,this.modal1=!0},getSmpling:function(){var t=this;this.loading=!0,this.errInfosChange();var a=JSON.parse(JSON.stringify(this.formValidate));a.arrival_time=this.selectDate,Object(o["f"])(a).then((function(a){t.loading=!1,console.log(a.data),t.list=a.data.data.data,t.total=a.data.data.total}))},deleteSmping:function(t,a,e){var s=this,i={title:a,num:e,url:"riskdistrict/".concat(t.id),method:"DELETE",ids:""};this.$modalSure(i).then((function(t){console.log(t),200==t.data.code?(s.$Message.success(t.data.msg),s.list.splice(e,1),s.getSmpling()):s.$Message.error(t.data.msg)})).catch((function(t){s.$Message.error(t.data.msg)}))},sizeChange:function(t){this.formValidate.size=t,this.getSmpling(),this.$refs.table.clearCurrentRow()},pageChange:function(t){this.formValidate.page=t,this.getSmpling()},edit:function(t){this.$router.push({path:"/admin/riskdistrict/riskdistrictAdd/".concat(t.id)});var a=window.localStorage;a.setItem("sampingAdd",JSON.stringify(t))},Searchs:function(){this.formValidate.page=1,this.getSmpling()}}},v=h,_=(e("0564"),e("2877")),g=Object(_["a"])(v,s,i,!1,null,"d7af3d48",null);a["default"]=g.exports},c7c7:function(t,a,e){}}]);
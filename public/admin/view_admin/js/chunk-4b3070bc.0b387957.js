(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-4b3070bc"],{"4e1e":function(t,e,a){"use strict";var s=a("8380"),i=a.n(s);i.a},8380:function(t,e,a){},"9b9e":function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=this,a=e.$createElement,s=e._self._c||a;return s("div",[s("Card",{staticClass:"ivu-mt",attrs:{bordered:!1,"dis-hover":""}},[s("Form",{ref:"formValidate",attrs:{"label-width":e.labelWidth,"label-position":e.labelPosition},nativeOn:{submit:function(t){t.preventDefault()}}},[s("Row",{attrs:{gutter:16,type:"flex"}},[s("Col",{staticStyle:{"line-height":"40px"},attrs:{xs:10,sm:12,md:16,lg:18}},[s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[e._v("姓名")])]),s("Col",{attrs:{span:"17"}},[s("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"real_name",clearable:""},model:{value:e.formValidate.real_name,callback:function(t){e.$set(e.formValidate,"real_name",t)},expression:"formValidate.real_name"}})],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[e._v("证件类型")])]),s("Col",{attrs:{span:"17"}},[s("Select",{staticClass:"shinput",attrs:{placeholder:"证件类型"},model:{value:e.formValidate.card_type,callback:function(t){e.$set(e.formValidate,"card_type",t)},expression:"formValidate.card_type"}},e._l(e.cardTypeList,(function(t){return s("Option",{key:t.id,attrs:{value:t.id}},[e._v(e._s(t.name))])})),1)],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[e._v("证件号")])]),s("Col",{attrs:{span:"17"}},[s("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"id_card",clearable:""},model:{value:e.formValidate.id_card,callback:function(t){e.$set(e.formValidate,"id_card",t)},expression:"formValidate.id_card"}})],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[e._v("手机号")])]),s("Col",{attrs:{span:"17"}},[s("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"phone",clearable:""},model:{value:e.formValidate.phone,callback:function(t){e.$set(e.formValidate,"phone",t)},expression:"formValidate.phone"}})],1)],1),e.collapse?[s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[e._v("来源地")])]),s("Col",{attrs:{span:"17"}},[s("el-cascader",{staticClass:"shinput",staticStyle:{width:"100%","margin-left":"1%"},attrs:{options:e.dataList,props:e.optionProps,clearable:"",size:"small"},on:{change:e.chanegov},model:{value:e.gov_idchecked,callback:function(t){e.gov_idchecked=t},expression:"gov_idchecked"}})],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[e._v("来义居所")])]),s("Col",{attrs:{span:"17"}},[s("Select",{staticClass:"shinput",attrs:{placeholder:"来义居所",clearable:""},on:{"on-change":e.userSearchs},model:{value:e.formValidate.yw_street_id,callback:function(t){e.$set(e.formValidate,"yw_street_id",t)},expression:"formValidate.yw_street_id"}},e._l(e.yiwuStreetList,(function(t){return s("Option",{key:t.id,attrs:{value:t.id}},[e._v(e._s(t.name))])})),1)],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[e._v("来义时间")])]),s("Col",{attrs:{span:"17"}},[s("DatePicker",{staticClass:"mr20 shinput",attrs:{editable:!1,format:"yyyy-MM-dd",type:"date",placement:"bottom-start",placeholder:"来义时间"},on:{"on-change":e.onchangeDateOne,"on-clear":e.closeIt},model:{value:e.formValidate.arrival_time,callback:function(t){e.$set(e.formValidate,"arrival_time",t)},expression:"formValidate.arrival_time"}})],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[e._v("时间范围")])]),s("Col",{attrs:{span:"17"}},[s("DatePicker",{staticClass:"mr20 shinput",attrs:{editable:!1,value:e.timeVal,format:"yyyy-MM-dd",type:"daterange",placement:"bottom-start",placeholder:"时间范围",options:e.options},on:{"on-change":e.onchangeTime,"on-clear":e.closeTime}})],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[e._v("行程码")])]),s("Col",{attrs:{span:"17"}},[s("Select",{staticClass:"shinput",attrs:{placeholder:"行程码是否带星号",clearable:""},on:{"on-change":e.userSearchs},model:{value:e.formValidate.isasterisk,callback:function(t){e.$set(e.formValidate,"isasterisk",t)},expression:"formValidate.isasterisk"}},e._l(e.xcmList,(function(t){return s("Option",{key:t.value,attrs:{value:t.value}},[e._v(e._s(t.text))])})),1)],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[e._v("行程途径")])]),s("Col",{attrs:{span:"17"}},[s("Input",{staticClass:"shinput",attrs:{placeholder:"请输入","element-id":"travel_route  ",clearable:""},model:{value:e.formValidate.travel_route,callback:function(t){e.$set(e.formValidate,"travel_route",t)},expression:"formValidate.travel_route"}})],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[e._v("申报时间")])]),s("Col",{attrs:{span:"17"}},[s("DatePicker",{staticClass:"mr20 shinput",attrs:{editable:!1,format:"yyyy-MM-dd",type:"date",placement:"bottom-start",placeholder:"申报时间"},on:{"on-change":function(e){t.formValidate.create_date=e},"on-clear":function(e){t.formValidate.create_date=""}},model:{value:e.formValidate.create_date,callback:function(t){e.$set(e.formValidate,"create_date",t)},expression:"formValidate.create_date"}})],1)],1),s("Col",{attrs:{xs:24,sm:12,md:8,lg:6}},[s("Col",{staticClass:"shtitle",attrs:{span:"7"}},[s("span",{staticClass:"shtitle"},[e._v("异常原因")])]),s("Col",{attrs:{span:"17"}},[s("Select",{staticClass:"shinput",attrs:{multiple:""},model:{value:e.error_infos,callback:function(t){e.error_infos=t},expression:"error_infos"}},e._l(e.errorInfoList,(function(t){return s("Option",{key:t.value,attrs:{value:t.value}},[e._v(e._s(t.label))])})),1)],1)],1)]:e._e()],2),s("Col",{staticClass:"ivu-text-right userFrom",attrs:{xs:14,sm:12,md:8,lg:6}},[s("a",{directives:[{name:"font",rawName:"v-font",value:14,expression:"14"}],staticClass:"ivu-ml-8 mr15",on:{click:function(t){e.collapse=!e.collapse}}},[e.collapse?[s("Button",{attrs:{type:"primary",label:"default"}},[e._v("收起 "),s("Icon",{attrs:{type:"ios-arrow-up"}})],1)]:[s("Button",{attrs:{type:"primary",label:"default"}},[e._v("更多 "),s("Icon",{attrs:{type:"ios-arrow-down"}})],1)]],2),s("Button",{staticClass:"mr15",attrs:{type:"primary",icon:"ios-search",label:"default"},on:{click:e.Searchs}},[e._v("搜索")]),s("Button",{staticClass:"ResetSearch",on:{click:function(t){return e.reset("leave")}}},[e._v("重置")])],1)],1)],1),s("Form",[s("Row",{staticClass:"mt20",attrs:{type:"flex"}},[s("Button",{staticClass:"bnt mr15",attrs:{type:"success",loading:e.button_loading},on:{click:e.exports}},[e._v(e._s(e.downloadstr))])],1)],1),s("Table",{staticClass:"mt25",attrs:{columns:e.columns1,data:e.list,"no-userFrom-text":"暂无数据","no-filtered-userFrom-text":"暂无筛选结果",loading:e.loading,"highlight-row":""},scopedSlots:e._u([{key:"index",fn:function(t){t.row;var a=t.index;return[s("span",[e._v(" "+e._s(a+1)+" ")])]}},{key:"travel_img",fn:function(t){var e=t.row;t.index;return[s("viewer",[s("div",{staticClass:"tabBox_img"},[s("img",{directives:[{name:"lazy",rawName:"v-lazy",value:e.travel_img,expression:"row.travel_img"}]})])])]}},{key:"jkm_mzt",fn:function(t){var e=t.row;t.index;return[s("viewer",[s("div",{staticClass:"tabBox_img"},[s("img",{directives:[{name:"lazy",rawName:"v-lazy",value:e.jkm_mzt,expression:"row.jkm_mzt"}]})])])]}},{key:"action",fn:function(t){t.row,t.index}}])}),s("div",{staticClass:"acea-row row-right page"},[s("Page",{attrs:{total:e.total,current:e.formValidate.page,"show-elevator":"","show-total":"","show-sizer":"","page-size-opts":[5,10,15,20],"page-size":e.formValidate.size},on:{"on-page-size-change":e.sizeChange,"on-change":e.pageChange}})],1),s("Modal",{staticStyle:{display:"flex","justify-content":"center","flex-direction":"column"},attrs:{title:"查看二维码"},on:{"on-ok":e.ok,"on-cancel":e.cancel},model:{value:e.modal1,callback:function(t){e.modal1=t},expression:"modal1"}},[s("img",{attrs:{src:e.srcList,alt:"",sizes:"",srcset:""}}),s("div",[e._v("机构名称:"+e._s(e.select_name))])])],1)],1)},i=[],l=a("2f62"),r=a("4328"),n=a.n(r),o=a("995b"),c=a("8f58"),d=a("3f2a"),m=(a("2e83"),a("5f87"));function p(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,s)}return a}function u(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?p(a,!0).forEach((function(e){h(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):p(a).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}function h(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}var f={name:"abnormaData_abnormaData_fly",mixins:[o["a"]],data:function(){var t;return t={token:"Bearer "+Object(m["a"])(),modal1:!1,fileurlL:"/adminapi/file/tmp/upload?token="+Object(m["a"])(),srcList:[],grid:{xl:7,lg:7,md:12,sm:24,xs:24},total:0,loading:!1,roleData:{status1:""},dataList:[],formValidate:{page:1,size:10},list:[],columns1:[{title:"序号",slot:"index",minWidth:50},{title:"异常原因",key:"error_infos",minWidth:170},{title:"姓名",key:"real_name",minWidth:120},{title:"证件类型",key:"card_type_text",minWidth:120},{title:"证件号",key:"id_card",minWidth:150},{title:"手机号",key:"phone",minWidth:120},{title:"来源地",key:"destination",minWidth:120},{title:"途径城市",key:"travel_route",minWidth:250},{title:"来义居所",key:"yw_street",minWidth:120},{title:"来义时间",key:"arrival_time",minWidth:120},{title:"申报时间",key:"create_time",minWidth:150},{title:"详细地址",key:"address",minWidth:150},{title:"行程码图片",slot:"travel_img",minWidth:120},{title:"健康码",key:"jkm_mzt",minWidth:120},{title:"疫苗接种剂次",key:"vaccination_times",minWidth:120},{title:"疫苗接种日期",key:"vaccination_date",minWidth:120},{title:"最近核酸检测结果",key:"hsjc_result",minWidth:120},{title:"检测时间",key:"hsjc_time",minWidth:120}],FromData:null,modalTitleSs:"",ids:Number},h(t,"srcList",[]),h(t,"excelDatamodals",!1),h(t,"excelData",!0),h(t,"select_name",""),t},computed:u({},Object(l["e"])("admin/layout",["isMobile"]),{labelWidth:function(){return this.isMobile?void 0:50},labelPosition:function(){return this.isMobile?"top":"left"}}),created:function(){this.getSmpling()},methods:{getExcelData:function(t){return new Promise((function(e,a){Object(d["h"])(t).then((function(t){return e(t.data)}))}))},getSmpling:function(){var t=this;this.loading=!0,this.errInfosChange();var e=JSON.parse(JSON.stringify(this.formValidate));e.arrival_time=this.selectDate,this.srcList=[],Object(c["a"])(e).then((function(e){t.loading=!1,console.log(e.data),t.list=e.data.data.data,t.total=e.data.data.total,t.list.map((function(e){t.srcList.push(e.travel_img)}))}))},exportfuntion:function(){return console.log(this.token),"http://localhost:8080/adminapi/export/sampleOrgan?"+n.a.stringify(this.formValidate)},onCancel:function(){},ok:function(){},cancel:function(){},handleSuccess:function(t,e,a){var s=this,i={path:t.data.src};sampleOrganVerify(i).then((function(t){console.log(t.data.data,"resData"),s.excelDatamodals=!0,s.excelData=t.data.data,s.getSmpling()}))},openItImage:function(t,e){this.srcList=[],this.srcList=t,this.select_name=e,this.modal1=!0},deleteSmping:function(t,e,a){var s=this,i={title:e,num:a,url:"riskdistrict/".concat(t.id),method:"DELETE",ids:""};this.$modalSure(i).then((function(t){console.log(t),200==t.data.code?(s.$Message.success(t.data.msg),s.list.splice(a,1),s.getSmpling()):s.$Message.error(t.data.msg)})).catch((function(t){s.$Message.error(t.data.msg)}))},sizeChange:function(t){this.formValidate.size=t,this.getSmpling(),this.$refs.table.clearCurrentRow()},pageChange:function(t){this.formValidate.page=t,this.getSmpling()},edit:function(t){this.$router.push({path:"/admin/riskdistrict/riskdistrictAdd/".concat(t.id)});var e=window.localStorage;e.setItem("sampingAdd",JSON.stringify(t))},Searchs:function(){this.formValidate.page=1,this.getSmpling()}}},g=f,v=(a("4e1e"),a("2877")),_=Object(v["a"])(g,s,i,!1,null,"4dc729ea",null);e["default"]=_.exports}}]);
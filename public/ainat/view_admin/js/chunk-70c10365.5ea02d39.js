(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-70c10365"],{"3fcd":function(t,e,a){"use strict";var n=a("f8ba"),r=a.n(n);r.a},f8ba:function(t,e,a){},fc59:function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"article-manager"},[a("div",{staticClass:"i-layout-page-header"},[a("PageHeader",{staticClass:"product_tabs",attrs:{"hidden-breadcrumb":""}},[a("div",{attrs:{slot:"content"},slot:"content"},[a("Tabs",{on:{"on-click":t.onClickTab},model:{value:t.artFrom.name,callback:function(e){t.$set(t.artFrom,"name",e)},expression:"artFrom.name"}},t._l(t.headeNum,(function(t,e){return a("TabPane",{key:e,attrs:{label:t.name,name:e.toString()}})})),1)],1)]),a("div")],1),a("Card",{staticClass:"ivu-mt",attrs:{bordered:!1,"dis-hover":""}},[a("Form",{ref:"artFrom",attrs:{model:t.artFrom,"label-width":t.labelWidth,"label-position":t.labelPosition},nativeOn:{submit:function(e){if(!e.type.indexOf("key")&&t._k(e.keyCode,"prevent1",void 0,e.key,void 0))return null}}},[a("Row",{attrs:{type:"flex",gutter:6}},[a("Col",{attrs:{span:"12"}},[a("Col",t._b({},"Col",t.grid,!1),[a("FormItem",{attrs:{label:"选择时间：","label-for":"user_time"}},[a("DatePicker",{staticClass:"mr20",staticStyle:{width:"300px"},attrs:{editable:!1,value:t.timeVal,format:"yyyy/MM/dd",type:"daterange",placement:"bottom-start",placeholder:"自定义时间",options:t.options},on:{"on-change":t.onchangeTime}})],1)],1)],1),a("Col",{attrs:{span:"6"}}),a("Col",{staticClass:"ivu-text-right userFrom",attrs:{span:"6"}},[a("Button",{staticClass:"mr15",attrs:{type:"primary",icon:"ios-search",label:"default"},on:{click:t.userSearchs}},[t._v("搜索")]),a("Button",{staticClass:"ResetSearch",on:{click:function(e){return t.reset("artFrom")}}},[t._v("重置")])],1)],1)],1),a("Table",{ref:"table",staticClass:"ivu-mt",attrs:{columns:t.columns,height:"540",data:t.tableList,loading:t.loading,"highlight-row":"","no-data-text":"暂无数据","no-filtered-data-text":"暂无筛选结果"},on:{"on-selection-change":t.onSelectTab,"on-select-all":t.selectAll,"on-select-all-cancel":t.selectAll},scopedSlots:t._u([{key:"id",fn:function(e){var a=e.row;return[t._v("\n        "+t._s(a.id)+"\n      ")]}},{key:"status",fn:function(e){var n=e.row;e.index;return[0==n.status?a("Tag",{attrs:{color:"error"}},[t._v("未办")]):a("Tag",{attrs:{color:"success"}},[t._v("已办")])]}},{key:"olddata",fn:function(e){var n=e.row;e.index;return[a("div",{staticClass:"newdatabox"},t._l(n.olddata,(function(e,n){return a("div",{key:n},[a("span",[t._v("\n              "+t._s(n)+":\n              "+t._s(e)+"\n            ")])])})),0)]}},{key:"newdata",fn:function(e){var n=e.row;e.index;return[a("div",{staticClass:"newdatabox"},t._l(n.newdata,(function(e,n){return a("div",{key:n},[a("span",[t._v("\n              "+t._s(n)+":\n              "+t._s(e)+"\n            ")])])})),0)]}},{key:"action",fn:function(e){var n=e.row;e.index;return[a("a",{on:{click:function(e){return t.shenhe(n)}}},[t._v("查看详情")])]}}])}),a("div",{staticClass:"acea-row row-right page"},[a("Page",{attrs:{total:t.total,current:t.artFrom.page,"show-elevator":"","show-total":"","show-sizer":"","page-size-opts":[5,10,15,20],"page-size":t.artFrom.size},on:{"on-page-size-change":t.sizeChange,"on-change":t.pageChange}})],1)],1)],1)},r=[],i=a("2f62");function o(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,n)}return a}function s(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?o(a,!0).forEach((function(e){l(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):o(a).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}function l(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}var c={name:"company_modifyrecord",components:{},computed:s({},Object(i["e"])("admin/layout",["isMobile"]),{labelWidth:function(){return this.isMobile?void 0:75},labelPosition:function(){return this.isMobile?"top":"right"}}),data:function(){return{headeNum:[{name:"全部",index:-1,value:""},{name:"企业日志",index:0,value:"company"},{name:"导出日志",index:1,value:"export"},{name:"预约日志",index:2,value:"subscribe"}],formValidate2:{},grid:{xl:6,lg:8,md:12,sm:24,xs:24},timeVal:"",options:{shortcuts:[{text:"今天",value:function(){var t=new Date,e=new Date;return e.setTime(new Date((new Date).getFullYear(),(new Date).getMonth(),(new Date).getDate())),[e,t]}},{text:"昨天",value:function(){var t=new Date,e=new Date;return e.setTime(e.setTime(new Date((new Date).getFullYear(),(new Date).getMonth(),(new Date).getDate()-1))),t.setTime(t.setTime(new Date((new Date).getFullYear(),(new Date).getMonth(),(new Date).getDate()-1))),[e,t]}},{text:"最近7天",value:function(){var t=new Date,e=new Date;return e.setTime(e.setTime(new Date((new Date).getFullYear(),(new Date).getMonth(),(new Date).getDate()-6))),[e,t]}},{text:"最近30天",value:function(){var t=new Date,e=new Date;return e.setTime(e.setTime(new Date((new Date).getFullYear(),(new Date).getMonth(),(new Date).getDate()-29))),[e,t]}},{text:"本月",value:function(){var t=new Date,e=new Date;return e.setTime(e.setTime(new Date((new Date).getFullYear(),(new Date).getMonth(),1))),[e,t]}},{text:"本年",value:function(){var t=new Date,e=new Date;return e.setTime(e.setTime(new Date((new Date).getFullYear(),0,1))),[e,t]}}]},artFrom:{page:1,size:10,name:"0",type:"",source:"admin"},tableList:[],loading:!1,columns:[{title:"序号",key:"order",width:80},{title:"操作人",key:"person",width:80},{title:"操作时间",key:"create_time",width:150},{title:"内容",key:"content"}],data:[],total:0,attrTemplate:!1,ids:[],display:"none",formSelection:[],selectionCopy:[],checkBox:!1,isAll:-1}},watch:{tableList:{deep:!0,handler:function(t){var e=this;t.forEach((function(t){e.formSelection.forEach((function(e){e.id===t.id&&(t.checkBox=!0)}))}));var a=this.tableList.filter((function(t){return t.checkBox}));this.tableList.length?this.checkBox=this.tableList.length===a.length:this.checkBox=!1}}},created:function(){},mounted:function(){this.getDataList()},methods:{onchangeTime:function(t){this.timeVal=t,""!=t[0]&&""!=t[1]?this.artFrom.date_range=this.timeVal.join("-"):this.artFrom.date_range=""},onClickTab:function(t){this.artFrom.type=this.headeNum[t].value,this.getDataList()},reset:function(t){this.artFrom={page:1,size:10,type:"company",name:0,source:"admin",date_range:""},this.timeVal="",this.getDataList()},selectAll:function(t){var e=this;t.length&&(this.formSelection=t,this.selectionCopy=t),this.selectionCopy.forEach((function(t,a){t.checkBox=e.checkBox,e.$set(e.tableList,a,t)}))},onSelectTab:function(t){this.formSelection=t;var e=[];t.map((function(t){e.push(t.id)})),this.ids=e},getDataList:function(){var t=this;this.loading=!0,console.log(this.artFrom),actionlog(this.artFrom).then((function(e){var a=e.data.data;t.tableList=a.data.map((function(e,a){return e.order=(t.artFrom.page-1)*t.artFrom.size+a+1,1===t.isAll?e.checkBox=!0:e.checkBox=!1,e})),t.total=a.total,t.loading=!1})).catch((function(e){t.loading=!1}))},sizeChange:function(t){this.artFrom.size=t,this.getDataList(),this.$refs.table.clearCurrentRow()},pageChange:function(t){this.artFrom.page=t,this.getDataList(),this.$refs.table.clearCurrentRow()},cascaderSearchs:function(t,e){this.userSearchs()},userSearchs:function(){this.artFrom.page=1,this.formSelection=[],this.getDataList()},sortChanged:function(t){this.artFrom[t.key]=t.order,this.getDataList()}}},u=c,h=(a("3fcd"),a("2877")),d=Object(h["a"])(u,n,r,!1,null,"449d5867",null);e["default"]=d.exports}}]);
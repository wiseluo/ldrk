(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-31589ecf"],{1690:function(e,t,a){"use strict";a.r(t);var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("div",{staticClass:"i-layout-page-header"},[a("PageHeader",{staticClass:"product_tabs",attrs:{title:"镇街汇总","hidden-breadcrumb":""}})],1),a("Card",{staticStyle:{"margin-top":"20px"}},[a("div",{staticClass:"headerEcharts"},[a("Button",{staticStyle:{margin:"0 0 15px 0"},on:{click:e.getSmpling}},[e._v("刷新")]),a("div",{staticClass:"isEchart"},[e._v("\n        表格\n        "),a("i-switch",{model:{value:e.isShowEcharts,callback:function(t){e.isShowEcharts=t},expression:"isShowEcharts"}}),e._v("\n        视图\n      ")],1)],1),a("Radio-group",{attrs:{size:"small",type:"button"},on:{"on-change":e.getSmpling},model:{value:e.tablekey,callback:function(t){e.tablekey=t},expression:"tablekey"}},e._l(e.tablekeyS,(function(t){return a("Radio",{key:t.tablekey,attrs:{label:t.tablekey}},[e._v("\n        "+e._s(t.name)+"\n      ")])})),1),a("div",{directives:[{name:"show",rawName:"v-show",value:e.isShowEcharts,expression:"isShowEcharts"}],staticClass:"echart",staticStyle:{width:"100%",height:"400px","margin-top":"0px"},attrs:{id:"fltotal"}}),a("Table",{directives:[{name:"show",rawName:"v-show",value:!e.isShowEcharts,expression:"!isShowEcharts"}],attrs:{columns:e.column,data:e.list},scopedSlots:e._u([{key:"index",fn:function(t){t.row;var n=t.index;return[n===e.list.length-1?a("span",[e._v("合计")]):a("span",[e._v(" "+e._s(n+1)+" ")])]}}])})],1)],1)},r=[],o=a("7680"),l=a("313e"),i=a.n(l);a("817d");var c={name:"admin-changsuomadata-townTotal",data:function(){return{isShowEcharts:!0,column:[],list:[],index:1,tablekey:"total_nums",tablekeyS:[]}},created:function(){},mounted:function(){this.getSmpling()},methods:{reset:function(){this.column=[{title:"序号",slot:"index",width:100},{title:"日期",key:"date",width:100}],this.list=[],this.tablekeyS=[]},getSmpling:function(){var e=this;this.reset(),Object(o["e"])().then((function(t){for(var a=t.data.data,n=a.header.title_arr,r=a.list,o=a.header.value_map,l=Object.keys(a.header.value_map),i=0,c=l;i<c.length;i++){var s=c[i];e.tablekeyS.push({tablekey:s,name:o[s]})}e.assemblyRules(n),e.assemblyList(r,e.tablekey),e.setflTotalEchart(a,e.tablekey)}))},assemblyRules:function(e){var t=this;e.forEach((function(e){t.column.push({title:e,key:e,width:100})})),this.column.push({title:"合计",key:"合计",width:100})},assemblyList:function(e,t){var a=this;e.forEach((function(e){var n={};n.date=e.date,e.list.forEach((function(e){n[e.title]=e.value[t]})),a.list.push(n)}));for(var n=Object.keys(this.list[0]),r={},o=function(){var e=i[l],t=0;if("date"===e)return"continue";a.list.forEach((function(a){t+=parseInt(a[e])})),r[e]=t},l=0,i=n;l<i.length;l++)o();this.list.push(r)},setflTotalEchart:function(e,t){var a=[],n=e.header.title_arr,r=e.list;n.forEach((function(e){var n=[];r.forEach((function(a){a.list.some((function(a){if(a.title==e)return n.push(a.value[t]),!0}))})),a.push({name:e,data:n,type:"line",smooth:!0})}));var o=e.header.date_arr,l=i.a.init(document.getElementById("fltotal"));l.clear();var c={legend:{top:0,textStyle:{color:"#1e1e1e"}},tooltip:{trigger:"axis"},grid:{top:"20%",left:"3%",right:"4%",bottom:"3%",containLabel:!0},color:["#5470c6","#91cc75","#fac858","#ee6666","#73c0de","#3ba272","#fc8452","#9a60b4","#ea7ccc"],xAxis:{type:"category",axisLine:{lineStyle:{color:"#1e1e1e"}},nameLocation:"start",data:o,name:"时间"},yAxis:{name:"扫码数",type:"value",axisLine:{lineStyle:{color:"#1e1e1e"}}},series:a};l.setOption(c,!0),window.addEventListener("resize",(function(){l.resize()}))}}},s=c,u=(a("bab6"),a("2877")),d=Object(u["a"])(s,n,r,!1,null,"5bf52990",null);t["default"]=d.exports},7680:function(e,t,a){"use strict";a.d(t,"a",(function(){return r})),a.d(t,"e",(function(){return o})),a.d(t,"d",(function(){return l})),a.d(t,"b",(function(){return i})),a.d(t,"i",(function(){return c})),a.d(t,"f",(function(){return s})),a.d(t,"c",(function(){return u})),a.d(t,"g",(function(){return d})),a.d(t,"j",(function(){return m})),a.d(t,"h",(function(){return h}));var n=a("b6bd");function r(e){return Object(n["a"])({url:"/placedeclare/abnormal",method:"get",params:e})}function o(e){return Object(n["a"])({url:"/querycenter/place_street_date_nums",method:"GET",params:e})}function l(e){return Object(n["a"])({url:"querycenter/place_hour_nums",method:"GET",params:e})}function i(e){return Object(n["a"])({url:"querycenter/place_classify_date_nums",method:"GET",params:e})}function c(e){return Object(n["a"])({url:"querycenter/place_type_date_nums",method:"get",params:e})}function s(e){return Object(n["a"])({url:"querycenter/place_date_nums_by_name",method:"GET",params:{name:"ymjz"}})}function u(e){return Object(n["a"])({url:"querycenter/place_date_nums_by_name",method:"GET",params:{name:"jkm"}})}function d(e){return Object(n["a"])({url:"querycenter/place_date_nums_by_name",method:"GET",params:{name:"xcm"}})}function m(e){return Object(n["a"])({url:"querycenter/place_date_nums_by_name",method:"GET",params:{name:"hsjc"}})}function h(e){return Object(n["a"])({url:"querycenter/place_date_nums_by_name",method:"GET",params:{name:"gkzz"}})}},"817d":function(e,t,a){var n,r,o;(function(l,i){r=[t,a("313e")],n=i,o="function"===typeof n?n.apply(t,r):n,void 0===o||(e.exports=o)})(0,(function(e,t){var a=function(e){"undefined"!==typeof console&&console&&console.error&&console.error(e)};if(t){var n=["#2ec7c9","#b6a2de","#5ab1ef","#ffb980","#d87a80","#8d98b3","#e5cf0d","#97b552","#95706d","#dc69aa","#07a2a4","#9a7fd1","#588dd5","#f5994e","#c05050","#59678c","#c9ab00","#7eb00a","#6f5553","#c14089"],r={color:n,title:{textStyle:{fontWeight:"normal",color:"#008acd"}},visualMap:{itemWidth:15,color:["#5ab1ef","#e0ffff"]},toolbox:{iconStyle:{normal:{borderColor:n[0]}}},tooltip:{backgroundColor:"rgba(50,50,50,0.5)",axisPointer:{type:"line",lineStyle:{color:"#008acd"},crossStyle:{color:"#008acd"},shadowStyle:{color:"rgba(200,200,200,0.2)"}}},dataZoom:{dataBackgroundColor:"#efefff",fillerColor:"rgba(182,162,222,0.2)",handleColor:"#008acd"},grid:{borderColor:"#eee"},categoryAxis:{axisLine:{lineStyle:{color:"#008acd"}},splitLine:{lineStyle:{color:["#eee"]}}},valueAxis:{axisLine:{lineStyle:{color:"#008acd"}},splitArea:{show:!0,areaStyle:{color:["rgba(250,250,250,0.1)","rgba(200,200,200,0.1)"]}},splitLine:{lineStyle:{color:["#eee"]}}},timeline:{lineStyle:{color:"#008acd"},controlStyle:{normal:{color:"#008acd"},emphasis:{color:"#008acd"}},symbol:"emptyCircle",symbolSize:3},line:{smooth:!0,symbol:"emptyCircle",symbolSize:3},candlestick:{itemStyle:{normal:{color:"#d87a80",color0:"#2ec7c9",lineStyle:{color:"#d87a80",color0:"#2ec7c9"}}}},scatter:{symbol:"circle",symbolSize:4},map:{label:{normal:{textStyle:{color:"#d87a80"}}},itemStyle:{normal:{borderColor:"#eee",areaColor:"#ddd"},emphasis:{areaColor:"#fe994e"}}},graph:{color:n},gauge:{axisLine:{lineStyle:{color:[[.2,"#2ec7c9"],[.8,"#5ab1ef"],[1,"#d87a80"]],width:10}},axisTick:{splitNumber:10,length:15,lineStyle:{color:"auto"}},splitLine:{length:22,lineStyle:{color:"auto"}},pointer:{width:5}}};t.registerTheme("macarons",r)}else a("ECharts is not Loaded")}))},bab6:function(e,t,a){"use strict";var n=a("d7a2"),r=a.n(n);r.a},d7a2:function(e,t,a){}}]);
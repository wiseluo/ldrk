(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-1cabdb6d"],{"69d0":function(e,t,n){"use strict";var a=n("c067"),l=n.n(a);l.a},"74c2":function(e,t,n){"use strict";n.r(t);var a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("div",{staticClass:"i-layout-page-header"},[n("PageHeader",{staticClass:"product_tabs",attrs:{title:"核酸汇总","hidden-breadcrumb":""}})],1),n("Card",{staticStyle:{"margin-top":"20px"}},[n("div",{staticClass:"headerEcharts"},[n("Button",{staticStyle:{margin:"0 0 15px 0"},on:{click:e.getSmpling}},[e._v("刷新")]),n("div",{staticClass:"isEchart"},[e._v("\n        表格\n        "),n("i-switch",{model:{value:e.isShowEcharts,callback:function(t){e.isShowEcharts=t},expression:"isShowEcharts"}}),e._v("\n        视图\n      ")],1)],1),n("div",{directives:[{name:"show",rawName:"v-show",value:e.isShowEcharts,expression:"isShowEcharts"}],staticClass:"echart",staticStyle:{width:"100%",height:"400px","margin-top":"0px"},attrs:{id:"fltotal"}}),n("Table",{directives:[{name:"show",rawName:"v-show",value:!e.isShowEcharts,expression:"!isShowEcharts"}],staticClass:"mt25",attrs:{columns:e.columns1,data:e.list,"no-userFrom-text":"暂无数据","no-filtered-userFrom-text":"暂无筛选结果",loading:e.loading,"highlight-row":""},scopedSlots:e._u([{key:"index",fn:function(t){t.row;var a=t.index;return[a===e.list.length-1?n("span",[e._v("合计")]):n("span",[e._v(" "+e._s(a+1)+" ")])]}}])})],1)],1)},l=[],c=n("7680"),r=n("313e"),o=n.n(r);n("817d");var i={name:"changsuomaData-nucleicAcid",data:function(){return{loading:!1,isShowEcharts:!0,list:[],columns1:[{title:"序号",slot:"index",width:100},{title:"日期",key:"date",width:80},{title:"24小时内核酸人数",key:"nucleic1",width:80},{title:"48小时内核酸人数",key:"nucleic2",width:80},{title:"72小时内核酸人数",key:"nucleic3",width:80},{title:"7天内核酸人数",key:"nucleic4",width:80},{title:"14天内核酸人数",key:"nucleic5",width:80},{title:"超14天内核酸人数",key:"nucleic6",width:80},{title:"未知数",key:"nucleic7",width:80},{title:"合计",key:"sum",width:80}]}},computed:{},created:function(){this.getSmpling()},methods:{getSmpling:function(){var e=this;this.loading=!0,Object(c["j"])().then((function(t){e.loading=!1;for(var n=t.data.data.list,a=[],l=0;l<n.length;l++)a[l]={},a[l].date=n[l].date,a[l]["nucleic1"]=n[l].list[n[l].list.length-1].value.hsjc_1_day_nums,a[l]["nucleic2"]=n[l].list[n[l].list.length-1].value.hsjc_2_day_nums,a[l]["nucleic3"]=n[l].list[n[l].list.length-1].value.hsjc_3_day_nums,a[l]["nucleic4"]=n[l].list[n[l].list.length-1].value.hsjc_7_day_nums,a[l]["nucleic5"]=n[l].list[n[l].list.length-1].value.hsjc_14_day_nums,a[l]["nucleic6"]=n[l].list[n[l].list.length-1].value.hsjc_outer_14_day_nums,a[l]["nucleic7"]=n[l].list[n[l].list.length-1].value.hsjc_unknow_nums,a[l]["sum"]=Number(a[l]["nucleic1"])+Number(a[l]["nucleic2"])+Number(a[l]["nucleic3"])+Number(a[l]["nucleic4"])+Number(a[l]["nucleic5"])+Number(a[l]["nucleic6"])+Number(a[l]["nucleic7"]);for(var c=[],r=[],o=0;o<e.columns1.length;o++)"date"!=e.columns1[o].key&&e.columns1[o].key&&(c.push(e.columns1[o].key),"sum"!=e.columns1[o].key&&r.push(e.columns1[o].key));for(var i={},s=0;s<c.length;s++){i[c[s]]=0;for(var u=0;u<a.length;u++)i[c[s]]+=Number(a[u][c[s]])}for(var d=[],m=0;m<r.length;m++){d[m]=[];for(var h=0;h<a.length;h++)d[m].push(Number(a[h][r[m]]))}a.push(i),e.list=a;for(var f=[],y=0;y<e.columns1.length;y++)"序号"!=e.columns1[y].title&&"日期"!=e.columns1[y].title&&"合计"!=e.columns1[y].title&&f.push(e.columns1[y].title);e.setflTotalEchart(t.data.data,f,d)}))},setflTotalEchart:function(e,t,n){for(var a=[],l=0;l<t.length;l++)a.push({name:t[l],data:n[l],type:"line",smooth:!0});var c=e.header.date_arr,r=o.a.init(document.getElementById("fltotal"));r.clear();var i={legend:{top:0,textStyle:{color:"#1e1e1e"}},tooltip:{trigger:"axis"},grid:{top:"30%",left:"3%",right:"4%",bottom:"3%",containLabel:!0},color:["#5470c6","#91cc75","#fac858","#ee6666","#73c0de","#3ba272","#fc8452","#9a60b4","#ea7ccc"],xAxis:{type:"category",axisLine:{lineStyle:{color:"#1e1e1e"}},nameLocation:"start",data:c,name:"时间"},yAxis:{name:"扫码数",type:"value",axisLine:{lineStyle:{color:"#1e1e1e"}}},series:a};r.setOption(i,!0),window.addEventListener("resize",(function(){r.resize()}))}}},s=i,u=(n("69d0"),n("2877")),d=Object(u["a"])(s,a,l,!1,null,"6f792644",null);t["default"]=d.exports},7680:function(e,t,n){"use strict";n.d(t,"a",(function(){return l})),n.d(t,"e",(function(){return c})),n.d(t,"d",(function(){return r})),n.d(t,"b",(function(){return o})),n.d(t,"i",(function(){return i})),n.d(t,"f",(function(){return s})),n.d(t,"c",(function(){return u})),n.d(t,"g",(function(){return d})),n.d(t,"j",(function(){return m})),n.d(t,"h",(function(){return h}));var a=n("b6bd");function l(e){return Object(a["a"])({url:"/placedeclare/abnormal",method:"get",params:e})}function c(e){return Object(a["a"])({url:"/querycenter/place_street_date_nums",method:"GET",params:e})}function r(e){return Object(a["a"])({url:"querycenter/place_hour_nums",method:"GET",params:e})}function o(e){return Object(a["a"])({url:"querycenter/place_classify_date_nums",method:"GET",params:e})}function i(e){return Object(a["a"])({url:"querycenter/place_type_date_nums",method:"get",params:e})}function s(e){return Object(a["a"])({url:"querycenter/place_date_nums_by_name",method:"GET",params:{name:"ymjz"}})}function u(e){return Object(a["a"])({url:"querycenter/place_date_nums_by_name",method:"GET",params:{name:"jkm"}})}function d(e){return Object(a["a"])({url:"querycenter/place_date_nums_by_name",method:"GET",params:{name:"xcm"}})}function m(e){return Object(a["a"])({url:"querycenter/place_date_nums_by_name",method:"GET",params:{name:"hsjc"}})}function h(e){return Object(a["a"])({url:"querycenter/place_date_nums_by_name",method:"GET",params:{name:"gkzz"}})}},"817d":function(e,t,n){var a,l,c;(function(r,o){l=[t,n("313e")],a=o,c="function"===typeof a?a.apply(t,l):a,void 0===c||(e.exports=c)})(0,(function(e,t){var n=function(e){"undefined"!==typeof console&&console&&console.error&&console.error(e)};if(t){var a=["#2ec7c9","#b6a2de","#5ab1ef","#ffb980","#d87a80","#8d98b3","#e5cf0d","#97b552","#95706d","#dc69aa","#07a2a4","#9a7fd1","#588dd5","#f5994e","#c05050","#59678c","#c9ab00","#7eb00a","#6f5553","#c14089"],l={color:a,title:{textStyle:{fontWeight:"normal",color:"#008acd"}},visualMap:{itemWidth:15,color:["#5ab1ef","#e0ffff"]},toolbox:{iconStyle:{normal:{borderColor:a[0]}}},tooltip:{backgroundColor:"rgba(50,50,50,0.5)",axisPointer:{type:"line",lineStyle:{color:"#008acd"},crossStyle:{color:"#008acd"},shadowStyle:{color:"rgba(200,200,200,0.2)"}}},dataZoom:{dataBackgroundColor:"#efefff",fillerColor:"rgba(182,162,222,0.2)",handleColor:"#008acd"},grid:{borderColor:"#eee"},categoryAxis:{axisLine:{lineStyle:{color:"#008acd"}},splitLine:{lineStyle:{color:["#eee"]}}},valueAxis:{axisLine:{lineStyle:{color:"#008acd"}},splitArea:{show:!0,areaStyle:{color:["rgba(250,250,250,0.1)","rgba(200,200,200,0.1)"]}},splitLine:{lineStyle:{color:["#eee"]}}},timeline:{lineStyle:{color:"#008acd"},controlStyle:{normal:{color:"#008acd"},emphasis:{color:"#008acd"}},symbol:"emptyCircle",symbolSize:3},line:{smooth:!0,symbol:"emptyCircle",symbolSize:3},candlestick:{itemStyle:{normal:{color:"#d87a80",color0:"#2ec7c9",lineStyle:{color:"#d87a80",color0:"#2ec7c9"}}}},scatter:{symbol:"circle",symbolSize:4},map:{label:{normal:{textStyle:{color:"#d87a80"}}},itemStyle:{normal:{borderColor:"#eee",areaColor:"#ddd"},emphasis:{areaColor:"#fe994e"}}},graph:{color:a},gauge:{axisLine:{lineStyle:{color:[[.2,"#2ec7c9"],[.8,"#5ab1ef"],[1,"#d87a80"]],width:10}},axisTick:{splitNumber:10,length:15,lineStyle:{color:"auto"}},splitLine:{length:22,lineStyle:{color:"auto"}},pointer:{width:5}}};t.registerTheme("macarons",l)}else n("ECharts is not Loaded")}))},c067:function(e,t,n){}}]);
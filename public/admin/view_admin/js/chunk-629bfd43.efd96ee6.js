(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-629bfd43"],{"45d1d":function(e,t,n){"use strict";n.r(t);var a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("div",{staticClass:"i-layout-page-header"},[n("PageHeader",{staticClass:"product_tabs",attrs:{title:"行程码汇总","hidden-breadcrumb":""}})],1),n("Card",{staticStyle:{"margin-top":"20px"}},[n("div",{staticClass:"headerEcharts"},[n("Button",{staticStyle:{margin:"0 0 15px 0"},on:{click:e.getSmpling}},[e._v("刷新")]),n("div",{staticClass:"isEchart"},[e._v("\n        表格\n        "),n("i-switch",{model:{value:e.isShowEcharts,callback:function(t){e.isShowEcharts=t},expression:"isShowEcharts"}}),e._v("\n        视图\n      ")],1)],1),n("div",{directives:[{name:"show",rawName:"v-show",value:e.isShowEcharts,expression:"isShowEcharts"}],staticClass:"echart",staticStyle:{width:"100%",height:"400px","margin-top":"0px"},attrs:{id:"fltotal"}}),n("Table",{directives:[{name:"show",rawName:"v-show",value:!e.isShowEcharts,expression:"!isShowEcharts"}],attrs:{columns:e.column,data:e.list},scopedSlots:e._u([{key:"index",fn:function(t){t.row;var a=t.index;return[a===e.list.length-1?n("span",[e._v("合计")]):n("span",[e._v(" "+e._s(a+1)+" ")])]}}])})],1)],1)},r=[],c=n("7680"),s=n("313e"),i=n.n(s),u={name:"changsuomaData-xcmTotal",data:function(){return{isShowEcharts:!0,column:[],list:[],index:1}},created:function(){this.getSmpling()},methods:{reset:function(){this.list=[],this.column=[{title:"序号",slot:"index",width:100},{title:"日期",key:"date",width:100}]},getSmpling:function(){var e=this;this.reset(),Object(c["g"])().then((function(t){var n=t.data.data,a=n.list;e.assemblyRules(),e.assemblyList(a),e.setxcmTotalEchart(n)}))},assemblyRules:function(){var e=this,t=[{name:"绿码",key:"xcm_green_nums"},{name:"黄码",key:"xcm_yellow_nums"},{name:"红码",key:"xcm_red_nums"},{name:"未知码",key:"xcm_unknow_nums"},{name:"合计",key:"xcm_sum"}];t.forEach((function(t){e.column.push({title:t.name,key:t.key,width:100})}))},assemblyList:function(e){var t=this;e.forEach((function(e){var n={};n.date=e.date;var a=parseInt(e.list[0].value.xcm_green_nums),r=parseInt(e.list[0].value.xcm_yellow_nums),c=parseInt(e.list[0].value.xcm_red_nums),s=parseInt(e.list[0].value.xcm_unknow_nums);n.xcm_green_nums=a,n.xcm_yellow_nums=r,n.xcm_red_nums=c,n.xcm_unknow_nums=s,n.xcm_sum=a+r+c+s,t.list.push(n)}));for(var n=Object.keys(this.list[0]),a={},r=function(){var e=s[c],n=0;if("date"===e)return"continue";t.list.forEach((function(t){n+=parseInt(t[e])})),a[e]=n},c=0,s=n;c<s.length;c++)r();this.list.push(a)},setxcmTotalEchart:function(e){var t=[],n=[{name:"绿码",key:"xcm_green_nums",color:"green"},{name:"黄码",key:"xcm_yellow_nums",color:"yellow"},{name:"红码",key:"xcm_red_nums",color:"red"},{name:"未知码",key:"xcm_unknow_nums",color:"black"}],a=e.list;n.forEach((function(e){var n=[];a.forEach((function(t){n.push(t.list[0].value[e.key])})),t.push({name:e.name,data:n,type:"line",smooth:!0,itemStyle:{normal:{color:e.color,lineStyle:{color:e.color}}}})}));var r=e.header.date_arr,c=i.a.init(document.getElementById("fltotal"));c.clear();var s={legend:{top:0,textStyle:{color:"#1e1e1e"}},tooltip:{trigger:"axis"},grid:{top:"20%",left:"3%",right:"4%",bottom:"3%",containLabel:!0},color:["#5470c6","#91cc75","#fac858","#ee6666","#73c0de","#3ba272","#fc8452","#9a60b4","#ea7ccc"],xAxis:{type:"category",axisLine:{lineStyle:{color:"#1e1e1e"}},nameLocation:"start",data:r,name:"时间"},yAxis:{name:"扫码数",type:"value",axisLine:{lineStyle:{color:"#1e1e1e"}}},series:t};c.setOption(s,!0),window.addEventListener("resize",(function(){c.resize()}))}}},o=u,l=(n("535d"),n("2877")),m=Object(l["a"])(o,a,r,!1,null,"4c423d2e",null);t["default"]=m.exports},"535d":function(e,t,n){"use strict";var a=n("a50c"),r=n.n(a);r.a},7680:function(e,t,n){"use strict";n.d(t,"a",(function(){return r})),n.d(t,"e",(function(){return c})),n.d(t,"d",(function(){return s})),n.d(t,"b",(function(){return i})),n.d(t,"i",(function(){return u})),n.d(t,"f",(function(){return o})),n.d(t,"c",(function(){return l})),n.d(t,"g",(function(){return m})),n.d(t,"j",(function(){return d})),n.d(t,"h",(function(){return h}));var a=n("b6bd");function r(e){return Object(a["a"])({url:"/placedeclare/abnormal",method:"get",params:e})}function c(e){return Object(a["a"])({url:"/querycenter/place_street_date_nums",method:"GET",params:e})}function s(e){return Object(a["a"])({url:"querycenter/place_hour_nums",method:"GET",params:e})}function i(e){return Object(a["a"])({url:"querycenter/place_classify_date_nums",method:"GET",params:e})}function u(e){return Object(a["a"])({url:"querycenter/place_type_date_nums",method:"get",params:e})}function o(e){return Object(a["a"])({url:"querycenter/place_date_nums_by_name",method:"GET",params:{name:"ymjz"}})}function l(e){return Object(a["a"])({url:"querycenter/place_date_nums_by_name",method:"GET",params:{name:"jkm"}})}function m(e){return Object(a["a"])({url:"querycenter/place_date_nums_by_name",method:"GET",params:{name:"xcm"}})}function d(e){return Object(a["a"])({url:"querycenter/place_date_nums_by_name",method:"GET",params:{name:"hsjc"}})}function h(e){return Object(a["a"])({url:"querycenter/place_date_nums_by_name",method:"GET",params:{name:"gkzz"}})}},a50c:function(e,t,n){}}]);
(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2b87f159","chunk-295c196b"],{6112:function(t,e,n){"use strict";n.r(e);var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("div",{staticClass:"i-layout-page-header"},[n("PageHeader",{staticClass:"product_tabs",attrs:{"hidden-breadcrumb":""}},[n("div",{attrs:{slot:"title"},slot:"title"},[n("router-link",{attrs:{to:{path:"/admin/setting/pages/devise"}}},[n("Button",{staticClass:"mr20",attrs:{icon:"ios-arrow-back",size:"small"}},[t._v("返回")])],1),n("span",{staticClass:"mr20",domProps:{textContent:t._s("页面设计")}})],1)])],1),n("Card",{staticClass:"ivu-mt",attrs:{bordered:!1,"dis-hover":""}},[n("div",{staticClass:"flex-wrapper"},[n("iframe",{ref:"iframe",staticClass:"iframe-box",attrs:{src:t.iframeUrl,frameborder:"0"}}),n("div",[n("div",{staticClass:"content"},[n("rightConfig",{attrs:{name:t.configName,pageId:t.pageId}})],1)]),n("links")],1)])],1)},a=[],i=n("f478"),o=(n("2f62"),function(){var t=this,e=t.$createElement,n=t._self._c||e;return t.rCom.length?n("div",{staticClass:"right-box"},[n("div",{staticClass:"title-bar"},[t._v("模块配置")]),t.rCom.length?n("div",{staticClass:"mobile-config"},[t._l(t.rCom,(function(e,r){return n("div",{key:r},[n(e.components.name,{tag:"component",attrs:{name:e.configNme,configData:t.configData}})],1)})),t.rCom.length?n("div",{staticStyle:{"text-align":"center"}},[n("Button",{staticStyle:{width:"100%",margin:"0 auto",height:"40px"},attrs:{type:"primary"},on:{click:t.saveConfig}},[t._v("保存")])],1):t._e()],2):t._e()]):t._e()}),u=[],c=n("2542");function s(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,r)}return n}function d(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?s(n,!0).forEach((function(e){f(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):s(n).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function f(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var l={name:"rightConfig",components:d({},c["a"]),props:{name:{type:null,default:""},pageId:{type:Number,default:0}},computed:{defultArr:function(){this.$store.dispatch("admin/user/getPageName");var t=this.$store.state.admin.user.pageName;return this.$store.state.admin[t].component}},watch:{name:{handler:function(t,e){this.rCom=[];var n=this.$store.state.admin.user.pageName;if(this.configData=this.$store.state.admin[n].defaultConfig[t],this.rCom=this.$store.state.admin[n].component[t].list,this.configData.selectConfig){var r=this.configData.selectConfig.type?this.configData.selectConfig.type:"";this.configData.selectConfig.list=this.categoryList,r?this.getByCategory():this.getCategory()}},deep:!0},defultArr:{handler:function(t,e){this.rCom=[];this.objToArray(t);this.rCom=t[this.name].list},deep:!0}},data:function(){return{rCom:[],configData:{},isShow:!0,categoryList:[],status:0}},mounted:function(){this.storeStatus()},methods:{storeStatus:function(){var t=this;Object(i["l"])().then((function(e){t.status=parseInt(e.data.store_status)}))},getCategory:function(){var t=this;Object(i["g"])().then((function(e){var n=[];e.data.map((function(t){n.push({title:t.title,pid:t.pid,activeValue:t.id.toString()})})),t.categoryList=n,t.bus.$emit("upData",n)}))},getByCategory:function(){var t=this;Object(i["f"])().then((function(e){var n=[];e.data.map((function(t){n.push({title:t.cate_name,pid:t.pid,activeValue:t.id.toString()})})),t.categoryList=n,t.bus.$emit("upData",n)}))},saveConfig:function(){var t=this,e=this.$store.state.admin.user.pageName,n=this.$store.state.admin[e].defaultConfig,r=this.$store.state.admin[e].periphery;if("tabBar"==this.name){if(!r||!this.status)for(var a=n.tabBar.tabBarList.list,o=0;o<a.length;o++)if("/pages/storeList/index"==a[o].link||"pages/storeList/index"==a[o].link)return this.$Message.error("请先开启您的周边功能(/pages/storeList/index)");if(n.tabBar.tabBarList.list.length<2)return this.$Message.error("您最少应添加2个导航")}Object(i["e"])(this.pageId,{value:n}).then((function(e){t.$Message.success("保存成功")}))},objToArray:function(t){var e=[];for(var n in t)e.push(t[n]);return e}}},m=l,h=(n("c545"),n("2877")),p=Object(h["a"])(m,o,u,!1,null,"6b70dc86",null),g=p.exports,b=n("2250"),v={name:"index",components:{rightConfig:g,links:b["default"]},data:function(){return{configName:"",iframeUrl:"",setConfig:"",updataConfig:"",pageId:0}},created:function(){var t=this,e=this.$route.query.id,n=this.$route.query.name,r=this.$store.state[n].defaultConfig;this.setConfig="admin/"+n+"/setConfig",this.updataConfig="admin/"+n+"/updataConfig",this.pageId=parseInt(e),this.iframeUrl="".concat(location.origin,"?type=iframeMakkMinkkJuan"),Object(i["c"])(parseInt(e)).then((function(n){var a=n.data.info.value;a?t.upData(a):Object(i["e"])(parseInt(e),{value:r}).then((function(t){}))}))},mounted:function(){window.addEventListener("message",this.handleMessage,!1)},methods:{handleMessage:function(t){t.data.name&&(this.configName=t.data.name,this.add(t.data.name))},add:function(t){this.$store.commit(this.setConfig,t)},upData:function(t){this.$store.commit(this.updataConfig,t)}}},O=v,j=(n("9a48"),Object(h["a"])(O,r,a,!1,null,"3b84f684",null));e["default"]=j.exports},"6c07":function(t,e,n){},8260:function(t,e,n){},"8c03":function(t,e,n){"use strict";n.r(e);var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("Form",{ref:"formValidate",attrs:{model:t.formValidate,"label-width":100},nativeOn:{submit:function(t){t.preventDefault()}}},[n("Row",{attrs:{gutter:24,type:"flex"}},[n("Col",{staticClass:"ivu-text-left",attrs:{span:"24"}},[n("FormItem",{attrs:{label:"搜索日期："}},[n("RadioGroup",{staticClass:"mr",attrs:{type:"button"},on:{"on-change":function(e){return t.selectChange(t.formValidate.data)}},model:{value:t.formValidate.data,callback:function(e){t.$set(t.formValidate,"data",e)},expression:"formValidate.data"}},t._l(t.fromList.fromTxt,(function(e,r){return n("Radio",{key:r,attrs:{label:e.val}},[t._v(t._s(e.text))])})),1),n("DatePicker",{staticStyle:{width:"200px"},attrs:{editable:!1,value:t.timeVal,format:"yyyy/MM/dd",type:"daterange",placement:"bottom-end",placeholder:"自定义时间"},on:{"on-change":t.onchangeTime}})],1)],1),n("Col",{staticClass:"ivu-text-left",attrs:{span:"12"}},[n("FormItem",{attrs:{label:"用户名称："}},[n("Input",{staticStyle:{width:"90%"},attrs:{search:"","enter-button":"",placeholder:"请输入用户名称"},on:{"on-search":t.userSearchs},model:{value:t.formValidate.nickname,callback:function(e){t.$set(t.formValidate,"nickname",e)},expression:"formValidate.nickname"}})],1)],1)],1)],1),n("Table",{ref:"selection",attrs:{loading:t.loading2,"highlight-row":"","no-userFrom-text":"暂无数据","no-filtered-userFrom-text":"暂无筛选结果",columns:t.columns4,data:t.tableList2},scopedSlots:t._u([{key:"headimgurl",fn:function(t){var e=t.row;t.index;return[n("viewer",[n("div",{staticClass:"tabBox_img"},[n("img",{directives:[{name:"lazy",rawName:"v-lazy",value:e.headimgurl,expression:"row.headimgurl"}]})])])]}},{key:"user_type",fn:function(e){var r=e.row;e.index;return["wechat"===r.user_type?n("span",[t._v("公众号")]):t._e(),"routine"===r.user_type?n("span",[t._v("小程序")]):t._e(),"h5"===r.user_type?n("span",[t._v("H5")]):t._e(),"pc"===r.user_type?n("span",[t._v("PC")]):t._e()]}},{key:"sex",fn:function(e){var r=e.row;e.index;return[n("span",{directives:[{name:"show",rawName:"v-show",value:1===r.sex,expression:"row.sex ===1"}]},[t._v("男")]),n("span",{directives:[{name:"show",rawName:"v-show",value:2===r.sex,expression:"row.sex ===2"}]},[t._v("女")]),n("span",{directives:[{name:"show",rawName:"v-show",value:0===r.sex,expression:"row.sex ===0"}]},[t._v("保密")])]}},{key:"country",fn:function(e){var r=e.row;e.index;return[n("span",[t._v(t._s(r.country+r.province+r.city))])]}},{key:"subscribe",fn:function(e){var r=e.row;e.index;return[n("span",{domProps:{textContent:t._s(1===r.subscribe?"关注":"未关注")}})]}}])}),n("div",{staticClass:"acea-row row-right page"},[n("Page",{attrs:{total:t.total2,"show-elevator":"","show-total":"","page-size":t.formValidate.limit},on:{"on-change":t.pageChange2}})],1)],1)},a=[],i=n("a34a"),o=n.n(i),u=n("90e7"),c=n("6112");function s(t,e,n,r,a,i,o){try{var u=t[i](o),c=u.value}catch(s){return void n(s)}u.done?e(c):Promise.resolve(c).then(r,a)}function d(t){return function(){var e=this,n=arguments;return new Promise((function(r,a){var i=t.apply(e,n);function o(t){s(i,r,a,o,u,"next",t)}function u(t){s(i,r,a,o,u,"throw",t)}o(void 0)}))}}var f={components:{template:c["default"]},name:"index",data:function(){var t=this;return{formValidate:{page:1,limit:15,data:"",nickname:""},tableList2:[],timeVal:[],fromList:{title:"选择时间",custom:!0,fromTxt:[{text:"全部",val:""},{text:"今天",val:"today"},{text:"昨天",val:"yesterday"},{text:"最近7天",val:"lately7"},{text:"最近30天",val:"lately30"},{text:"本月",val:"month"},{text:"本年",val:"year"}]},currentid:0,productRow:{},columns4:[{title:"选择",key:"chose",width:60,align:"center",render:function(e,n){var r=n.row.uid,a=!1;a=t.currentid===r;var i=t;return e("div",[e("Radio",{props:{value:a},on:{"on-change":function(){if(i.currentid=r,t.productRow=n.row,t.productRow.uid)if("image"===t.$route.query.fodder){var e={image:t.productRow.headimgurl,uid:t.productRow.uid};form_create_helper.set("image",e),form_create_helper.close("image")}else t.$emit("imageObject",{image:t.productRow.headimgurl,uid:t.productRow.uid});else t.$Message.warning("请先选择商品")}}})])}},{title:"ID",key:"uid",width:80},{title:"微信用户名称",key:"nickname",minWidth:180},{title:"客服头像",slot:"headimgurl",minWidth:60},{title:"用户类型",slot:"user_type",minWidth:100},{title:"性别",slot:"sex",minWidth:60},{title:"地区",slot:"country",minWidth:120},{title:"是否关注公众号",slot:"subscribe",minWidth:120}],loading2:!1,total2:0}},created:function(){},mounted:function(){this.getListService()},methods:{onchangeTime:function(t){this.timeVal=t,this.formValidate.data=this.timeVal.join("-"),this.getListService()},selectChange:function(t){this.formValidate.data=t,this.timeVal=[],this.getListService()},getListService:function(){var t=this;this.loading2=!0,Object(u["A"])(this.formValidate).then(function(){var e=d(o.a.mark((function e(n){var r;return o.a.wrap((function(e){while(1)switch(e.prev=e.next){case 0:r=n.data,t.tableList2=r.list,t.total2=r.count,t.tableList2.map((function(t){t._isChecked=!1})),t.loading2=!1;case 5:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()).catch((function(e){t.loading2=!1,t.$Message.error(e.msg)}))},pageChange2:function(t){this.formValidate.page=t,this.getListService()},userSearchs:function(){this.formValidate.page=1,this.getListService()}}},l=f,m=(n("a882"),n("2877")),h=Object(m["a"])(l,r,a,!1,null,"9cd3fe30",null);e["default"]=h.exports},"8d17c":function(t,e,n){},"90e7":function(t,e,n){"use strict";n.d(e,"p",(function(){return a})),n.d(e,"h",(function(){return i})),n.d(e,"ob",(function(){return o})),n.d(e,"nb",(function(){return u})),n.d(e,"g",(function(){return c})),n.d(e,"O",(function(){return s})),n.d(e,"V",(function(){return d})),n.d(e,"R",(function(){return f})),n.d(e,"S",(function(){return l})),n.d(e,"b",(function(){return m})),n.d(e,"P",(function(){return h})),n.d(e,"sb",(function(){return p})),n.d(e,"T",(function(){return g})),n.d(e,"j",(function(){return b})),n.d(e,"i",(function(){return v})),n.d(e,"a",(function(){return O})),n.d(e,"I",(function(){return j})),n.d(e,"X",(function(){return y})),n.d(e,"H",(function(){return w})),n.d(e,"Y",(function(){return _})),n.d(e,"fb",(function(){return x})),n.d(e,"C",(function(){return C})),n.d(e,"eb",(function(){return k})),n.d(e,"m",(function(){return $})),n.d(e,"k",(function(){return P})),n.d(e,"l",(function(){return V})),n.d(e,"n",(function(){return L})),n.d(e,"o",(function(){return S})),n.d(e,"L",(function(){return T})),n.d(e,"M",(function(){return D})),n.d(e,"J",(function(){return E})),n.d(e,"K",(function(){return I})),n.d(e,"E",(function(){return N})),n.d(e,"w",(function(){return M})),n.d(e,"A",(function(){return R})),n.d(e,"z",(function(){return B})),n.d(e,"r",(function(){return G})),n.d(e,"B",(function(){return U})),n.d(e,"t",(function(){return W})),n.d(e,"y",(function(){return A})),n.d(e,"s",(function(){return F})),n.d(e,"q",(function(){return q})),n.d(e,"D",(function(){return z})),n.d(e,"f",(function(){return J})),n.d(e,"c",(function(){return H})),n.d(e,"d",(function(){return K})),n.d(e,"pb",(function(){return Q})),n.d(e,"qb",(function(){return X})),n.d(e,"rb",(function(){return Y})),n.d(e,"W",(function(){return Z})),n.d(e,"gb",(function(){return tt})),n.d(e,"F",(function(){return et})),n.d(e,"ib",(function(){return nt})),n.d(e,"hb",(function(){return rt})),n.d(e,"jb",(function(){return at})),n.d(e,"kb",(function(){return it})),n.d(e,"lb",(function(){return ot})),n.d(e,"mb",(function(){return ut})),n.d(e,"tb",(function(){return ct})),n.d(e,"ub",(function(){return st})),n.d(e,"G",(function(){return dt})),n.d(e,"e",(function(){return ft})),n.d(e,"vb",(function(){return lt})),n.d(e,"Z",(function(){return mt})),n.d(e,"ab",(function(){return ht})),n.d(e,"x",(function(){return pt})),n.d(e,"u",(function(){return gt})),n.d(e,"U",(function(){return bt})),n.d(e,"bb",(function(){return vt})),n.d(e,"cb",(function(){return Ot})),n.d(e,"db",(function(){return jt})),n.d(e,"v",(function(){return yt})),n.d(e,"Q",(function(){return wt})),n.d(e,"N",(function(){return _t}));var r=n("b6bd");function a(t){return Object(r["a"])({url:"setting/config/header_basics",method:"get",params:t})}function i(t,e){return Object(r["a"])({url:e,method:"get",params:t})}function o(t){return Object(r["a"])({url:t.url,method:"get",params:t.data})}function u(){return Object(r["a"])({url:"notify/sms/temp/create",method:"get"})}function c(t){return Object(r["a"])({url:"serve/login",method:"post",data:t})}function s(){return Object(r["a"])({url:"serve/info",method:"get"})}function d(t){return Object(r["a"])({url:"serve/sms/open",method:"get",params:t})}function f(t){return Object(r["a"])({url:"serve/opn_express",method:"post",data:t})}function l(t){return Object(r["a"])({url:"serve/open",method:"get",params:t})}function m(t){return Object(r["a"])({url:"serve/checkCode",method:"post",data:t})}function h(t){return Object(r["a"])({url:"serve/modify",method:"post",data:t})}function p(t){return Object(r["a"])({url:"serve/update_phone",method:"post",data:t})}function g(t){return Object(r["a"])({url:"serve/record",method:"get",params:t})}function b(t){return Object(r["a"])({url:"serve/export_temp",method:"get",params:t})}function v(){return Object(r["a"])({url:"serve/export_all",method:"get"})}function O(t){return Object(r["a"])({url:"serve/captcha",method:"post",data:t})}function j(t){return Object(r["a"])({url:"serve/register",method:"post",data:t})}function y(t){return Object(r["a"])({url:"serve/meal_list",method:"get",params:t})}function w(t){return Object(r["a"])({url:"serve/pay_meal",method:"post",data:t})}function _(t){return Object(r["a"])({url:"notify/sms/record",method:"get",params:t})}function x(){return Object(r["a"])({url:"merchant/store",method:"GET"})}function C(){return Object(r["a"])({url:"merchant/store/address",method:"GET"})}function k(t){return Object(r["a"])({url:"merchant/store/".concat(t.id),method:"POST",data:t})}function $(t){return Object(r["a"])({url:"freight/express",method:"get",params:t})}function P(){return Object(r["a"])({url:"/freight/express/create",method:"get"})}function V(t){return Object(r["a"])({url:"freight/express/".concat(t,"/edit"),method:"get"})}function L(t){return Object(r["a"])({url:"freight/express/set_status/".concat(t.id,"/").concat(t.status),method:"PUT"})}function S(){return Object(r["a"])({url:"freight/express/sync_express",method:"get"})}function T(t){return Object(r["a"])({url:"setting/role",method:"GET",params:t})}function D(t){return Object(r["a"])({url:"setting/role/set_status/".concat(t.id,"/").concat(t.status),method:"PUT"})}function E(t){return Object(r["a"])({url:"setting/role/".concat(t.id),method:"post",data:t})}function I(t){return Object(r["a"])({url:"setting/role/".concat(t,"/edit"),method:"get"})}function N(){return Object(r["a"])({url:"setting/role/create",method:"get"})}function M(t){return Object(r["a"])({url:"app/wechat/kefu",method:"get",params:t})}function R(t){return Object(r["a"])({url:"app/wechat/kefu/create",method:"get",params:t})}function B(){return Object(r["a"])({url:"app/wechat/kefu/add",method:"get"})}function G(t){return Object(r["a"])({url:"app/wechat/kefu",method:"post",data:t})}function U(t){return Object(r["a"])({url:"app/wechat/kefu/set_status/".concat(t.id,"/").concat(t.account_status),method:"PUT"})}function W(t){return Object(r["a"])({url:"app/wechat/kefu/".concat(t,"/edit"),method:"GET"})}function A(t,e){return Object(r["a"])({url:"app/wechat/kefu/record/".concat(e),method:"GET",params:t})}function F(t){return Object(r["a"])({url:"app/wechat/kefu/chat_list",method:"GET",params:t})}function q(){return Object(r["a"])({url:"notify/sms/is_login",method:"GET"})}function z(){return Object(r["a"])({url:"notify/sms/logout",method:"GET"})}function J(t){return Object(r["a"])({url:"setting/city/list/".concat(t),method:"get"})}function H(t){return Object(r["a"])({url:"setting/city/add/".concat(t),method:"get"})}function K(t){return Object(r["a"])({url:"setting/city/".concat(t,"/edit"),method:"get"})}function Q(t){return Object(r["a"])({url:"setting/shipping_templates/list",method:"get",params:t})}function X(t){return Object(r["a"])({url:"setting/shipping_templates/city_list",method:"get"})}function Y(t,e){return Object(r["a"])({url:"setting/shipping_templates/save/".concat(t),method:"post",data:e})}function Z(t){return Object(r["a"])({url:"setting/shipping_templates/".concat(t,"/edit"),method:"get"})}function tt(){return Object(r["a"])({url:"merchant/store/get_header",method:"get"})}function et(t){return Object(r["a"])({url:"merchant/store",method:"get",params:t})}function nt(t,e){return Object(r["a"])({url:"merchant/store/set_show/".concat(t,"/").concat(e),method:"put"})}function rt(t){return Object(r["a"])({url:"merchant/store/get_info/".concat(t),method:"get"})}function at(t){return Object(r["a"])({url:"merchant/store_staff",method:"get",params:t})}function it(){return Object(r["a"])({url:"merchant/store_staff/create",method:"get"})}function ot(t){return Object(r["a"])({url:"merchant/store_staff/".concat(t,"/edit"),method:"get"})}function ut(t,e){return Object(r["a"])({url:"merchant/store_staff/set_show/".concat(t,"/").concat(e),method:"put"})}function ct(t){return Object(r["a"])({url:"merchant/verify_order",method:"get",params:t})}function st(t){return Object(r["a"])({url:"merchant/verify/spread_info/".concat(t),method:"get"})}function dt(){return Object(r["a"])({url:"merchant/store_list",method:"get"})}function ft(){return Object(r["a"])({url:"setting/city/clean_cache",method:"get"})}function lt(t){return Object(r["a"])({url:"app/wechat/speechcraft",method:"get",params:t})}function mt(){return Object(r["a"])({url:"app/wechat/speechcraft/create",method:"get"})}function ht(t){return Object(r["a"])({url:"app/wechat/speechcraft/".concat(t,"/edit"),method:"get"})}function pt(t){return Object(r["a"])({url:"app/wechat/kefu/login/".concat(t),method:"get"})}function gt(t){return Object(r["a"])({url:"app/feedback",method:"get",params:t})}function bt(t){return Object(r["a"])({url:"serve/sms/sign",method:"PUT",data:t})}function vt(){return Object(r["a"])({url:"app/wechat/speechcraftcate",method:"get"})}function Ot(){return Object(r["a"])({url:"app/wechat/speechcraftcate/create",method:"get"})}function jt(t){return Object(r["a"])({url:"app/wechat/speechcraftcate/".concat(t,"/edit"),method:"get"})}function yt(t){return Object(r["a"])({url:"app/feedback/".concat(t,"/edit"),method:"get"})}function wt(){return Object(r["a"])({url:"serve/open",method:"get"})}function _t(){return Object(r["a"])({url:"serve/dump_open",method:"get"})}},"9a48":function(t,e,n){"use strict";var r=n("8260"),a=n.n(r);a.a},a882:function(t,e,n){"use strict";var r=n("8d17c"),a=n.n(r);a.a},c545:function(t,e,n){"use strict";var r=n("6c07"),a=n.n(r);a.a}}]);
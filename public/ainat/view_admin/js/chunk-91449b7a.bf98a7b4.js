(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-91449b7a"],{"90e7":function(t,e,n){"use strict";n.d(e,"f",(function(){return a})),n.d(e,"e",(function(){return c})),n.d(e,"u",(function(){return i})),n.d(e,"v",(function(){return u})),n.d(e,"s",(function(){return o})),n.d(e,"t",(function(){return s})),n.d(e,"r",(function(){return d})),n.d(e,"l",(function(){return h})),n.d(e,"p",(function(){return l})),n.d(e,"o",(function(){return f})),n.d(e,"g",(function(){return p})),n.d(e,"q",(function(){return m})),n.d(e,"i",(function(){return b})),n.d(e,"n",(function(){return g})),n.d(e,"h",(function(){return w})),n.d(e,"d",(function(){return v})),n.d(e,"a",(function(){return j})),n.d(e,"b",(function(){return O})),n.d(e,"c",(function(){return _})),n.d(e,"B",(function(){return k})),n.d(e,"w",(function(){return T})),n.d(e,"x",(function(){return y})),n.d(e,"m",(function(){return S})),n.d(e,"j",(function(){return $})),n.d(e,"y",(function(){return L})),n.d(e,"z",(function(){return x})),n.d(e,"A",(function(){return C})),n.d(e,"k",(function(){return I}));var r=n("b6bd");function a(t){return Object(r["a"])({url:"setting/config/header_basics",method:"get",params:t})}function c(t,e){return Object(r["a"])({url:e,method:"get",params:t})}function i(t){return Object(r["a"])({url:"setting/role",method:"GET",params:t})}function u(t){return Object(r["a"])({url:"setting/role/set_status/".concat(t.id,"/").concat(t.status),method:"PUT"})}function o(t){return Object(r["a"])({url:"setting/role/".concat(t.id),method:"post",data:t})}function s(t){return Object(r["a"])({url:"setting/role/".concat(t,"/edit"),method:"get"})}function d(){return Object(r["a"])({url:"setting/role/create",method:"get"})}function h(t){return Object(r["a"])({url:"app/wechat/kefu",method:"get",params:t})}function l(t){return Object(r["a"])({url:"app/wechat/kefu/create",method:"get",params:t})}function f(){return Object(r["a"])({url:"app/wechat/kefu/add",method:"get"})}function p(t){return Object(r["a"])({url:"app/wechat/kefu",method:"post",data:t})}function m(t){return Object(r["a"])({url:"app/wechat/kefu/set_status/".concat(t.id,"/").concat(t.account_status),method:"PUT"})}function b(t){return Object(r["a"])({url:"app/wechat/kefu/".concat(t,"/edit"),method:"GET"})}function g(t,e){return Object(r["a"])({url:"app/wechat/kefu/record/".concat(e),method:"GET",params:t})}function w(t){return Object(r["a"])({url:"app/wechat/kefu/chat_list",method:"GET",params:t})}function v(t){return Object(r["a"])({url:"setting/city/list/".concat(t),method:"get"})}function j(t){return Object(r["a"])({url:"setting/city/add/".concat(t),method:"get"})}function O(t){return Object(r["a"])({url:"setting/city/".concat(t,"/edit"),method:"get"})}function _(){return Object(r["a"])({url:"setting/city/clean_cache",method:"get"})}function k(t){return Object(r["a"])({url:"app/wechat/speechcraft",method:"get",params:t})}function T(){return Object(r["a"])({url:"app/wechat/speechcraft/create",method:"get"})}function y(t){return Object(r["a"])({url:"app/wechat/speechcraft/".concat(t,"/edit"),method:"get"})}function S(t){return Object(r["a"])({url:"app/wechat/kefu/login/".concat(t),method:"get"})}function $(t){return Object(r["a"])({url:"app/feedback",method:"get",params:t})}function L(){return Object(r["a"])({url:"app/wechat/speechcraftcate",method:"get"})}function x(){return Object(r["a"])({url:"app/wechat/speechcraftcate/create",method:"get"})}function C(t){return Object(r["a"])({url:"app/wechat/speechcraftcate/".concat(t,"/edit"),method:"get"})}function I(t){return Object(r["a"])({url:"app/feedback/".concat(t,"/edit"),method:"get"})}},a53e:function(t,e,n){"use strict";n.r(e);var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"article-manager"},[n("div",{staticClass:"i-layout-page-header"},[n("PageHeader",{staticClass:"product_tabs",attrs:{title:t.title,"hidden-breadcrumb":""}},[t.currentTab?n("div",{attrs:{slot:"content"},slot:"content"},[n("Tabs",{on:{"on-click":t.changeTab},model:{value:t.currentTab,callback:function(e){t.currentTab=e},expression:"currentTab"}},t._l(t.headerList,(function(t,e){return n("TabPane",{key:e,attrs:{icon:t.icon,label:t.label,name:t.value.toString()}})})),1)],1):t._e()])],1),n("Card",{staticClass:"ivu-mt fromBox",attrs:{bordered:!1,"dis-hover":""}},[t.headerChildrenList.length?n("Tabs",{attrs:{type:"card"},on:{"on-click":t.changeChildrenTab},model:{value:t.childrenId,callback:function(e){t.childrenId=e},expression:"childrenId"}},t._l(t.headerChildrenList,(function(t,e){return n("TabPane",{key:e,attrs:{label:t.label,name:t.id.toString()}})})),1):t._e(),0!==t.rules.length?n("form-create",{attrs:{option:t.option,rule:t.rules},on:{"on-submit":t.onSubmit}}):t._e(),t.spinShow?n("Spin",{attrs:{size:"large",fix:""}}):t._e()],1)],1)},a=[],c=n("a34a"),i=n.n(c),u=n("9860"),o=n.n(u),s=n("90e7"),d=n("b6bd");function h(t,e,n,r,a,c,i){try{var u=t[c](i),o=u.value}catch(s){return void n(s)}u.done?e(o):Promise.resolve(o).then(r,a)}function l(t){return function(){var e=this,n=arguments;return new Promise((function(r,a){var c=t.apply(e,n);function i(t){h(c,r,a,i,u,"next",t)}function u(t){h(c,r,a,i,u,"throw",t)}i(void 0)}))}}var f={name:"setting_setSystem",components:{formCreate:o.a.$form()},data:function(){return{rules:[],option:{form:{labelWidth:185},submitBtn:{col:{span:3,push:3}},global:{upload:{props:{onSuccess:function(t,e){200===t.status?e.url=t.data.src:this.$Message.error(t.msg)}}},frame:{props:{closeBtn:!1,okBtn:!1}}}},spinShow:!1,FromData:null,currentTab:"",headerList:[],headerChildrenList:[],childrenId:"",title:""}},created:function(){this.getAllData()},watch:{$route:function(t,e){this.headerChildrenList=[],this.getAllData()},childrenId:function(){this.getFrom()}},methods:{childrenList:function(){var t=this;t.headerList.forEach((function(e){e.value.toString()===t.currentTab&&(void 0===e.children?(t.childrenId=e.id,t.headerChildrenList=[]):(t.headerChildrenList=e.children,t.childrenId=e.children.length?e.children[0].id.toString():""))}))},getHeader:function(){var t=this;return this.spinShow=!0,new Promise((function(e,n){var r=t.$route.params.tab_id,a={type:t.$route.params.type?t.$route.params.type:0,pid:r||0};Object(s["f"])(a).then(function(){var n=l(i.a.mark((function n(r){var a,c;return i.a.wrap((function(n){while(1)switch(n.prev=n.next){case 0:a=r.data.config_tab,c=[],c[0]=a[0],t.headerList=c,t.currentTab=a[0].value.toString(),t.childrenList(),e(t.currentTab),t.spinShow=!1;case 8:case"end":return n.stop()}}),n)})));return function(t){return n.apply(this,arguments)}}()).catch((function(e){t.spinShow=!1,t.$Message.error(e.msg)}))}))},getFrom:function(){var t=this;return this.spinShow=!0,new Promise((function(e,n){var r="";r="3"===t.$route.params.type?t.$route.params.tab_id:t.childrenId?t.childrenId:t.currentTab;var a={tab_id:Number(r)},c="freight/config/edit_basics",u="agent/config/edit_basics",o="marketing/integral_config/edit_basics",d="serve/sms_config/edit_basics",h="setting/config/edit_basics",f="setting_logistics"===t.$route.name?c:"setting_distributionSet"===t.$route.name?u:"setting_message"===t.$route.name?d:"setting_setSystem"===t.$route.name?h:o;Object(s["e"])(a,f).then(function(){var e=l(i.a.mark((function e(n){return i.a.wrap((function(e){while(1)switch(e.prev=e.next){case 0:if(t.spinShow=!1,!1!==n.data.status){e.next=3;break}return e.abrupt("return",t.$authLapse(n.data));case 3:t.FromData=n.data,t.rules=n.data.rules,t.title=n.data.title;case 6:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()).catch((function(e){t.spinShow=!1,t.$Message.error(e.msg)}))}))},getAllData:function(){var t=l(i.a.mark((function t(){return i.a.wrap((function(t){while(1)switch(t.prev=t.next){case 0:if("3"===this.$route.params.type){t.next=5;break}return t.next=3,this.getHeader();case 3:t.next=7;break;case 5:this.headerList=[],this.getFrom();case 7:case"end":return t.stop()}}),t,this)})));function e(){return t.apply(this,arguments)}return e}(),changeTab:function(){this.childrenList()},changeChildrenTab:function(t){this.childrenId=t},onSubmit:function(t){var e=this;Object(d["a"])({url:this.FromData.action,method:this.FromData.method,data:t}).then((function(t){e.$store.dispatch("admin/account/setPageTitle"),e.$Message.success(t.msg)})).catch((function(t){e.$Message.error(t.msg)}))}}},p=f,m=(n("adc5"),n("2877")),b=Object(m["a"])(p,r,a,!1,null,"2dcdaa70",null);e["default"]=b.exports},adc5:function(t,e,n){"use strict";var r=n("fe17"),a=n.n(r);a.a},fe17:function(t,e,n){}}]);
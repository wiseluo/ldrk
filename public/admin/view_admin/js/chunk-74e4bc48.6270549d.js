(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-74e4bc48"],{4127:function(t,e,n){"use strict";var r=n("d233"),i=n("b313"),o=Object.prototype.hasOwnProperty,a={brackets:function(t){return t+"[]"},comma:"comma",indices:function(t,e){return t+"["+e+"]"},repeat:function(t){return t}},c=Array.isArray,s=Array.prototype.push,u=function(t,e){s.apply(t,c(e)?e:[e])},l=Date.prototype.toISOString,f=i["default"],d={addQueryPrefix:!1,allowDots:!1,charset:"utf-8",charsetSentinel:!1,delimiter:"&",encode:!0,encoder:r.encode,encodeValuesOnly:!1,format:f,formatter:i.formatters[f],indices:!1,serializeDate:function(t){return l.call(t)},skipNulls:!1,strictNullHandling:!1},p=function(t){return"string"===typeof t||"number"===typeof t||"boolean"===typeof t||"symbol"===typeof t||"bigint"===typeof t},h=function t(e,n,i,o,a,s,l,f,h,m,y,g,b){var v=e;if("function"===typeof l?v=l(n,v):v instanceof Date?v=m(v):"comma"===i&&c(v)&&(v=v.join(",")),null===v){if(o)return s&&!g?s(n,d.encoder,b,"key"):n;v=""}if(p(v)||r.isBuffer(v)){if(s){var O=g?n:s(n,d.encoder,b,"key");return[y(O)+"="+y(s(v,d.encoder,b,"value"))]}return[y(n)+"="+y(String(v))]}var j,k=[];if("undefined"===typeof v)return k;if(c(l))j=l;else{var w=Object.keys(v);j=f?w.sort(f):w}for(var x=0;x<j.length;++x){var C=j[x];a&&null===v[C]||(c(v)?u(k,t(v[C],"function"===typeof i?i(n,C):n,i,o,a,s,l,f,h,m,y,g,b)):u(k,t(v[C],n+(h?"."+C:"["+C+"]"),i,o,a,s,l,f,h,m,y,g,b)))}return k},m=function(t){if(!t)return d;if(null!==t.encoder&&void 0!==t.encoder&&"function"!==typeof t.encoder)throw new TypeError("Encoder has to be a function.");var e=t.charset||d.charset;if("undefined"!==typeof t.charset&&"utf-8"!==t.charset&&"iso-8859-1"!==t.charset)throw new TypeError("The charset option must be either utf-8, iso-8859-1, or undefined");var n=i["default"];if("undefined"!==typeof t.format){if(!o.call(i.formatters,t.format))throw new TypeError("Unknown format option provided.");n=t.format}var r=i.formatters[n],a=d.filter;return("function"===typeof t.filter||c(t.filter))&&(a=t.filter),{addQueryPrefix:"boolean"===typeof t.addQueryPrefix?t.addQueryPrefix:d.addQueryPrefix,allowDots:"undefined"===typeof t.allowDots?d.allowDots:!!t.allowDots,charset:e,charsetSentinel:"boolean"===typeof t.charsetSentinel?t.charsetSentinel:d.charsetSentinel,delimiter:"undefined"===typeof t.delimiter?d.delimiter:t.delimiter,encode:"boolean"===typeof t.encode?t.encode:d.encode,encoder:"function"===typeof t.encoder?t.encoder:d.encoder,encodeValuesOnly:"boolean"===typeof t.encodeValuesOnly?t.encodeValuesOnly:d.encodeValuesOnly,filter:a,formatter:r,serializeDate:"function"===typeof t.serializeDate?t.serializeDate:d.serializeDate,skipNulls:"boolean"===typeof t.skipNulls?t.skipNulls:d.skipNulls,sort:"function"===typeof t.sort?t.sort:null,strictNullHandling:"boolean"===typeof t.strictNullHandling?t.strictNullHandling:d.strictNullHandling}};t.exports=function(t,e){var n,r,i=t,o=m(e);"function"===typeof o.filter?(r=o.filter,i=r("",i)):c(o.filter)&&(r=o.filter,n=r);var s,l=[];if("object"!==typeof i||null===i)return"";s=e&&e.arrayFormat in a?e.arrayFormat:e&&"indices"in e?e.indices?"indices":"repeat":"indices";var f=a[s];n||(n=Object.keys(i)),o.sort&&n.sort(o.sort);for(var d=0;d<n.length;++d){var p=n[d];o.skipNulls&&null===i[p]||u(l,h(i[p],p,f,o.strictNullHandling,o.skipNulls,o.encode?o.encoder:null,o.filter,o.sort,o.allowDots,o.serializeDate,o.formatter,o.encodeValuesOnly,o.charset))}var y=l.join(o.delimiter),g=!0===o.addQueryPrefix?"?":"";return o.charsetSentinel&&("iso-8859-1"===o.charset?g+="utf8=%26%2310003%3B&":g+="utf8=%E2%9C%93&"),y.length>0?g+y:""}},4328:function(t,e,n){"use strict";var r=n("4127"),i=n("9e6a"),o=n("b313");t.exports={formats:o,parse:i,stringify:r}},5402:function(t,e,n){},"5af0":function(t,e,n){},"5ec8":function(t,e,n){},"5f87":function(t,e,n){"use strict";n.d(e,"a",(function(){return a}));var r=n("a78e"),i=n.n(r),o="admin-token";function a(){return i.a.get(o)}},6112:function(t,e,n){"use strict";var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("div",{staticClass:"i-layout-page-header"},[n("PageHeader",{staticClass:"product_tabs",attrs:{"hidden-breadcrumb":""}},[n("div",{attrs:{slot:"title"},slot:"title"},[n("router-link",{attrs:{to:{path:"/admin/setting/pages/devise"}}},[n("Button",{staticClass:"mr20",attrs:{icon:"ios-arrow-back",size:"small"}},[t._v("返回")])],1),n("span",{staticClass:"mr20",domProps:{textContent:t._s("页面设计")}})],1)])],1),n("Card",{staticClass:"ivu-mt",attrs:{bordered:!1,"dis-hover":""}},[n("div",{staticClass:"flex-wrapper"},[n("iframe",{ref:"iframe",staticClass:"iframe-box",attrs:{src:t.iframeUrl,frameborder:"0"}}),n("div",[n("div",{staticClass:"content"},[n("rightConfig",{attrs:{name:t.configName,pageId:t.pageId}})],1)]),n("links")],1)])],1)},i=[],o=n("f478"),a=(n("2f62"),function(){var t=this,e=t.$createElement,n=t._self._c||e;return t.rCom.length?n("div",{staticClass:"right-box"},[n("div",{staticClass:"title-bar"},[t._v("模块配置")]),t.rCom.length?n("div",{staticClass:"mobile-config"},[t._l(t.rCom,(function(e,r){return n("div",{key:r},[n(e.components.name,{tag:"component",attrs:{name:e.configNme,configData:t.configData}})],1)})),t.rCom.length?n("div",{staticStyle:{"text-align":"center"}},[n("Button",{staticStyle:{width:"100%",margin:"0 auto",height:"40px"},attrs:{type:"primary"},on:{click:t.saveConfig}},[t._v("保存")])],1):t._e()],2):t._e()]):t._e()}),c=[],s=n("2542");function u(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,r)}return n}function l(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?u(n,!0).forEach((function(e){f(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):u(n).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function f(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var d={name:"rightConfig",components:l({},s["a"]),props:{name:{type:null,default:""},pageId:{type:Number,default:0}},computed:{defultArr:function(){this.$store.dispatch("admin/user/getPageName");var t=this.$store.state.admin.user.pageName;return this.$store.state.admin[t].component}},watch:{name:{handler:function(t,e){this.rCom=[];var n=this.$store.state.admin.user.pageName;if(this.configData=this.$store.state.admin[n].defaultConfig[t],this.rCom=this.$store.state.admin[n].component[t].list,this.configData.selectConfig){var r=this.configData.selectConfig.type?this.configData.selectConfig.type:"";this.configData.selectConfig.list=this.categoryList,r?this.getByCategory():this.getCategory()}},deep:!0},defultArr:{handler:function(t,e){this.rCom=[];this.objToArray(t);this.rCom=t[this.name].list},deep:!0}},data:function(){return{rCom:[],configData:{},isShow:!0,categoryList:[],status:0}},mounted:function(){this.storeStatus()},methods:{storeStatus:function(){var t=this;Object(o["f"])().then((function(e){t.status=parseInt(e.data.store_status)}))},getCategory:function(){var t=this;Object(o["d"])().then((function(e){var n=[];e.data.map((function(t){n.push({title:t.title,pid:t.pid,activeValue:t.id.toString()})})),t.categoryList=n,t.bus.$emit("upData",n)}))},getByCategory:function(){var t=this;Object(o["c"])().then((function(e){var n=[];e.data.map((function(t){n.push({title:t.cate_name,pid:t.pid,activeValue:t.id.toString()})})),t.categoryList=n,t.bus.$emit("upData",n)}))},saveConfig:function(){var t=this,e=this.$store.state.admin.user.pageName,n=this.$store.state.admin[e].defaultConfig,r=this.$store.state.admin[e].periphery;if("tabBar"==this.name){if(!r||!this.status)for(var i=n.tabBar.tabBarList.list,a=0;a<i.length;a++)if("/pages/storeList/index"==i[a].link||"pages/storeList/index"==i[a].link)return this.$Message.error("请先开启您的周边功能(/pages/storeList/index)");if(n.tabBar.tabBarList.list.length<2)return this.$Message.error("您最少应添加2个导航")}Object(o["b"])(this.pageId,{value:n}).then((function(e){t.$Message.success("保存成功")}))},objToArray:function(t){var e=[];for(var n in t)e.push(t[n]);return e}}},p=d,h=(n("85c4"),n("2877")),m=Object(h["a"])(p,a,c,!1,null,"c51a2b40",null),y=m.exports,g=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"right-box"},t._l(t.list,(function(e,r){return n("div",{key:r,staticClass:"link-item"},[n("div",{staticClass:"title"},[t._v(t._s(e.name))]),n("div",{staticClass:"txt"},[n("span",[t._v("地址：")]),t._v(t._s(e.url)+"\n        ")]),e.parameter?n("div",{staticClass:"txt"},[t._m(0,!0),t._l(e.parameter,(function(e,r,i){return n("span",[t._v(t._s(r+"="+e)),n("i",{staticStyle:{"font-style":"normal"}},[t._v("&")])])}))],2):t._e(),n("div",{staticClass:"tips"},[t._v("例如："+t._s(e.example)+"\n            "),n("span",{staticClass:"copy copy-data",attrs:{"data-clipboard-text":e.example}},[t._v("复制")])])])})),0)},b=[function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("p",[n("span",[t._v("参数：")])])}],v=n("b311"),O=n.n(v),j={name:"links",data:function(){return{list:[{name:"商城首页",url:"/pages/users/order_list/index",parameter:[{}],example:"/pages/activity/bargain/index"},{name:"商城首页",url:"/pages/users/order_list/index",parameter:[{}],example:"/pages/activity/bargain/index"}]}},created:function(){var t=this;Object(o["e"])().then((function(e){t.list=e.data.url}))},mounted:function(){this.$nextTick((function(){var t=this,e=new O.a(".copy-data");e.on("success",(function(){t.$Message.success("复制成功")}))}))},methods:{}},k=j,w=(n("713c"),Object(h["a"])(k,g,b,!1,null,"acf035fe",null)),x=w.exports,C={name:"index",components:{rightConfig:y,links:x},data:function(){return{configName:"",iframeUrl:"",setConfig:"",updataConfig:"",pageId:0}},created:function(){var t=this,e=this.$route.query.id,n=this.$route.query.name,r=this.$store.state[n].defaultConfig;this.setConfig="admin/"+n+"/setConfig",this.updataConfig="admin/"+n+"/updataConfig",this.pageId=parseInt(e),this.iframeUrl="".concat(location.origin,"?type=iframeMakkMinkkJuan"),Object(o["a"])(parseInt(e)).then((function(n){var i=n.data.info.value;i?t.upData(i):Object(o["b"])(parseInt(e),{value:r}).then((function(t){}))}))},mounted:function(){window.addEventListener("message",this.handleMessage,!1)},methods:{handleMessage:function(t){t.data.name&&(this.configName=t.data.name,this.add(t.data.name))},add:function(t){this.$store.commit(this.setConfig,t)},upData:function(t){this.$store.commit(this.updataConfig,t)}}},E=C,S=(n("950b"),Object(h["a"])(E,r,i,!1,null,"15dae600",null));e["a"]=S.exports},"713c":function(t,e,n){"use strict";var r=n("5af0"),i=n.n(r);i.a},"85c4":function(t,e,n){"use strict";var r=n("5402"),i=n.n(r);i.a},"950b":function(t,e,n){"use strict";var r=n("5ec8"),i=n.n(r);i.a},"9e6a":function(t,e,n){"use strict";var r=n("d233"),i=Object.prototype.hasOwnProperty,o={allowDots:!1,allowPrototypes:!1,arrayLimit:20,charset:"utf-8",charsetSentinel:!1,comma:!1,decoder:r.decode,delimiter:"&",depth:5,ignoreQueryPrefix:!1,interpretNumericEntities:!1,parameterLimit:1e3,parseArrays:!0,plainObjects:!1,strictNullHandling:!1},a=function(t){return t.replace(/&#(\d+);/g,(function(t,e){return String.fromCharCode(parseInt(e,10))}))},c="utf8=%26%2310003%3B",s="utf8=%E2%9C%93",u=function(t,e){var n,u={},l=e.ignoreQueryPrefix?t.replace(/^\?/,""):t,f=e.parameterLimit===1/0?void 0:e.parameterLimit,d=l.split(e.delimiter,f),p=-1,h=e.charset;if(e.charsetSentinel)for(n=0;n<d.length;++n)0===d[n].indexOf("utf8=")&&(d[n]===s?h="utf-8":d[n]===c&&(h="iso-8859-1"),p=n,n=d.length);for(n=0;n<d.length;++n)if(n!==p){var m,y,g=d[n],b=g.indexOf("]="),v=-1===b?g.indexOf("="):b+1;-1===v?(m=e.decoder(g,o.decoder,h,"key"),y=e.strictNullHandling?null:""):(m=e.decoder(g.slice(0,v),o.decoder,h,"key"),y=e.decoder(g.slice(v+1),o.decoder,h,"value")),y&&e.interpretNumericEntities&&"iso-8859-1"===h&&(y=a(y)),y&&e.comma&&y.indexOf(",")>-1&&(y=y.split(",")),i.call(u,m)?u[m]=r.combine(u[m],y):u[m]=y}return u},l=function(t,e,n){for(var r=e,i=t.length-1;i>=0;--i){var o,a=t[i];if("[]"===a&&n.parseArrays)o=[].concat(r);else{o=n.plainObjects?Object.create(null):{};var c="["===a.charAt(0)&&"]"===a.charAt(a.length-1)?a.slice(1,-1):a,s=parseInt(c,10);n.parseArrays||""!==c?!isNaN(s)&&a!==c&&String(s)===c&&s>=0&&n.parseArrays&&s<=n.arrayLimit?(o=[],o[s]=r):o[c]=r:o={0:r}}r=o}return r},f=function(t,e,n){if(t){var r=n.allowDots?t.replace(/\.([^.[]+)/g,"[$1]"):t,o=/(\[[^[\]]*])/,a=/(\[[^[\]]*])/g,c=n.depth>0&&o.exec(r),s=c?r.slice(0,c.index):r,u=[];if(s){if(!n.plainObjects&&i.call(Object.prototype,s)&&!n.allowPrototypes)return;u.push(s)}var f=0;while(n.depth>0&&null!==(c=a.exec(r))&&f<n.depth){if(f+=1,!n.plainObjects&&i.call(Object.prototype,c[1].slice(1,-1))&&!n.allowPrototypes)return;u.push(c[1])}return c&&u.push("["+r.slice(c.index)+"]"),l(u,e,n)}},d=function(t){if(!t)return o;if(null!==t.decoder&&void 0!==t.decoder&&"function"!==typeof t.decoder)throw new TypeError("Decoder has to be a function.");if("undefined"!==typeof t.charset&&"utf-8"!==t.charset&&"iso-8859-1"!==t.charset)throw new Error("The charset option must be either utf-8, iso-8859-1, or undefined");var e="undefined"===typeof t.charset?o.charset:t.charset;return{allowDots:"undefined"===typeof t.allowDots?o.allowDots:!!t.allowDots,allowPrototypes:"boolean"===typeof t.allowPrototypes?t.allowPrototypes:o.allowPrototypes,arrayLimit:"number"===typeof t.arrayLimit?t.arrayLimit:o.arrayLimit,charset:e,charsetSentinel:"boolean"===typeof t.charsetSentinel?t.charsetSentinel:o.charsetSentinel,comma:"boolean"===typeof t.comma?t.comma:o.comma,decoder:"function"===typeof t.decoder?t.decoder:o.decoder,delimiter:"string"===typeof t.delimiter||r.isRegExp(t.delimiter)?t.delimiter:o.delimiter,depth:"number"===typeof t.depth||!1===t.depth?+t.depth:o.depth,ignoreQueryPrefix:!0===t.ignoreQueryPrefix,interpretNumericEntities:"boolean"===typeof t.interpretNumericEntities?t.interpretNumericEntities:o.interpretNumericEntities,parameterLimit:"number"===typeof t.parameterLimit?t.parameterLimit:o.parameterLimit,parseArrays:!1!==t.parseArrays,plainObjects:"boolean"===typeof t.plainObjects?t.plainObjects:o.plainObjects,strictNullHandling:"boolean"===typeof t.strictNullHandling?t.strictNullHandling:o.strictNullHandling}};t.exports=function(t,e){var n=d(e);if(""===t||null===t||"undefined"===typeof t)return n.plainObjects?Object.create(null):{};for(var i="string"===typeof t?u(t,n):t,o=n.plainObjects?Object.create(null):{},a=Object.keys(i),c=0;c<a.length;++c){var s=a[c],l=f(s,i[s],n);o=r.merge(o,l,n)}return r.compact(o)}},b311:function(t,e,n){
/*!
 * clipboard.js v2.0.6
 * https://clipboardjs.com/
 * 
 * Licensed MIT © Zeno Rocha
 */
(function(e,n){t.exports=n()})(0,(function(){return function(t){var e={};function n(r){if(e[r])return e[r].exports;var i=e[r]={i:r,l:!1,exports:{}};return t[r].call(i.exports,i,i.exports,n),i.l=!0,i.exports}return n.m=t,n.c=e,n.d=function(t,e,r){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},n.r=function(t){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"===typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var i in t)n.d(r,i,function(e){return t[e]}.bind(null,i));return r},n.n=function(t){var e=t&&t.__esModule?function(){return t["default"]}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=6)}([function(t,e){function n(t){var e;if("SELECT"===t.nodeName)t.focus(),e=t.value;else if("INPUT"===t.nodeName||"TEXTAREA"===t.nodeName){var n=t.hasAttribute("readonly");n||t.setAttribute("readonly",""),t.select(),t.setSelectionRange(0,t.value.length),n||t.removeAttribute("readonly"),e=t.value}else{t.hasAttribute("contenteditable")&&t.focus();var r=window.getSelection(),i=document.createRange();i.selectNodeContents(t),r.removeAllRanges(),r.addRange(i),e=r.toString()}return e}t.exports=n},function(t,e){function n(){}n.prototype={on:function(t,e,n){var r=this.e||(this.e={});return(r[t]||(r[t]=[])).push({fn:e,ctx:n}),this},once:function(t,e,n){var r=this;function i(){r.off(t,i),e.apply(n,arguments)}return i._=e,this.on(t,i,n)},emit:function(t){var e=[].slice.call(arguments,1),n=((this.e||(this.e={}))[t]||[]).slice(),r=0,i=n.length;for(r;r<i;r++)n[r].fn.apply(n[r].ctx,e);return this},off:function(t,e){var n=this.e||(this.e={}),r=n[t],i=[];if(r&&e)for(var o=0,a=r.length;o<a;o++)r[o].fn!==e&&r[o].fn._!==e&&i.push(r[o]);return i.length?n[t]=i:delete n[t],this}},t.exports=n,t.exports.TinyEmitter=n},function(t,e,n){var r=n(3),i=n(4);function o(t,e,n){if(!t&&!e&&!n)throw new Error("Missing required arguments");if(!r.string(e))throw new TypeError("Second argument must be a String");if(!r.fn(n))throw new TypeError("Third argument must be a Function");if(r.node(t))return a(t,e,n);if(r.nodeList(t))return c(t,e,n);if(r.string(t))return s(t,e,n);throw new TypeError("First argument must be a String, HTMLElement, HTMLCollection, or NodeList")}function a(t,e,n){return t.addEventListener(e,n),{destroy:function(){t.removeEventListener(e,n)}}}function c(t,e,n){return Array.prototype.forEach.call(t,(function(t){t.addEventListener(e,n)})),{destroy:function(){Array.prototype.forEach.call(t,(function(t){t.removeEventListener(e,n)}))}}}function s(t,e,n){return i(document.body,t,e,n)}t.exports=o},function(t,e){e.node=function(t){return void 0!==t&&t instanceof HTMLElement&&1===t.nodeType},e.nodeList=function(t){var n=Object.prototype.toString.call(t);return void 0!==t&&("[object NodeList]"===n||"[object HTMLCollection]"===n)&&"length"in t&&(0===t.length||e.node(t[0]))},e.string=function(t){return"string"===typeof t||t instanceof String},e.fn=function(t){var e=Object.prototype.toString.call(t);return"[object Function]"===e}},function(t,e,n){var r=n(5);function i(t,e,n,r,i){var o=a.apply(this,arguments);return t.addEventListener(n,o,i),{destroy:function(){t.removeEventListener(n,o,i)}}}function o(t,e,n,r,o){return"function"===typeof t.addEventListener?i.apply(null,arguments):"function"===typeof n?i.bind(null,document).apply(null,arguments):("string"===typeof t&&(t=document.querySelectorAll(t)),Array.prototype.map.call(t,(function(t){return i(t,e,n,r,o)})))}function a(t,e,n,i){return function(n){n.delegateTarget=r(n.target,e),n.delegateTarget&&i.call(t,n)}}t.exports=o},function(t,e){var n=9;if("undefined"!==typeof Element&&!Element.prototype.matches){var r=Element.prototype;r.matches=r.matchesSelector||r.mozMatchesSelector||r.msMatchesSelector||r.oMatchesSelector||r.webkitMatchesSelector}function i(t,e){while(t&&t.nodeType!==n){if("function"===typeof t.matches&&t.matches(e))return t;t=t.parentNode}}t.exports=i},function(t,e,n){"use strict";n.r(e);var r=n(0),i=n.n(r),o="function"===typeof Symbol&&"symbol"===typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"===typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},a=function(){function t(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}return function(e,n,r){return n&&t(e.prototype,n),r&&t(e,r),e}}();function c(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}var s=function(){function t(e){c(this,t),this.resolveOptions(e),this.initSelection()}return a(t,[{key:"resolveOptions",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};this.action=t.action,this.container=t.container,this.emitter=t.emitter,this.target=t.target,this.text=t.text,this.trigger=t.trigger,this.selectedText=""}},{key:"initSelection",value:function(){this.text?this.selectFake():this.target&&this.selectTarget()}},{key:"selectFake",value:function(){var t=this,e="rtl"==document.documentElement.getAttribute("dir");this.removeFake(),this.fakeHandlerCallback=function(){return t.removeFake()},this.fakeHandler=this.container.addEventListener("click",this.fakeHandlerCallback)||!0,this.fakeElem=document.createElement("textarea"),this.fakeElem.style.fontSize="12pt",this.fakeElem.style.border="0",this.fakeElem.style.padding="0",this.fakeElem.style.margin="0",this.fakeElem.style.position="absolute",this.fakeElem.style[e?"right":"left"]="-9999px";var n=window.pageYOffset||document.documentElement.scrollTop;this.fakeElem.style.top=n+"px",this.fakeElem.setAttribute("readonly",""),this.fakeElem.value=this.text,this.container.appendChild(this.fakeElem),this.selectedText=i()(this.fakeElem),this.copyText()}},{key:"removeFake",value:function(){this.fakeHandler&&(this.container.removeEventListener("click",this.fakeHandlerCallback),this.fakeHandler=null,this.fakeHandlerCallback=null),this.fakeElem&&(this.container.removeChild(this.fakeElem),this.fakeElem=null)}},{key:"selectTarget",value:function(){this.selectedText=i()(this.target),this.copyText()}},{key:"copyText",value:function(){var t=void 0;try{t=document.execCommand(this.action)}catch(e){t=!1}this.handleResult(t)}},{key:"handleResult",value:function(t){this.emitter.emit(t?"success":"error",{action:this.action,text:this.selectedText,trigger:this.trigger,clearSelection:this.clearSelection.bind(this)})}},{key:"clearSelection",value:function(){this.trigger&&this.trigger.focus(),document.activeElement.blur(),window.getSelection().removeAllRanges()}},{key:"destroy",value:function(){this.removeFake()}},{key:"action",set:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"copy";if(this._action=t,"copy"!==this._action&&"cut"!==this._action)throw new Error('Invalid "action" value, use either "copy" or "cut"')},get:function(){return this._action}},{key:"target",set:function(t){if(void 0!==t){if(!t||"object"!==("undefined"===typeof t?"undefined":o(t))||1!==t.nodeType)throw new Error('Invalid "target" value, use a valid Element');if("copy"===this.action&&t.hasAttribute("disabled"))throw new Error('Invalid "target" attribute. Please use "readonly" instead of "disabled" attribute');if("cut"===this.action&&(t.hasAttribute("readonly")||t.hasAttribute("disabled")))throw new Error('Invalid "target" attribute. You can\'t cut text from elements with "readonly" or "disabled" attributes');this._target=t}},get:function(){return this._target}}]),t}(),u=s,l=n(1),f=n.n(l),d=n(2),p=n.n(d),h="function"===typeof Symbol&&"symbol"===typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"===typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},m=function(){function t(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}return function(e,n,r){return n&&t(e.prototype,n),r&&t(e,r),e}}();function y(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function g(t,e){if(!t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!e||"object"!==typeof e&&"function"!==typeof e?t:e}function b(t,e){if("function"!==typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function, not "+typeof e);t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,enumerable:!1,writable:!0,configurable:!0}}),e&&(Object.setPrototypeOf?Object.setPrototypeOf(t,e):t.__proto__=e)}var v=function(t){function e(t,n){y(this,e);var r=g(this,(e.__proto__||Object.getPrototypeOf(e)).call(this));return r.resolveOptions(n),r.listenClick(t),r}return b(e,t),m(e,[{key:"resolveOptions",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};this.action="function"===typeof t.action?t.action:this.defaultAction,this.target="function"===typeof t.target?t.target:this.defaultTarget,this.text="function"===typeof t.text?t.text:this.defaultText,this.container="object"===h(t.container)?t.container:document.body}},{key:"listenClick",value:function(t){var e=this;this.listener=p()(t,"click",(function(t){return e.onClick(t)}))}},{key:"onClick",value:function(t){var e=t.delegateTarget||t.currentTarget;this.clipboardAction&&(this.clipboardAction=null),this.clipboardAction=new u({action:this.action(e),target:this.target(e),text:this.text(e),container:this.container,trigger:e,emitter:this})}},{key:"defaultAction",value:function(t){return O("action",t)}},{key:"defaultTarget",value:function(t){var e=O("target",t);if(e)return document.querySelector(e)}},{key:"defaultText",value:function(t){return O("text",t)}},{key:"destroy",value:function(){this.listener.destroy(),this.clipboardAction&&(this.clipboardAction.destroy(),this.clipboardAction=null)}}],[{key:"isSupported",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:["copy","cut"],e="string"===typeof t?[t]:t,n=!!document.queryCommandSupported;return e.forEach((function(t){n=n&&!!document.queryCommandSupported(t)})),n}}]),e}(f.a);function O(t,e){var n="data-clipboard-"+t;if(e.hasAttribute(n))return e.getAttribute(n)}e["default"]=v}])["default"]}))},b313:function(t,e,n){"use strict";var r=String.prototype.replace,i=/%20/g,o=n("d233"),a={RFC1738:"RFC1738",RFC3986:"RFC3986"};t.exports=o.assign({default:a.RFC3986,formatters:{RFC1738:function(t){return r.call(t,i,"+")},RFC3986:function(t){return String(t)}}},a)},d233:function(t,e,n){"use strict";var r=Object.prototype.hasOwnProperty,i=Array.isArray,o=function(){for(var t=[],e=0;e<256;++e)t.push("%"+((e<16?"0":"")+e.toString(16)).toUpperCase());return t}(),a=function(t){while(t.length>1){var e=t.pop(),n=e.obj[e.prop];if(i(n)){for(var r=[],o=0;o<n.length;++o)"undefined"!==typeof n[o]&&r.push(n[o]);e.obj[e.prop]=r}}},c=function(t,e){for(var n=e&&e.plainObjects?Object.create(null):{},r=0;r<t.length;++r)"undefined"!==typeof t[r]&&(n[r]=t[r]);return n},s=function t(e,n,o){if(!n)return e;if("object"!==typeof n){if(i(e))e.push(n);else{if(!e||"object"!==typeof e)return[e,n];(o&&(o.plainObjects||o.allowPrototypes)||!r.call(Object.prototype,n))&&(e[n]=!0)}return e}if(!e||"object"!==typeof e)return[e].concat(n);var a=e;return i(e)&&!i(n)&&(a=c(e,o)),i(e)&&i(n)?(n.forEach((function(n,i){if(r.call(e,i)){var a=e[i];a&&"object"===typeof a&&n&&"object"===typeof n?e[i]=t(a,n,o):e.push(n)}else e[i]=n})),e):Object.keys(n).reduce((function(e,i){var a=n[i];return r.call(e,i)?e[i]=t(e[i],a,o):e[i]=a,e}),a)},u=function(t,e){return Object.keys(e).reduce((function(t,n){return t[n]=e[n],t}),t)},l=function(t,e,n){var r=t.replace(/\+/g," ");if("iso-8859-1"===n)return r.replace(/%[0-9a-f]{2}/gi,unescape);try{return decodeURIComponent(r)}catch(i){return r}},f=function(t,e,n){if(0===t.length)return t;var r=t;if("symbol"===typeof t?r=Symbol.prototype.toString.call(t):"string"!==typeof t&&(r=String(t)),"iso-8859-1"===n)return escape(r).replace(/%u[0-9a-f]{4}/gi,(function(t){return"%26%23"+parseInt(t.slice(2),16)+"%3B"}));for(var i="",a=0;a<r.length;++a){var c=r.charCodeAt(a);45===c||46===c||95===c||126===c||c>=48&&c<=57||c>=65&&c<=90||c>=97&&c<=122?i+=r.charAt(a):c<128?i+=o[c]:c<2048?i+=o[192|c>>6]+o[128|63&c]:c<55296||c>=57344?i+=o[224|c>>12]+o[128|c>>6&63]+o[128|63&c]:(a+=1,c=65536+((1023&c)<<10|1023&r.charCodeAt(a)),i+=o[240|c>>18]+o[128|c>>12&63]+o[128|c>>6&63]+o[128|63&c])}return i},d=function(t){for(var e=[{obj:{o:t},prop:"o"}],n=[],r=0;r<e.length;++r)for(var i=e[r],o=i.obj[i.prop],c=Object.keys(o),s=0;s<c.length;++s){var u=c[s],l=o[u];"object"===typeof l&&null!==l&&-1===n.indexOf(l)&&(e.push({obj:o,prop:u}),n.push(l))}return a(e),t},p=function(t){return"[object RegExp]"===Object.prototype.toString.call(t)},h=function(t){return!(!t||"object"!==typeof t)&&!!(t.constructor&&t.constructor.isBuffer&&t.constructor.isBuffer(t))},m=function(t,e){return[].concat(t,e)};t.exports={arrayToObject:c,assign:u,combine:m,compact:d,decode:l,encode:f,isBuffer:h,isRegExp:p,merge:s}},fcac:function(t,e,n){"use strict";n.d(e,"c",(function(){return i})),n.d(e,"d",(function(){return o})),n.d(e,"l",(function(){return a})),n.d(e,"s",(function(){return c})),n.d(e,"f",(function(){return s})),n.d(e,"g",(function(){return u})),n.d(e,"r",(function(){return l})),n.d(e,"b",(function(){return f})),n.d(e,"e",(function(){return d})),n.d(e,"j",(function(){return p})),n.d(e,"h",(function(){return h})),n.d(e,"m",(function(){return m})),n.d(e,"k",(function(){return y})),n.d(e,"i",(function(){return g})),n.d(e,"q",(function(){return b})),n.d(e,"a",(function(){return v})),n.d(e,"n",(function(){return O})),n.d(e,"p",(function(){return j})),n.d(e,"o",(function(){return k}));var r=n("b6bd");function i(t){return Object(r["a"])({url:"company/index",method:"get",params:t})}function o(t){return Object(r["a"])({url:"company/staff",method:"get",params:t})}function a(t){return Object(r["a"])({url:"staff/list",method:"get",params:t})}function c(t){return Object(r["a"])({url:"company/unqualified",method:"get",params:t})}function s(t){return Object(r["a"])({url:"staff/check_frequency",method:"post",data:t})}function u(t){return Object(r["a"])({url:"company/batchUpdateCompanyStaffClassify",method:"post",data:t})}function l(t){return Object(r["a"])({url:"company/unqualified/sms",method:"get",params:t})}function f(t){return Object(r["a"])({url:"company/classify",method:"get",params:t})}function d(t){return Object(r["a"])({url:"company/batchUpdateCompanyClassify",method:"post",data:t})}function p(t,e){return Object(r["a"])({url:"company/classify/".concat(t),method:"put",data:e})}function h(t,e){return Object(r["a"])({url:"company/classify",method:"post",data:e})}function m(t){return Object(r["a"])({url:"company/staff/classify",method:"get",params:t})}function y(t,e){return Object(r["a"])({url:"company/staff/classify/".concat(t),method:"put",data:e})}function g(t,e){return Object(r["a"])({url:"company/staff/classify",method:"post",data:e})}function b(t){return Object(r["a"])({url:"/company/transfer_link",method:"post",data:t})}function v(t){return Object(r["a"])({url:"/community",method:"get",params:t})}function O(t){return Object(r["a"])({url:"/admincompany/follow",method:"POST",data:t})}function j(t){return Object(r["a"])({url:"/admincompany/unfollow",method:"POST",data:t})}function k(t){return Object(r["a"])({url:"/admincompany/followed",method:"GET",params:t})}}}]);
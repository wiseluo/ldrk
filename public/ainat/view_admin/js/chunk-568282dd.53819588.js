(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-568282dd"],{"0b5c":function(e,t,n){},"318e":function(e,t,n){"use strict";n.r(t);var o=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"page-account"},[n("div",{staticClass:"instructions"}),n("div",{ref:"loginbox",staticClass:"container",class:[e.fullWidth>768?"containerSamll":"containerBig"]},[n("div",{staticClass:"index_from page-account-container"},[e._m(0),n("Form",{ref:"formInline",attrs:{model:e.formInline,rules:e.ruleInline},on:{keyup:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"enter",13,t.key,"Enter")?null:e.handleSubmit("formInline")}}},[n("Row",[n("Col",{attrs:{span:"6"}},[n("h3",{staticStyle:{"padding-top":"11%"}},[e._v("手机号：")])]),n("Col",{attrs:{span:"18"}},[n("FormItem",{attrs:{prop:"username"}},[n("Input",{attrs:{type:"text",prefix:"ios-contact-outline",placeholder:"请输入手机号",size:"large"},model:{value:e.formInline.phone,callback:function(t){e.$set(e.formInline,"phone",t)},expression:"formInline.phone"}})],1)],1)],1),n("Row",[n("Col",{attrs:{span:"6"}},[n("h3",{staticStyle:{"padding-top":"11%"}},[e._v("验证码：")])]),n("Col",{attrs:{span:"18"}},[n("FormItem",[n("Input",{attrs:{type:"text",placeholder:"请输入短信验证码",size:"large"},model:{value:e.formInline.smscode,callback:function(t){e.$set(e.formInline,"smscode",t)},expression:"formInline.smscode"}},[n("Icon",{attrs:{slot:"prefix",type:"ios-contact"},slot:"prefix"}),n("Button",{directives:[{name:"show",rawName:"v-show",value:e.show,expression:"show"}],attrs:{slot:"append",icon:"ios-search"},on:{click:e.getcode1},slot:"append"},[e._v(e._s(e.imgcode111))]),n("Button",{directives:[{name:"show",rawName:"v-show",value:!e.show,expression:"!show"}],attrs:{slot:"append"},slot:"append"},[e._v(e._s(e.count)+" s")])],1)],1)],1)],1),n("FormItem",[n("Button",{staticClass:"btn",attrs:{type:"primary",long:"",size:"large"},on:{click:function(t){return e.handleSubmit("formInline")}}},[e._v(e._s(e.$t("page.login.submit"))+"\n          ")])],1)],1)],1)]),e.iserrormsg?n("div",{ref:"loginbox",staticStyle:{height:"150px"}},[n("h1",{staticStyle:{padding:"15px"}},[e._v("检测失败,请在政务网打开!")]),e._m(1),n("span")]):e._e(),n("Modal",{attrs:{scrollable:"","footer-hide":"",closable:"",title:"请完成安全校验","mask-closable":!1,"z-index":2,width:"342"},model:{value:e.modals,callback:function(t){e.modals=t},expression:"modals"}},[n("div",{staticClass:"captchaBox"},[n("div",{ref:"captcha",staticStyle:{position:"relative"},attrs:{id:"captcha"}}),n("div",{attrs:{id:"msg"}})])])],1)},i=[function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"page-account-top"},[n("div",{staticClass:"page-account-top-logo"},[n("h1",[e._v("AI核酸比对系统")])])])},function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("span",[n("a",{attrs:{href:"https://cjzyw.scjgj.yw.gov.cn/admin/login"}},[e._v("\n        首次登陆可尝试该链接\n      ")])])}],r=n("a34a"),s=n.n(r),a=n("5723"),c=(n("8237"),n("3dda")),u=n.n(c),l=(n("d708"),n("c276")),d=(n("7daa"),n("2f62"));function h(e,t,n,o,i,r,s){try{var a=e[r](s),c=a.value}catch(u){return void n(u)}a.done?t(c):Promise.resolve(c).then(o,i)}function m(e){return function(){var t=this,n=arguments;return new Promise((function(o,i){var r=e.apply(t,n);function s(e){h(r,o,i,s,a,"next",e)}function a(e){h(r,o,i,s,a,"throw",e)}s(void 0)}))}}function f(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function p(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?f(n,!0).forEach((function(t){g(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):f(n).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function g(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}var w={mixins:[u.a],data:function(){return{auth_code:"",islogin:!1,isnotchecknet:!0,wurl:"",show:!0,smscode:"",imgcode111:"获取验证码",count:"",fullWidth:document.documentElement.clientWidth,swiperOption:{pagination:".swiper-pagination",autoplay:!0},modals:!1,autoLogin:!0,formInline:{smscode:"",phone:""},ruleInline:{},errorNum:0,iserrormsg:!1,jigsaw:null,login_logo:"",swiperList:[],defaultSwiperList:n("433f"),get_img_count:0,img_url:"https://img01.yzcdn.cn/vant/logo22.png",is_img_success:!0}},created:function(){var e=window.location.host;if("ldrk.jk-kj.com/"==e||"localhost:8080"==e||"localhost:8081"==e)this.isnotchecknet=!0,this.islogin=!0;else if(this.checkip(e))this.isnotchecknet=!0,this.islogin=!0;else{var t=navigator.userAgent.toLowerCase();-1!==t.indexOf("dingtalk")?this.isnotchecknet=!0:this.isnotchecknet=!1}if(!this.isnotchecknet){var n=this,o=this.$loading({lock:!0,text:"检测环境中",spinner:"el-icon-loading",background:"rgba(0, 0, 0, 0.7)"});setTimeout((function(){n.getcheckIpForNet(o)}),2e3)}var i=this.$route.query;i&&this.$router.replace({path:"/ainat/home"}),this.wurl=window.location.href;var r=this;top!=window&&(top.location.href=location.href),document.onkeydown=function(e){if("login"===r.$route.name){var t=window.event.keyCode;13===t&&r.handleSubmit("formInline")}},window.addEventListener("resize",this.handleResize)},watch:{fullWidth:function(e){if(!this.timer){this.screenWidth=e,this.timer=!0;var t=this;setTimeout((function(){t.timer=!1}),400)}},$route:function(e){this.captchas()}},mounted:function(){var e=this;this.$nextTick((function(){var t=navigator.userAgent.toLowerCase();-1!==t.indexOf("dingtalk")?(e.ddlogin(),e.islogin=!1):e.urllogin();e.screenWidth<768?document.getElementsByTagName("canvas")[0].removeAttribute("class",""):document.getElementsByTagName("canvas")[0].className=""})),this.captchas()},methods:p({getcheckIpForNet:function(e){var t=this;Object(a["g"])(window.datanumber+"&randomnumber="+window.randomnumber).then((function(n){200==n.data.code?(e.close(),t.islogin=!0):(e.close(),t.iserrormsg=!0)}))},connected:function(){console.log("connected")},tryAgain:function(){console.log("conntryAgainected")}},Object(d["b"])("admin/page",["closeAll"]),{checkip:function(e){var t=/^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/,n=t.test(e);return!!n},getCode:function(){var e=this,t=60;this.timer||(this.count=t,this.show=!1,this.timer=setInterval((function(){e.count>0&&e.count<=t?e.count--:(e.show=!0,clearInterval(e.timer),e.timer=null)}),1e3))},scanlogin:function(){location.origin;-1!=location.origin.indexOf("cjzyw.yw.gov.cn")?window.location.href="https://login-pro.ding.zj.gov.cn/oauth2/auth.htm?response_type=code&client_id=llcyry_dingoa&redirect_uri=https://cjzyw.yw.gov.cn/ainat/login&scope=get_user_info&authType=QRCODE":window.location.href="https://login.dg-work.cn/oauth2/auth.htm?response_type=code&client_id=llcyry_dingoa&redirect_uri=https://llwf.jk-kj.com/ainat/login&scope=get_user_info&authType=QRCODE"},getcode1:function(){var e=this;""!==this.formInline.phone?Object(a["l"])({phone:this.formInline.phone,prefix:"smslogin"}).then(function(){var t=m(s.a.mark((function t(n){var o;return s.a.wrap((function(t){while(1)switch(t.prev=t.next){case 0:o=n.data,200==o.code?(e.$Message.success(o.msg),e.getCode()):e.$Message.error(o.msg);case 2:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()):this.$Message.error("请输入手机号码")},urllogin:function(){var e=this;if(""!==this.$route.query.redirect&&this.$route.query.redirect&&-1!=this.$route.query.redirect.indexOf("auth_code")){var t=this.$route.query.redirect.split("="),n=t[1];Object(a["d"])({auth_code:n}).then(function(){var t=m(s.a.mark((function t(n){return s.a.wrap((function(t){while(1)switch(t.prev=t.next){case 0:e.successLoginDone(n);case 1:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()).catch((function(t){e.$Message.error(t.msg)}))}if(""!==this.$route.query.auth_code&&this.$route.query.auth_code){var o=this.$route.query.auth_code;Object(a["d"])({auth_code:o}).then(function(){var t=m(s.a.mark((function t(n){return s.a.wrap((function(t){while(1)switch(t.prev=t.next){case 0:e.successLoginDone(n);case 1:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()).catch((function(t){e.$Message.error(t.msg)}))}"undefined"!==typeof this.$route.query.code&&""!=this.$route.query.code&&Object(a["e"])({scan_code:this.$route.query.code}).then(function(){var t=m(s.a.mark((function t(n){return s.a.wrap((function(t){while(1)switch(t.prev=t.next){case 0:e.successLoginDone(n);case 1:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()).catch((function(t){e.$Message.error(t.msg)}))},ddlogin:function(){var e=this;dd.ready((function(){dd.getAuthCode({}).then((function(t){if(t){var n=t.auth_code;e.auth_code=n,Object(a["d"])({auth_code:n}).then(function(){var t=m(s.a.mark((function t(n){return s.a.wrap((function(t){while(1)switch(t.prev=t.next){case 0:e.successLoginDone(n);case 1:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}())}})).catch((function(e){alert(e)}))}))},swiperData:function(){var e=this;Object(a["i"])().then((function(t){var o=t.data||{};e.login_logo=o.login_logo?o.login_logo:n("9d64")})).catch((function(t){e.$Message.error(t.msg)}))},closeModel2:function(){var e=this;Object(a["f"])({phone:this.formInline.phone,smscode:this.formInline.smscode}).then(function(){var t=m(s.a.mark((function t(n){return s.a.wrap((function(t){while(1)switch(t.prev=t.next){case 0:e.successLoginDone(n.data);case 1:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()).catch((function(t){e.$Message.error(t.msg)}))},closeModel:function(){var e=this;Object(a["a"])({account:this.formInline.username,pwd:this.formInline.password,imgcode:this.formInline.code}).then(function(){var t=m(s.a.mark((function t(n){return s.a.wrap((function(t){while(1)switch(t.prev=t.next){case 0:e.successLoginDone(n.data);case 1:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()).catch((function(t){console.log(t,"ccc"),e.errorNum++,e.captchas(),e.$Message.error(t.msg)}))},successLoginDone:function(){var e=m(s.a.mark((function e(t){var n,o,i,r;return s.a.wrap((function(e){while(1)switch(e.prev=e.next){case 0:if(console.log("successLoginDone res",t),n={},n="undefined"!=typeof t.token?t:t.data,"undefined"===typeof t.status||200==t.status){e.next=6;break}return this.$Message.error(t.msg),e.abrupt("return");case 6:if("undefined"===typeof t.code||200==t.code){e.next=9;break}return this.$Message.error(t.msg),e.abrupt("return");case 9:return this.$store.dispatch("admin/account/setPageTitle"),o=this.$Message.loading({content:"登录中...",duration:0}),o(),i=n.expires_time,l["a"].cookies.set("uuid",n.user_info.id,{expires:i}),l["a"].cookies.set("token",n.token,{expires:i}),l["a"].cookies.set("expires_time",n.expires_time,{expires:i}),e.next=18,this.$store.dispatch("admin/db/database",{user:!0});case 18:r=e.sent,r.set("unique_auth",n.unique_auth).set("user_info",n.user_info).write(),this.$store.commit("admin/menus/getmenusNav",n.menus),this.$store.dispatch("admin/user/set",{name:n.user_info.account,role_code:n.user_info.role_code,avatar:n.user_info.head_pic,access:n.unique_auth,logo:n.logo,logoSmall:n.logo_square,version:n.version,newOrderAudioLink:n.newOrderAudioLink}),this.$router.replace({path:this.$route.query.redirect||"/ainat/home"}),console.log("closeAll"),this.closeAll();case 25:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}(),getExpiresTime:function(e){var t=Math.round(new Date/1e3),n=e-t;return parseFloat(parseFloat(parseFloat(n/60)/60)/24)},closefail:function(){this.$Message.error("校验错误")},handleResize:function(e){this.fullWidth=document.documentElement.clientWidth,this.fullWidth<768?document.getElementsByTagName("canvas")[0].removeAttribute("class",""):document.getElementsByTagName("canvas")[0].className=""},captchas:function(){var e=location.origin;-1!=location.origin.indexOf("localhost")&&(e="https://cjzyw.yw.gov.cn/"),this.imgcode=e+"/ainatapi/captcha?"+Date.parse(new Date)},handleSubmit:function(e){var t=this;this.$refs[e].validate((function(e){e&&(t.errorNum>=2?t.modals=!0:t.closeModel2())}))}}),beforeCreate:function(){this.fullWidth<768?document.getElementsByTagName("canvas")[0].removeAttribute("class",""):document.getElementsByTagName("canvas")[0].className=""},beforeDestroy:function(){window.removeEventListener("resize",this.handleResize),document.getElementsByTagName("canvas")[0].removeAttribute("class","")}},v=w,y=(n("9717"),n("8aae"),n("2877")),x=Object(y["a"])(v,o,i,!1,null,"c4b5a890",null);t["default"]=x.exports},"3dda":function(e,t){},"433f":function(e,t,n){e.exports=n.p+"view_admin/img/sw.8ad9bc6b.jpg"},5895:function(e,t,n){},"7daa":function(e,t){!function(){function e(e,t,n){return e.getAttribute(t)||n}function t(e){return document.getElementsByTagName(e)}function n(){var n=t("script"),o=n.length,i=n[o-1];return{l:o,z:e(i,"zIndex",-2),o:e(i,"opacity",.8),c:e(i,"color","255,255,255"),n:e(i,"count",240)}}function o(){r=a.width=window.innerWidth||document.documentElement.clientWidth||document.body.clientWidth,s=a.height=window.innerHeight||document.documentElement.clientHeight||document.body.clientHeight}function i(){if(d+=1,d<5)h(i);else{d=0,l.clearRect(0,0,r,s);var e,t,n,o,a,u,m=[f].concat(p);p.forEach((function(i){for(i.x+=i.xa,i.y+=i.ya,i.xa*=i.x>r||i.x<0?-1:1,i.ya*=i.y>s||i.y<0?-1:1,l.fillRect(i.x-.5,i.y-.5,2,2),l.fillStyle="#FFFFFF",t=0;t<m.length;t++)e=m[t],i!==e&&null!==e.x&&null!==e.y&&(o=i.x-e.x,a=i.y-e.y,u=o*o+a*a,u<e.max&&(e===f&&u>=e.max/2&&(i.x-=.03*o,i.y-=.03*a),n=(e.max-u)/e.max,l.beginPath(),l.lineWidth=n/2,l.strokeStyle="rgba("+c.c+","+(n+.2)+")",l.moveTo(i.x,i.y),l.lineTo(e.x,e.y),l.stroke()));m.splice(m.indexOf(i),1)})),h(i)}}var r,s,a=document.createElement("canvas"),c=n(),u="c_n"+c.l,l=a.getContext("2d"),d=0,h=window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.oRequestAnimationFrame||window.msRequestAnimationFrame||function(e){window.setTimeout(e,1e3/45)},m=Math.random,f={x:null,y:null,max:2e4};a.id=u,a.style.cssText="position:fixed;top:0;left:0;z-index:"+c.z+";opacity:"+c.o,t("body")[0].appendChild(a),o(),window.onresize=o,window.onmousemove=function(e){e=e||window.event,f.x=e.clientX,f.y=e.clientY},window.onmouseout=function(){f.x=null,f.y=null};for(var p=[],g=0;c.n>g;g++){var w=m()*r,v=m()*s,y=2*m()-1,x=2*m()-1;p.push({x:w,y:v,xa:y,ya:x,max:6e3})}setTimeout((function(){i()}),100)}()},"8aae":function(e,t,n){"use strict";var o=n("0b5c"),i=n.n(o);i.a},9717:function(e,t,n){"use strict";var o=n("5895"),i=n.n(o);i.a}}]);
webpackJsonp([10],{"4ml/":function(e,t){},NHnr:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var a=n("7+uW"),i={render:function(){var e=this.$createElement,t=this._self._c||e;return t("div",{attrs:{id:"app"}},[t("transition",{attrs:{name:this.animationType}},[t("router-view")],1)],1)},staticRenderFns:[]};var r=n("VU/8")({name:"App",data:function(){return{animationType:"",catchList:[]}},created:function(){},methods:{}},i,!1,function(e){n("Uhn8")},null,null).exports,o=n("/ocq"),l={getdeclare:function(e,t){return e.declare}},u=n("NYxO"),c={set_declare:function(e,t){e.commit("declare",t)}},s={declare:{},catchList:[]},d={declare:function(e,t){e.declare=t},keepAlive:function(e,t){!e.catchList.includes(t)&&e.catchList.push(t)},noKeepAlive:function(e){e.catchList=[]}};a.a.use(u.a);var p=new u.a.Store({state:s,mutations:d,getters:l,actions:c}),m=n("xFmM"),h=n("ddBe"),f=n.n(h),v=n("qSrU"),g=n.n(v),w=n("DJAq"),y=new(n.n(w).a)("admin"),k=g()(y);k.defaults({sys:{},database:{}}).write();var x=k,M={cookies:m.a,db:x};M.title=function(e){var t=e.title,n=e.count;t=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";return window&&window.$t&&0===e.indexOf("$t:")?window.$t(e.split("$t:")[1]):e}(t);var a="";a=M.cookies.get("pageTitle")?t?t+" - "+M.cookies.get("pageTitle"):M.cookies.get("pageTitle"):t?t+" - "+f.a.titleSuffix:f.a.titleSuffix,n&&(a="("+n+"条消息)"+a),window.document.title=a},M.wss=function(e){return"https:"==document.location.protocol?e.replace("ws:","wss:"):e.replace("wss:","ws:")};a.a.use(o.a);var b=new o.a({routes:[{path:"/",name:"Index",component:function(){return n.e(1).then(n.bind(null,"eerB"))},meta:{title:"首页"}},{path:"/zizhushengbaoWeixian",name:"zizhushengbaoWeixian",component:function(){return Promise.all([n.e(0),n.e(5)]).then(n.bind(null,"SuBd"))},meta:{title:"危险申报",keepAlive:!0}},{path:"/risingFalling",name:"risingFalling",component:function(){return Promise.all([n.e(0),n.e(2)]).then(n.bind(null,"92+b"))},meta:{title:"来返义申报",keepAlive:!0}},{path:"/goAndBackYiwu",name:"goAndBackYiwu",component:function(){return Promise.all([n.e(0),n.e(6)]).then(n.bind(null,"I3My"))},meta:{title:"外出申报",keepAlive:!0}},{path:"/declarationBayonet",name:"declarationBayonet",component:function(){return Promise.all([n.e(0),n.e(4)]).then(n.bind(null,"7jtk"))},meta:{title:"卡口申报",keepAlive:!0}},{path:"/shangyu",name:"shangyu",component:function(){return Promise.all([n.e(0),n.e(7)]).then(n.bind(null,"HhxL"))},meta:{title:"上虞申报",keepAlive:!0}},{path:"/success",name:"success",component:function(){return Promise.all([n.e(0),n.e(8)]).then(n.bind(null,"mbkp"))},meta:{title:"外出申报"}},{path:"/truckDeclaration",name:"truckDeclaration",component:function(){return Promise.all([n.e(0),n.e(3)]).then(n.bind(null,"Sdk+"))},meta:{title:"货车申报"}}]}),A=n("Fd2+"),S=(n("4ml/"),n("nq7x")),D=n.n(S);n("Y/GN"),n("mtWM");a.a.use(D.a),a.a.mixin({data:function(){return{}},methods:{vuexSet_deklarieren:function(e){this.$store.dispatch("set_declare",e)}}}),a.a.prototype.$dateTimeFilters=function(e,t){e||(e=new Date),(e+="").includes("-")&&(e=e.replace(/-/g,"/"));var n={yyyy:new Date(e).getFullYear(),MM:(new Date(e).getMonth()+1+"").padStart(2,"0"),dd:(new Date(e).getDate()+"").padStart(2,"0"),hh:(new Date(e).getHours()+"").padStart(2,"0"),mm:(new Date(e).getMinutes()+"").padStart(2,"0"),ss:(new Date(e).getSeconds()+"").padStart(2,"0")};if(!t||!t.length)return n.yyyy+"-"+n.MM+"-"+n.dd+" "+n.hh+":"+n.mm+":"+n.ss;var a=t;return a.includes("yyyy")&&(a=a.replace("yyyy",n.yyyy)),a.includes("MM")&&(a=a.replace("MM",n.MM)),a.includes("dd")&&(a=a.replace("dd",n.dd)),a.includes("hh")&&(a=a.replace("hh",n.hh)),a.includes("mm")&&(a=a.replace("mm",n.mm)),a.includes("ss")&&(a=a.replace("ss",n.ss)),a},a.a.use(A.c),a.a.use(u.a),a.a.config.productionTip=!1,new a.a({el:"#app",store:p,router:b,components:{App:r},template:"<App/>"})},Uhn8:function(e,t){},"Y/GN":function(e,t){},ddBe:function(e,t){},xFmM:function(e,t,n){"use strict";var a=n("woOf"),i=n.n(a),r=n("ZQ6q"),o=n.n(r),l=n("ddBe"),u=n.n(l),c={set:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"default",t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{},a={expires:1};i()(a,n),o.a.set("h5-"+e,t,a)},setKefu:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"default",t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{},a={expires:u.a.cookiesExpires};i()(a,n),o.a.set("kefu-"+e,t,a)},get:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"default";return o.a.get("h5-"+e)},kefuGet:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"default";return o.a.get("kefu-"+e)},getAll:function(){return o.a.get()},remove:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"default";return o.a.remove("h5-"+e)},kefuRemove:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"default";return o.a.remove("kefu-"+e)}};t.a=c}},["NHnr"]);
//# sourceMappingURL=app.480d22aca119dc218a7b.js.map
(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-11989a7e"],{"16b5":function(t,e,i){"use strict";var n=i("7590"),o=i.n(n);o.a},2996:function(t,e,i){"use strict";i.r(e);var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"diy-page"},[i("PageHeader",{staticClass:"product_tabs",attrs:{title:t.$route.meta.title,"hidden-breadcrumb":""}},[i("div",{attrs:{slot:"title"},slot:"title"},[i("router-link",{attrs:{to:{path:"/admin/setting/pages/devise"}}},[i("Button",{staticClass:"mr20",attrs:{icon:"ios-arrow-back",size:"small"}},[t._v("返回")])],1),i("span",{staticClass:"mr20",domProps:{textContent:t._s(t.$route.meta.title)}})],1)]),i("Card",{staticClass:"ivu-mt",staticStyle:{width:"100%"},attrs:{bordered:!1,"dis-hover":""}},[i("div",{staticClass:"diy-wrapper"},[i("div",{staticClass:"left"},[i("div",{staticClass:"title-bar"},t._l(t.tabList,(function(e,n){return i("div",{key:n,staticClass:"title-item",class:{on:t.tabCur==n},on:{click:function(e){return t.bindTab(n)}}},[t._v("\n            "+t._s(e.title)+"\n          ")])})),0),0==t.tabCur?i("div",{staticClass:"wrapper"},t._l(t.leftMenu,(function(e,n){return i("div",{key:n},[i("div",{staticClass:"tips",on:{click:function(t){e.isOpen=!e.isOpen}}},[t._v("\n              "+t._s(e.title)+"\n\n              "),e.isOpen?i("Icon",{attrs:{type:"ios-arrow-down",size:"16"}}):i("Icon",{attrs:{type:"ios-arrow-forward",size:"16"}})],1),i("draggable",{staticClass:"dragArea list-group",attrs:{list:e.list,group:{name:"people",pull:"clone",put:!1},clone:t.cloneDog,filter:".search"},on:{change:t.log,remove:t.leftRemove}},t._l(e.list,(function(n,o){return i("div",{directives:[{name:"show",rawName:"v-show",value:e.isOpen,expression:"item.isOpen"}],key:n.id,staticClass:"list-group-item",class:{search:"搜索框"==n.cname},on:{click:function(e){return t.addDom(n,o,0)}}},[i("span",{staticClass:"iconfont-diy",class:n.icon}),i("p",[t._v(t._s(n.cname))])])})),0)],1)})),0):i("div",t._l(t.urlList,(function(e,n){return i("div",{key:n,staticClass:"link-item"},[i("div",{staticClass:"name"},[t._v(t._s(e.name))]),i("div",{staticClass:"link-txt"},[t._v("地址："+t._s(e.url))]),i("div",{staticClass:"params"},[i("span",{staticClass:"txt"},[t._v("参数：")]),i("span",[t._v(t._s(e.parameter))])]),i("div",{staticClass:"lable"},[i("p",{staticClass:"txt"},[t._v("例如："+t._s(e.example))]),i("Button",{directives:[{name:"clipboard",rawName:"v-clipboard:copy",value:e.example,expression:"item.example",arg:"copy"},{name:"clipboard",rawName:"v-clipboard:success",value:t.onCopy,expression:"onCopy",arg:"success"},{name:"clipboard",rawName:"v-clipboard:error",value:t.onError,expression:"onError",arg:"error"}],attrs:{size:"small"}},[t._v("复制")])],1)])})),0)]),i("div",{staticClass:"wrapper-con",staticStyle:{flex:"1",background:"#f0f2f5",display:"flex","justify-content":"center","padding-top":"45px",height:"100%"}},[i("div",{staticClass:"content"},[i("div",{staticStyle:{display:"flex","flex-direction":"column",margin:"0 24px 0",overflow:"hidden",height:"100%"}},[i("div",{staticClass:"page-title",class:{on:-100==t.activeIndex},on:{click:t.showTitle}},[t._v("\n              "+t._s(t.titleTxt)+"\n              "),i("div",{staticClass:"delete-box"}),i("div",{staticClass:"handle"})]),i("div",{staticClass:"scroll-box"},[i("draggable",{staticClass:"dragArea list-group",attrs:{list:t.mConfig,group:"people",filter:".top",move:t.onMove},on:{change:t.log,remove:t.leftRemove}},t._l(t.mConfig,(function(e,n){return i("div",{key:n,staticClass:"mConfig-item",class:{on:t.activeIndex==n,top:"search_box"==e.name||"nav_bar"==e.name},on:{click:function(i){return i.stopPropagation(),t.bindconfig(e,n)}}},[i(e.name,{ref:"getComponentData",refInFor:!0,tag:"component",attrs:{configData:t.propsObj,index:n,num:e.num}}),i("div",{staticClass:"delete-box"},[i("span",{on:{click:function(i){return i.stopPropagation(),t.bindDelete(e,n)}}},[t._v("删除")])]),i("div",{staticClass:"handle"})],1)})),0)],1),i("div",{staticClass:"page-foot",class:{on:-101==t.activeIndex},on:{click:t.showFoot}},[i("footPage"),i("div",{staticClass:"delete-box"}),i("div",{staticClass:"handle"})],1)])])]),i("div",{staticClass:"right-box"},t._l(t.rConfig,(function(e,n){return i("div",{key:n,staticClass:"mConfig-item"},[i("div",{staticClass:"title-bar"},[t._v(t._s(e.cname))]),i(e.configName,{tag:"component",attrs:{activeIndex:t.activeIndex,num:e.num,index:n},on:{config:t.config}})],1)})),0)])]),i("div",{staticClass:"foot-box"},[i("Button",{on:{click:t.reast}},[t._v("重置")]),i("Button",{attrs:{type:"primary",loading:t.loading},on:{click:t.saveConfig}},[t._v("保存")])],1)],1)},o=[],a=i("f478"),s=i("310e"),r=i.n(s),c=i("877c"),l=i("6980"),m=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"page-fooot",style:{background:t.bgColor}},t._l(t.menuList,(function(e,n){return i("div",{key:n,staticClass:"foot-item"},[i("img",0==n?{attrs:{src:e.imgList[0],alt:""}}:{attrs:{src:e.imgList[1],alt:""}}),i("p",0==n?{style:{color:t.activeTxtColor}}:{style:{color:t.txtColor}},[t._v(t._s(e.name))])])})),0)},f=[],d=i("2f62");function u(t,e){var i=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),i.push.apply(i,n)}return i}function g(t){for(var e=1;e<arguments.length;e++){var i=null!=arguments[e]?arguments[e]:{};e%2?u(i,!0).forEach((function(e){p(t,e,i[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(i)):u(i).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(i,e))}))}return t}function p(t,e,i){return e in t?Object.defineProperty(t,e,{value:i,enumerable:!0,configurable:!0,writable:!0}):t[e]=i,t}var h={name:"index.vue",props:{configObj:{type:Object,default:function(){return{}}},configNme:{type:String,default:""}},data:function(){return{status:!0,txtColor:"",activeTxtColor:"",bgColor:"",menuList:[]}},computed:g({},Object(d["e"])("admin/mobildConfig",["pageFooter"])),watch:{pageFooter:{handler:function(t,e){this.setConfig(t)},deep:!0}},mounted:function(){var t=this.$store.state.admin.mobildConfig.pageFooter;this.setConfig(t)},methods:{setConfig:function(t){this.status=t.status,this.txtColor=t.txtColor.color[0].item,this.activeTxtColor=t.activeTxtColor.color[0].item,this.bgColor=t.bgColor.color[0].item,this.menuList=[],this.$set(this,"menuList",t.menuList)}}},v=h,C=(i("16b5"),i("2877")),b=Object(C["a"])(v,m,f,!1,null,"95d40956",null),y=b.exports;function O(t,e){var i=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),i.push.apply(i,n)}return i}function x(t){for(var e=1;e<arguments.length;e++){var i=null!=arguments[e]?arguments[e]:{};e%2?O(i,!0).forEach((function(e){w(t,e,i[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(i)):O(i).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(i,e))}))}return t}function w(t,e,i){return e in t?Object.defineProperty(t,e,{value:i,enumerable:!0,configurable:!0,writable:!0}):t[e]=i,t}var _={name:"index.vue",components:x({footPage:y,draggable:r.a},c["a"],{},l["a"]),filters:{filterTxt:function(t){if(t)return t.substr(0,t.length-1)}},computed:x({},Object(d["e"])({titleTxt:function(t){return t.admin.mobildConfig.pageTitle||"首页"}})),data:function(){return{leftMenu:[],lConfig:[],mConfig:[],rConfig:[],activeConfigName:"",propsObj:{},activeIndex:-99,number:0,pageId:"",pageName:"",pageType:"",category:[],tabList:[{title:"组件库",key:0},{title:"页面链接",key:1}],tabCur:0,urlList:[],footActive:!1,loading:!1,isSearch:!1,isTab:!1,isFllow:!1}},created:function(){this.categoryList(),this.getUrlList(),this.pageId=this.$route.query.id,this.pageName=this.$route.query.name,this.pageType=this.$route.query.type,this.lConfig=this.objToArr(c["a"])},mounted:function(){var t=this;this.$nextTick((function(){t.arraySort(),0!=t.pageId&&t.getDefaultConfig()}))},methods:{leftMove:function(t){},leftRemove:function(t){t.to,t.from;var e=t.item,i=(t.clone,t.oldIndex,t.newIndex);this.isSearch&&0==i&&("z_wechat_attention"==e._underlying_vm_.name?this.isFllow=!0:(this.$store.commit("admin/mobildConfig/ARRAYREAST",this.mConfig[0].num),this.mConfig.splice(0,1))),console.log(this.mConfig[1].num,i),this.isFllow&&i>=1&&this.$store.commit("admin/mobildConfig/ARRAYREAST",this.mConfig[1].num)},onMove:function(t){return"search_box"!=t.relatedContext.element.name&&"nav_bar"!=t.relatedContext.element.name},onCopy:function(){this.$Message.success("复制成功")},onError:function(){this.$Message.error("复制失败")},getUrlList:function(){var t=this;Object(a["i"])().then((function(e){t.urlList=e.data.url}))},bindTab:function(t){this.tabCur=t},showTitle:function(){this.activeIndex=-100;var t={};for(var e in l["a"])"pageTitle"==e&&(t=l["a"][e],t.configName=l["a"][e].name,t.cname="页面设置");this.rConfig=[],this.rConfig[0]=JSON.parse(JSON.stringify(t))},showFoot:function(){this.activeIndex=-101;var t={};for(var e in l["a"])"pageFoot"==e&&(t=l["a"][e],t.configName=l["a"][e].name,t.cname="底部菜单");this.rConfig=[],this.rConfig[0]=JSON.parse(JSON.stringify(t))},objToArr:function(t){var e=Object.keys(t),i=e.map((function(e){return t[e]}));return i},log:function(t){if(t.moved){if("search_box"==t.moved.element.name)return this.$Message.warnning("该组件禁止拖拽");if("nav_bar"==t.moved.element.name)return this.$Message.warnning("该组件禁止拖拽");t.moved.oldNum=this.mConfig[t.moved.oldIndex].num,t.moved.newNum=this.mConfig[t.moved.newIndex].num,t.moved.status=t.moved.oldIndex>t.moved.newIndex,this.mConfig.forEach((function(t,e){t.num=1e3*(new Date).getTime()+e})),t.moved.list=this.mConfig,this.$store.commit("admin/mobildConfig/defaultArraySort",t.moved)}if(t.added){var e=t.added.element,i=1e3*(new Date).getTime();e.num=i,this.activeConfigName=e.name;var n=JSON.parse(JSON.stringify(e));n.id="id"+n.num,this.mConfig[t.added.newIndex]=n,this.rConfig=[],this.rConfig.push(n),this.mConfig.forEach((function(t,e){t.num=1e3*(new Date).getTime()+e})),t.added.list=this.mConfig,this.$store.commit("admin/mobildConfig/SETCONFIGNAME",e.name),this.$store.commit("admin/mobildConfig/defaultArraySort",t.added)}},cloneDog:function(t){return x({},t)},addDom:function(t,e,i){if("search_box"==t.name){if(this.isSearch)return this.$Message.error("该组件只能添加一次");this.isSearch=!0}if("nav_bar"==t.name){if(this.isTab)return this.$Message.error("该组件只能添加一次");this.isTab=!0}1;var n={},o=1e3*(new Date).getTime();t.num="".concat(o),t.id="id".concat(o),this.activeConfigName=t.name;var a=JSON.parse(JSON.stringify(t));this.rConfig=[],"search_box"==t.name?this.mConfig.unshift(a):"nav_bar"==t.name?"search_box"===this.mConfig[0].name?this.mConfig.splice(1,0,a):this.mConfig.splice(0,0,a):this.mConfig.push(a),this.rConfig.push(a),this.mConfig.forEach((function(t,e){t.num=1e3*(new Date).getTime()+e})),this.activeIndex=this.mConfig.length-1,n.element=t,n.list=this.mConfig,this.$store.commit("admin/mobildConfig/SETCONFIGNAME",t.name),this.$store.commit("admin/mobildConfig/defaultArraySort",n)},bindconfig:function(t,e){this.rConfig=[];var i=JSON.parse(JSON.stringify(t));this.rConfig.push(i),this.activeIndex=e,this.$store.commit("admin/mobildConfig/SETCONFIGNAME",t.name)},bindDelete:function(t,e){"search_box"==t.name&&(this.isSearch=!1),"nav_bar"==t.name&&(this.isTab=!1),this.mConfig.splice(e,1),this.rConfig.splice(0,1),this.$store.commit("admin/mobildConfig/DELETEARRAY",t)},config:function(t){var e=this.propsObj;e.data=t,e.name=this.activeConfigName},addSort:function(t,e,i){return t[e]=t.splice(i,1,t[e])[0],t},arraySort:function(){var t=[],e={title:"基础组件",list:[],isOpen:!0},i={title:"营销组件",list:[],isOpen:!0},n={title:"工具组件",list:[],isOpen:!0};this.lConfig.map((function(t,o){0==t.type&&e.list.push(t),1==t.type&&i.list.push(t),2==t.type&&n.list.push(t)})),t.push(e,i,n),this.leftMenu=t},saveConfig:function(){var t=this;if(0==this.mConfig.length)return this.$Message.error("暂未添加任何组件，保存失败！");this.loading=!0;var e=this.$store.state.admin.mobildConfig.defaultArray;if(!this.footActive){var i=1e3*(new Date).getTime();e[i]=this.$store.state.admin.mobildConfig.pageFooter,this.footActive=!0}Object(a["e"])(this.pageId,{type:this.pageType,value:e,title:this.titleTxt}).then((function(e){t.loading=!1,t.pageId=e.data.id,t.$Message.success(e.msg)})).catch((function(e){t.loading=!1,t.$Message.error(e.msg)}))},getDefaultConfig:function(){var t=this;Object(a["c"])(this.pageId,{type:1}).then((function(e){var i=e.data,n={},o=[];t.$store.commit("admin/mobildConfig/titleUpdata",i.info.title);var a=t.objToArr(i.info.value);function s(t,e){return t.timestamp-e.timestamp}a.sort(s),a.map((function(e,i){"headerSerch"==e.name&&(t.isSearch=!0),"tabNav"==e.name&&(t.isTab=!0),e.id="id"+e.timestamp,t.lConfig.map((function(i,a){if(e.name==i.defaultName){i.num=e.timestamp,i.id="id"+e.timestamp;var s=JSON.parse(JSON.stringify(i));o.push(s),n[e.timestamp]=e,t.mConfig.push(s),t.$store.commit("admin/mobildConfig/ADDARRAY",{num:e.timestamp,val:e})}}))}));var r=a[a.length-1];"pageFoot"==r.name&&t.$store.commit("admin/mobildConfig/footPageUpdata",r)}))},categoryList:function(){var t=this;Object(a["a"])((function(e){t.category=e.data}))},reast:function(){var t=this;0==this.pageId?this.$Message.error("新增页面，无法重置"):this.$Modal.confirm({title:"提示",content:"<p>是否重置当前页面数据</p>",onOk:function(){t.mConfig=[],t.rConfig=[],t.activeIndex=-99,t.getDefaultConfig()},onCancel:function(){}})}},beforeDestroy:function(){this.$store.commit("admin/mobildConfig/titleUpdata",""),this.$store.commit("admin/mobildConfig/SETEMPTY")},destroyed:function(){this.$store.commit("admin/mobildConfig/titleUpdata",""),this.$store.commit("admin/mobildConfig/SETEMPTY")}},T=_,S=(i("8645"),Object(C["a"])(T,n,o,!1,null,"11cef032",null));e["default"]=S.exports},7590:function(t,e,i){},8645:function(t,e,i){"use strict";var n=i("c536"),o=i.n(n);o.a},c536:function(t,e,i){}}]);
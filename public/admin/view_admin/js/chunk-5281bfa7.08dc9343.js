(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-5281bfa7"],{"171d":function(t,e,r){},"1deb":function(t,e,r){"use strict";var s=r("81ac"),a=r.n(s);a.a},"417c":function(t,e,r){"use strict";var s=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("Modal",{staticClass:"order_box",attrs:{scrollable:"",title:"订单记录",width:"700","footer-hide":""},model:{value:t.modals,callback:function(e){t.modals=e},expression:"modals"}},[r("Card",{attrs:{bordered:!1,"dis-hover":""}},[r("Table",{attrs:{columns:t.columns,border:"",data:t.recordData,loading:t.loading,"no-data-text":"暂无数据","highlight-row":"","no-filtered-data-text":"暂无筛选结果"}})],1)],1)},a=[],o=r("a34a"),i=r.n(o),n=r("f8b7");function l(t,e,r,s,a,o,i){try{var n=t[o](i),l=n.value}catch(d){return void r(d)}n.done?e(l):Promise.resolve(l).then(s,a)}function d(t){return function(){var e=this,r=arguments;return new Promise((function(s,a){var o=t.apply(e,r);function i(t){l(o,s,a,i,n,"next",t)}function n(t){l(o,s,a,i,n,"throw",t)}i(void 0)}))}}var c={name:"orderRecord",data:function(){return{modals:!1,loading:!1,recordData:[],page:{page:1,limit:10},columns:[{title:"订单ID",key:"oid",align:"center",minWidth:40},{title:"操作记录",key:"change_message",align:"center",minWidth:280},{title:"操作时间",key:"change_time",align:"center",minWidth:100}]}},methods:{pageChange:function(t){this.page.pageNum=t,this.getList()},getList:function(t){var e=this,r={id:t,datas:this.page};this.loading=!0,Object(n["j"])(r).then(function(){var t=d(i.a.mark((function t(r){return i.a.wrap((function(t){while(1)switch(t.prev=t.next){case 0:e.recordData=r.data,e.loading=!1;case 2:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()).catch((function(t){e.loading=!1,e.$Message.error(t.msg)}))}}},m=c,u=(r("982d"),r("2877")),p=Object(u["a"])(m,s,a,!1,null,"7b088fac",null);e["a"]=p.exports},"42fd":function(t,e,r){"use strict";var s=r("735f"),a=r.n(s);a.a},"61f8":function(t,e,r){"use strict";var s=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("Modal",{staticClass:"order_box",attrs:{scrollable:"",title:"请修改内容",closable:!1},model:{value:t.modals,callback:function(e){t.modals=e},expression:"modals"}},[r("Form",{ref:"formValidate",attrs:{model:t.formValidate,rules:t.ruleValidate,"label-width":80},nativeOn:{submit:function(t){t.preventDefault()}}},[r("FormItem",{attrs:{label:"备注：",prop:"remark"}},[r("Input",{staticStyle:{width:"100%"},attrs:{maxlength:"200","show-word-limit":"",type:"textarea",placeholder:"订单备注"},model:{value:t.formValidate.remark,callback:function(e){t.$set(t.formValidate,"remark",e)},expression:"formValidate.remark"}})],1)],1),r("div",{attrs:{slot:"footer"},slot:"footer"},[r("Button",{attrs:{type:"primary"},on:{click:function(e){return t.putRemark("formValidate")}}},[t._v("提交")]),r("Button",{on:{click:function(e){return t.cancel("formValidate")}}},[t._v("取消")])],1)],1)},a=[],o=r("a34a"),i=r.n(o),n=r("f8b7");function l(t,e,r,s,a,o,i){try{var n=t[o](i),l=n.value}catch(d){return void r(d)}n.done?e(l):Promise.resolve(l).then(s,a)}function d(t){return function(){var e=this,r=arguments;return new Promise((function(s,a){var o=t.apply(e,r);function i(t){l(o,s,a,i,n,"next",t)}function n(t){l(o,s,a,i,n,"throw",t)}i(void 0)}))}}var c={name:"orderMark",data:function(){return{formValidate:{remark:""},modals:!1,ruleValidate:{remark:[{required:!0,message:"请输入备注信息",trigger:"blur"}]}}},props:{orderId:Number},methods:{cancel:function(t){this.modals=!1,this.$refs[t].resetFields()},putRemark:function(t){var e=this,r={id:this.orderId,remark:this.formValidate};this.$refs[t].validate((function(s){s?Object(n["D"])(r).then(function(){var r=d(i.a.mark((function r(s){return i.a.wrap((function(r){while(1)switch(r.prev=r.next){case 0:e.$Message.success(s.msg),e.modals=!1,e.$refs[t].resetFields(),e.$emit("submitFail");case 4:case"end":return r.stop()}}),r)})));return function(t){return r.apply(this,arguments)}}()).catch((function(t){e.$Message.error(t.msg)})):e.$Message.warning("请填写备注信息")}))}}},m=c,u=r("2877"),p=Object(u["a"])(m,s,a,!1,null,"4d7832de",null);e["a"]=p.exports},"735f":function(t,e,r){},"81ac":function(t,e,r){},"88de":function(t,e,r){},"916a":function(t,e,r){"use strict";var s=r("171d"),a=r.n(s);a.a},"982d":function(t,e,r){"use strict";var s=r("88de"),a=r.n(s);a.a},a464:function(t,e,r){"use strict";var s=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("Row",{staticClass:"expand-row"},[r("Col",{attrs:{span:"8"}},[r("span",{staticClass:"expand-key"},[t._v("商品总价：")]),r("span",{staticClass:"expand-value",domProps:{textContent:t._s(t.row.total_price)}})]),r("Col",{attrs:{span:"8"}},[r("span",{staticClass:"expand-key"},[t._v("下单时间：")]),r("span",{staticClass:"expand-value",domProps:{textContent:t._s(t.row.add_time)}})]),r("Col",{attrs:{span:"8"}},[r("span",{staticClass:"expand-key"},[t._v("推广人：")]),r("span",{staticClass:"expand-value",domProps:{textContent:t._s(t.row.spread_nickname?t.row.spread_nickname:"无")}})])],1),r("Row",[r("Col",{attrs:{span:"8"}},[r("span",{staticClass:"expand-key"},[t._v("用户备注：")]),r("span",{staticClass:"expand-value",domProps:{textContent:t._s(t.row.mark?t.row.mark:"无")}})]),r("Col",{attrs:{span:"8"}},[r("span",{staticClass:"expand-key"},[t._v("商家备注：")]),r("span",{staticClass:"expand-value",domProps:{textContent:t._s(t.row.remark?t.row.remark:"无")}})])],1)],1)},a=[],o={name:"table-expand",props:{row:Object}},i=o,n=(r("1deb"),r("2877")),l=Object(n["a"])(i,s,a,!1,null,"72412dcc",null);e["a"]=l.exports},bd9b:function(t,e){t.exports="data:image/jpeg;base64,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"},d616:function(t,e,r){"use strict";var s=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("Modal",{staticClass:"order_box",attrs:{scrollable:"",title:"订单发送货",closable:!1,width:"1000"},on:{"on-visible-change":t.changeModal},model:{value:t.modals,callback:function(e){t.modals=e},expression:"modals"}},[t.modals?r("Form",{ref:"formItem",attrs:{model:t.formItem,"label-width":100},nativeOn:{submit:function(t){t.preventDefault()}}},[r("FormItem",{attrs:{label:"选择类型："}},[r("RadioGroup",{on:{"on-change":t.changeRadio},model:{value:t.formItem.type,callback:function(e){t.$set(t.formItem,"type",e)},expression:"formItem.type"}},[r("Radio",{attrs:{label:"1"}},[t._v("发货")]),r("Radio",{attrs:{label:"2"}},[t._v("送货")]),r("Radio",{attrs:{label:"3"}},[t._v("无需配送")])],1)],1),r("FormItem",{directives:[{name:"show",rawName:"v-show",value:"1"===t.formItem.type&&t.export_open,expression:"formItem.type === '1' && export_open"}],attrs:{label:"发货类型："}},[r("RadioGroup",{on:{"on-change":t.changeExpress},model:{value:t.formItem.express_record_type,callback:function(e){t.$set(t.formItem,"express_record_type",e)},expression:"formItem.express_record_type"}},[r("Radio",{attrs:{label:"1"}},[t._v("手动填写")]),r("Radio",{attrs:{label:"2"}},[t._v("电子面单打印")])],1)],1),r("div",{directives:[{name:"show",rawName:"v-show",value:"1"===t.formItem.type,expression:"formItem.type === '1'"}]},[r("FormItem",{attrs:{label:"快递公司："}},[r("Select",{staticStyle:{width:"80%"},attrs:{filterable:"",placeholder:"请选择快递公司"},on:{"on-change":t.expressChange},model:{value:t.formItem.delivery_name,callback:function(e){t.$set(t.formItem,"delivery_name",e)},expression:"formItem.delivery_name"}},t._l(t.express,(function(e,s){return r("Option",{key:e.value,attrs:{value:e.value}},[t._v(t._s(e.value))])})),1)],1),"1"===t.formItem.express_record_type?r("FormItem",{attrs:{label:"快递单号："}},[r("Input",{staticStyle:{width:"80%"},attrs:{placeholder:"请输入快递单号"},model:{value:t.formItem.delivery_id,callback:function(e){t.$set(t.formItem,"delivery_id",e)},expression:"formItem.delivery_id"}}),"顺丰速运"==t.formItem.delivery_name?r("div",{staticClass:"trips"},[r("p",[t._v("顺丰请输入单号 :收件人或寄件人手机号后四位，")]),r("p",[t._v("例如：SF000000000000:3941")])]):t._e()],1):t._e(),"2"===t.formItem.express_record_type?[r("FormItem",{staticClass:"express_temp_id",attrs:{label:"电子面单："}},[r("Select",{staticStyle:{width:"80%"},attrs:{placeholder:"请选择电子面单"},on:{"on-change":t.expressTempChange},model:{value:t.formItem.express_temp_id,callback:function(e){t.$set(t.formItem,"express_temp_id",e)},expression:"formItem.express_temp_id"}},t._l(t.expressTemp,(function(e,s){return r("Option",{key:s,attrs:{value:e.temp_id}},[t._v(t._s(e.title))])})),1),t.formItem.express_temp_id?r("Button",{attrs:{type:"text"},on:{click:t.preview}},[t._v("预览")]):t._e()],1),r("FormItem",{attrs:{label:"寄件人姓名："}},[r("Input",{staticStyle:{width:"80%"},attrs:{placeholder:"请输入寄件人姓名"},model:{value:t.formItem.to_name,callback:function(e){t.$set(t.formItem,"to_name",e)},expression:"formItem.to_name"}})],1),r("FormItem",{attrs:{label:"寄件人电话："}},[r("Input",{staticStyle:{width:"80%"},attrs:{placeholder:"请输入寄件人电话"},model:{value:t.formItem.to_tel,callback:function(e){t.$set(t.formItem,"to_tel",e)},expression:"formItem.to_tel"}})],1),r("FormItem",{attrs:{label:"寄件人地址："}},[r("Input",{staticStyle:{width:"80%"},attrs:{placeholder:"请输入寄件人地址"},model:{value:t.formItem.to_addr,callback:function(e){t.$set(t.formItem,"to_addr",e)},expression:"formItem.to_addr"}})],1)]:t._e()],2),r("div",{directives:[{name:"show",rawName:"v-show",value:"2"===t.formItem.type,expression:"formItem.type === '2'"}]},[r("FormItem",{attrs:{label:"送货人："}},[r("Select",{staticStyle:{width:"80%"},attrs:{placeholder:"请选择送货人"},on:{"on-change":t.shDeliveryChange},model:{value:t.formItem.sh_delivery,callback:function(e){t.$set(t.formItem,"sh_delivery",e)},expression:"formItem.sh_delivery"}},t._l(t.deliveryList,(function(e,s){return r("Option",{key:s,attrs:{value:e.id}},[t._v(t._s(e.wx_name)+"（"+t._s(e.phone)+"）")])})),1)],1)],1),r("div",{directives:[{name:"show",rawName:"v-show",value:"3"===t.formItem.type,expression:"formItem.type === '3'"}]},[r("FormItem",{attrs:{label:"备注："}},[r("Input",{staticStyle:{width:"80%"},attrs:{type:"textarea",autosize:{minRows:2,maxRows:5},placeholder:"备注"},model:{value:t.formItem.fictitious_content,callback:function(e){t.$set(t.formItem,"fictitious_content",e)},expression:"formItem.fictitious_content"}})],1)],1),r("div",{directives:[{name:"show",rawName:"v-show",value:"3"!==t.formItem.type,expression:"formItem.type !== '3'"}]},[r("FormItem",{attrs:{label:"分单发货："}},[r("i-switch",{attrs:{size:"large",disabled:8===t.orderStatus||"offline"===t.pay_type},on:{"on-change":t.changeSplitStatus},model:{value:t.splitSwitch,callback:function(e){t.splitSwitch=e},expression:"splitSwitch"}},[r("span",{attrs:{slot:"open"},slot:"open"},[t._v("开启")]),r("span",{attrs:{slot:"close"},slot:"close"},[t._v("关闭")])]),r("div",{staticClass:"trips"},[r("p",[t._v("\n            可选择表格中的商品单独发货，发货后会生成新的订单且不能撤回，请谨慎操作！\n          ")])]),t.splitSwitch?r("Table",{attrs:{data:t.manyFormValidate,columns:t.header,border:""},on:{"on-selection-change":t.selectOne},scopedSlots:t._u([{key:"image",fn:function(e){var s=e.row;e.index;return[r("div",{staticClass:"product-data"},[r("img",{staticClass:"image",attrs:{src:s.cart_info.productInfo.image}}),r("div",{staticClass:"line2"},[t._v("\n                "+t._s(s.cart_info.productInfo.store_name)+"\n              ")])])]}},{key:"value",fn:function(e){var s=e.row;e.index;return[r("div",{staticClass:"product-data"},[r("div",[t._v(t._s(s.cart_info.productInfo.attrInfo.suk))])])]}},{key:"price",fn:function(e){var s=e.row;e.index;return[r("div",{staticClass:"product-data"},[r("div",[t._v(t._s(s.cart_info.truePrice))])])]}}],null,!1,1113423484)}):t._e()],1)],1)],1):t._e(),r("div",{attrs:{slot:"footer"},slot:"footer"},[r("Button",{on:{click:t.cancel}},[t._v("取消")]),r("Button",{attrs:{type:"primary"},on:{click:t.putSend}},[t._v("提交")])],1),r("div",{directives:[{name:"viewer",rawName:"v-viewer"},{name:"show",rawName:"v-show",value:t.temp,expression:"temp"}],ref:"viewer"},[r("img",{staticStyle:{display:"none"},attrs:{src:t.temp.pic}})])],1)},a=[],o=r("a34a"),i=r.n(o),n=r("f8b7");function l(t,e,r,s,a,o,i){try{var n=t[o](i),l=n.value}catch(d){return void r(d)}n.done?e(l):Promise.resolve(l).then(s,a)}function d(t){return function(){var e=this,r=arguments;return new Promise((function(s,a){var o=t.apply(e,r);function i(t){l(o,s,a,i,n,"next",t)}function n(t){l(o,s,a,i,n,"throw",t)}i(void 0)}))}}var c={name:"orderSend",props:{orderId:Number,status:Number,pay_type:String},data:function(){var t=this;return{orderStatus:0,splitSwitch:!0,formItem:{type:"1",express_record_type:"1",delivery_name:"",delivery_id:"",express_temp_id:"",to_name:"",to_tel:"",to_addr:"",sh_delivery:"",fictitious_content:""},modals:!1,express:[],expressTemp:[],deliveryList:[],temp:{},export_open:!0,manyFormValidate:[],header:[{type:"selection",width:60,align:"center"},{title:"商品信息",slot:"image",width:200,align:"center"},{title:"规格",slot:"value",align:"center",minWidth:120},{title:"价格",slot:"price",align:"center",minWidth:120},{title:"总数",key:"cart_num",align:"center",minWidth:120},{title:"待发数量",key:"surplus_num",align:"center",width:180,render:function(e,r){return e("div",[e("InputNumber",{props:{min:1,max:r.row.surplus_num,value:r.row.surplus_num},on:{"on-change":function(e){r.row.surplus_num=e||1,t.manyFormValidate[r.index]=r.row,t.selectData.forEach((function(e,s){e.cart_id===r.row.cart_id&&t.selectData.splice(s,1,r.row)}))}}})])}}],selectData:[]}},methods:{selectOne:function(t){console.log(t),this.selectData=t},changeModal:function(t){t||this.cancel()},changeSplitStatus:function(t){var e=this;console.log(t),t&&Object(n["J"])(this.orderId).then((function(t){e.manyFormValidate=t.data}))},changeRadio:function(t){switch(this.$refs.formItem.resetFields(),t){case"1":this.formItem.delivery_name="",this.formItem.delivery_id="",this.formItem.express_temp_id="",this.formItem.express_record_type="1",this.expressTemp=[];break;case"2":this.formItem.sh_delivery="";break;case"3":this.formItem.fictitious_content="";break;default:break}},changeExpress:function(t){switch(t){case"2":this.formItem.delivery_name="",this.formItem.express_temp_id="",this.expressTemp=[],this.getList(2);break;case"1":this.formItem.delivery_name="",this.formItem.delivery_id="",this.getList(1);break;default:break}},reset:function(){this.formItem={type:"1",express_record_type:"1",delivery_name:"",delivery_id:"",express_temp_id:"",expressTemp:[],to_name:"",to_tel:"",to_addr:"",sh_delivery:"",fictitious_content:""}},getList:function(t){var e=this,r=2===t?1:"";Object(n["h"])(r).then(function(){var t=d(i.a.mark((function t(r){return i.a.wrap((function(t){while(1)switch(t.prev=t.next){case 0:e.express=r.data,e.getSheetInfo();case 2:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()).catch((function(t){e.loading=!1,e.$Message.error(t.msg)}))},putSend:function(t){var e=this,r={id:this.orderId,datas:this.formItem};if("1"===this.formItem.type&&"2"===this.formItem.express_record_type){if(""===this.formItem.delivery_name)return this.$Message.error("快递公司不能为空");if(""===this.formItem.express_temp_id)return this.$Message.error("电子面单不能为空");if(""===this.formItem.to_name)return this.$Message.error("寄件人姓名不能为空");if(""===this.formItem.to_tel)return this.$Message.error("寄件人电话不能为空");if(!/^1(3|4|5|7|8|9|6)\d{9}$/i.test(this.formItem.to_tel))return this.$Message.error("请输入正确的手机号码");if(""===this.formItem.to_addr)return this.$Message.error("寄件人地址不能为空")}if("1"===this.formItem.type&&"1"===this.formItem.express_record_type){if(""===this.formItem.delivery_name)return this.$Message.error("快递公司不能为空");if(""===this.formItem.delivery_id)return this.$Message.error("快递单号不能为空")}if("2"===this.formItem.type&&""===this.formItem.sh_delivery)return this.$Message.error("送货人不能为空");this.splitSwitch?(console.log(this.selectData),r.datas.cart_ids=[],this.selectData.forEach((function(t){r.datas.cart_ids.push({cart_id:t.cart_id,cart_num:t.surplus_num})})),console.log(r),Object(n["K"])(r).then((function(t){e.modals=!1,e.$Message.success(t.msg),e.$emit("submitFail"),e.reset(),e.splitSwitch=!1})).catch((function(t){console.log("222"),e.$Message.error(t.msg)}))):Object(n["C"])(r).then(function(){var t=d(i.a.mark((function t(r){return i.a.wrap((function(t){while(1)switch(t.prev=t.next){case 0:e.modals=!1,e.$Message.success(r.msg),e.splitSwitch=!1,e.$emit("submitFail"),e.reset();case 5:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()).catch((function(t){e.$Message.error(t.msg)}))},cancel:function(t){this.modals=!1,this.orderStatus=0,this.splitSwitch=!1,this.selectData=[],this.reset()},expressChange:function(t){var e=this,r=this.express.find((function(e){return e.value===t}));void 0!==r&&(this.formItem.delivery_code=r.code,"2"===this.formItem.express_record_type&&(this.expressTemp=[],this.formItem.express_temp_id="",Object(n["s"])({com:this.formItem.delivery_code}).then((function(t){e.expressTemp=t.data,t.data.length||e.$Message.error("请配置你所选快递公司的电子面单")})).catch((function(t){e.$Message.error(t.msg)}))))},getCartInfo:function(t,e){var r=this;console.log(t),this.$set(this,"orderStatus",t),this.$set(this,"splitSwitch",8===t),8===t&&Object(n["J"])(this.orderId).then((function(t){r.manyFormValidate=t.data}))},getDeliveryList:function(){var t=this;Object(n["q"])().then((function(e){t.deliveryList=e.data.list})).catch((function(e){t.$Message.error(e.msg)}))},getSheetInfo:function(){var t=this;Object(n["A"])().then((function(e){var r=e.data;for(var s in r)r.hasOwnProperty(s)&&(t.formItem[s]=r[s]);t.export_open=void 0===r.export_open||r.export_open,t.export_open||(t.formItem.express_record_type="1"),t.formItem.to_addr=r.to_add})).catch((function(e){t.$Message.error(e.msg)}))},shDeliveryChange:function(t){if(t){var e=this.deliveryList.find((function(e){return e.id===t}));this.formItem.sh_delivery_name=e.wx_name,this.formItem.sh_delivery_id=e.phone,this.formItem.sh_delivery_uid=e.uid}},expressTempChange:function(t){this.temp=this.expressTemp.find((function(e){return t===e.temp_id})),void 0===this.temp&&(this.temp={})},preview:function(){this.$refs.viewer.$viewer.show()}}},m=c,u=(r("916a"),r("2877")),p=Object(u["a"])(m,s,a,!1,null,"941634b0",null);e["a"]=p.exports},fc48:function(t,e,r){"use strict";var s=function(){var t=this,e=t.$createElement,s=t._self._c||e;return t.orderDatalist?s("div",[s("Modal",{staticClass:"order_box",attrs:{scrollable:"",title:"订单信息",width:"700","footer-hide":""},model:{value:t.modals,callback:function(e){t.modals=e},expression:"modals"}},[s("Card",{staticClass:"i-table-no-border",attrs:{bordered:!1,"dis-hover":""}},[s("div",{staticClass:"ivu-description-list-title"},[t._v("收货信息")]),s("Row",{staticClass:"mb10"},[s("Col",{attrs:{span:"12"}},[t._v("用户昵称："+t._s(t.orderDatalist.userInfo.nickname))]),s("Col",{attrs:{span:"12"}},[t._v("收货人："+t._s(t.orderDatalist.orderInfo.real_name))])],1),s("Row",{staticClass:"mb10"},[s("Col",{attrs:{span:"12"}},[t._v("联系电话："+t._s(t.orderDatalist.orderInfo.user_phone))]),s("Col",{attrs:{span:"12"}},[t._v("收货地址："+t._s(t.orderDatalist.orderInfo.user_address))])],1),s("Divider",{staticStyle:{margin:"20px 0 !important"}}),s("div",{staticClass:"ivu-description-list-title"},[t._v("订单信息")]),s("Row",{staticClass:"mb10"},[s("Col",{attrs:{span:"12"}},[t._v("订单ID："+t._s(t.orderDatalist.orderInfo.order_id))]),s("Col",{staticClass:"fontColor1",attrs:{span:"12"}},[t._v("订单状态："+t._s(t.orderDatalist.orderInfo._status._title))])],1),s("Row",{staticClass:"mb10"},[s("Col",{attrs:{span:"12"}},[t._v("商品总数："+t._s(t.orderDatalist.orderInfo.total_num))]),s("Col",{attrs:{span:"12"}},[t._v("商品总价："+t._s(t.orderDatalist.orderInfo.total_price))])],1),s("Row",{staticClass:"mb10"},[s("Col",{attrs:{span:"12"}},[t._v("交付邮费："+t._s(t.orderDatalist.orderInfo.pay_postage))]),s("Col",{attrs:{span:"12"}},[t._v("优惠券金额："+t._s(t.orderDatalist.orderInfo.coupon_price))])],1),s("Row",{staticClass:"mb10"},[s("Col",{attrs:{span:"12"}},[t._v("会员商品优惠："+t._s(t.orderDatalist.orderInfo.vip_true_price||0))]),s("Col",{attrs:{span:"12"}},[t._v("积分抵扣："+t._s(t.orderDatalist.orderInfo.deduction_price||0))])],1),s("Row",{staticClass:"mb10"},[s("Col",{staticClass:"mb10",attrs:{span:"12"}},[t._v("实际支付："+t._s(t.orderDatalist.orderInfo.pay_price))]),parseFloat(t.orderDatalist.orderInfo.refund_price)?s("Col",{staticClass:"fontColor3 mb10",attrs:{span:"12"}},[t._v("退款金额："+t._s(parseFloat(t.orderDatalist.orderInfo.refund_price)))]):t._e(),parseFloat(t.orderDatalist.orderInfo.use_integral)?s("Col",{staticClass:"fontColor3 mb10",attrs:{span:"12"}},[t._v("使用积分："+t._s(parseFloat(t.orderDatalist.orderInfo.use_integral)))]):t._e(),parseFloat(t.orderDatalist.orderInfo.back_integral)?s("Col",{staticClass:"fontColor3 mb10",attrs:{span:"12"}},[t._v("退回积分："+t._s(parseFloat(t.orderDatalist.orderInfo.back_integral)))]):t._e(),s("Col",{staticClass:"mb10",attrs:{span:"12"}},[t._v("创建时间："+t._s(t.orderDatalist.orderInfo._add_time))]),s("Col",{staticClass:"mb10",attrs:{span:"12"}},[t._v("支付时间："+t._s(t.orderDatalist.orderInfo._pay_time))]),s("Col",{staticClass:"mb10",attrs:{span:"12"}},[t._v("支付方式："+t._s(t.orderDatalist.orderInfo._status._payType))]),s("Col",{staticClass:"mb10",attrs:{span:"12"}},[t._v("推广人："+t._s(t.orderDatalist.userInfo.spread_name+"/"+t.orderDatalist.userInfo.spread_uid))]),2===t.orderDatalist.orderInfo.shipping_type&&0===t.orderDatalist.orderInfo.refund_status&&1===t.orderDatalist.orderInfo.paid?s("Col",{staticClass:"mb10",attrs:{span:"12"}},[t._v("门店名称："+t._s(t.orderDatalist.orderInfo._store_name))]):t._e(),2===t.orderDatalist.orderInfo.shipping_type&&0===t.orderDatalist.orderInfo.refund_status&&1===t.orderDatalist.orderInfo.paid?s("Col",{staticClass:"mb10",attrs:{span:"12"}},[t._v("核销码："+t._s(t.orderDatalist.orderInfo.verify_code))]):t._e(),t.orderDatalist.orderInfo.remark?s("Col",{staticClass:"mb10",attrs:{span:"12"}},[t._v("商家备注："+t._s(t.orderDatalist.orderInfo.remark))]):t._e(),t.orderDatalist.orderInfo.fictitious_content?s("Col",{staticClass:"mb10",attrs:{span:"12"}},[t._v("虚拟发货备注："+t._s(t.orderDatalist.orderInfo.fictitious_content))]):t._e()],1),"express"===t.orderDatalist.orderInfo.delivery_type?s("div",[s("Divider",{staticStyle:{margin:"20px 0 !important"}}),s("div",{staticClass:"ivu-description-list-title"},[t._v("物流信息")]),s("Row",{staticClass:"mb10"},[s("Col",{attrs:{span:"12"}},[t._v("快递公司："+t._s(t.orderDatalist.orderInfo.delivery_name))]),s("Col",{attrs:{span:"12"}},[t._v("快递单号："+t._s(t.orderDatalist.orderInfo.delivery_id)+" "),s("Button",{attrs:{type:"info",size:"small"},on:{click:t.openLogistics}},[t._v("物流查询")])],1)],1)],1):t._e(),"send"===t.orderDatalist.orderInfo.delivery_type?s("div",[s("Divider",{staticStyle:{margin:"20px 0 !important"}}),s("div",{staticClass:"ivu-description-list-title"},[t._v("配送信息")]),s("Row",{staticClass:"mb10"},[s("Col",{attrs:{span:"12"}},[t._v("送货人姓名："+t._s(t.orderDatalist.orderInfo.delivery_name))]),s("Col",{attrs:{span:"12"}},[t._v("送货人电话："+t._s(t.orderDatalist.orderInfo.delivery_id))])],1)],1):t._e(),t.orderDatalist.orderInfo.mark?s("div",[t.orderDatalist.orderInfo.mark?s("Divider",{staticStyle:{margin:"20px 0 !important"}}):t._e(),t.orderDatalist.orderInfo.mark?s("div",{staticClass:"ivu-description-list-title"},[t._v("备注信息")]):t._e(),s("Row",{staticClass:"mb10"},[s("Col",{staticClass:"fontColor2",attrs:{span:"12"}},[t._v(t._s(t.orderDatalist.orderInfo.mark))])],1)],1):t._e()],1)],1),s("Modal",{staticClass:"order_box2",attrs:{scrollable:"",title:"物流查询",width:"350"},model:{value:t.modal2,callback:function(e){t.modal2=e},expression:"modal2"}},[s("div",{staticClass:"logistics acea-row row-top"},[s("div",{staticClass:"logistics_img"},[s("img",{attrs:{src:r("bd9b")}})]),s("div",{staticClass:"logistics_cent"},[s("span",[t._v("物流公司："+t._s(t.orderDatalist.orderInfo.delivery_name))]),s("span",[t._v("物流单号："+t._s(t.orderDatalist.orderInfo.delivery_id))])])]),s("div",{staticClass:"acea-row row-column-around trees-coadd"},[s("div",{staticClass:"scollhide"},[s("Timeline",t._l(t.result,(function(e,r){return s("TimelineItem",{key:r},[s("p",{staticClass:"time",domProps:{textContent:t._s(e.time)}}),s("p",{staticClass:"content",domProps:{textContent:t._s(e.status)}})])})),1)],1)])])],1):t._e()},a=[],o=r("a34a"),i=r.n(o),n=r("f8b7");function l(t,e,r,s,a,o,i){try{var n=t[o](i),l=n.value}catch(d){return void r(d)}n.done?e(l):Promise.resolve(l).then(s,a)}function d(t){return function(){var e=this,r=arguments;return new Promise((function(s,a){var o=t.apply(e,r);function i(t){l(o,s,a,i,n,"next",t)}function n(t){l(o,s,a,i,n,"throw",t)}i(void 0)}))}}var c={name:"orderDetails",data:function(){return{modal2:!1,modals:!1,grid:{xl:8,lg:8,md:12,sm:24,xs:24},result:[]}},props:{orderDatalist:Object,orderId:Number},methods:{openLogistics:function(){this.getOrderData(),this.modal2=!0},getOrderData:function(){var t=this;Object(n["g"])(this.orderId).then(function(){var e=d(i.a.mark((function e(r){return i.a.wrap((function(e){while(1)switch(e.prev=e.next){case 0:t.result=r.data.result;case 1:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()).catch((function(e){t.$Message.error(e.msg)}))}},computed:{}},m=c,u=(r("42fd"),r("2877")),p=Object(u["a"])(m,s,a,!1,null,"ee2ac44a",null);e["a"]=p.exports}}]);
webpackJsonp([4],{"7jtk":function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=a("mvHQ"),s=a.n(i),o=a("xFmM"),n=a("95YI"),c=a.n(n),r=a("tOjG"),l=a("/vAn"),d=a("Fd2+"),u=a("4ecV"),h=a("mLC4"),m=(a("7+uW"),{components:{VDistpicker:c.a,newsuccess:l.a},mixins:[h.a],data:function(){return{successInfo:{},successShow:!1,addAdress:"上海",isHide:!1,show:!1,areaList:r.areaList,cascaderValue:"",juzhudiValue:"",columns:[],loading:!0,showDiagler:!1,smsCode:"",minDate:new Date(2020,0,1),maxDate:new Date(2025,10,1),currentDate:new Date(2021,0,17),idCardTypeColums:[{value:"id",text:"身份证"},{value:"passport",text:"护照号"},{value:"officer",text:"军官证号"}],columnsXcmXinghao:[{value:1,text:"是"},{value:0,text:"否"}],columnsLaiYuanDi:[{value:"301",text:"宁波市"},{value:"307",text:"绍兴市"},{value:"305",text:"杭州市"}],showPickerUploader:!1,columnsJuzhudi:[],showPicker:!1,showPicker2:!1,showPickerDate:!1,showPickerDate_fh:!1,showPicker_idCardType:!1,showPicker_juzhudi:!1,showPicker_cmXinghao:!1,fileList:[],formValute:{district_id:"14",city:"上海",card_type:"id",real_name:"",yw_street_id:"",phone:"",sms_code:"",id_card:"",isasterisk:0,card_type_text:"身份证"},formValute_text:{province:"",city:"",street:"",county:""},checked:!1,value:"",sms:"",smsIF:!1,setTime:"",columns2:[],smsdJS:60,newData:[],selectuID_FirstList:[],selectList_FirstIndex:[],selectList_SecondIndex:[],levelTwo_BySelect_GetList_Is:[],selectListSecond:[],acData_second:0,acData:0,isFirst:!1,msg:1}},created:function(){this.isFirst=!0,this.getbarrierDistrict(),this.getAreaUserApi(),this.addDate(),this.getAreaUserApi_yiwu({pid:2832})},methods:{setSuccessInfoNull:function(){o.a.set("successInfo",null),this.successInfo=null},addDate:function(){var e=new Date,t={year:e.getFullYear(),month:e.getMonth()+1,date:e.getDate()},a=t.month>10?t.month:"0"+t.month,i=t.date>=10?t.date:"0"+t.date;this.formValute.arrival_time=t.year+"-"+a+"-"+i,this.currentDate=new Date(this.formValute.arrival_time)},getOowndeclare:function(){var e=this;if(this.isFirst){var t=/^[1-9]\d{5}(18|19|20)\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/;(t.test(this.formValute.id_card)&&"id"===this.formValute.card_type||"id"!==this.formValute.card_type)&&(t.test(this.formValute.id_card)?Object(u.j)({card_type:this.formValute.card_type,real_name:this.formValute.real_name,id_card:this.formValute.id_card}).then(function(t){200===t.code&&(e.isFirst=!1,e.formValute.province_id="",e.formValute.county_id="",e.formValute.district_id="",e.formValute_text.province="",e.formValute_text.county="",e.formValute_text.city="",e.formValute.province_id=t.data.province_id,e.formValute.county_id=t.data.county_id,e.formValute.district_id=t.data.district_id,e.formValute_text.province=t.data.province,e.formValute_text.county=t.data.county,e.formValute_text.city=t.data.city)}):"id"===this.formValute.card_type?d.b.fail("请填写正确的证件号"):Object(u.j)({card_type:this.formValute.card_type,real_name:this.formValute.real_name,id_card:this.formValute.id_card}).then(function(t){200===t.code&&(e.isFirst=!1,t.data&&(e.formValute.province_id="",e.formValute.county_id="",e.formValute.district_id="",e.formValute_text.province="",e.formValute_text.county="",e.formValute_text.city="",e.formValute.province_id=t.data.province_id,e.formValute.county_id=t.data.county_id,e.formValute.district_id=t.data.district_id,e.formValute_text.province=t.data.province,e.formValute_text.county=t.data.county,e.formValute_text.city=t.data.city))}))}},getriskdistrictLiandon_ByUid:function(e,t,a){var i=this;console.log(e),Object(u.g)({pid:e}).then(function(s){switch(console.log(s.data,"大师"),t){case"province_id":i.selectList_FirstIndex=i.columns.findIndex(function(t){return t.id===e}),i.selectuID_FirstList=i.columns[i.selectList_FirstIndex],i.selectuID_FirstList.children=s.data,i.selectuID_FirstList.children.map(function(e){e.children=[]}),i.columns.splice(i.selectList_FirstIndex,1,i.selectuID_FirstList),i.cascaderValue=a;break;case"district_id":console.table(i.selectuID_FirstList.children),i.selectList_SecondIndex=i.selectuID_FirstList.children.findIndex(function(t){return t.id===e}),i.selectListSecond=i.selectuID_FirstList.children,i.selectListSecond[i.selectList_SecondIndex].children=s.data,i.levelTwo_BySelect_GetList_Is=i.selectListSecond[i.selectList_SecondIndex].children,i.columns.splice(i.selectList_FirstIndex,1,i.selectuID_FirstList),i.cascaderValue=a,i.formValute.county_id}})},onFailed:function(e){console.log("failed",e)},closeSparingContent:function(e){this.show=e},imagevalidator:function(){return!!this.formValute.image},onInput:function(e){this.formValute.isasterisk=e?1:0},getAreaUserApi_yiwu:function(e){var t=this;Object(u.g)(e).then(function(e){200===e.code&&(console.log(e.data),e.data.map(function(e){t.columnsJuzhudi.push({value:e.id,text:e.name})})),console.log(t.columnsJuzhudi)})},getbarrierDistrict:function(){var e=this;Object(u.l)().then(function(t){e.columnsLaiYuanDi=t.data})},onSubmit:function(e){var t=this.formValute;if(!t.real_name)return d.b.fail("请输入姓名"),!1;var a=new RegExp("[^a-zA-Z0-9_一-龥]","i");if(a.test(t.real_name))return console.log(t.real_name,a.test(t.real_name)),d.b.fail("姓名不能包含特殊字符"),!1;if("id"===this.formValute.card_type&&/\d+|\w+/.test(t.real_name))return d.b.fail("姓名不能包含字母或数字"),!1;if(!t.card_type)return d.b.fail("请选择证件号类别"),!1;if(!t.id_card)return d.b.fail("请输入证件号"),!1;if("id"===this.formValute.card_type){if(!/^[1-9]\d{5}(18|19|20)\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/.test(t.id_card))return d.b.fail("证件号格式错误"),!1;console.log("校验")}if(!t.phone)return d.b.fail("请输入手机号"),!1;return/^1\d{10}$/.test(t.phone)?t.sms_code?t.sms_code.length<6?(d.b.fail("验证码长度不足6位"),!1):t.district_id?t.yw_street_id?void("id"===this.formValute.card_type?(this.qgrkk({real_name:this.formValute.real_name,id_card:this.formValute.id_card}),this.setSuccessInfoNull()):(this.id_verify_result=0,this.show=!0,this.successShow=!1,this.isHide=!0,this.setSuccessInfoNull())):(d.b.fail("请选择来义居住镇街"),!1):(d.b.fail("请选择来源地"),!1):(d.b.fail("请输入验证码"),!1):(d.b.fail("手机号格式错误"),!1)},onConfirm_idCardType:function(e){console.log(e,"SA"),this.formValute.card_type=e.value,this.formValute.card_type_text=e.text,this.showPicker_idCardType=!1},onCancel_idCardType:function(){},onConfirm_LaiYuanDi:function(e){this.formValute.district_id=e.id,this.formValute.city=e.district,this.addAdress=e.district,this.showPicker=!1},onConfirm_juzhudi:function(e){this.formValute.yw_street_id=e.value,this.formValute_text.yw_street=e.text,this.showPicker_juzhudi=!1},onConfirm_cmXinghao:function(e){this.showPicker_cmXinghao=!1,this.formValute.isasterisk=e.value,this.formValute_text.isasterisk=e.text},getAreaUserApi:function(e){var t=this;Object(u.g)(e).then(function(a){if(console.log(a.data),t.loading=!1,e){var i=t;switch(e.level){case 0:t.acData=i.columns.findIndex(function(t){return t.id===e.pid}),console.log(t.acData),t.columns[t.acData].children=a.data,t.newData=JSON.parse(s()(t.columns[t.acData])),console.log(t.newData.children,"1级联动"),0!==t.newData.children.length?t.newData.children.map(function(e){e&&(e.children=[])}):t.showPicker=!1,t.newData.children.map(function(e){e&&(e.children=[])}),console.log(t.newData.children.length),t.columns.splice(t.acData,1,t.newData);break;case 1:console.log(t.newData.children,"2级联动"),t.acData_second=t.newData.children.findIndex(function(t){return t.id===e.pid}),t.newData.children[t.acData_second].children=a.data,t.columns.splice(t.acData,1,t.newData),t.newData.children[t.acData_second].children.map(function(e){e.children=[]});break;case 2:var o=t.newData.children[t.acData_second].children,n=o.findIndex(function(t){return t.id===e.pid});a.data.length>0&&(o[n].children=a.data),t.columns.splice(t.acData,1,t.newData)}}else t.columns=a.data,t.columns.map(function(e){"台湾"!==e.name&&(e.children=[])})})},getsms:function(){var e=this;Object(u.k)({prefix:"declare_verify",phone:this.formValute.phone}).then(function(t){200===t.code?(e.smsIF=!0,e.showDiagler=!0,e.djsSms(),d.b.success(t.msg)):d.b.fail(t.msg)})},djsSms:function(){var e=this;this.smsdJS=this.smsdJS-1,this.smsIF=!0,this.smsdJS<=0?(this.smsIF=!1,this.smsdJS=60,clearTimeout(this.setTime)):this.setTime=setTimeout(function(){e.djsSms()},1e3)},afterRead:function(e){var t=this;e.status="uploading",e.message="上传中...",console.log(e),Object(u.n)({file:e.content}).then(function(a){200===a.code?(console.log(a.data),t.formValute.travel_img=a.data.src,e.status="done",e.message="上传成功"):(e.status="failed",e.message="上传失败")}).catch(function(t){e.status="failed",e.message="上传失败"})},choose:function(){this.show=!this.show},goBack:function(){this.$router.back(-1)},onChangeProvince:function(e){console.log(e),this.formValute.laiyuandi.province=e},onConfirm_Date:function(e){this.showPickerDate=!1,this.formValute.arrival_time=this.$dateTimeFilters(e,"yyyy-MM-dd")},asyncValidator:function(e){return"身份证"!==this.formValute.card_type_text||/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/.test(e)},eventIfSelect:function(){this.formValute.card_type_text||d.b.fail("请先选择证件类型")},back:function(){this.$router.push({path:"/"})}}}),f={render:function(){var e=this,t=this,a=t.$createElement,i=t._self._c||a;return i("div",{staticClass:"zizhushegnbao"},[i("meta",{attrs:{name:"viewport",content:"width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"}}),t._v(" "),i("div",{directives:[{name:"show",rawName:"v-show",value:!t.show,expression:"!show"}],staticClass:"main"},[i("div",{staticClass:"title"},[i("van-button",{staticStyle:{"background-color":"#565353 !important",border:"0"},attrs:{to:"/",icon:"arrow-left",type:"primary"}}),i("span",{staticStyle:{margin:"0 auto"}},[t._v("上海来返义申报")])],1),t._v(" "),i("van-field",{staticClass:"zizhushengbaoFiled",attrs:{"label-class":"zizhushengbaoLabel",placeholder:"请输入姓名",rules:[{required:!0,message:"请填写姓名"}],label:"姓名："},model:{value:t.formValute.real_name,callback:function(e){t.$set(t.formValute,"real_name",e)},expression:"formValute.real_name"}}),t._v(" "),i("van-field",{staticClass:"zizhushengbaoFiled",attrs:{"label-class":"zizhushengbaoLabel",placeholder:"(身份证等选择)",readonly:"","right-icon":"arrow",label:"证件类型："},on:{click:function(e){t.showPicker_idCardType=!0}},model:{value:t.formValute.card_type_text,callback:function(e){t.$set(t.formValute,"card_type_text",e)},expression:"formValute.card_type_text"}}),t._v(" "),i("van-field",{staticClass:"zizhushengbaoFiled",attrs:{readonly:!t.formValute.card_type_text,"label-class":"zizhushengbaoLabel",placeholder:""===t.formValute.card_type_text?"请先选择证件号类别":"请输入正确的证件号",rules:[{validator:t.asyncValidator,message:"请输入正确的证件号"}],label:"证件号："},on:{input:function(){e.formValute.id_card=e.formValute.id_card.replace(/[^\w\.\/]/g,"")}},model:{value:t.formValute.id_card,callback:function(e){t.$set(t.formValute,"id_card",e)},expression:"formValute.id_card"}}),t._v(" "),i("van-field",{staticClass:"zizhushengbaoFiled",attrs:{"label-class":"zizhushengbaoLabel",center:"",clearable:"",type:"number",placeholder:"请输入手机号",rules:[{required:!0,message:"请填写手机号"}],label:"手机号："},model:{value:t.formValute.phone,callback:function(e){t.$set(t.formValute,"phone",e)},expression:"formValute.phone"}}),t._v(" "),i("van-field",{staticClass:"zizhushengbaoFiled",attrs:{placeholder:""===t.formValute.phone?"请先输入手机号":"请输入验证码",readonly:!t.formValute.phone,"label-class":"zizhushengbaoLabel",center:"",clearable:"",label:"验证码："},scopedSlots:t._u([{key:"button",fn:function(){return[t.smsIF?i("van-button",{staticClass:"gray",attrs:{size:"small",type:"primary","native-type":"button",disabled:""}},[t._v("获取验证码"),i("span",[t._v(t._s(t.smsdJS))])]):i("van-button",{attrs:{size:"small",type:"primary","native-type":"button"},on:{click:t.getsms}},[t._v("获取验证码")])]},proxy:!0}]),model:{value:t.formValute.sms_code,callback:function(e){t.$set(t.formValute,"sms_code",e)},expression:"formValute.sms_code"}}),t._v(" "),i("van-field",{staticClass:"zizhushengbaoFiled",attrs:{"label-class":"zizhushengbaoLabel",placeholder:"(来源地选择)",rules:[{required:!0,message:"请选择来源地"}],type:"textarea",rows:"1",autosize:"",readonly:"",label:"来源地："},on:{click:function(e){t.showPicker=!0}},model:{value:t.addAdress,callback:function(e){t.addAdress=e},expression:"addAdress"}}),t._v(" "),i("van-field",{staticClass:"zizhushengbaoFiled",attrs:{"label-class":"zizhushengbaoLabel",placeholder:"(选择)",type:"textarea",rows:"1",autosize:"",rules:[{required:!0,message:"请选择来义后居所"}],readonly:"",label:"来义后居所："},on:{click:function(e){t.showPicker_juzhudi=!0}},model:{value:t.formValute_text.yw_street,callback:function(e){t.$set(t.formValute_text,"yw_street",e)},expression:"formValute_text.yw_street"}}),t._v(" "),i("van-popup",{attrs:{round:"",position:"bottom"},model:{value:t.showPicker,callback:function(e){t.showPicker=e},expression:"showPicker"}},[i("van-picker",{attrs:{title:"请选择来源地","show-toolbar":"","value-key":"district",columns:t.columnsLaiYuanDi},on:{confirm:t.onConfirm_LaiYuanDi,cancel:function(e){t.showPicker_LaiYuanDi=!1}}})],1),t._v(" "),i("van-popup",{attrs:{round:"",position:"bottom"},model:{value:t.showPicker_juzhudi,callback:function(e){t.showPicker_juzhudi=e},expression:"showPicker_juzhudi"}},[i("van-picker",{attrs:{title:"来义后居住镇街","show-toolbar":"",columns:t.columnsJuzhudi},on:{confirm:t.onConfirm_juzhudi,cancel:function(e){t.showPicker_juzhudi=!1}}})],1),t._v(" "),i("van-popup",{attrs:{round:"",position:"bottom"},model:{value:t.showPicker_cmXinghao,callback:function(e){t.showPicker_cmXinghao=e},expression:"showPicker_cmXinghao"}},[i("van-picker",{attrs:{title:"是否行程码带星号","show-toolbar":"",columns:t.columnsXcmXinghao,"inactive-color":"#ee0a24","active-color":"#dcdee0"},on:{confirm:t.onConfirm_cmXinghao,cancel:function(e){t.showPicker_cmXinghao=!1}}})],1),t._v(" "),i("van-popup",{attrs:{round:"",position:"bottom"},model:{value:t.showPickerDate,callback:function(e){t.showPickerDate=e},expression:"showPickerDate"}},[i("van-datetime-picker",{attrs:{type:"date",title:"选择来义时间"},on:{confirm:t.onConfirm_Date,cancel:function(e){t.showPickerDate=!1}},model:{value:t.currentDate,callback:function(e){t.currentDate=e},expression:"currentDate"}})],1),t._v(" "),i("van-popup",{attrs:{round:"",position:"bottom"},model:{value:t.showPicker_idCardType,callback:function(e){t.showPicker_idCardType=e},expression:"showPicker_idCardType"}},[i("van-picker",{attrs:{title:"证件类型","show-toolbar":"",columns:t.idCardTypeColums},on:{confirm:t.onConfirm_idCardType,cancel:t.onCancel_idCardType}})],1),t._v(" "),i("div",{staticClass:"dibu"},[i("span",{staticClass:"canYouHandBaoGao"},[t._v(" 行程码是否有星号 ")]),t._v(" "),i("van-switch",{attrs:{"active-color":"red","inactive-color":"#dcdee0"},on:{input:t.onInput},model:{value:t.checked,callback:function(e){t.checked=e},expression:"checked"}}),t._v(" "),t.formValute.isasterisk?i("span",{staticClass:"xcmZt"},[t._v("是")]):i("span",{staticClass:"xcmZt"},[t._v("否")])],1),t._v(" "),i("van-button",{staticClass:"submit",attrs:{type:"primary",block:"","native-type":"submit"},on:{click:t.onSubmit}},[t._v("提交")]),t._v(" "),i("van-dialog",{attrs:{title:"点击下面方框选择行程码","show-cancel-button":""},model:{value:t.showPickerUploader,callback:function(e){t.showPickerUploader=e},expression:"showPickerUploader"}},[i("van-uploader",{attrs:{"max-count":"1","after-read":t.afterRead},model:{value:t.fileList,callback:function(e){t.fileList=e},expression:"fileList"}})],1)],1),t._v(" "),i("van-overlay",{attrs:{show:t.overlay}},[i("van-loading",{staticStyle:{position:"fixed",top:"50%",left:"50%",transform:"translate(-50%, -50%)"},attrs:{size:"24px",vertical:""}},[t._v("正在校验姓名和身份证号码")])],1),t._v(" "),t.show?i("newsuccess",{staticClass:"fixedPOstion",attrs:{dataList:t.formValute,dataList_text:t.formValute_text,addAdress:t.addAdress,fileList:t.fileList,id_verify_result:t.id_verify_result,successShow:t.successShow,successInfo:t.successInfo,getRoute:"declarationBayonet"},on:{"update:successShow":function(e){t.successShow=e},"update:success-show":function(e){t.successShow=e},"update:successInfo":function(e){t.successInfo=e},"update:success-info":function(e){t.successInfo=e},closeSparingContent:function(e){return t.closeSparingContent(e)},Back:function(e){return t.back(e)}}}):t._e()],1)},staticRenderFns:[]};var _=a("VU/8")(m,f,!1,function(e){a("y0eQ"),a("Zf+P")},"data-v-5638fb82",null);t.default=_.exports},"Zf+P":function(e,t){},y0eQ:function(e,t){}});
//# sourceMappingURL=4.921cc085ad092495552c.js.map
webpackJsonp([2],{"+dog":function(t,o,e){"use strict";Object.defineProperty(o,"__esModule",{value:!0});var i=e("Dd8w"),s=e.n(i),n=(e("ehhp"),e("NVmV")),a=e.n(n),r=e("NYxO"),l={components:{DropDown:e("y++D").a,mtLoadmore:a.a},data:function(){return{titleInfo:{title:"帖子详情",showIcon:!1},userId:123,showEditor:!0,editorType:"",navDropDown:!1,showTopDrop:!1,indexShow:-1,topic:{topicId:1,id:123,isStored:!0,title:"帖子主题帖子主题帖子主题帖子主题帖子主题帖子主题帖子主题",name:"我是谁",time:"2017-11-12",avatar:"http://221.123.178.232/smallgamesdk/Public/Uploads/20180109172657362.jpg",content:"有人说三代火影很弱，没什么招牌忍术，不要跟我说尸鬼封禁，那是旋涡一族的术。<br> 三代作为影，他没有血继限界，可以说他是靠怒力上位的。<br> 他的查克拉量应该很大，招牌术就是猿魔、手里剑影分身、一次放五种遁术、火龙岩弹。<br> 可以说三代强在查克拉量和战斗经验上。毕竟看到初代和二代不慌的能有几个。<br> 三代牛的还是活得长，得有七八十岁吧。<br>"},commentList:[{id:123,listId:1001,name:"我是谁",time:"5-12 12:30",isStored:!0,avatar:"http://221.123.178.232/smallgamesdk/Public/Uploads/20180109172657362.jpg",content:"有人说三代火影很弱，没什么招牌忍术，不要跟我说尸鬼封禁，那是旋涡一族的术。",total:13,reply:[{id:1,name:"我在哪",time:"5-12 12:30",avatar:"http://221.123.178.232/smallgamesdk/Public/Uploads/20180109172657362.jpg",content:"没什么招牌忍术",to:""},{id:23,name:"我是谁",time:"5-12 12:30",avatar:"http://221.123.178.232/smallgamesdk/Public/Uploads/20180109172657362.jpg",content:"怎么可能，会所有忍术不是盖的。猿魔也是啊",to:{id:1,name:"我在哪"}}]},{id:123,listId:1002,isStored:!1,name:"我是谁",time:"5-12 16:40",avatar:"http://221.123.178.232/smallgamesdk/Public/Uploads/20180109172657362.jpg",content:"三代火影很弱，没什么招牌忍术",total:0},{id:123,listId:1003,isStored:!1,name:"谁",time:"5-12 12:30",avatar:"http://221.123.178.232/smallgamesdk/Public/Uploads/20180109172657362.jpg",content:"有人说三代火影很弱，没什么招牌忍术，不要跟我说尸鬼封禁，那是旋涡一族的术。",total:2,reply:[{id:1,name:"我在哪",time:"5-12 12:30",avatar:"http://221.123.178.232/smallgamesdk/Public/Uploads/20180109172657362.jpg",content:"没什么招牌忍术",to:""},{id:23,name:"谁",time:"5-12 12:30",avatar:"http://221.123.178.232/smallgamesdk/Public/Uploads/20180109172657362.jpg",content:"怎么可能，会所有忍术不是盖的。猿魔也是啊",to:{id:1,name:"我在哪"}}]}],datalist:[],allLoaded:!1}},methods:s()({},Object(r.b)(["handleTitle"]),{showMenu:function(t){this.indexShow=t==this.indexShow?-1:t},_store:function(t,o){o?console.log("已收藏,执行取消收藏操作"):console.log("未收藏，执行收藏操作")},_reply:function(t){console.log("弹出填写页面，进行编辑")},replyComment:function(t,o){this.$router.push({path:"/editor",params:{type:123}})},loadTop:function(){console.log("刷新操作"),this.$refs.loadmore.onTopLoaded()},loadBottom:function(){console.log("加载更多操作"),this.allLoaded=!0,this.$refs.loadmore.onBottomLoaded()}}),mounted:function(){var t=this;this.handleTitle({title:this.titleInfo.title,showIcon:this.titleInfo.showIcon}),setTimeout(function(){t.datalist=[1,3,4]},1e3)}},d={render:function(){var t=this,o=t.$createElement,e=t._self._c||o;return e("div",{staticClass:"topic"},[e("div",{staticClass:"nav"},[e("router-link",{staticClass:"self-icon-comment-o fa-2x",attrs:{tag:"i",to:{name:"editor",params:{type:"theme"}}}}),t._v(" "),e("i",{staticClass:"self-icon-more_vert fa-2x",on:{click:function(o){t.navDropDown=!t.navDropDown}}})],1),t._v(" "),t.navDropDown?e("div",{staticClass:"mask",on:{click:function(o){o.stopPropagation(),t.navDropDown=!1}}},[e("ul",{staticClass:"drop-down"},[t.topic.isStored?e("li",[t._v("已收藏")]):e("li",[t._v("收藏")]),t._v(" "),e("li",[t._v("转发")]),t._v(" "),e("li",[t._v("倒序查看")])])]):t._e(),t._v(" "),e("mt-loadmore",{ref:"loadmore",attrs:{"top-method":t.loadTop,"bottom-method":t.loadBottom,"bottom-all-loaded":t.allLoaded}},[e("div",{staticClass:"theme"},[e("h1",[t._v(t._s(t.topic.title))]),t._v(" "),e("div",{staticClass:"theme-top"},[e("div",[e("router-link",{attrs:{to:"/center/friend/info"}},[e("img",{attrs:{src:t.topic.avatar}})])],1),t._v(" "),e("div",[e("div",{staticClass:"more",on:{click:function(o){t.showTopDrop=!t.showTopDrop}}},[e("i",{staticClass:"self-icon-more_horiz fa-lg"})]),t._v(" "),e("p",[t._v(t._s(t.topic.name))]),t._v(" "),e("p",[t._v(t._s(t.topic.time))])])]),t._v(" "),e("div",[e("drop-down",{directives:[{name:"show",rawName:"v-show",value:t.showTopDrop,expression:"showTopDrop"}],attrs:{userId:t.userId,itemId:t.topic.topicId,itemUserId:t.topic.id,isStored:t.topic.isStored},on:{store:t._store,reply:t._reply}}),t._v(" "),e("div",{staticClass:"content",domProps:{innerHTML:t._s(t.topic.content)}})],1)]),t._v(" "),e("div",{staticClass:"list"},[e("ul",t._l(t.commentList,function(o,i){return e("li",{key:i},[e("div",{staticClass:"item"},[e("div",[e("router-link",{attrs:{to:"/center/friend/info"}},[e("img",{attrs:{src:o.avatar}})])],1),t._v(" "),e("div",[e("div",[e("div",{staticClass:"more",on:{click:function(o){t.showMenu(i)}}},[e("i",{staticClass:"self-icon-more_horiz fa-lg"})]),t._v(" "),e("p",[t._v(t._s(o.name))]),t._v(" "),e("p",[t._v(t._s(o.time))])]),t._v(" "),e("div",{staticClass:"content-box"},[e("drop-down",{directives:[{name:"show",rawName:"v-show",value:t.indexShow==i,expression:"indexShow == index"}],attrs:{userId:t.userId,itemId:o.listId,itemUserId:o.id,isStored:o.isStored}}),t._v(" "),e("div",{staticClass:"content"},[e("div",{domProps:{innerHTML:t._s(o.content)}}),t._v(" "),o.total>0?e("ul",{staticClass:"reply"},[t._l(o.reply,function(o,i){return e("li",{key:i,on:{"!click":function(o){o.stopPropagation(),t.replyComment("commentId","replyUserid")}}},[e("router-link",{attrs:{to:"/center/friend/info"}},[t._v(t._s(o.name))]),t._v(":\n                                            "),e("span",[t._v("回复"),e("router-link",{attrs:{to:"/center/friend/info"}},[t._v(" "+t._s(o.name))]),t._v("：")],1),t._v("\n                                            "+t._s(o.content)+"\n                                        ")],1)}),t._v(" "),o.total>=3?e("router-link",{staticClass:"bottom",attrs:{to:{name:"forum_comment_list",params:{commentId:123}}}},[t._v("还有"+t._s(o.total-2)+"条评论 "),e("span",{staticClass:"self-icon-caret-up"})]):t._e()],2):t._e()])],1)])])])})),t._v(" "),e("ol",[e("li")])])])],1)},staticRenderFns:[]};var c=e("VU/8")(l,d,!1,function(t){e("wO1N")},"data-v-1261a12c",null);o.default=c.exports},"g/JL":function(t,o){},wO1N:function(t,o){},"y++D":function(t,o,e){"use strict";var i={name:"DropDown",props:{userId:{type:Number,default:0},itemUserId:{type:Number,default:0},itemId:{type:Number,default:0},isStored:{type:Boolean,default:!1}},methods:{_reply:function(t){this.$emit("reply",t)},_store:function(t){this.$emit("store",t)}}},s={render:function(){var t=this,o=t.$createElement,e=t._self._c||o;return e("ul",{staticClass:"forum-drop-down"},[e("li",{on:{click:function(o){t._store(t.itemId,t.isStored)}}},[t._v(t._s(t.isStored?"已收藏":"收藏"))]),t._v(" "),e("li",{on:{click:function(o){t._reply(t.itemId)}}},[t._v("回复")]),t._v(" "),t.userId==t.itemUserId?e("li",[t._v("删除")]):t._e()])},staticRenderFns:[]};var n=e("VU/8")(i,s,!1,function(t){e("g/JL")},null,null);o.a=n.exports}});
//# sourceMappingURL=2.e69aea9b761fd1171983.js.map
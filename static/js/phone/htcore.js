function HTCORE(a,c,b,d){null!=a&&(HTCORE.path=a);null!=c&&(HTCORE.tracing=c);if(void 0==d||null==d)d="";this.id="htcore_obj_"+HTCORE.count++;(void 0!=b||1==b)&&null==document.getElementById("flashSettings")&&0<arguments.length&&(document.write('<div id="flashSettings" style="width: 215px; height: 138px; position: absolute; z-index: 100;left: -500px; top: -500px">Flash Settings Dialog</div>\n'),HTCORE.settings=new HTCORE,HTCORE.settings.addFlashToElement("flashSettings",215,138,"#FFFFFF",d,!0))}
HTCORE.version="0.41";HTCORE.tracing=!1;HTCORE.count=0;HTCORE.path="htcore.swf";HTCORE.settings=null;HTCORE.prototype.id=null;HTCORE.prototype.flash=null;HTCORE.prototype.root=null;HTCORE.prototype.stage=null;
HTCORE.prototype.getHTML=function(a,c,b,d,e,f){var k=new com.deconcept.PlayerVersion([8,0,0]);if(0==com.deconcept.FlashObjectUtil.getPlayerVersion().versionIsValid(k))return"<div style='border:2px solid #FF0000'>To see this contents you need to install <a target='_blank' href='http://www.macromedia.com/go/getflashplayer'>Flash Player</a> version 8.0 or higher.</div>";d=d||"_none_";a='\r\n<object width="'+a+'" height="'+c+'" id="'+this.id+'" type="application/x-shockwave-flash" data="'+HTCORE.path+
"?callback="+d+'"';f&&(a+='style="position: absolute"');a+='>\r\n<param name="allowScriptAccess" value="sameDomain" />\r\n<param name="bgcolor" value="'+(b||"#FFFFFF")+'" />\r\n<param name="movie" value="'+HTCORE.path+"?callback="+d+'" />\r\n<param name="scale" value="noscale" />\r\n<param name="salign" value="lt" />\r\n';e&&(a+='<param name="wmode" value="transparent" />');a+="</object>";HTCORE.tracing&&(a+='<div style="border:1px solid #ddd;padding: 4px;background-color: #fafafa;font-size: 8pt;" id="htcorelogger"></div>');
return a};HTCORE.prototype.addFlashToElement=function(a,c,b,d,e,f){a="string"==typeof a?document.getElementById(a):a;c=this.getHTML(c,b,d,e,f);b=document.createElement("div");b.innerHTML=c;for(c=b.removeChild(b.firstChild);a.firstChild;)a.removeChild(a.firstChild);a.appendChild(c);return c};
HTCORE.prototype.Init=function(a,c,b,d,e,f){void 0===a&&(a="go");void 0===c&&(c=!0);void 0===b&&(b=!1);void 0===d&&(d=1);void 0===e&&(e=1);void 0===f&&(f="#ffffff");a=this.getHTML(d,e,f,a,c,b);document.write(a);HTCORE.tracing&&HTCORE.trace("HTCORE Logger initialized.");return document.getElementById(this.id)};HTCORE.prototype.getRoot=function(){null==this.root&&(this.root=new HTCORE.MovieClip(this,null,"_root"));return this.root};
HTCORE.prototype.getStage=function(){null==this.stage&&(this.stage=new HTCORE.MovieClip(this,null,"_stage"),this.stage.exposeProperty("width",this.stage),this.stage.exposeProperty("height",this.stage),this.stage.exposeProperty("scaleMode",this.stage),this.stage.exposeProperty("showMenu",this.stage),this.stage.exposeProperty("align",this.stage));return this.stage};HTCORE.prototype.getFlash=function(){null==this.flash&&(this.flash=document[this.id]);return this.flash};
HTCORE.returnsHash={"true":!0,"false":!1,undefined:void 0,"null":null,NaN:NaN};HTCORE.prototype.callFunction=function(a){var c=this.getFlash().CallFunction('<invoke name="'+a+'" returntype="javascript">'+__flash__argumentsToXML(arguments,1)+"</invoke>");HTCORE.returnsHash.hasOwnProperty(c)?c=HTCORE.returnsHash[c]:'"'==c.charAt(0)?'"'==c.charAt(c.length-1)&&(c=c.substring(1,c.length-1)):c-=0;return c};
HTCORE.prototype.storeValue=function(a,c,b,d){1==d&&(c="[JSON]"+JSON.stringify(c));return void 0==b||null==b?this.callFunction("htcoreStoreValue",[a,c]):this.callFunction("htcoreStoreValue",[a,c,b])};HTCORE.prototype.getStoredValue=function(a){a=this.callFunction("htcoreGetValue",[a]);a=a.split('\\"').join('"');a=a.split("\\'").join("'");alert(a);return"[JSON]"==a.substring(0,6)?JSON.parse(a.substring(6)):a};
HTCORE.hideFlashSettings=function(){var a=document.getElementById("flashSettings");a.style.left="-500px";a.style.top="-500px"};HTCORE.showFlashSettings=function(a,c,b){void 0==a&&(a=100);void 0==c&&(c=100);void 0==b&&(b=1);var d=document.getElementById("flashSettings");d.style.left=a+"px";d.style.top=c+"px";HTCORE.settings.callStaticFunction("System","showSettings",b)};
HTCORE.prototype.callStaticFunction=function(a,c){var b=[];b[0]=a;b[1]=c;for(var d=2;d<arguments.length;d++)b[d]=arguments[d];return this.callFunction("htcoreCallStaticFunction",b)};HTCORE.prototype.getStaticProperty=function(a,c){return this.callFunction("htcoreGetStaticProperty",[a,c])};HTCORE.prototype.attachEventListener=function(a,c,b){var d=a;void 0!=a.id&&(d=a.id);this.callFunction("htcoreAttachEventListener",[d,c,b])};
HTCORE.prototype.callBulkFunctions=function(a){for(var c=Array(a.length),b=0,d=a.length;b<d;b++)c[b]=a[b].join("\u0001");a=c.join("\u0002");this.callFunction("htcoreBulkCallFunction",a)};HTCORE.prototype.updateAfterEvent=function(){this.callFunction("htcoreUpdateAfterEvent")};HTCORE.prototype.createFlashArray=function(a){var c=new HTCORE.FlashObject(this,"Array");c.exposeFunction("push",c);c.exposeFunction("reverse",c);c.exposeProperty("length",c);for(var b=a.length,d=0;d<b;d++)c.push(a[d]);return c};
HTCORE.extend=function(a,c){var b=function(){};b.prototype=a.prototype;c.prototype=new b;c.prototype.baseConstructor=a;c.prototype.superClass=a.prototype;c.prototype._prototype=c.prototype;void 0==a.prototype.superClass&&(a.prototype.superClass=Object.prototype);return c};HTCORE.extractArgs=function(a,c){for(var b=[],d=c;d<a.length;d++)b[d-c]=a[d];return b};
HTCORE.FlashObject=function(a,c,b,d){if(0!=arguments.length){this.htcore=a;this.flash=this.htcore.getFlash();this._prototype=HTCORE.FlashObject.prototype;if(null==b||void 0==b)b=[];if(null!=c&&void 0!=c){var e=[];e[0]=c;for(i=0;i<b.length;i++){var f=b[i];void 0!=f.id&&(f="ref:"+f.id);e[i+1]=f}this.id=this.htcore.callFunction("htcoreCreateObject",e)}else null!=d&&void 0!=d&&(this.id=d)}};HTCORE.FlashObject.prototype.bound=!1;HTCORE.FlashObject.prototype.id=null;
HTCORE.FlashObject.prototype._prototype=null;HTCORE.FlashObject.prototype.htcore=null;HTCORE.FlashObject.prototype.flash=null;
HTCORE.FlashObject.prototype.callFunction=function(a){var c=[];c[0]=this.id;c[1]=a;for(i=1;i<arguments.length;i++){var b=arguments[i];null==b&&(b="null");if("string"==typeof b&&"ref:"==b.substring(0,4)){var b=b.substring(4),d=null;-1!=b.indexOf(".")&&(d=b.substring(b.indexOf(".")),b=b.substring(0,b.indexOf(".")));b="ref:"+eval(b).id;null!=d&&(b+=d)}void 0!=b.id&&(b="ref:"+b.id);c[i+1]=b}return this.htcore.callFunction("htcoreCallFunction",c)};
HTCORE.FlashObject.prototype.bind=function(a,c,b){if(null!=a&&void 0!=a)for(var d=0;d<a.length;d++)this.exposeProperty(a[d]);if(null!=c&&void 0!=c)for(a=0;a<c.length;a++)this.exposeFunction(c[a]);if(null!=b&&void 0!=b)for(c=0;c<b.length;c++)this.mapFunction(b[c])};
HTCORE.FlashObject.prototype.exposeProperty=function(a,c){var b=a.substring(0,1).toUpperCase()+a.substring(1),d=this._prototype;null!=c&&(d=c);d["get"+b]=function(){var b=this.htcore.callFunction("htcoreGetProperty",[this.id,a]);if(null==b)return null;if(void 0!=b)return"string"==typeof b?b.split("\\r").join("\r").split("\\n").join("\n"):b};d["set"+b]=function(b){this.htcore.callFunction("htcoreSetProperty",[this.id,a,b])}};
HTCORE.FlashObject.prototype.exposeFunction=function(a,c){var b=this._prototype;null!=c&&(b=c);b[a]=function(){var b=[];b[0]=this.id;b[1]=a;for(var c=0;c<arguments.length;c++)b[c+2]=arguments[c];return this.htcore.callFunction("htcoreCallFunction",b)}};
HTCORE.FlashObject.prototype.mapFunction=function(a,c){var b=this._prototype;null!=c&&(b=c);b[a]=function(){var b=[];b[0]=this.id;for(var c=0;c<arguments.length;c++){var f=arguments[c];void 0!=f.id&&(f=f.id);b[c+1]=f}c="htcore"+a.substring(0,1).toUpperCase()+a.substring(1);return this.htcore.callFunction(c,b)}};
HTCORE.MovieClip=function(a,c,b){if(0!=arguments.length){arguments.callee.prototype.baseConstructor.call(this,a,null,null,b);if(void 0==b||null==b)this.id=void 0!=c&&null!=c&&void 0!=this.flash.htcoreCreateEmptyMovieClip&&null!=this.flash.htcoreCreateEmptyMovieClip?this.htcore.callFunction("htcoreCreateEmptyMovieClip",[c]):this.htcore.callFunction("htcoreCreateEmptyMovieClip",["_root"]);0==HTCORE.MovieClip.bound&&(this.bind(HTCORE.MovieClip.movieClipProperties,HTCORE.MovieClip.movieClipFunctions,
HTCORE.MovieClip.movieClipMappings),HTCORE.MovieClip.bound=!0)}};HTCORE.extend(HTCORE.FlashObject,HTCORE.MovieClip);HTCORE.MovieClip.prototype.drawCircle=function(a,c,b){var d=Math.PI/180*45,e=b/Math.cos(d/2),f=0,k=f-d/2,l=Array(9),h=0;l[h++]=[this.id,"moveTo",a+b,c];for(var g=0;8>g;g++){var f=f+d,k=k+d,m=b*Math.cos(f),n=b*Math.sin(f),p=e*Math.cos(k),q=e*Math.sin(k);l[h++]=[this.id,"curveTo",a+p,c+q,a+m,c+n]}this.htcore.callBulkFunctions(l)};HTCORE.MovieClip.bound=!1;
HTCORE.MovieClip.movieClipProperties="_x _y _height _width _rotation _xmouse _ymouse _xscale _yscale _alpha blendMode _visible cacheAsBitmap".split(" ");HTCORE.MovieClip.movieClipFunctions="moveTo lineTo curveTo lineStyle beginFill endFill clear getURL removeMovieClip".split(" ");HTCORE.MovieClip.movieClipMappings="attachVideo createTextField addEventHandler attachBitmap applyFilter loadMovie".split(" ");
HTCORE.MovieClip.prototype.clone=function(){var a=this.htcore.callFunction("htcoreDuplicateMovieClip",[this.id]);return new HTCORE.MovieClip(this.htcore,null,a)};HTCORE.CameraClip=function(a,c){if(0!=arguments.length){arguments.callee.prototype.baseConstructor.call(this,a,c,null);if(void 0==c||null==c)c="_root";this.id=this.htcore.callFunction("htcoreCreateVideoClip",[c]);var b=this.htcore.callFunction("htcoreGetCamera");this.attachVideo(b)}};
HTCORE.CameraClip.GetCameras=function(a){return a.getFlash().htcoreGetStaticProperty(["Camera","names"])};HTCORE.extend(HTCORE.MovieClip,HTCORE.CameraClip);
HTCORE.VideoClip=function(a,c,b,d,e){if(0!=arguments.length){arguments.callee.prototype.baseConstructor.call(this,a,c,null);if(void 0==c||null==c)c="_root";this.id=this.htcore.callFunction("htcoreCreateVideoClip",[c]);var f=new HTCORE.FlashObject(this.htcore,"NetConnection");f.callFunction("connect",null);f=new HTCORE.FlashObject(this.htcore,"NetStream",[f]);f.exposeProperty("time",f);this.netStream=f;this.attachVideo(f);null!=e&&void 0!=e&&this.htcore.flash.htcoreAttachVideoStatusEvent([f.id,e]);
null!=d&&void 0!=d&&this.htcore.flash.htcoreAttachCuePointEvent([f.id,d]);f.callFunction("setBufferTime",0);f.callFunction("play",b)}};HTCORE.extend(HTCORE.MovieClip,HTCORE.VideoClip);HTCORE.VideoClip.prototype.netStream=null;HTCORE.VideoClip.GetStatusValue=function(a,c){for(var b=a.split(";"),d=[],e=0;e<b.length;e++){var f=b[e].split("=");""!=f[0]&&(d[f[0]]=f[1])}return d[c]};HTCORE.VideoClip.NetStream_Buffer_Empty="NetStream.Buffer.Empty";HTCORE.VideoClip.NetStream_Buffer_Full="NetStream.Buffer.Full";
HTCORE.VideoClip.NetStream_Buffer_Flush="NetStream.Buffer.Flush";HTCORE.VideoClip.NetStream_Play_Start="NetStream.Play.Start";HTCORE.VideoClip.NetStream_Play_Stop="NetStream.Play.Stop";HTCORE.VideoClip.NetStream_Play_StreamNotFound="NetStream.Play.StreamNotFound";HTCORE.VideoClip.NetStream_Seek_InvalidTime="NetStream.Seek.InvalidTime";HTCORE.VideoClip.NetStream_Seek_Notify="NetStream.Seek.Notify";
HTCORE.TextField=function(a,c){0!=arguments.length&&(arguments.callee.prototype.baseConstructor.call(this,a,null,c),0==HTCORE.TextField.bound&&(this.bind(HTCORE.TextField.textFieldProperties,HTCORE.TextField.textFieldFunctions),HTCORE.TextField.bound=!0))};HTCORE.extend(HTCORE.MovieClip,HTCORE.TextField);HTCORE.TextField.bound=!1;HTCORE.TextField.textFieldProperties="type multiline wordWrap text htmlText embedFonts".split(" ");HTCORE.TextField.textFieldFunctions=["setTextFormat"];
1==HTCORE.tracing&&(window.onerror=HTCORE.windowError);HTCORE.windowError=function(a,c,b){HTCORE.trace("Error on line "+b+" of document "+c+": "+a);return!0};HTCORE.trace=function(a){if(1==HTCORE.tracing){var c=document.getElementById("htcorelogger");if(null!=c){var b=document.createElement("p");b.style.margin=0;b.style.padding=0;b.style.textAlign="left";a=document.createTextNode(a);b.appendChild(a);c.appendChild(b)}}};
HTCORE.Connection=function(a,c,b,d,e){var f=a.getFlash();a=new HTCORE.FlashObject(a,"XMLSocket");f.htcoreAttachSocketEvents([a.id,b,d,e]);a.exposeFunction("connect",a);a.exposeFunction("close",a);a.exposeFunction("send",a);a.connect(c,"9000");return a};if("undefined"==typeof com){var com;com={}}"undefined"==typeof com.deconcept&&(com.deconcept={});"undefined"==typeof com.deconcept.util&&(com.deconcept.util={});"undefined"==typeof com.deconcept.FlashObjectUtil&&(com.deconcept.FlashObjectUtil={});
com.deconcept.FlashObjectUtil.getPlayerVersion=function(){var a=new com.deconcept.PlayerVersion(0,0,0);if(navigator.plugins&&navigator.mimeTypes.length){var c=navigator.plugins["Shockwave Flash"];c&&c.description&&(a=new com.deconcept.PlayerVersion(c.description.replace(/([a-z]|[A-Z]|\s)+/,"").replace(/(\s+r|\s+b[0-9]+)/,".").split(".")))}else if(window.ActiveXObject)try{c=new ActiveXObject("ShockwaveFlash.ShockwaveFlash"),a=new com.deconcept.PlayerVersion(c.GetVariable("$version").split(" ")[1].split(","))}catch(b){}return a};
com.deconcept.PlayerVersion=function(a){this.major=parseInt(a[0])||0;this.minor=parseInt(a[1])||0;this.rev=parseInt(a[2])||0};com.deconcept.PlayerVersion.prototype.versionIsValid=function(a){return this.major<a.major?!1:this.major>a.major?!0:this.minor<a.minor?!1:this.minor>a.minor?!0:this.rev<a.rev?!1:!0};Array.prototype.______array="______array";
var JSON={org:"http://www.JSON.org",copyright:"(c)2005 JSON.org",license:"http://www.crockford.com/JSON/license.html",stringify:function(a){var c,b,d,e="";switch(typeof a){case "object":if(a){if("______array"==a.______array){for(b=0;b<a.length;++b)c=this.stringify(a[b]),e&&(e+=","),e+=c;return"["+e+"]"}if("undefined"!=typeof a.toString){for(b in a)c=a[b],"undefined"!=typeof c&&"function"!=typeof c&&(c=this.stringify(c),e&&(e+=","),e+=this.stringify(b)+":"+c);return"{"+e+"}"}}return"null";case "number":return isFinite(a)?
String(a):"null";case "string":d=a.length;e='"';for(b=0;b<d;b+=1)if(c=a.charAt(b)," "<=c){if("\\"==c||'"'==c)e+="\\";e+=c}else switch(c){case "\b":e+="\\b";break;case "\f":e+="\\f";break;case "\n":e+="\\n";break;case "\r":e+="\\r";break;case "\t":e+="\\t";break;default:c=c.charCodeAt(),e+="\\u00"+Math.floor(c/16).toString(16)+(c%16).toString(16)}return e+'"';case "boolean":return String(a);default:return"null"}},parse:function(a){function c(b){throw{name:"JSONError",message:b,at:h-1,text:a};}function b(){g=
a.charAt(h);h+=1;return g}function d(){for(;""!=g&&" ">=g;)b()}function e(){var a,d="",e,f;if('"'==g)a:for(;b();){if('"'==g)return b(),d;if("\\"==g)switch(b()){case "b":d+="\b";break;case "f":d+="\f";break;case "n":d+="\n";break;case "r":d+="\r";break;case "t":d+="\t";break;case "u":for(a=f=0;4>a;a+=1){e=parseInt(b(),16);if(!isFinite(e))break a;f=16*f+e}d+=String.fromCharCode(f);break;default:d+=g}else d+=g}c("Bad string")}function f(){var a="";"-"==g&&(a="-",b());for(;"0"<=g&&"9">=g;)a+=g,b();if("."==
g)for(a+=".";b()&&"0"<=g&&"9">=g;)a+=g;if("e"==g||"E"==g){a+="e";b();if("-"==g||"+"==g)a+=g,b();for(;"0"<=g&&"9">=g;)a+=g,b()}a=+a;if(isFinite(a))return a;c("Bad number")}function k(){switch(g){case "t":if("r"==b()&&"u"==b()&&"e"==b())return b(),!0;break;case "f":if("a"==b()&&"l"==b()&&"s"==b()&&"e"==b())return b(),!1;break;case "n":if("u"==b()&&"l"==b()&&"l"==b())return b(),null}c("Syntax error")}function l(){d();switch(g){case "{":var a;a:{var h={};if("{"==g){b();d();if("}"==g){b();a=h;break a}for(;g;){a=
e();d();if(":"!=g)break;b();h[a]=l();d();if("}"==g){b();a=h;break a}else if(","!=g)break;b();d()}}c("Bad object");a=void 0}return a;case "[":a:{a=[];if("["==g){b();d();if("]"==g){b();break a}for(;g;){a.push(l());d();if("]"==g){b();break a}else if(","!=g)break;b();d()}}c("Bad array");a=void 0}return a;case '"':return e();case "-":return f();default:return"0"<=g&&"9">=g?f():k()}}var h=0,g=" ";return l()}};
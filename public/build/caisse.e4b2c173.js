(self.webpackChunk=self.webpackChunk||[]).push([[93],{9918:(t,e,r)=>{r(9554),r(1539),r(4747),r(4678),r(1058),r(6977);var n=document.querySelectorAll(".onglets"),o=document.querySelectorAll(".contenu"),a=0;n.forEach((function(t){t.addEventListener("click",(function(){if(!t.classList.contains("active")){for(t.classList.add("active"),a=t.getAttribute("data-anim"),i=0;i<n.length;i++)n[i].getAttribute("data-anim")!=a&&n[i].classList.remove("active");for(j=0;j<o.length;j++)o[j].getAttribute("data-anim")==a?o[j].classList.add("activeContenu"):o[j].classList.remove("activeContenu")}}))}));var c=document.querySelectorAll(".content input"),u=document.querySelector(".autre .center input"),s=document.querySelector("#nb_noir_blanc"),l=document.querySelector("#nb_couleurs");function f(){var t="",e=document.querySelectorAll(".content input"),r=0;e.forEach((function(e){var n=e.value,o=e.getAttribute("dataprice"),a=e.getAttribute("dataname"),i=e.getAttribute("datacat");(""===n||parseFloat(n)<0)&&(n=0),n>0&&(t=t+"<p>"+n+" "+i+" "+a+" ("+o+"€) = <span>"+n*o+"€</span></p>"),(r+=o*n)<0&&(r=0)}));var n,o=document.querySelector(".autre .center input");n=parseFloat(o.value),isNaN(n)&&(n=0),n>0&&(t=t+"<p>Divers services Xtra-copy = <span>"+n+"€</span></p>");var a=document.querySelectorAll(".data-nb"),i=document.querySelectorAll(".data-color"),c=document.querySelector("#nb_noir_blanc"),u=document.querySelector("#nb_couleurs"),s=parseInt(c.value),l=parseInt(u.value),f=0,p=0;a.forEach((function(e){var r=parseInt(e.getAttribute("data-start")),n=parseInt(e.getAttribute("data-end")),o=parseFloat(e.getAttribute("data-price"));s>=r&&s<=n&&(t=t+"<p>Nombre de copies N/B est de : "+s+" * ("+(p=o)+"€) = <span>"+(s*p).toFixed(2)+"€</span></p>")})),i.forEach((function(e){var r=parseInt(e.getAttribute("data-start")),n=parseInt(e.getAttribute("data-end")),o=parseFloat(e.getAttribute("data-price"));l>=r&&l<=n&&(t=t+"<p>Nombre de copies couleur est de : "+l+" * ("+(f=o)+"€) = <span>"+(l*f).toFixed(2)+"€</span></p>")})),r=r+n+(s*p+l*f);var v=document.querySelector(".total p span"),d=r.toFixed(2);isNaN(d)&&(d=0),v.innerHTML=d,document.querySelector("#affichage").innerHTML=t,document.querySelector("#invoice_form_texte").value=t,document.querySelector("#invoice_form_total").value=r;var m=document.querySelector(".send");m.disabled=!0,d>0&&(m.disabled=!1)}s.addEventListener("input",(function(){s.value<0&&(s.value=0),f()})),l.addEventListener("input",(function(){l.value<0&&(l.value=0),f()})),u.addEventListener("input",(function(){u.value<0&&(u.value=0),f()})),c.forEach((function(t){t.addEventListener("input",(function(){t.value<0&&(t.value=0),f()}))})),document.addEventListener("DOMContentLoaded",(function(){f()}))},8533:(t,e,r)=>{"use strict";var n=r(2092).forEach,o=r(9341)("forEach");t.exports=o?[].forEach:function(t){return n(this,t,arguments.length>1?arguments[1]:void 0)}},2092:(t,e,r)=>{var n=r(9974),o=r(1702),a=r(8361),i=r(7908),c=r(6244),u=r(5417),s=o([].push),l=function(t){var e=1==t,r=2==t,o=3==t,l=4==t,f=6==t,p=7==t,v=5==t||f;return function(d,m,g,S){for(var b,y,h=i(d),L=a(h),x=n(m,g),A=c(L),E=0,q=S||u,F=e?q(d,A):r||p?q(d,0):void 0;A>E;E++)if((v||E in L)&&(y=x(b=L[E],E,h),t))if(e)F[E]=y;else if(y)switch(t){case 3:return!0;case 5:return b;case 6:return E;case 2:s(F,b)}else switch(t){case 4:return!1;case 7:s(F,b)}return f?-1:o||l?l:F}};t.exports={forEach:l(0),map:l(1),filter:l(2),some:l(3),every:l(4),find:l(5),findIndex:l(6),filterReject:l(7)}},9341:(t,e,r)=>{"use strict";var n=r(7293);t.exports=function(t,e){var r=[][t];return!!r&&n((function(){r.call(null,e||function(){return 1},1)}))}},7475:(t,e,r)=>{var n=r(3157),o=r(4411),a=r(111),i=r(5112)("species"),c=Array;t.exports=function(t){var e;return n(t)&&(e=t.constructor,(o(e)&&(e===c||n(e.prototype))||a(e)&&null===(e=e[i]))&&(e=void 0)),void 0===e?c:e}},5417:(t,e,r)=>{var n=r(7475);t.exports=function(t,e){return new(n(t))(0===e?0:e)}},648:(t,e,r)=>{var n=r(1694),o=r(614),a=r(4326),i=r(5112)("toStringTag"),c=Object,u="Arguments"==a(function(){return arguments}());t.exports=n?a:function(t){var e,r,n;return void 0===t?"Undefined":null===t?"Null":"string"==typeof(r=function(t,e){try{return t[e]}catch(t){}}(e=c(t),i))?r:u?a(e):"Object"==(n=a(e))&&o(e.callee)?"Arguments":n}},8324:t=>{t.exports={CSSRuleList:0,CSSStyleDeclaration:0,CSSValueList:0,ClientRectList:0,DOMRectList:0,DOMStringList:0,DOMTokenList:1,DataTransferItemList:0,FileList:0,HTMLAllCollection:0,HTMLCollection:0,HTMLFormElement:0,HTMLSelectElement:0,MediaList:0,MimeTypeArray:0,NamedNodeMap:0,NodeList:1,PaintRequestList:0,Plugin:0,PluginArray:0,SVGLengthList:0,SVGNumberList:0,SVGPathSegList:0,SVGPointList:0,SVGStringList:0,SVGTransformList:0,SourceBufferList:0,StyleSheetList:0,TextTrackCueList:0,TextTrackList:0,TouchList:0}},8509:(t,e,r)=>{var n=r(317)("span").classList,o=n&&n.constructor&&n.constructor.prototype;t.exports=o===Object.prototype?void 0:o},9974:(t,e,r)=>{var n=r(1470),o=r(9662),a=r(4374),i=n(n.bind);t.exports=function(t,e){return o(t),void 0===e?t:a?i(t,e):function(){return t.apply(e,arguments)}}},1470:(t,e,r)=>{var n=r(4326),o=r(1702);t.exports=function(t){if("Function"===n(t))return o(t)}},3157:(t,e,r)=>{var n=r(4326);t.exports=Array.isArray||function(t){return"Array"==n(t)}},4411:(t,e,r)=>{var n=r(1702),o=r(7293),a=r(614),i=r(648),c=r(5005),u=r(2788),s=function(){},l=[],f=c("Reflect","construct"),p=/^\s*(?:class|function)\b/,v=n(p.exec),d=!p.exec(s),m=function(t){if(!a(t))return!1;try{return f(s,l,t),!0}catch(t){return!1}},g=function(t){if(!a(t))return!1;switch(i(t)){case"AsyncFunction":case"GeneratorFunction":case"AsyncGeneratorFunction":return!1}try{return d||!!v(p,u(t))}catch(t){return!0}};g.sham=!0,t.exports=!f||o((function(){var t;return m(m.call)||!m(Object)||!m((function(){t=!0}))||t}))?g:m},2814:(t,e,r)=>{var n=r(7854),o=r(7293),a=r(1702),i=r(1340),c=r(3111).trim,u=r(1361),s=a("".charAt),l=n.parseFloat,f=n.Symbol,p=f&&f.iterator,v=1/l(u+"-0")!=-1/0||p&&!o((function(){l(Object(p))}));t.exports=v?function(t){var e=c(i(t)),r=l(e);return 0===r&&"-"==s(e,0)?-0:r}:l},3009:(t,e,r)=>{var n=r(7854),o=r(7293),a=r(1702),i=r(1340),c=r(3111).trim,u=r(1361),s=n.parseInt,l=n.Symbol,f=l&&l.iterator,p=/^[+-]?0x/i,v=a(p.exec),d=8!==s(u+"08")||22!==s(u+"0x16")||f&&!o((function(){s(Object(f))}));t.exports=d?function(t,e){var r=c(i(t));return s(r,e>>>0||(v(p,r)?16:10))}:s},288:(t,e,r)=>{"use strict";var n=r(1694),o=r(648);t.exports=n?{}.toString:function(){return"[object "+o(this)+"]"}},8415:(t,e,r)=>{"use strict";var n=r(9303),o=r(1340),a=r(4488),i=RangeError;t.exports=function(t){var e=o(a(this)),r="",c=n(t);if(c<0||c==1/0)throw i("Wrong number of repetitions");for(;c>0;(c>>>=1)&&(e+=e))1&c&&(r+=e);return r}},3111:(t,e,r)=>{var n=r(1702),o=r(4488),a=r(1340),i=r(1361),c=n("".replace),u=RegExp("^["+i+"]+"),s=RegExp("(^|[^"+i+"])["+i+"]+$"),l=function(t){return function(e){var r=a(o(e));return 1&t&&(r=c(r,u,"")),2&t&&(r=c(r,s,"$1")),r}};t.exports={start:l(1),end:l(2),trim:l(3)}},863:(t,e,r)=>{var n=r(1702);t.exports=n(1..valueOf)},1694:(t,e,r)=>{var n={};n[r(5112)("toStringTag")]="z",t.exports="[object z]"===String(n)},1340:(t,e,r)=>{var n=r(648),o=String;t.exports=function(t){if("Symbol"===n(t))throw TypeError("Cannot convert a Symbol value to a string");return o(t)}},1361:t=>{t.exports="\t\n\v\f\r                　\u2028\u2029\ufeff"},9554:(t,e,r)=>{"use strict";var n=r(2109),o=r(8533);n({target:"Array",proto:!0,forced:[].forEach!=o},{forEach:o})},6977:(t,e,r)=>{"use strict";var n=r(2109),o=r(1702),a=r(9303),i=r(863),c=r(8415),u=r(7293),s=RangeError,l=String,f=Math.floor,p=o(c),v=o("".slice),d=o(1..toFixed),m=function(t,e,r){return 0===e?r:e%2==1?m(t,e-1,r*t):m(t*t,e/2,r)},g=function(t,e,r){for(var n=-1,o=r;++n<6;)o+=e*t[n],t[n]=o%1e7,o=f(o/1e7)},S=function(t,e){for(var r=6,n=0;--r>=0;)n+=t[r],t[r]=f(n/e),n=n%e*1e7},b=function(t){for(var e=6,r="";--e>=0;)if(""!==r||0===e||0!==t[e]){var n=l(t[e]);r=""===r?n:r+p("0",7-n.length)+n}return r};n({target:"Number",proto:!0,forced:u((function(){return"0.000"!==d(8e-5,3)||"1"!==d(.9,0)||"1.25"!==d(1.255,2)||"1000000000000000128"!==d(0xde0b6b3a7640080,0)}))||!u((function(){d({})}))},{toFixed:function(t){var e,r,n,o,c=i(this),u=a(t),f=[0,0,0,0,0,0],d="",y="0";if(u<0||u>20)throw s("Incorrect fraction digits");if(c!=c)return"NaN";if(c<=-1e21||c>=1e21)return l(c);if(c<0&&(d="-",c=-c),c>1e-21)if(r=(e=function(t){for(var e=0,r=t;r>=4096;)e+=12,r/=4096;for(;r>=2;)e+=1,r/=2;return e}(c*m(2,69,1))-69)<0?c*m(2,-e,1):c/m(2,e,1),r*=4503599627370496,(e=52-e)>0){for(g(f,0,r),n=u;n>=7;)g(f,1e7,0),n-=7;for(g(f,m(10,n,1),0),n=e-1;n>=23;)S(f,1<<23),n-=23;S(f,1<<n),g(f,1,1),S(f,2),y=b(f)}else g(f,0,r),g(f,1<<-e,0),y=b(f)+p("0",u);return y=u>0?d+((o=y.length)<=u?"0."+p("0",u-o)+y:v(y,0,o-u)+"."+v(y,o-u)):d+y}})},1539:(t,e,r)=>{var n=r(1694),o=r(8052),a=r(288);n||o(Object.prototype,"toString",a,{unsafe:!0})},4678:(t,e,r)=>{var n=r(2109),o=r(2814);n({global:!0,forced:parseFloat!=o},{parseFloat:o})},1058:(t,e,r)=>{var n=r(2109),o=r(3009);n({global:!0,forced:parseInt!=o},{parseInt:o})},4747:(t,e,r)=>{var n=r(7854),o=r(8324),a=r(8509),i=r(8533),c=r(8880),u=function(t){if(t&&t.forEach!==i)try{c(t,"forEach",i)}catch(e){t.forEach=i}};for(var s in o)o[s]&&u(n[s]&&n[s].prototype);u(a)}},t=>{t.O(0,[109],(()=>{return e=9918,t(t.s=e);var e}));t.O()}]);
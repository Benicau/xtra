(self.webpackChunk=self.webpackChunk||[]).push([[765],{6986:(e,t,n)=>{n(2564),document.addEventListener("DOMContentLoaded",(function(){var e=[],t=[],n=0;function o(){for(var n=0;n<2;n++)e[n]=Math.floor(100*Math.random()),t[n]=Math.floor(100*Math.random()),e[n]>80&&(e[n]=80),t[n]>80&&(t[n]=80),e[n]<20&&(e[n]=20),t[n]<20&&(t[n]=20);!function(){for(var n=0;n<2;n++)$(".layer"+n).animate({left:e[n]+"%",top:t[n]+"%"},1e4,o)}()}setInterval((function(){!function(){n++;for(var e=0;e<2;e++){var t="rotate("+n+"deg)";$(".layer"+e).css("transform",t)}360==n&&(n=0)}()}),20),o()}));var o=document.querySelectorAll(".service"),r=0;function s(e){for(var t=0;t<o.length;t++)o[t].classList.add("none");o[e].classList.remove("none"),o[e].style.opacity=0,setTimeout((function(){o[e].style.opacity=1}),200),r=e}s(r),setInterval((function(){s(r=(r+1)%o.length)}),1e4);var l=document.querySelector(".menuBurger"),a=document.querySelector(".MenuNavResponsive");l.onclick=function(){l.classList.toggle("open"),a.classList.toggle("openSlide")},document.querySelector(".MenuNavResponsive ul").onclick=function(){l.classList.toggle("open"),a.classList.toggle("openSlide")},document.querySelector(".logo").onclick=function(){l.classList.toggle("open"),a.classList.toggle("openSlide")}},206:(e,t,n)=>{var o=n(1702);e.exports=o([].slice)},9363:e=>{e.exports="function"==typeof Bun&&Bun&&"string"==typeof Bun.version},2104:(e,t,n)=>{var o=n(4374),r=Function.prototype,s=r.apply,l=r.call;e.exports="object"==typeof Reflect&&Reflect.apply||(o?l.bind(s):function(){return l.apply(s,arguments)})},7152:(e,t,n)=>{"use strict";var o,r=n(7854),s=n(2104),l=n(614),a=n(9363),c=n(8113),i=n(206),u=n(8053),f=r.Function,p=/MSIE .\./.test(c)||a&&((o=r.Bun.version.split(".")).length<3||0==o[0]&&(o[1]<3||3==o[1]&&0==o[2]));e.exports=function(e,t){var n=t?2:1;return p?function(o,r){var a=u(arguments.length,1)>n,c=l(o)?o:f(o),p=a?i(arguments,n):[],v=a?function(){s(c,this,p)}:c;return t?e(v,r):e(v)}:e}},8053:e=>{var t=TypeError;e.exports=function(e,n){if(e<n)throw t("Not enough arguments");return e}},6815:(e,t,n)=>{var o=n(2109),r=n(7854),s=n(7152)(r.setInterval,!0);o({global:!0,bind:!0,forced:r.setInterval!==s},{setInterval:s})},8417:(e,t,n)=>{var o=n(2109),r=n(7854),s=n(7152)(r.setTimeout,!0);o({global:!0,bind:!0,forced:r.setTimeout!==s},{setTimeout:s})},2564:(e,t,n)=>{n(6815),n(8417)}},e=>{e.O(0,[109],(()=>{return t=6986,e(e.s=t);var t}));e.O()}]);
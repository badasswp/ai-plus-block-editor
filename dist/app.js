(()=>{"use strict";var t={20:(t,e,n)=>{var o=n(609),r=Symbol.for("react.element"),a=Symbol.for("react.fragment"),i=Object.prototype.hasOwnProperty,s=o.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,c={key:!0,ref:!0,__self:!0,__source:!0};function d(t,e,n){var o,a={},d=null,l=null;for(o in void 0!==n&&(d=""+n),void 0!==e.key&&(d=""+e.key),void 0!==e.ref&&(l=e.ref),e)i.call(e,o)&&!c.hasOwnProperty(o)&&(a[o]=e[o]);if(t&&t.defaultProps)for(o in e=t.defaultProps)void 0===a[o]&&(a[o]=e[o]);return{$$typeof:r,type:t,key:d,ref:l,props:a,_owner:s.current}}e.Fragment=a,e.jsx=d,e.jsxs=d},56:(t,e,n)=>{t.exports=function(t){var e=n.nc;e&&t.setAttribute("nonce",e)}},72:t=>{var e=[];function n(t){for(var n=-1,o=0;o<e.length;o++)if(e[o].identifier===t){n=o;break}return n}function o(t,o){for(var a={},i=[],s=0;s<t.length;s++){var c=t[s],d=o.base?c[0]+o.base:c[0],l=a[d]||0,p="".concat(d," ").concat(l);a[d]=l+1;var u=n(p),A={css:c[1],media:c[2],sourceMap:c[3],supports:c[4],layer:c[5]};if(-1!==u)e[u].references++,e[u].updater(A);else{var f=r(A,o);o.byIndex=s,e.splice(s,0,{identifier:p,updater:f,references:1})}i.push(p)}return i}function r(t,e){var n=e.domAPI(e);return n.update(t),function(e){if(e){if(e.css===t.css&&e.media===t.media&&e.sourceMap===t.sourceMap&&e.supports===t.supports&&e.layer===t.layer)return;n.update(t=e)}else n.remove()}}t.exports=function(t,r){var a=o(t=t||[],r=r||{});return function(t){t=t||[];for(var i=0;i<a.length;i++){var s=n(a[i]);e[s].references--}for(var c=o(t,r),d=0;d<a.length;d++){var l=n(a[d]);0===e[l].references&&(e[l].updater(),e.splice(l,1))}a=c}}},87:t=>{t.exports=window.wp.element},100:(t,e,n)=>{n.d(e,{A:()=>s});var o=n(354),r=n.n(o),a=n(314),i=n.n(a)()(r());i.push([t.id,".apbe-toast{position:fixed;background:#000;color:#fff;bottom:-50px;opacity:0;left:15px;padding:20px;border-radius:5px;font-size:13px;z-index:1;box-shadow:0 0 15px rgba(0,0,0,.1);animation:slideUp .5s ease-out forwards}.apbe-sidebar p{margin-top:0 !important;margin-bottom:7.5px !important}.apbe-sidebar button{height:auto;padding:11.5px 15px;justify-content:center}.apbe-sidebar ul{margin:0;padding:0;display:flex;flex-direction:column}.apbe-sidebar ul li{padding:0 0 22.5px 0;margin:0}.apbe-sidebar ul li:first-child{padding-top:0}.apbe-button-group{display:flex;justify-content:space-between;margin-top:8.5px}.apbe-button-group button{height:38px;border-radius:3px}.apbe-button-group button.is-secondary{padding-left:9px;padding-right:9px}@keyframes slideUp{to{bottom:40px;opacity:1}}","",{version:3,sources:["webpack://./src/styles/app.scss"],names:[],mappings:"AACC,YACC,cAAA,CACA,eAAA,CACA,UAAA,CACA,YAAA,CACA,SAAA,CACA,SAAA,CACA,YAAA,CACA,iBAAA,CACA,cAAA,CACA,SAAA,CACA,kCAAA,CACE,uCAAA,CAIF,gBACC,uBAAA,CACA,8BAAA,CAGD,qBACC,WAAA,CACA,mBAAA,CACA,sBAAA,CAGD,iBACC,QAAA,CACA,SAAA,CACA,YAAA,CACA,qBAAA,CAEA,oBACC,oBAAA,CACA,QAAA,CAEA,gCACC,aAAA,CAOH,mBACC,YAAA,CACA,6BAAA,CACA,gBAAA,CAEA,0BACC,WAAA,CACA,iBAAA,CAEA,uCACC,gBAAA,CACA,iBAAA,CAOL,mBACE,GACD,WAAA,CACG,SAAA,CAAA",sourcesContent:[".apbe {\n\t&-toast {\n\t\tposition: fixed;\n\t\tbackground: #000;\n\t\tcolor: #FFF;\n\t\tbottom: -50px;\n\t\topacity: 0;\n\t\tleft: 15px;\n\t\tpadding: 20px;\n\t\tborder-radius: 5px;\n\t\tfont-size: 13px;\n\t\tz-index: 1;\n\t\tbox-shadow: 0 0 15px rgba(0, 0, 0, 0.1);\n  \t\tanimation: slideUp 0.5s ease-out forwards;\n\t}\n\n\t&-sidebar {\n\t\tp {\n\t\t\tmargin-top: 0 !important;\n\t\t\tmargin-bottom: 7.5px !important;\n\t\t}\n\n\t\tbutton {\n\t\t\theight: auto;\n\t\t\tpadding: 11.5px 15px;\n\t\t\tjustify-content: center;\n\t\t}\n\n\t\tul {\n\t\t\tmargin: 0;\n\t\t\tpadding: 0;\n\t\t\tdisplay: flex;\n\t\t\tflex-direction: column;\n\n\t\t\tli {\n\t\t\t\tpadding: 0 0 22.5px 0;\n\t\t\t\tmargin: 0;\n\n\t\t\t\t&:first-child {\n\t\t\t\t\tpadding-top: 0;\n\t\t\t\t}\n\t\t\t}\n\t\t}\n\t}\n\n\t&-button {\n\t\t&-group {\n\t\t\tdisplay: flex;\n\t\t\tjustify-content: space-between;\n\t\t\tmargin-top: 8.5px;\n\n\t\t\tbutton {\n\t\t\t\theight: 38px;\n\t\t\t\tborder-radius: 3px;\n\n\t\t\t\t&.is-secondary {\n\t\t\t\t\tpadding-left: 9px;\n\t\t\t\t\tpadding-right: 9px;\n\t\t\t\t}\n\t\t\t}\n\t\t}\n\t}\n}\n\n@keyframes slideUp {\n  to {\n\tbottom: 40px;\n    opacity: 1;\n  }\n}\n"],sourceRoot:""}]);const s=i},105:(t,e,n)=>{n.d(e,{l:()=>a});var o=n(723),r=n(619);const a=t=>{const e=[],n={casual:(0,o.__)("Use Casual Tone","ai-plus-block-editor"),official:(0,o.__)("Use Official Tone","ai-plus-block-editor"),descriptive:(0,o.__)("Use Descriptive Tone","ai-plus-block-editor"),narrative:(0,o.__)("Use Narrative Tone","ai-plus-block-editor"),aggressive:(0,o.__)("Use Aggressive Tone","ai-plus-block-editor")};return Object.keys(n).forEach((o=>{e.push({title:n[o],onClick:()=>{t(o)}})})),(0,r.applyFilters)("apbe.blockControlOptions",e)}},113:t=>{t.exports=function(t,e){if(e.styleSheet)e.styleSheet.cssText=t;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(t))}}},143:t=>{t.exports=window.wp.data},213:(t,e,n)=>{var o=n(848),r=n(723),a=n(830),i=n(619),s=n(715),c=n(692),d=n(143),l=n(87),p=n(427),u=n(455),A=n.n(u),f=n(231),h=n(105),x=(n(533),function(t,e,n,o){return new(n||(n=Promise))((function(r,a){function i(t){try{c(o.next(t))}catch(t){a(t)}}function s(t){try{c(o.throw(t))}catch(t){a(t)}}function c(t){var e;t.done?r(t.value):(e=t.value,e instanceof n?e:new n((function(t){t(e)}))).then(i,s)}c((o=o.apply(t,e||[])).next())}))});(0,i.addFilter)("blocks.registerBlockType","apbe/ai",(t=>{const{name:e,edit:n}=t;return"core/paragraph"!==e||(t.edit=t=>{const[e,i]=(0,l.useState)(""),[u,g]=(0,l.useState)(!1);return(0,l.useEffect)((()=>{var t;e&&(t=e,x(void 0,void 0,void 0,(function*(){const{getCurrentPostId:e}=(0,d.select)("core/editor"),{createErrorNotice:n}=(0,d.useDispatch)(c.store),{updateBlockAttributes:o}=(0,d.dispatch)("core/block-editor"),{getSelectedBlock:r,getSelectedBlockClientId:a}=(0,d.select)("core/block-editor"),{content:i}=r().attributes;g(!0);try{const n=yield A()({path:"/ai-plus-block-editor/v1/tone",method:"POST",data:{id:e(),text:i.text||i,newTone:t}});let r=1;const s=setInterval((()=>{n.length===r&&clearInterval(s),o(a(),{content:n.substring(0,r)}),r++}),5);g(!1)}catch(t){n(t.message)}})))}),[e]),(0,o.jsxs)(l.Fragment,{children:[(0,o.jsx)(f.A,{message:(0,r.__)("AI is generating text, please hold on for a bit…"),isLoading:u}),(0,o.jsx)(s.BlockControls,{children:(0,o.jsx)(p.ToolbarGroup,{children:(0,o.jsx)(p.ToolbarDropdownMenu,{icon:a.A,label:(0,r.__)("AI + Block Editor"),controls:(0,h.l)(i)})})}),n(t)]})}),t}))},231:(t,e,n)=>{n.d(e,{A:()=>r});var o=n(848);const r=({isLoading:t,message:e})=>t&&(0,o.jsx)("div",{className:"apbe-toast",role:"alert",children:(0,o.jsx)("span",{children:e})})},258:(t,e,n)=>{n.d(e,{A:()=>A});var o=n(848),r=n(723),a=n(351),i=n(87),s=n(692),c=n(427),d=n(143),l=n(455),p=n.n(l),u=n(231);const A=()=>{const[t,e]=(0,i.useState)(""),[n,l]=(0,i.useState)(!1),{editPost:A,savePost:f}=(0,d.dispatch)("core/editor"),{createErrorNotice:h,removeNotice:x}=(0,d.useDispatch)(s.store),{getCurrentPostId:g,getEditedPostContent:v,getEditedPostAttribute:m}=(0,d.select)("core/editor"),b=v(),C=(0,d.useSelect)((t=>t(s.store).getNotices()),[]);return(0,i.useEffect)((()=>{e(m("meta").apbe_summary)}),[m]),(0,o.jsxs)(o.Fragment,{children:[(0,o.jsx)("p",{children:(0,o.jsx)("strong",{children:(0,r.__)("Summary","ai-plus-block-editor")})}),(0,o.jsx)(c.TextareaControl,{rows:4,value:t,onChange:t=>e(t),__nextHasNoMarginBottom:!0}),(0,o.jsxs)("div",{className:"apbe-button-group",children:[(0,o.jsx)(c.Button,{variant:"primary",onClick:()=>{return t=void 0,n=void 0,r=function*(){C.forEach((t=>x(t.id))),l(!0);try{const t=yield p()({path:"/ai-plus-block-editor/v1/sidebar",method:"POST",data:{id:g(),text:b.text||b,feature:"summary"}});(()=>{let n=1;return new Promise((o=>{const r=setInterval((()=>{t.length===n&&(clearInterval(r),o(t)),e(t.substring(0,n)),n++}),5)}))})().then((t=>{A({excerpt:t}),A({meta:{apbe_summary:t}})})),l(!1)}catch(t){h(t.message),l(!1)}},new((o=void 0)||(o=Promise))((function(e,a){function i(t){try{c(r.next(t))}catch(t){a(t)}}function s(t){try{c(r.throw(t))}catch(t){a(t)}}function c(t){var n;t.done?e(t.value):(n=t.value,n instanceof o?n:new o((function(t){t(n)}))).then(i,s)}c((r=r.apply(t,n||[])).next())}));var t,n,o,r},children:(0,r.__)("Generate","ai-plus-block-editor")}),(0,o.jsx)(c.Button,{variant:"secondary",onClick:()=>{A({excerpt:t}),A({meta:{apbe_summary:t}}),f()},children:(0,o.jsx)(c.Icon,{icon:a.A})})]}),(0,o.jsx)(u.A,{message:(0,r.__)("AI is generating text, please hold on for a bit…"),isLoading:n})]})}},279:t=>{t.exports=window.wp.plugins},309:t=>{t.exports=window.wp.editPost},314:t=>{t.exports=function(t){var e=[];return e.toString=function(){return this.map((function(e){var n="",o=void 0!==e[5];return e[4]&&(n+="@supports (".concat(e[4],") {")),e[2]&&(n+="@media ".concat(e[2]," {")),o&&(n+="@layer".concat(e[5].length>0?" ".concat(e[5]):""," {")),n+=t(e),o&&(n+="}"),e[2]&&(n+="}"),e[4]&&(n+="}"),n})).join("")},e.i=function(t,n,o,r,a){"string"==typeof t&&(t=[[null,t,void 0]]);var i={};if(o)for(var s=0;s<this.length;s++){var c=this[s][0];null!=c&&(i[c]=!0)}for(var d=0;d<t.length;d++){var l=[].concat(t[d]);o&&i[l[0]]||(void 0!==a&&(void 0===l[5]||(l[1]="@layer".concat(l[5].length>0?" ".concat(l[5]):""," {").concat(l[1],"}")),l[5]=a),n&&(l[2]?(l[1]="@media ".concat(l[2]," {").concat(l[1],"}"),l[2]=n):l[2]=n),r&&(l[4]?(l[1]="@supports (".concat(l[4],") {").concat(l[1],"}"),l[4]=r):l[4]="".concat(r)),e.push(l))}},e}},351:(t,e,n)=>{n.d(e,{A:()=>a});var o=n(609),r=n(573);const a=(0,o.createElement)(r.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,o.createElement)(r.Path,{d:"M16.7 7.1l-6.3 8.5-3.3-2.5-.9 1.2 4.5 3.4L17.9 8z"}))},354:t=>{t.exports=function(t){var e=t[1],n=t[3];if(!n)return e;if("function"==typeof btoa){var o=btoa(unescape(encodeURIComponent(JSON.stringify(n)))),r="sourceMappingURL=data:application/json;charset=utf-8;base64,".concat(o),a="/*# ".concat(r," */");return[e].concat([a]).join("\n")}return[e].join("\n")}},427:t=>{t.exports=window.wp.components},455:t=>{t.exports=window.wp.apiFetch},457:(t,e,n)=>{n.d(e,{A:()=>A});var o=n(848),r=n(723),a=n(351),i=n(87),s=n(692),c=n(427),d=n(143),l=n(455),p=n.n(l),u=n(231);const A=()=>{const[t,e]=(0,i.useState)(""),[n,l]=(0,i.useState)(!1),{editPost:A,savePost:f}=(0,d.dispatch)("core/editor"),{createErrorNotice:h,removeNotice:x}=(0,d.useDispatch)(s.store),{getCurrentPostId:g,getEditedPostContent:v,getEditedPostAttribute:m}=(0,d.select)("core/editor"),b=v(),C=(0,d.useSelect)((t=>t(s.store).getNotices()),[]);return(0,i.useEffect)((()=>{e(m("meta").apbe_slug)}),[m]),(0,o.jsxs)(o.Fragment,{children:[(0,o.jsx)("p",{children:(0,o.jsx)("strong",{children:(0,r.__)("Slug","ai-plus-block-editor")})}),(0,o.jsx)(c.TextControl,{placeholder:"your-article-slug",value:t,onChange:t=>e(t),__nextHasNoMarginBottom:!0}),(0,o.jsxs)("div",{className:"apbe-button-group",children:[(0,o.jsx)(c.Button,{variant:"primary",onClick:()=>{return t=void 0,n=void 0,r=function*(){C.forEach((t=>x(t.id))),l(!0);try{const t=yield p()({path:"/ai-plus-block-editor/v1/sidebar",method:"POST",data:{id:g(),text:b.text||b,feature:"slug"}});(()=>{let n=1;return new Promise((o=>{const r=setInterval((()=>{t.length===n&&(clearInterval(r),o(t)),e(t.substring(0,n)),n++}),5)}))})().then((t=>{A({slug:t}),A({meta:{apbe_slug:t}})})),l(!1)}catch(t){h(t.message),l(!1)}},new((o=void 0)||(o=Promise))((function(e,a){function i(t){try{c(r.next(t))}catch(t){a(t)}}function s(t){try{c(r.throw(t))}catch(t){a(t)}}function c(t){var n;t.done?e(t.value):(n=t.value,n instanceof o?n:new o((function(t){t(n)}))).then(i,s)}c((r=r.apply(t,n||[])).next())}));var t,n,o,r},children:(0,r.__)("Generate","ai-plus-block-editor")}),(0,o.jsx)(c.Button,{variant:"secondary",onClick:()=>{A({slug:t}),A({meta:{apbe_slug:t}}),f()},children:(0,o.jsx)(c.Icon,{icon:a.A})})]}),(0,o.jsx)(u.A,{message:(0,r.__)("AI is generating text, please hold on for a bit…"),isLoading:n})]})}},533:(t,e,n)=>{var o=n(72),r=n.n(o),a=n(825),i=n.n(a),s=n(659),c=n.n(s),d=n(56),l=n.n(d),p=n(540),u=n.n(p),A=n(113),f=n.n(A),h=n(100),x={};x.styleTagTransform=f(),x.setAttributes=l(),x.insert=c().bind(null,"head"),x.domAPI=i(),x.insertStyleElement=u(),r()(h.A,x),h.A&&h.A.locals&&h.A.locals},540:t=>{t.exports=function(t){var e=document.createElement("style");return t.setAttributes(e,t.attributes),t.insert(e,t.options),e}},573:t=>{t.exports=window.wp.primitives},609:t=>{t.exports=window.React},619:t=>{t.exports=window.wp.hooks},659:t=>{var e={};t.exports=function(t,n){var o=function(t){if(void 0===e[t]){var n=document.querySelector(t);if(window.HTMLIFrameElement&&n instanceof window.HTMLIFrameElement)try{n=n.contentDocument.head}catch(t){n=null}e[t]=n}return e[t]}(t);if(!o)throw new Error("Couldn't find a style target. This probably means that the value for the 'insert' parameter is invalid.");o.appendChild(n)}},692:t=>{t.exports=window.wp.notices},715:t=>{t.exports=window.wp.blockEditor},723:t=>{t.exports=window.wp.i18n},825:t=>{t.exports=function(t){if("undefined"==typeof document)return{update:function(){},remove:function(){}};var e=t.insertStyleElement(t);return{update:function(n){!function(t,e,n){var o="";n.supports&&(o+="@supports (".concat(n.supports,") {")),n.media&&(o+="@media ".concat(n.media," {"));var r=void 0!==n.layer;r&&(o+="@layer".concat(n.layer.length>0?" ".concat(n.layer):""," {")),o+=n.css,r&&(o+="}"),n.media&&(o+="}"),n.supports&&(o+="}");var a=n.sourceMap;a&&"undefined"!=typeof btoa&&(o+="\n/*# sourceMappingURL=data:application/json;base64,".concat(btoa(unescape(encodeURIComponent(JSON.stringify(a))))," */")),e.styleTagTransform(o,t,e.options)}(e,t,n)},remove:function(){!function(t){if(null===t.parentNode)return!1;t.parentNode.removeChild(t)}(e)}}}},830:(t,e,n)=>{n.d(e,{A:()=>a});var o=n(609),r=n(573);const a=(0,o.createElement)(r.SVG,{viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},(0,o.createElement)(r.Path,{d:"M17.8 2l-.9.3c-.1 0-3.6 1-5.2 2.1C10 5.5 9.3 6.5 8.9 7.1c-.6.9-1.7 4.7-1.7 6.3l-.9 2.3c-.2.4 0 .8.4 1 .1 0 .2.1.3.1.3 0 .6-.2.7-.5l.6-1.5c.3 0 .7-.1 1.2-.2.7-.1 1.4-.3 2.2-.5.8-.2 1.6-.5 2.4-.8.7-.3 1.4-.7 1.9-1.2s.8-1.2 1-1.9c.2-.7.3-1.6.4-2.4.1-.8.1-1.7.2-2.5 0-.8.1-1.5.2-2.1V2zm-1.9 5.6c-.1.8-.2 1.5-.3 2.1-.2.6-.4 1-.6 1.3-.3.3-.8.6-1.4.9-.7.3-1.4.5-2.2.8-.6.2-1.3.3-1.8.4L15 7.5c.3-.3.6-.7 1-1.1 0 .4 0 .8-.1 1.2zM6 20h8v-1.5H6V20z"}))},848:(t,e,n)=>{t.exports=n(20)},897:(t,e,n)=>{n.d(e,{A:()=>A});var o=n(848),r=n(723),a=n(351),i=n(87),s=n(692),c=n(427),d=n(143),l=n(455),p=n.n(l),u=n(231);const A=()=>{const[t,e]=(0,i.useState)(""),[n,l]=(0,i.useState)(!1),{editPost:A,savePost:f}=(0,d.dispatch)("core/editor"),{createErrorNotice:h,removeNotice:x}=(0,d.useDispatch)(s.store),{getCurrentPostId:g,getEditedPostAttribute:v,getEditedPostContent:m}=(0,d.select)("core/editor"),b=m(),C=(0,d.useSelect)((t=>t(s.store).getNotices()),[]);return(0,i.useEffect)((()=>{e(v("meta").apbe_seo_keywords)}),[v]),(0,o.jsxs)(o.Fragment,{children:[(0,o.jsx)("p",{children:(0,o.jsx)("strong",{children:(0,r.__)("SEO Keywords","ai-plus-block-editor")})}),(0,o.jsx)(c.TextareaControl,{rows:7,value:t,onChange:t=>e(t),__nextHasNoMarginBottom:!0}),(0,o.jsxs)("div",{className:"apbe-button-group",children:[(0,o.jsx)(c.Button,{variant:"primary",onClick:()=>{return t=void 0,n=void 0,r=function*(){C.forEach((t=>x(t.id))),l(!0);try{const t=yield p()({path:"/ai-plus-block-editor/v1/sidebar",method:"POST",data:{id:g(),text:b.text||b,feature:"keywords"}});(()=>{let n=1;return new Promise((o=>{const r=setInterval((()=>{t.length===n&&(clearInterval(r),o(t)),e(t.substring(0,n)),n++}),5)}))})().then((t=>{A({meta:{apbe_seo_keywords:t}})})),l(!1)}catch(t){h(t.message),l(!1)}},new((o=void 0)||(o=Promise))((function(e,a){function i(t){try{c(r.next(t))}catch(t){a(t)}}function s(t){try{c(r.throw(t))}catch(t){a(t)}}function c(t){var n;t.done?e(t.value):(n=t.value,n instanceof o?n:new o((function(t){t(n)}))).then(i,s)}c((r=r.apply(t,n||[])).next())}));var t,n,o,r},children:(0,r.__)("Generate","ai-plus-block-editor")}),(0,o.jsx)(c.Button,{variant:"secondary",onClick:()=>{A({meta:{apbe_seo_keywords:t}}),f()},children:(0,o.jsx)(c.Icon,{icon:a.A})})]}),(0,o.jsx)(u.A,{message:(0,r.__)("AI is generating text, please hold on for a bit…"),isLoading:n})]})}},924:(t,e,n)=>{n.d(e,{A:()=>A});var o=n(848),r=n(723),a=n(351),i=n(87),s=n(692),c=n(427),d=n(143),l=n(455),p=n.n(l),u=n(231);const A=()=>{const[t,e]=(0,i.useState)(""),[n,l]=(0,i.useState)(!1),{editPost:A,savePost:f}=(0,d.dispatch)("core/editor"),{createErrorNotice:h,removeNotice:x}=(0,d.useDispatch)(s.store),{getCurrentPostId:g,getEditedPostAttribute:v,getEditedPostContent:m}=(0,d.select)("core/editor"),b=m(),C=(0,d.useSelect)((t=>t(s.store).getNotices()),[]);return(0,i.useEffect)((()=>{e(v("meta").apbe_headline)}),[v]),(0,o.jsxs)(o.Fragment,{children:[(0,o.jsx)("p",{children:(0,o.jsx)("strong",{children:(0,r.__)("Headline","ai-plus-block-editor")})}),(0,o.jsx)(c.TextareaControl,{rows:4,value:t,onChange:t=>e(t),__nextHasNoMarginBottom:!0}),(0,o.jsxs)("div",{className:"apbe-button-group",children:[(0,o.jsx)(c.Button,{variant:"primary",onClick:()=>{return t=void 0,n=void 0,r=function*(){C.forEach((t=>x(t.id))),l(!0);try{const t=(yield p()({path:"/ai-plus-block-editor/v1/sidebar",method:"POST",data:{id:g(),text:b.text||b,feature:"headline"}})).trim().replace(/^"|"$/g,"");(()=>{let n=1;return new Promise((o=>{const r=setInterval((()=>{t.length===n&&(clearInterval(r),o(t)),e(t.substring(0,n)),n++}),5)}))})().then((t=>{A({meta:{apbe_headline:t}})})),l(!1)}catch(t){h(t.message),l(!1)}},new((o=void 0)||(o=Promise))((function(e,a){function i(t){try{c(r.next(t))}catch(t){a(t)}}function s(t){try{c(r.throw(t))}catch(t){a(t)}}function c(t){var n;t.done?e(t.value):(n=t.value,n instanceof o?n:new o((function(t){t(n)}))).then(i,s)}c((r=r.apply(t,n||[])).next())}));var t,n,o,r},children:(0,r.__)("Generate","ai-plus-block-editor")}),(0,o.jsx)(c.Button,{variant:"secondary",onClick:()=>{let e=1;new Promise((n=>{const o=setInterval((()=>{e===t.length&&(clearInterval(o),n(t)),A({title:t.substring(0,e)}),e++}),5)})).then((t=>{A({meta:{apbe_headline:t}}),f()}))},children:(0,o.jsx)(c.Icon,{icon:a.A})})]}),(0,o.jsx)(u.A,{message:(0,r.__)("AI is generating text, please hold on for a bit…"),isLoading:n})]})}},953:(t,e,n)=>{var o=n(848),r=n(723),a=n(830),i=n(87),s=n(427),c=n(279),d=n(309),l=n(897),p=n(457),u=n(258),A=n(924);n(533),(0,c.registerPlugin)("ai-plus-block-editor",{render:()=>(0,o.jsxs)(i.Fragment,{children:[(0,o.jsx)(d.PluginSidebarMoreMenuItem,{icon:a.A,target:"apbe-sidebar",children:(0,r.__)("AI + Block Editor","ai-plus-block-editor")}),(0,o.jsx)(d.PluginSidebar,{name:"apbe-sidebar",title:(0,r.__)("AI + Block Editor","ai-plus-block-editor"),icon:a.A,children:(0,o.jsx)(s.PanelBody,{children:(0,o.jsx)("div",{className:"apbe-sidebar",children:(0,o.jsxs)("ul",{children:[(0,o.jsx)("li",{children:(0,o.jsx)(A.A,{})}),(0,o.jsx)("li",{children:(0,o.jsx)(p.A,{})}),(0,o.jsx)("li",{children:(0,o.jsx)(l.A,{})}),(0,o.jsx)("li",{children:(0,o.jsx)(u.A,{})})]})})})})]})})}},e={};function n(o){var r=e[o];if(void 0!==r)return r.exports;var a=e[o]={id:o,exports:{}};return t[o](a,a.exports,n),a.exports}n.n=t=>{var e=t&&t.__esModule?()=>t.default:()=>t;return n.d(e,{a:e}),e},n.d=(t,e)=>{for(var o in e)n.o(e,o)&&!n.o(t,o)&&Object.defineProperty(t,o,{enumerable:!0,get:e[o]})},n.o=(t,e)=>Object.prototype.hasOwnProperty.call(t,e),n.nc=void 0,n(213),n(953)})();
//# sourceMappingURL=app.js.map
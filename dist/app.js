(()=>{"use strict";var e={20:(e,o,t)=>{var r=t(609),n=Symbol.for("react.element"),i=(Symbol.for("react.fragment"),Object.prototype.hasOwnProperty),s=r.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,a={key:!0,ref:!0,__self:!0,__source:!0};function c(e,o,t){var r,c={},l=null,p=null;for(r in void 0!==t&&(l=""+t),void 0!==o.key&&(l=""+o.key),void 0!==o.ref&&(p=o.ref),o)i.call(o,r)&&!a.hasOwnProperty(r)&&(c[r]=o[r]);if(e&&e.defaultProps)for(r in o=e.defaultProps)void 0===c[r]&&(c[r]=o[r]);return{$$typeof:n,type:e,key:l,ref:p,props:c,_owner:s.current}}o.jsx=c,o.jsxs=c},848:(e,o,t)=>{e.exports=t(20)},559:(e,o,t)=>{t.d(o,{A:()=>n});var r=t(723);const n={casual:(0,r.__)("Use Casual Tone","ai-plus-block-editor"),official:(0,r.__)("Use Official Tone","ai-plus-block-editor"),descriptive:(0,r.__)("Use Descriptive Tone","ai-plus-block-editor"),narrative:(0,r.__)("Use Narrative Tone","ai-plus-block-editor"),aggressive:(0,r.__)("Use Aggressive Tone","ai-plus-block-editor")}},609:e=>{e.exports=window.React},455:e=>{e.exports=window.wp.apiFetch},715:e=>{e.exports=window.wp.blockEditor},427:e=>{e.exports=window.wp.components},143:e=>{e.exports=window.wp.data},87:e=>{e.exports=window.wp.element},619:e=>{e.exports=window.wp.hooks},723:e=>{e.exports=window.wp.i18n}},o={};function t(r){var n=o[r];if(void 0!==n)return n.exports;var i=o[r]={exports:{}};return e[r](i,i.exports,t),i.exports}t.n=e=>{var o=e&&e.__esModule?()=>e.default:()=>e;return t.d(o,{a:o}),o},t.d=(e,o)=>{for(var r in o)t.o(o,r)&&!t.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:o[r]})},t.o=(e,o)=>Object.prototype.hasOwnProperty.call(e,o);var r=t(848),n=t(723),i=t(619),s=t(143),a=t(715),c=t(87),l=t(427),p=t(455),d=t.n(p),u=t(559);(0,i.addFilter)("blocks.registerBlockType","apbe/ai",(e=>{const{name:o,edit:t}=e;return"core/paragraph"!==o||(e.edit=e=>{const[o,i]=(0,c.useState)("superhero"),[p,f]=(0,c.useState)(""),w=[];return Object.keys(u.A).forEach((e=>{w.push({icon:"superhero",title:u.A[e],onClick:()=>{f(e)}})})),(0,c.useEffect)((()=>{p&&(e=>{var o,t,r,n;o=void 0,t=void 0,n=function*(){const{getCurrentPostId:o}=(0,s.select)("core/editor"),{updateBlockAttributes:t}=(0,s.dispatch)("core/block-editor"),{getSelectedBlock:r,getSelectedBlockClientId:n}=(0,s.select)("core/block-editor"),{content:a}=r().attributes,c={path:"/ai-plus-block-editor/v1/tone",method:"POST",data:{id:o(),text:a.text||a,tone:e}};i("format-status");const{data:l}=yield d()(c);t(n(),{content:l}),i("superhero")},new((r=void 0)||(r=Promise))((function(e,i){function s(e){try{c(n.next(e))}catch(e){i(e)}}function a(e){try{c(n.throw(e))}catch(e){i(e)}}function c(o){var t;o.done?e(o.value):(t=o.value,t instanceof r?t:new r((function(e){e(t)}))).then(s,a)}c((n=n.apply(o,t||[])).next())}))})(p)}),[p]),(0,r.jsxs)(c.Fragment,{children:[(0,r.jsx)(a.BlockControls,{children:(0,r.jsx)(l.ToolbarGroup,{children:(0,r.jsx)(l.ToolbarDropdownMenu,{icon:o,label:(0,n.__)("AI + Block Editor"),controls:w})})}),t(e)]})}),e}))})();
//# sourceMappingURL=app.js.map
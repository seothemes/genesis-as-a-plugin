!function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:r})},n.r=function(e){Object.defineProperty(e,"__esModule",{value:!0})},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=22)}([function(e,t){!function(){e.exports=this.wp.element}()},function(e,t){!function(){e.exports=this.wp.i18n}()},function(e,t){!function(){e.exports=this.wp.data}()},function(e,t){!function(){e.exports=this.wp.components}()},function(e,t){!function(){e.exports=this.wp.compose}()},function(e,t){!function(){e.exports=this.wp.plugins}()},function(e,t,n){var r=n(7);e.exports=function(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{},o=Object.keys(n);"function"==typeof Object.getOwnPropertySymbols&&(o=o.concat(Object.getOwnPropertySymbols(n).filter(function(e){return Object.getOwnPropertyDescriptor(n,e).enumerable}))),o.forEach(function(t){r(e,t,n[t])})}return e}},function(e,t){e.exports=function(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}},,,,,,,,,,,,,,,function(e,t,n){"use strict";n.r(t);var r=n(0),o=n(1),c=n(3),s=n(5),i=n(6),u=n.n(i),a=n(4),l=n(2);var f=Object(a.compose)([Object(l.withSelect)(function(){return{bodyClass:Object(l.select)("core/editor").getEditedPostAttribute("meta")._genesis_custom_body_class}}),Object(l.withDispatch)(function(e){return{onUpdate:function(t){var n=Object(l.select)("core/editor").getEditedPostAttribute("meta"),r=Object.keys(n).filter(function(e){return e.startsWith("_genesis")}).reduce(function(e,t){return e[t]=n[t],e},{}),o=u()({},r,{_genesis_custom_body_class:t});e("core/editor").editPost({meta:o})}}})])(
/**
 * The BodyClassTextControl component for use in the Custom Classes panel.
 *
 * @since   3.1.0
 * @package Genesis\JS
 * @author  StudioPress
 * @license GPL-2.0-or-later
 */
function(e){var t=e.bodyClass,n=e.onUpdate;return Object(r.createElement)(c.TextControl,{label:Object(o.__)("Body Class","genesis"),value:t,onChange:function(e){return n(e)}})});
/**
 * The PostClassTextControl component for use in the Custom Classes panel.
 *
 * @since   3.1.0
 * @package Genesis\JS
 * @author  StudioPress
 * @license GPL-2.0-or-later
 */var b=Object(a.compose)([Object(l.withSelect)(function(){return{postClass:Object(l.select)("core/editor").getEditedPostAttribute("meta")._genesis_custom_post_class}}),Object(l.withDispatch)(function(e){return{onUpdate:function(t){var n=Object(l.select)("core/editor").getEditedPostAttribute("meta"),r=Object.keys(n).filter(function(e){return e.startsWith("_genesis")}).reduce(function(e,t){return e[t]=n[t],e},{}),o=u()({},r,{_genesis_custom_post_class:t});e("core/editor").editPost({meta:o})}}})])(function(e){var t=e.postClass,n=e.onUpdate;return Object(r.createElement)(c.TextControl,{label:Object(o.__)("Post Class","genesis"),value:t,onChange:function(e){return n(e)}})});
/**
 * Adds a Classes panel to the Genesis Block Editor sidebar with body class
 * and post class input fields.
 *
 * Fields are stored in post meta as:
 *
 * - `_genesis_custom_body_class`
 * - `_genesis_custom_post_class`
 *
 * These are the same fields used by the original Layout Settings meta box.
 *
 * @since   3.1.0
 * @package Genesis\JS
 * @author  StudioPress
 * @license GPL-2.0-or-later
 */Object(s.registerPlugin)("genesis-custom-classes",{render:function(){return Object(r.createElement)(r.Fragment,null,Object(r.createElement)(c.Fill,{name:"GenesisSidebar"},Object(r.createElement)(c.Panel,null,Object(r.createElement)(c.PanelBody,{initialOpen:!0,title:Object(o.__)("Custom Classes","genesis")},Object(r.createElement)(f,null),Object(r.createElement)(b,null)))))}})}]);
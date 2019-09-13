!function(e){var t={};function n(r){if(t[r])return t[r].exports;var i=t[r]={i:r,l:!1,exports:{}};return e[r].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:r})},n.r=function(e){Object.defineProperty(e,"__esModule",{value:!0})},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=24)}([function(e,t){!function(){e.exports=this.wp.element}()},function(e,t){!function(){e.exports=this.wp.i18n}()},function(e,t){!function(){e.exports=this.wp.data}()},function(e,t){!function(){e.exports=this.wp.components}()},function(e,t){!function(){e.exports=this.wp.compose}()},function(e,t){!function(){e.exports=this.wp.plugins}()},function(e,t,n){var r=n(7);e.exports=function(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{},i=Object.keys(n);"function"==typeof Object.getOwnPropertySymbols&&(i=i.concat(Object.getOwnPropertySymbols(n).filter(function(e){return Object.getOwnPropertyDescriptor(n,e).enumerable}))),i.forEach(function(t){r(e,t,n[t])})}return e}},function(e,t){e.exports=function(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}},,,,,,,,,,,,,,,,,function(e,t,n){"use strict";n.r(t);var r=n(6),i=n.n(r),o=n(0),c=n(1),u=n(4),s=n(2),l=n(3),a=n(5);var f=Object(u.compose)([Object(s.withSelect)(function(){return{hideTitle:Object(s.select)("core/editor").getEditedPostAttribute("meta")._genesis_hide_title}}),Object(s.withDispatch)(function(e){return{onUpdate:function(t){var n=Object(s.select)("core/editor").getEditedPostAttribute("meta"),r=Object.keys(n).filter(function(e){return e.startsWith("_genesis")}).reduce(function(e,t){return e[t]=n[t],e},{}),o=i()({},r,{_genesis_hide_title:t});e("core/editor").editPost({meta:o})}}})])(
/**
 * Adds a “hide title” checkbox to Genesis Block Editor sidebar in a
 * Title panel. Unchecked by default.
 *
 * If checked and the post is updated or published, `_genesis_hide_title`
 * is set to true in post meta.
 *
 * To disable the checkbox, use the PHP `genesis_title_toggle_enabled`
 * filter: `add_filter( 'genesis_title_toggle_enabled', '__return_false' );`.
 *
 * @since   3.1.0
 * @package Genesis\JS
 * @author  StudioPress
 * @license GPL-2.0-or-later
 */
function(e){var t=e.hideTitle,n=e.onUpdate;return Object(o.createElement)(o.Fragment,null,Object(o.createElement)(l.Fill,{name:"GenesisSidebar"},Object(o.createElement)(l.PanelBody,{initialOpen:!0,title:Object(c.__)("Title","genesis")},Object(o.createElement)(l.PanelRow,null,Object(o.createElement)(l.CheckboxControl,{label:Object(c.__)("Hide Title","genesis"),checked:t,onChange:function(){return n(!t)}})))))});Object(a.registerPlugin)("genesis-title-toggle",{render:f})}]);
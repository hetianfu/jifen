(window.webpackJsonp=window.webpackJsonp||[]).push([[62],{"349":function(o,e,t){var n=t(350);"string"==typeof n&&(n=[[o.i,n,""]]);var r={"sourceMap":!1,"insertAt":"top","hmr":!0,"transform":void 0,"insertInto":void 0};t(70)(n,r);n.locals&&(o.exports=n.locals)},"350":function(o,e,t){(e=t(69)(!1)).push([o.i,".taro-scroll {\n  -webkit-overflow-scrolling: auto;\n}\n\n.taro-scroll::-webkit-scrollbar {\n  display: none;\n}\n\n.taro-scroll-view {\n  overflow: hidden;\n}\n\n.taro-scroll-view__scroll-x {\n  overflow-x: scroll;\n  overflow-y: hidden;\n}\n\n.taro-scroll-view__scroll-y {\n  overflow-x: hidden;\n  overflow-y: scroll;\n}\n",""]),o.exports=e},"454":function(o,e,t){"use strict";t(27);var n=t(1),r=t(71),l=t(12),i=t.n(l),c=(t(349),Object.assign||function(o){for(var e=1;e<arguments.length;e++){var t=arguments[e];for(var n in t)Object.prototype.hasOwnProperty.call(t,n)&&(o[n]=t[n])}return o}),s=function(){function defineProperties(o,e){for(var t=0;t<e.length;t++){var n=e[t];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(o,n.key,n)}}return function(o,e,t){return e&&defineProperties(o.prototype,e),t&&defineProperties(o,t),o}}();function _defineProperty(o,e,t){return e in o?Object.defineProperty(o,e,{"value":t,"enumerable":!0,"configurable":!0,"writable":!0}):o[e]=t,o}function _classCallCheck(o,e){if(!(o instanceof e))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(o,e){if(!o)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!e||"object"!=typeof e&&"function"!=typeof e?o:e}function easeOutScroll(){var o=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0,e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0,t=arguments[2];if(o!==e&&"number"==typeof o){var n=e-o,r=500,l=+new Date,i=e>=o;step()}function linear(o,e,t,n){return t*o/n+e}function step(){o=linear(+new Date-l,o,n,r),i&&o>=e||!i&&e>=o?t(e):(t(o),requestAnimationFrame(step))}}function scrollIntoView(o){document.querySelector("#"+o).scrollIntoView({"behavior":"smooth","block":"center","inline":"start"})}function scrollVertical(o,e){var t=this;e?easeOutScroll(this._scrollTop,o,(function(o){t.container&&(t.container.scrollTop=o)})):this.container&&(this.container.scrollTop=o),this._scrollTop=o}function scrollHorizontal(o,e){var t=this;e?easeOutScroll(this._scrollLeft,o,(function(o){t.container&&(t.container.scrollLeft=o)})):this.container&&(this.container.scrollLeft=o),this._scrollLeft=o}var a=function(o){function ScrollView(){_classCallCheck(this,ScrollView);var o=_possibleConstructorReturn(this,(ScrollView.__proto__||Object.getPrototypeOf(ScrollView)).apply(this,arguments));return o.onTouchMove=function(o){o.stopPropagation()},o}return function _inherits(o,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function, not "+typeof e);o.prototype=Object.create(e&&e.prototype,{"constructor":{"value":o,"enumerable":!1,"writable":!0,"configurable":!0}}),e&&(Object.setPrototypeOf?Object.setPrototypeOf(o,e):o.__proto__=e)}(ScrollView,o),s(ScrollView,[{"key":"componentDidMount","value":function componentDidMount(){this.handleScroll(this.props,!0)}},{"key":"componentWillReceiveProps","value":function componentWillReceiveProps(o){this.handleScroll(o)}},{"key":"handleScroll","value":function handleScroll(o){var e=this,t=arguments.length>1&&void 0!==arguments[1]&&arguments[1];if(o.scrollIntoView&&"string"==typeof o.scrollIntoView&&document&&document.querySelector&&document.querySelector("#"+o.scrollIntoView))t?setTimeout((function(){return scrollIntoView(o.scrollIntoView)}),500):scrollIntoView(o.scrollIntoView);else{var n="scrollWithAnimation"in o;o.scrollY&&"number"==typeof o.scrollTop&&o.scrollTop!==this._scrollTop&&(t?setTimeout((function(){return scrollVertical.bind(e)(o.scrollTop,n)}),10):scrollVertical.bind(this)(o.scrollTop,n)),o.scrollX&&"number"==typeof o.scrollLeft&&o.scrollLeft!==this._scrollLeft&&(t?setTimeout((function(){return scrollHorizontal.bind(e)(o.scrollLeft,n)}),10):scrollHorizontal.bind(this)(o.scrollLeft,n))}}},{"key":"render","value":function render(){var o,e=this,t=this.props,l=t.className,s=t.onScroll,a=t.onScrollToUpper,p=t.onScrollToLower,u=t.onTouchMove,f=t.scrollX,d=t.scrollY,h=this.props,v=h.upperThreshold,y=void 0===v?50:v,_=h.lowerThreshold,b=void 0===_?50:_,w=i()("taro-scroll",(_defineProperty(o={},"taro-scroll-view__scroll-x",f),_defineProperty(o,"taro-scroll-view__scroll-y",d),o),l);y=parseInt(y),b=parseInt(b);var m=function throttle(o,e){var t=null;return function(){for(var n=arguments.length,r=Array(n),l=0;l<n;l++)r[l]=arguments[l];clearTimeout(t),t=setTimeout((function(){o.apply(void 0,r)}),e)}}((function uperAndLower(o){if(e.container){var t=e.container,n=t.offsetWidth,r=t.offsetHeight,l=t.scrollLeft,i=t.scrollTop,c=t.scrollHeight,s=t.scrollWidth;p&&(e.props.scrollY&&r+i+b>=c||e.props.scrollX&&n+l+b>=s)&&p(o),a&&(e.props.scrollY&&i<=y||e.props.scrollX&&l<=y)&&a(o)}}),200);return n.l.createElement("div",c({"ref":function ref(o){e.container=o}},Object(r.a)(this.props,["className","scrollTop","scrollLeft"]),{"className":w,"onScroll":function _onScroll(o){var t=e.container,n=t.scrollLeft,r=t.scrollTop,l=t.scrollHeight,i=t.scrollWidth;e._scrollLeft=n,e._scrollTop=r,Object.defineProperty(o,"detail",{"enumerable":!0,"writable":!0,"value":{"scrollLeft":n,"scrollTop":r,"scrollHeight":l,"scrollWidth":i}}),m(o),s&&s(o)},"onTouchMove":function _onTouchMove(o){u?u(o):e.onTouchMove(o)},"onLoad":function onLoad(o){console.log("onload",o)}}),this.props.children)}}]),ScrollView}(n.l.Component);e.a=a},"588":function(o,e,t){},"644":function(o,e,t){"use strict";t.r(e),t.d(e,"default",(function(){return a}));var n=t(1),r=t(3),l=t(43),i=t(454),c=(t(588),function(){function defineProperties(o,e){for(var t=0;t<e.length;t++){var n=e[t];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(o,n.key,n)}}return function(o,e,t){return e&&defineProperties(o.prototype,e),t&&defineProperties(o,t),o}}()),s=function get(o,e,t){null===o&&(o=Function.prototype);var n=Object.getOwnPropertyDescriptor(o,e);if(void 0===n){var r=Object.getPrototypeOf(o);return null===r?void 0:get(r,e,t)}if("value"in n)return n.value;var l=n.get;return void 0!==l?l.call(t):void 0};function _classCallCheck(o,e){if(!(o instanceof e))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(o,e){if(!o)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!e||"object"!=typeof e&&"function"!=typeof e?o:e}var a=function(o){function Index(){_classCallCheck(this,Index);var o=_possibleConstructorReturn(this,(Index.__proto__||Object.getPrototypeOf(Index)).apply(this,arguments));return o.config={"navigationBarTitleText":"关于我们","navigationBarTextStyle":"black","navigationBarBackgroundColor":"#FFDD00"},o}return function _inherits(o,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function, not "+typeof e);o.prototype=Object.create(e&&e.prototype,{"constructor":{"value":o,"enumerable":!1,"writable":!0,"configurable":!0}}),e&&(Object.setPrototypeOf?Object.setPrototypeOf(o,e):o.__proto__=e)}(Index,o),c(Index,[{"key":"onScroll","value":function onScroll(o){var e=o.detail;console.log(e)}},{"key":"render","value":function render(){for(var o=[],e=0;e<100;e++)o.push(e);return n.l.createElement(l.a,{"className":"test-page"},n.l.createElement(i.a,{"scrollY":!0,"className":"page-scroll","onScroll":this.onScroll.bind(this)},n.l.createElement(l.a,{"className":"data-list"},o.map((function(o){return n.l.createElement(l.a,{"className":"data-item"},"sdfsfsdf",o)})))))}},{"key":"componentDidMount","value":function componentDidMount(){s(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidMount",this)&&s(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidMount",this).call(this)}},{"key":"componentDidShow","value":function componentDidShow(){s(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidShow",this)&&s(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidShow",this).call(this)}},{"key":"componentDidHide","value":function componentDidHide(){s(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this)&&s(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this).call(this)}}]),Index}(r.a.Component)}}]);
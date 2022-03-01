(window.webpackJsonp=window.webpackJsonp||[]).push([[38],{"338":function(e,t,n){"use strict";n.d(t,"b",(function(){return getNewsPage})),n.d(t,"a",(function(){return getNewsDetail})),n.d(t,"c",(function(){return getNewsType}));var r=n(9);function getNewsPage(e){return r.a.get("/informations/get-information-page",{"params":e})}function getNewsDetail(e){return r.a.get("/informations/get-information-by-id/"+e)}function getNewsType(){return r.a.get("/informations/get-category-list?isSystem=0")}},"407":function(e,t,n){"use strict";n(27);var r=n(1),o=n(71),i=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},a=function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}();function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var c=function(e){function RichText(){return _classCallCheck(this,RichText),_possibleConstructorReturn(this,(RichText.__proto__||Object.getPrototypeOf(RichText)).apply(this,arguments))}return function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(RichText,e),a(RichText,[{"key":"renderNodes","value":function renderNodes(e){if("text"===e.type)return r.l.createElement("span",{},e.text);var t=this.renderChildrens(e.children),n={"className":"","style":""};if(e.hasOwnProperty("attrs"))for(var o in e.attrs)"class"===o?n.className=e.attrs[o]||"":n[o]=e.attrs[o]||"";return r.l.createElement(e.name,n,t)}},{"key":"renderChildrens","value":function renderChildrens(){var e=this,t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[];if(0!==t.length)return t.map((function(t,n){return"text"===t.type?t.text:e.renderNodes(t)}))}},{"key":"render","value":function render(){var e=this,t=this.props,n=t.nodes,a=t.className,c=function _objectWithoutProperties(e,t){var n={};for(var r in e)t.indexOf(r)>=0||Object.prototype.hasOwnProperty.call(e,r)&&(n[r]=e[r]);return n}(t,["nodes","className"]);return Array.isArray(n)?r.l.createElement("div",i({"className":a},Object(o.a)(this.props,["nodes","className"]),c),n.map((function(t,n){return e.renderNodes(t)}))):r.l.createElement("div",i({"className":a},Object(o.a)(this.props,["className"]),c,{"dangerouslySetInnerHTML":{"__html":n}}))}}]),RichText}(r.l.Component);t.a=c},"429":function(e,t,n){"use strict";n.d(t,"a",(function(){return setNavigationBarTitle}));var r=n(0);function setNavigationBarTitle(e){var t=Object(r.m)(e);if(!t.res){var n={"errMsg":"setNavigationBarTitle"+t.msg};return console.error(n.errMsg),Promise.reject(n)}var o=e.title,i=e.success,a=e.fail,c=e.complete,s={"errMsg":"setNavigationBarTitle:ok"};return o&&"string"==typeof o?(document.title!==o&&(document.title=o),"function"==typeof i&&i(s),"function"==typeof c&&c(s),Promise.resolve(s)):(s.errMsg=Object(r.e)({"name":"setNavigationBarTitle","para":"title","correct":"String","wrong":o}),console.error(s.errMsg),"function"==typeof a&&a(s),"function"==typeof c&&c(s),Promise.reject(s))}},"579":function(e,t,n){},"639":function(e,t,n){"use strict";n.r(t),n.d(t,"default",(function(){return h}));var r=n(18),o=n.n(r),i=n(1),a=n(429),c=n(3),s=n(43),u=n(407),l=(n(579),n(2)),p=n(306),f=n(338),d=function(){function defineProperties(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(e,t,n){return t&&defineProperties(e.prototype,t),n&&defineProperties(e,n),e}}(),y=function get(e,t,n){null===e&&(e=Function.prototype);var r=Object.getOwnPropertyDescriptor(e,t);if(void 0===r){var o=Object.getPrototypeOf(e);return null===o?void 0:get(o,t,n)}if("value"in r)return r.value;var i=r.get;return void 0!==i?i.call(n):void 0};function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}var h=function(e){function Index(){_classCallCheck(this,Index);var e=_possibleConstructorReturn(this,(Index.__proto__||Object.getPrototypeOf(Index)).apply(this,arguments));e.config={"backgroundTextStyle":"dark","navigationBarTitleText":""};var t=e.$router.params;return t.name&&Object(a.a)({"title":t.name}),e.state={"id":t.id,"loading":!0,"newsInfo":{}},e}var t;return function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{"constructor":{"value":e,"enumerable":!1,"writable":!0,"configurable":!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(Index,e),d(Index,[{"key":"componentWillMount","value":function componentWillMount(){this.getInfo().then()}},{"key":"getInfo","value":(t=function _asyncToGenerator(e){return function(){var t=e.apply(this,arguments);return new Promise((function(e,n){return function step(r,o){try{var i=t[r](o),a=i.value}catch(e){return void n(e)}if(!i.done)return Promise.resolve(a).then((function(e){step("next",e)}),(function(e){step("throw",e)}));e(a)}("next")}))}}(o.a.mark((function _callee(){var e=this;return o.a.wrap((function _callee$(t){for(;;)switch(t.prev=t.next){case 0:Object(f.a)(this.state.id).then((function(t){var n=t.data;e.setState({"loading":!1,"newsInfo":n})}));case 1:case"end":return t.stop()}}),_callee,this)}))),function getInfo(){return t.apply(this,arguments)})},{"key":"render","value":function render(){var e=this.state,t=e.loading,n=e.newsInfo;return i.l.createElement(s.a,{"className":"page"},t&&i.l.createElement(p.J,{"isCover":!0}),i.l.createElement(s.a,{"className":"rich-text-wrap"},i.l.createElement(u.a,{"nodes":Object(l.e)(n.content)})))}},{"key":"componentDidMount","value":function componentDidMount(){y(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidMount",this)&&y(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidMount",this).call(this)}},{"key":"componentDidShow","value":function componentDidShow(){y(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidShow",this)&&y(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidShow",this).call(this)}},{"key":"componentDidHide","value":function componentDidHide(){y(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this)&&y(Index.prototype.__proto__||Object.getPrototypeOf(Index.prototype),"componentDidHide",this).call(this)}}]),Index}(c.a.Component)}}]);